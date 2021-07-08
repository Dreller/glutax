<?php  
# Includes
include_once('gtInclude.php');
# Get the requested report
$reportType = "";
if( isset($_GET['r']) && $_GET['r'] !== '' ){
    $reportType = $_GET['r'];
}
# Initialise the report title
$reportTitle = "";

# Define sets of columns
$cols_summary = Array(
    "iID",
    _LABEL_PURCH_DATE,
    _LABEL_STORE,
    _LABEL_PERSON,
    _LABEL_PURCH_EXTRA_SHORT,
    _LABEL_NOTE  
); 

$cols_details = Array(
    "iID",
    _LABEL_PURCH_DATE,
    _LABEL_STORE,
    _LABEL_SKU,
    _LABEL_PRODUCT_GF_SHORT,
    _LABEL_PRICE_UNIT,
    _LABEL_FORMAT,
    _LABEL_PRODUCT_EQU_SHORT,
    _LABEL_PRICE_UNIT,
    _LABEL_FORMAT,
    _LABEL_QUANTIY
); 

# Set Report parameters
switch($reportType){
    case "custom":
        $reportTitle = _REPORT_BROWSER;
        $summary = $_GET['summ'];
        $cols = ($summary == "y" ? $cols_summary:$cols_details);
        break;
    case "purch-all-summary":
        $reportTitle = _REPORT_PURCH_ALL_SUMMARY;
        $cols = $cols_summary;   
        break;   
    case "purch-all-details":
        $reportTitle = _REPORT_PURCH_ALL_DETAILS;
        $cols = $cols_details;   
        break; 
}
?>
<!-- Header -->
<h1 class="mt-5 text-white font-weight-light"><?= $reportTitle; ?></h1>
<p class="lead text-white-50"></p>
<hr>
<!-- Report -->
<div class="bg-light p-3 rounded shadow-sm text-start mb-3 table-responsive">
    <table id="glutaxReport" class="table table-light table-hover">
        <thead>
            <tr>
                <?php 
                    foreach( $cols as $col ){
                        echo "<th>$col</th>";
                    }
                ?>
            </tr>
        </thead>
    </table>
</div>
