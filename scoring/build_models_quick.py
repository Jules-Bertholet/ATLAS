#!/usr/bin/env python

import argparse
import os
import re
import subprocess

import numpy as np
import pandas as pd

parser = argparse.ArgumentParser(
    description="build model complexes using Rosetta's fixbb app"
)
parser.add_argument(
    "-f",
    help="ATLAS Mutants tab-delimited file (ex. Mutants_052915.tsv)",
    type=str,
    dest="f",
    required=True,
)
parser.add_argument(
    "-r",
    help="path to Rosetta3 (ex. /home/borrmant/Research/TCR/rosetta/rosetta-3.5/)",
    type=str,
    dest="ros_path",
    required=True,
)
parser.add_argument(
    "-s",
    help="path to Brian Pierce's TCR-pMHC structure database (ex. /home/borrmant/Research/TCR/tcr_structure_database/all/)",
    type=str,
    dest="struct_path",
    required=True,
)
args = parser.parse_args()


def make_resfile(MHC_mut, MHC_mut_chain, TCR_mut, TCR_mut_chain, PEP_mut):
    """
    Make resfile specifying mutations to design using fixbb app
    NOTE:
    Follow Brian Pierce's naming convention in the tcr_structure_database, namely:
    MHC chain A -> chain A
    MHC chain B -> chain B
    TCR chain A -> chain D
    TCR chain B -> chain E
    peptide -> chain C
    """

    tcr_chain_map = {"A": "D", "B": "E"}
    RF = open("resfile", "w")
    RF.write("NATRO\nstart\n")
    if MHC_mut != "WT":
        if pd.isnull(MHC_mut_chain):
            print("ERROR: MHC_mut_chain missing")
            quit()
        muts = re.findall("[A-Z]\d+[A-Z]", MHC_mut)
        chains = re.findall("[A-Z]", MHC_mut_chain)
        if len(muts) != len(chains):
            print("ERROR: not a chain for every mut")
            quit()
        for i in range(len(muts)):
            search_obj = re.search("[A-Z](\d+)([A-Z])", muts[i])
            res_num = search_obj.group(1)
            mut_aa = search_obj.group(2)
            RF.write(res_num + " " + chains[i] + " PIKAA " + mut_aa + "\n")
    if TCR_mut != "WT":
        if pd.isnull(TCR_mut_chain):
            print("ERROR: TCR_mut_chain missing")
            quit()
        muts = re.findall("[A-Z]\d+[A-Z]", TCR_mut)
        chains = re.findall("[A-Z]", TCR_mut_chain)
        if len(muts) != len(chains):
            print("ERROR: not a chain for every mut")
            quit()
        for i in range(len(muts)):
            search_obj = re.search("[A-Z](\d+)([A-Z])", muts[i])
            res_num = search_obj.group(1)
            mut_aa = search_obj.group(2)
            RF.write(
                res_num + " " + tcr_chain_map[chains[i]] + " PIKAA " + mut_aa + "\n"
            )
    if PEP_mut != "WT":
        muts = re.findall("[A-Z]\d+[A-Z]", PEP_mut)
        for mut in muts:
            search_obj = re.search("[A-Z](\d+)([A-Z])", mut)
            res_num = search_obj.group(1)
            mut_aa = search_obj.group(2)
            RF.write(res_num + " C PIKAA " + mut_aa + "\n")
    RF.close()


def fixbb(pdb, resfile, label):
    """
    Design mutations using Rosetta's fixed backbone application
    """
    if not os.path.exists("design_structures"):
        os.makedirs("design_structures")
    fixbb_cmd = [
        args.ros_path + "rosetta_source/bin/fixbb.linuxgccrelease",
        "-database",
        args.ros_path + "rosetta_database/",
        "-s",
        pdb,
        "-resfile",
        resfile,
        "-suffix",
        label,
        "-extrachi_cutoff",
        "1",
        "-ex1",
        "-ex2",
        "-ex3",
        "-overwrite",
        "-out:path:pdb",
        "design_structures",
    ]
    process = subprocess.Popen(fixbb_cmd)
    process.wait()


def main():
    # Read Mutants table into dataframe
    df = pd.read_csv(args.f, sep="\t")

    for i, row in df.iterrows():
        # Check if we need to model entry
        if pd.notnull(row["template_PDB"]):
            # Design model TCR-pMHC
            template_pdb = str(row["template_PDB"])
            # Get mutations that need to be designed
            MHC_mut = df.loc[i, "MHC_mut"]
            MHC_mut_chain = df.loc[i, "MHC_mut_chain"]
            TCR_mut = df.loc[i, "TCR_mut"]
            TCR_mut_chain = df.loc[i, "TCR_mut_chain"]
            PEP_mut = df.loc[i, "PEP_mut"]
            if type(PEP_mut) != str:
                continue

            # Make resfile for fixbb app
            make_resfile(MHC_mut, MHC_mut_chain, TCR_mut, TCR_mut_chain, PEP_mut)
            # Make label for structure
            label = "_".join(
                map(str, [MHC_mut, MHC_mut_chain, TCR_mut, TCR_mut_chain, PEP_mut])
            )
            label = "_" + re.sub("\s+", "", label)
            print(label)
            label = re.sub("\|", ".", label)
            print(label)
            # Design mutations by fixbb app and save structure
            fixbb(args.struct_path + template_pdb + ".pdb", "resfile", label)
            # Remove temp files
            os.remove("resfile")
            os.remove("score" + label + ".sc")


if __name__ == "__main__":
    main()
