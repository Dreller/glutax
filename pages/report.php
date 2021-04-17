<?php  
# Global Includes.
include_once('../php/gtInclude.php');
# Get Report Type
$rType = "";
if( isset($_GET['r']) ){
    $rType = $_GET['r'];
}
# Check if Report definition exists
$defPath = '../php/rpt/' . $rType . '.php';
if( !file_exists($defPath) ){
    echo "ERROR: REPORT '$rType' IS NOT DEFINED";
    die();
}

# Variables
$db = new gtDb();
include($defPath);

?>