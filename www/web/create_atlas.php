#!/usr/bin/env php
<?php

function load_data($data_path, $table_name)
{
    $descriptorspec = array(
        0 => array("pipe", "r"),
        1 => array("pipe", "w"),
        2 => array("pipe", "w")
    );
    $import_command = proc_open(["sqlite3", "-tabs", "./tables/dump.sqlite", ".import " . $data_path . " " . $table_name . " --skip 1"], $descriptorspec, $pipes);
    $exit_code = proc_close($import_command);
    if ($exit_code == 0) {
        echo "Data loaded successfully\n";
    } else {
        echo "Error loading " . $table_name . " data: " . $exit_code . "\n";
    }
}

unlink("./tables/dump.sqlite");
$conn = new SQLite3("./tables/dump.sqlite");

//Path to data
$data_path = './tables/';


// MHCs
$sql = "CREATE TABLE MHCs(
        MHCname VARCHAR(20) NOT NULL,
        class VARCHAR(2) NOT NULL,
        PRIMARY KEY (MHCname));";
if ($conn->exec($sql)) {
    echo "Table created successfully\n";
} else {
    echo "Error creating table: " . $conn->lastErrorMsg() . "\n";
}

// Write to table
load_data($data_path . "MHCs.tsv", "MHCs");

// Authors
$sql = "CREATE TABLE Authors(
        PMID INT(8) NOT NULL,
        Authors TEXT NOT NULL,
        Title TEXT NOT NULL,
            Journal VARCHAR(100) NOT NULL,
            PRIMARY KEY (PMID));";
if ($conn->exec($sql)) {
    echo "Table created successfully\n";
} else {
    echo "Error creating table: " . $conn->lastErrorMsg() . "\n";
}
load_data($data_path . "Authors.tsv", "Authors");

// TCRs
$sql = "CREATE TABLE TCRs(
        TCRname VARCHAR(50) NOT NULL,
        TRAV VARCHAR(50) NOT NULL,
        TRBV VARCHAR(50) NOT NULL,
        PRIMARY KEY (TCRname));";
if ($conn->exec($sql)) {
    echo "Table created successfully\n";
} else {
    echo "Error creating table: " . $conn->lastErrorMsg() . "\n";
}

load_data($data_path . "TCRs.tsv", "TCRs");

// Mutants


$sql = "CREATE TABLE Mutants(
    Ind INT NOT NULL,
    TCRname VARCHAR(100) NOT NULL,
    MHCname VARCHAR(100) NOT NULL,
    MHCname_PDB VARCHAR(100) NOT NULL,
    MHC_mut VARCHAR(500) NOT NULL,
    MHC_mut_chain VARCHAR(100) NULL,
    TCR_mut VARCHAR(500) NOT NULL,
    Kd_microM VARCHAR(100) NOT NULL,
    Kon_per_M_per_s FLOAT NULL,
    Koff_per_s FLOAT NULL,
    Kd_wt_div_KD_mut FLOAT NULL,
    DeltaG_kcal_per_mol FLOAT NULL,
    Delta_DeltaG_kcal_per_mol FLOAT NULL,
    Temperature_K FLOAT NULL,
    PEPseq VARCHAR(100) NOT NULL,
    PEP_mut VARCHAR(100) NOT NULL,
    true_PDB VARCHAR(4) NULL,
    Structure_Method VARCHAR(100) NULL,
    Resolution FLOAT NULL,
    R_value FLOAT NULL,
    R_free FLOAT NULL,
    template_PDB VARCHAR(4) NULL,
    pMHC_PDB VARCHAR(4) NULL,
    TCR_mut_chain VARCHAR(100) NULL,
    CDR VARCHAR(100) NULL,
    wtCDRseq VARCHAR(500) NULL,
    TCR_PDB_chain VARCHAR(100) NULL,
    PMID INT(8) NOT NULL,
    Exp_Method VARCHAR(100) NULL,
    SPR_SensorChip VARCHAR(100) NULL,
    Immobilized_Ligand VARCHAR(500) NULL,
    Coupling_Method VARCHAR(100) NULL,
    Analyte VARCHAR(500) NULL, 
    PRIMARY KEY (Ind)
);";
if ($conn->exec($sql)) {
    echo "Table created successfully\n";
} else {
    echo "Error creating table: " . $conn->lastErrorMsg() . "\n";
}

load_data($data_path . "ATLAS.tsv", "Mutants", $conn);
