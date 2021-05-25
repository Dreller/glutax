<?php
# Includes
include_once('gtInclude.php');
# Initialize a database connexion
$db = new gtDb();
# Get the requested report
$reportType = "";
if( isset($_GET['r']) && $_GET['r'] !== '' ){
    $reportType = $_GET['r'];
}
# Set globally arrays of specific data formatting
$toCurrency = Array(
    _SQL_PUR_AMT_EXTRA,
    _SQL_EXP_PRO_PRICE
);
$toDate = Array(
    _SQL_PUR_DATE
);
# Define sets of columns
$cols_summary = Array(
            _SQL_PUR_DATE,
            _SQL_STO_NAME,
            _SQL_PER_NAME,
            _SQL_PUR_AMT_EXTRA,
            _SQL_PUR_NOTE
        );

$cols_details = Array(
    _SQL_PUR_DATE,
    _SQL_STO_NAME,
    _SQL_PRO_SKU,
    _SQL_EXP_PRO_NAME,
    _SQL_EXP_PRO_PRICE,
    'CONCAT('._SQL_EXP_PRO_SIZE.'," ",'._SQL_EXP_PRO_FORMAT.') AS prodPackage',
    _SQL_EXP_EQU_NAME,
    _SQL_EXP_EQU_PRICE,
    'CONCAT('._SQL_EXP_EQU_SIZE.'," ",'._SQL_EXP_PRO_FORMAT.') AS equPackage',
    _SQL_EXP_QUANTITY
);

# Set Report parameters
switch($reportType){
    case "custom":

        $startDate = $_GET['start'];
        $endDate = $_GET['end'];
        $summary = $_GET['summ'];

        $table = _SQL_PUR;

        $db->where(_SQL_PUR_DATE, $startDate, ">=");
        $db->where(_SQL_PUR_DATE, $endDate, "<=");

        if( $summary == "y" ){
            $cols = $cols_summary;
            $db->join(_SQL_STO, _SQL_STO_ID .'='. _SQL_PUR_STORE, 'LEFT');
            $db->join(_SQL_PER, _SQL_PER_ID .'='. _SQL_PUR_PERSON, 'LEFT');
        }else{
            $cols = $cols_details;
            $db->join(_SQL_EXP, _SQL_EXP_PURCHASE . '=' . _SQL_PUR_ID);
            $db->join(_SQL_STO, _SQL_STO_ID .'='. _SQL_PUR_STORE, 'LEFT');
            $db->join(_SQL_PRO, _SQL_PRO_ID. '=' . _SQL_EXP_PRODUCT, 'LEFT');
        }

        $db->orderBy(_SQL_PUR_DATE, 'DESC'); 
        break;
    case "purch-all-summary":
        $table = _SQL_PUR;
        $cols = $cols_summary;
        $db->join(_SQL_STO, _SQL_STO_ID .'='. _SQL_PUR_STORE, 'LEFT');
        $db->join(_SQL_PER, _SQL_PER_ID .'='. _SQL_PUR_PERSON, 'LEFT');
        $db->orderBy(_SQL_PUR_DATE, 'DESC');        
        break;
    case "purch-all-details":
        $table = _SQL_PUR;
        $cols = $cols_details;
        $db->join(_SQL_EXP, _SQL_EXP_PURCHASE . '=' . _SQL_PUR_ID);
        $db->join(_SQL_STO, _SQL_STO_ID .'='. _SQL_PUR_STORE, 'LEFT');
        $db->join(_SQL_PRO, _SQL_PRO_ID. '=' . _SQL_EXP_PRODUCT, 'LEFT');
        $db->orderBy(_SQL_PUR_DATE, 'DESC');        
        break;
}
# Execute the query
$rows = $db->get($table, null, $cols);
# For each record, we need to convert them as a value-only array
foreach($rows as $row){
    $lowLevel = [];
    foreach( $cols as $col ){
        # Need to handle CONCAT passed in Columns Array
        $colName = $col;
        if( substr($colName, 0, 6) == 'CONCAT'){
            $parts = explode(" ", $colName);
            $colName = end($parts);
        }
        # Save the value of the column
        $myData = $row[$colName];
        # Currency Conversion
        if( in_array($colName, $toCurrency) ){
            $myData = $_CURRENCY->format($myData);
        }
        # Date Conversion
        if( in_array($colName, $toDate) ){
            $myData = $_DATE->format(strtotime($myData));
        }
        # Insert in temporary array
        $lowLevel[] = $myData;
    }
    # Insert in parent-array
    $data[] = $lowLevel;
}
# Prepare the output
$dtable = Array(
    "draw"  => 1,
    "recordsTotal" => $db->count,
    "recordsFiltered" => $db->count,
    "data"  => $data
);
# Return JSON
echo json_encode($dtable);
?>