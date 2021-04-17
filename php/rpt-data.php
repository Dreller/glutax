<?php
include_once('gtInclude.php');
$db = new gtDb();
$db->where(_SQL_PUR_ACCOUNT, $_ACCT);
$db->join(_SQL_EXP, _SQL_EXP_PURCHASE . '=' . _SQL_PUR_ID);
$db->join(_SQL_STO, _SQL_STO_ID .'='. _SQL_PUR_STORE, 'LEFT');
$db->join(_SQL_PER, _SQL_PER_ID .'='. _SQL_PUR_PERSON, 'LEFT');
$db->orderBy(_SQL_PUR_DATE, 'DESC');

$cols = Array(
    _SQL_PUR_DATE,
    _SQL_STO_NAME
);

$rows = $db->get(_SQL_PUR, null, $cols);

foreach($rows as $row){
    $lowLevel = [];
    foreach( $cols as $col ){
        $lowLevel[] = $row[$col];
    }
    $data[] = $lowLevel;
}

$dtable = Array(
    "draw"  => 1,
    "recordsTotal" => $db->count,
    "recordsFiltered" => $db->count,
    "data"  => $data
);

echo json_encode($dtable);
?>