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
# Set Report parameters
switch($reportType){
    case "purch-all-summary":
        $reportTitle = _REPORT_PURCH_ALL_SUMMARY;
        $cols = Array(
            _LABEL_PURCH_DATE,
            _LABEL_STORE,
            _LABEL_PERSON,
            _LABEL_PURCH_EXTRA_SHORT,
            _LABEL_NOTE  
        );    
        break;   
        case "purch-all-details":
            $reportTitle = _REPORT_PURCH_ALL_DETAILS;
            $cols = Array(
                _LABEL_PURCH_DATE,
                _LABEL_STORE,
                _LABEL_PRODUCT_GF_SHORT,
                _LABEL_QUANTIY,
                _LABEL_PRICE_UNIT,
                _LABEL_FORMAT
            );    
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
