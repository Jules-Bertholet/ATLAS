<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="my_css.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/js/bootstrap.min.js"></script>

    <title>ATLAS: Database of TCR-pMHC affinities and structures</title>
</head>

<body>
    <?php require 'ATLAS_functions.php'; ?>
    <nav class="navbar navbar-default">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li><a href="./">Home</a></li>
                    <li class="active"><a href="search.php">Search</a></li>
                    <li><a href="downloads.php">Downloads</a></li>
                    <li><a href="contact.html">Contact</a></li>
                    <li><a href="https://github.com/piercelab/ATLAS">Github</a></li>
                    <li><a href="help.php">Help</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">
        <!-- <img class="my_logo" src="atlas_logo_v1.png" /> -->
        <div class="page-header">
            <h3>Search ATLAS</h3>
        </div>
        <div class="well">
            <p> Search through the ATLAS database by selecting a specific TCR, MHC allele, energy upper bound,
                or peptide sequence motif. The ATLAS search returns entries satisfying <b>all</b> selected search
                fields. To browse the entire dataset simply click 'Search' leaving all fields with their default
                parameters.
                For a more extensive description of search categories and criteria please see the <a
                    href="help.php">help</a> page.
            </p>
        </div>
    </div>

    <!-- Search -->
    <div class="container">
        <form action="search_results.php" method="POST" role="form">
            <div class="row">
                <div class="col-sm-3">
                    <div class="panel panel-danger">
                        <div class="panel-heading">
                            <h3 class="panel-title">TCR </h3>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">

                                <?php $link = database_connect(); ?>
                                <!-- Select TCR -->
                                <?php
                                    $query = "SELECT * FROM TCRs";
                                    $result = $link->query($query) or die($link->lastErrorMsg());
                                    $i = 0;
                                while ($row = $result->fetchArray()) {
                                    $TCRnames[$i] = $row['TCRname'];
                                    $i++;
                                }
                                ?>
                                <label for="Name"> Name: </label>
                                <select class="form-control" name="TCR">
                                    <option>all</option>
                                    <?php
                                    for ($j = 0; $j < count($TCRnames); $j++) {
                                        ?>
                                    <option>
                                        <?php
                                        echo $TCRnames[$j]; ?>
                                    </option>
                                        <?php
                                    }
                                    ?>
                                </select>

                                <!-- Select TRAV -->
                                <?php
                                    $query = "SELECT TRAV from TCRs";
                                    $result = $link->query($query) or die($link->lastErrorMsg());
                                    $i = 0;
                                while ($row = $result->fetchArray()) {
                                    $TRAVnames[$i] = $row['TRAV'];
                                    $i++;
                                }
                                    $TRAVnames = array_values(array_unique($TRAVnames));
                                ?>
                                <label for="TRAV"> TRAV: </label>
                                <select class="form-control" name="TRAV">
                                    <option>all</option>
                                    <?php
                                    for ($j = 0; $j < count($TRAVnames); $j++) {
                                        ?>
                                    <option>
                                        <?php
                                        echo $TRAVnames[$j]; ?>
                                    </option>
                                        <?php
                                    }
                                    ?>
                                </select>

                                <!-- Select TRBV -->
                                <?php
                                    $query = "SELECT TRBV from TCRs";
                                    $result = $link->query($query) or die($link->lastErrorMsg());
                                    $i = 0;
                                while ($row = $result->fetchArray()) {
                                    $TRBVnames[$i] = $row['TRBV'];
                                    $i++;
                                }
                                    $TRBVnames = array_values(array_unique($TRBVnames));
                                ?>
                                <label for="TRBV"> TRBV: </label>
                                <select class="form-control" name="TRBV">
                                    <option>all</option>
                                    <?php
                                    for ($j = 0; $j < count($TRBVnames); $j++) {
                                        ?>
                                    <option>
                                        <?php
                                        echo $TRBVnames[$j]; ?>
                                    </option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h3 class="panel-title"> MHC </h3>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">

                                <!-- Select MHC class -->
                                <label for="MHCclass"> Class: </label>
                                <select class="form-control" name="MHCclass">
                                    <option>all</option>
                                    <option>I</option>
                                    <option>II</option>
                                </select>

                                <!-- Select HLA allele -->
                                <?php
                                    $query = "SELECT MHCname from MHCs";
                                    $result = $link->query($query) or die($link->lastErrorMsg());
                                    $i = 0;
                                while ($row = $result->fetchArray()) {
                                    $MHCnames[$i] = $row['MHCname'];
                                    $i++;
                                }
                                ?>
                                <label for="MHCname"> Allele: </label>
                                <select class="form-control" name="MHCname">
                                    <option>all</option>
                                    <?php
                                    for ($j = 0; $j < count($MHCnames); $j++) {
                                        ?>
                                    <option>
                                        <?php
                                        echo $MHCnames[$j]; ?>
                                    </option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="panel panel-warning">
                        <div class="panel-heading">
                            <h3 class="panel-title"> Energy </h3>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <!--Select dG range -->
                                <label for="dG"> &#916G (kcal mol<sup>-1</sup>) < </label> <input type="text"
                                        class="form-control" name="dG" value="0.00">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h3 class="panel-title"> Peptide </h3>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <!--Select Peptide sequence -->
                                <label for="peptide"> Peptide Sequence: </label>
                                <input type="text" class="form-control" name="peptide" value="all">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="panel panel-danger">
                        <div class="panel-heading">
                            <h3 class="panel-title"> PDB </h3>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <?php $link = database_connect(); ?>
                                <!-- Select PDB ID -->
                                <?php
                                    $query = "SELECT DISTINCT true_PDB FROM Mutants WHERE true_PDB != ''";
                                    $result = $link->query($query) or die($link->lastErrorMsg());
                                    $i = 0;
                                while ($row = $result->fetchArray()) {
                                    $PDBids[$i] = $row['true_PDB'];
                                    $i++;
                                }
                                    sort($PDBids);
                                ?>
                                <label for="ID"> ID: </label>
                                <select class="form-control" name="pdbid">
                                    <option>all</option>
                                    <?php
                                    for ($j = 0; $j < count($PDBids); $j++) {
                                        ?>
                                    <option>
                                        <?php
                                        echo $PDBids[$j]; ?>
                                    </option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <input type="submit" name="SEL" class="btn btn-primary" value="Search"><br><br>
        </form>
        <hr>
        <footer>
            <div class="row">
                <div class="col-sm-4" align="center">
                    <img src="logos/umasslogoformal.gif" width='180' />
                </div>
                <div class="col-sm-4" align="center">
                    <img src="logos/1_university_mark.jpg" width='225' />
                </div>
                <div class="col-sm-4" align="center">
                    <img src="logos/IBBR-Logo_Long.png" width='250' />
                </div>
            </div>
            <br></br>
        </footer>
    </div>
</body>

</html>