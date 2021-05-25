<?php 
session_start();
include_once( 'lang/' . $_SESSION['accountLanguage'] . '.php' );
include_once('gtMap.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require('gtDb.php');
$db = new gtDb();

$http = 0;
$json = Array();

$result = "";
$message = "";

switch($_GET['type']){
    case "SKU":
        $sku = $_GET['sku'];
        $prod= $_GET['p'];
        $db->where(_SQL_PRO_ACCOUNT, $_SESSION[_SQL_ACC_ID]);
        $db->where(_SQL_PRO_SKU, $sku);
        $db->where(_SQL_PRO_ID, $prod, "<>");
        $temp = $db->getOne(_SQL_PRO);
        if( $db->count > 0 ){
            $result = "error";
            $message = sprintf(_ERROR_SKU_USED, $sku, $temp[_SQL_PRO_NAME]);
        }else{
            $result = "ok";
            $message = $sku;
        }
}

$json['result'] = $result;
if( $message != "" ){
    $json['msg'] = $message;
}
header('Content-Type: application/json');
echo json_encode($json);
?>