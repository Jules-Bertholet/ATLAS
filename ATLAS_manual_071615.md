# ATLAS

## Purpose

To create a comprehensive and easy-to-use database of structures and affinities of TCR-pMHC recognition and mutants at this interface.

## Protocol For Adding New Data Entries

1. Go to IMGT site
2. Click on IMGT/3Dstructure-DB and IMGT/2Dstructure-DB under IMGT Database
3. Search Species – human, and TRAV under IMGT group
4. You will see PDBids, release date, etc.
    1. Compare last entered PDBid in dataset to PDBids on site
    2. Find where database ends and new entries can be explored
5. Click on PDBid
6. Go to literature and search through paper to see if binding experiments were completed. If so find rest of information. If not, move on. Use PDB site as a second option/check validity of data.

## TCR Name

Name of the wild type TCR. The names of mutants are not needed. However, in some cases a series of experiments was performed using a particular mutant as a template (with its structure solved). If this is the case, list the name of that mutant, as it will serve as the wild type template that the other mutants in that series of binding experiments will be compared to

## MHCname / MHCname_PDB

MHC Allele. Most of the time the MHCname / MHCname_PDB will be identical. Some experiments were done where the MHC allele was changed from mutant to mutant, but the complex's structure was only solved for one of the MHC alleles. When this happens, list the allele that the experiment was performed with in the MHCname column. In the MHCname_PDB column, record the MHC allele found in the template structure and list all mutations from the structure allele to the allele the experiment was performed with in the MHC_mut column.

## MHC_mut

Record all mutations between the MHC the experiment was performed with and the MHC found in the <u>template structure</u>.

## MHC_mut_chain

For MHC Class I molecules this is the alpha chain, 'A'. For MHC Class II, record whether the mutations occur in the alpha or beta chain, 'A' or 'B'. The chains should also be listed in the same order their corresponding MHC mutations are listed

## TCR_mut

Record all mutations between the TCR the experiment was performed with and the TCR in the <u>template structure</u>. For residue positions ALWAYS use PDB numbering, which can be found on both PDB and on IMGT.

## kd_microM

Record the equilibrium binding constant $k_d$. Make sure the value is recorded in <u>micromolar</u>. If $k_d$ is not given in the paper, $k_d$ can be calculated from $\Delta G (\Delta G = -RT \ln k)$ or as $k_d = k_{off}/k_{on}$. Record any non-binding or no binding detected as n.d.

## Kon_per_M_per_s

Record $k_{on}$ in M<sup>-1</sup>s<sup>-1</sup> if given. Watch the order of magnitude. Record any not-detected as n.d. If $k_{on}$ is not given, put N/A.

## Koff_per_s

Record $k_{off}$ in s<sup>-1</sup> if given. Record any not detected as n.d. If $k_{off}$ is not given, put N/A.

## Kd_wt/Kd_mut

Ratio of the $k_d$ of the <u>template structure</u> to the $k_d$ of the mutant. For entries that have a true PDB ID but no template structure, put N/A.

## DeltaG_kcal_per_mol

If given in the article, use those values (make sure it's in kcal). If not, calculate using $\Delta G = -RT \ln k$.

## Delta_DeltaG_kcal_per_mol

$\Delta \Delta G = -RT \ln (k_{d\ mut} / k_{d\ template})$ or $\Delta \Delta G = \Delta G_{mut} – \Delta G_{template}$. For entries that have a true PDB ID but no template structure, put N/A.

## Temperature_K

Temperature in Kelvin the experiments were performed at. If the temperature is not 25°C / 298K, make sure you use the correct temperature in calculating $\Delta G$.

## PEPseq

Sequence of the peptide

## PEP_mut

Record any mutations needed to get from the peptide in the template structure to the peptide used in the experiment. Number the residue positions as they are in the peptide fragment, not as they are in the uncleaved protein.

\*At this point if a residue is added or deleted, do not add this data to ATLAS

## true_PDB

Record the PDB ID if applicable. A structure has a true PDB ID if there exists a solved structure of the MHC, TCR, and peptide complex with no mutations. Make sure that the structure contains all parts. This means it will have 5 (MHC Class I) or 6 (MHC class II) sequence chains: HLA (Class II will have an alpha and beta chain), beta-2-microglobulin, peptide, TCR alpha chain, and TCR beta chain.

## Structure_method

The method used to determine the crystal structure of the complex. This can be found in the box on the right side of the PDB main page for each entry. This is only recorded if a true PDB ID is recorded.

## Resolution, R-value, R-free

These can all be found in the box on the right side of the PDB main page for each entry. These are only recorded if a true PDB ID is recorded.

## Template_PDB

This is the PDB structure complex that can be mutated to obtain each particular mutant. It is possible for an entry that is based off a template structure to also have its own true PDB ID, however, if an entry doesn't have a true PDB ID, it MUST have a template PDB ID in order to be modeled.

## pMHC_PDB

Record the structure of the peptide-MHC complex if available. Don't worry about the structure method, resolution, or R-values for these.

## TCR_mut_chain

Record whether the mutations to the TCR from the template structure occur in the alpha or beta chains. If there are multiple mutations in a chain, that chain should be listed as many times as there are mutations. The chains should also be listed in the same order their corresponding TCR mutations are listed

## CDR

Record the CDR (1, 2 or 3, no a or b needed) that is mutated to form the mutant TCR from the template structure. If there are multiple mutations in a single CDR, that CDR should be listed as many times as there are mutations in that loop, in the same order their corresponding TCR mutations are listed.

## wtCDRseq

List the sequences of the CDRs from the <u>template</u> structure that must be mutated to obtain the mutant. The sequences should be listed as many times as there are mutations in the corresponding loop, in the same order the corresponding TCR mutations are listed.

## TCR_PDB_chain

Record the chain ID (D for alpha chain, E for beta chain) the TCR mutations occur in. If there are multiple mutations in a chain, that chain ID should be listed as many times as there are mutations. The chain IDs should be listed in the same order their corresponding TCR mutations are listed

## PMID

Record the seven or eight digit number the reference paper can be found under in Pubmed.

## Exp. Method

Record the method of determining experimental $k_d$ values. If surface plasmon resonance experiments were performed, record the Biacore model used.

## SPR SensorChip

Record the sensor chip used in Biacore experiments if applicable.

## Immobilized Ligand

This is the ligand (TCR or pMHC complex) that is bound to the sensor chip and immobilized in SPR experiments.

## Coupling Method

Record whether the immobilized ligand was bound to the chip directly or indirectly. Direct coupling methods include amine coupling and thiol coupling. Indirect methods include using biotin/streptavidin, antibody capture, or any other method that puts another molecule between the sensor chip and the immobilized ligand.

## Analyte

This is the ligand (TCR or pMHC complex) that is flowed over the sensor chip in SPR experiments.

## Keep in mind

In ATLAS, "wild type" does not always refer to the traditional wild type, but instead refers to the template structure that serves as a basis of comparison for a particular entry or set of entries. ATLAS is designed to test and train scoring functions. So when parsing the data, the computer does the following:

- Check to see if a true_PDB structure exists
- If TRUE, it will use that structure for scoring
- If FALSE, it will use the template_PDB structure and make all mutations specified in the TCR_mut, MHC_mut, and PEP_mut columns.

This means that the only mutations that should ever be listed are mutations that MUST be made to the template_PDB structure. This also means that a traditional wild type structure may require mutations to match the template. For example, when looking at the DMF5-HLA-A*02:01-ELAGIGILTV structure 3QDG, WT should be recorded in the pep_mut column despite the ELA peptide being a mutant of the EAA peptide. A mutant containing EAAGIGILTV should record L2A in the pep_mut column, even though EAA is the traditional wild type peptide.
