#!/usr/bin/env python

import argparse
import os
import re
import subprocess
import tempfile

import numpy as np
import pandas as pd

parser = argparse.ArgumentParser(
    description="build model complexes and score using Rosetta's fixbb app"
)
parser.add_argument(
    "-f",
    help="ATLAS Mutants tab-delimited file (ex. Mutants_052915.tsv)",
    type=str,
    dest="f",
    required=False,
    default="../www/tables/ATLAS.tsv",
)
parser.add_argument(
    "-w",
    help="Weights file for scoring with Rosetta (ex. weights_1.wts)",
    type=str,
    dest="w",
    required=False,
    default="./weights_1.wts",
)
parser.add_argument(
    "-r",
    help="path to Rosetta3 (ex. /home/borrmant/Research/TCR/rosetta/rosetta-3.5/)",
    type=str,
    dest="ros_path",
    required=False,
    default="../rosetta/main",
)
parser.add_argument(
    "-s",
    help="path to Brian Pierce's TCR-pMHC structure database (ex. /home/borrmant/Research/TCR/tcr_structure_database/all/)",
    type=str,
    dest="struct_path",
    required=False,
    default="../www/structures",
)
args = parser.parse_args()

designed_path = os.path.join(args.struct_path, "designed_pdb")
if not os.path.exists(designed_path):
    os.makedirs(designed_path)


def make_resfile(
    filename: str, MHC_mut, MHC_mut_chain, TCR_mut, TCR_mut_chain, PEP_mut
):
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
    RF = open(filename, "w")
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


def fixbb(pdb: str, resfile: str, label: str, temp_path: str):
    """
    Design mutations using Rosetta's fixed backbone application
    """
    fixbb_cmd = [
        "bsub",
        "-W",
        "60",
        "-o",
        os.path.join(temp_path, "job.out"),
        "-e",
        os.path.join(temp_path, "job.err"),
        os.path.join(args.ros_path, "source/bin/fixbb.static.linuxgccrelease"),
        "-database",
        os.path.join(args.ros_path, "database"),
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
        designed_path,
        "-out:path:score",
        temp_path,
    ]
    process = subprocess.Popen(fixbb_cmd)
    process.wait()


def main():
    # Read Mutants table into dataframe
    df = pd.read_csv(args.f, sep="\t")

    for i, row in df.iterrows():
        if pd.isnull(row["true_PDB"]):
            template_pdb = str(row["template_PDB"])
            # Get mutations that need to be designed
            MHC_mut = df.loc[i, "MHC_mut"]
            MHC_mut_chain = df.loc[i, "MHC_mut_chain"]
            TCR_mut = df.loc[i, "TCR_mut"]
            TCR_mut_chain = df.loc[i, "TCR_mut_chain"]
            PEP_mut = df.loc[i, "PEP_mut"]
            # Make label for structure
            label = "_".join(
                map(str, [MHC_mut, MHC_mut_chain, TCR_mut, TCR_mut_chain, PEP_mut])
            )
            label = "_" + re.sub("\s+", "", label)
            label = label.replace("|", ".")
            tempfile_dir = os.path.join("tmp", "atlas_fixbb_" + template_pdb + label)
            os.makedirs(tempfile_dir, exist_ok=True)
            # Make resfile for fixbb app
            resfile = os.path.join(tempfile_dir, "resfile_" + template_pdb + label)
            make_resfile(
                resfile, MHC_mut, MHC_mut_chain, TCR_mut, TCR_mut_chain, PEP_mut
            )
            # Design mutations by fixbb app and save structure
            fixbb(
                os.path.join(args.struct_path, "true_pdb", template_pdb + ".pdb"),
                resfile,
                label,
                tempfile_dir,
            )


if __name__ == "__main__":
    main()
