<?php 
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require('gtDb.php');
$db = new gtDb();

if( getenv('REQUEST_METHOD') == 'POST' ){
    $raw = file_get_contents("php://input");
    $input = json_decode($raw, true);

    $method = $input['method'];
    unset($input['method']);
        

    $http = 0;
    $json = Array();

    if( $method == "add" ){

        $type = $input['type'];
        unset($input['type']);
        
        $tableName = 'tb'.$type;
        $input[$type . 'AccountID'] = $_SESSION['accountID'];
        $id = $db->insert($tableName, $input);

        if( $id ){
            $http = 201;
            $json['status'] = "callback";
            $json['cb_fct'] = "loadTable";
            $json['cb_arg'] = $type;
        }else{
            $http = 500;
            $json['status'] = "error";
            $json['description'] = "Unable to insert new record in table '$tableName'.";
            $json['error'] = $db->getLastError();
            $json['query'] = $db->getLastQuery();
        }
        goto OutputJSON;
    }

    if( $method == "chg" ){

        $type = $input['type'];
        unset($input['type']);

        $id = $input['id'];
        unset($input['id']);
        
        $tableName = 'tb'.$type;

        $db->where($type . 'AccountID', $_SESSION['accountID']);
        $db->where($type . 'ID', $id);

        if( $db->update($tableName, $input) ){
            $http = 200;
            $json['status'] = "callback";
            $json['cb_fct'] = "loadTable";
            $json['cb_arg'] = $type;
        }else{
            $http = 500;
            $json['status'] = "error";
            $json['description'] = "Unable to update record # $id in table '$tableName'.";
            $json['error'] = $db->getLastError();
            $json['query'] = $db->getLastQuery();
        }
        goto OutputJSON;
    }

    if( $method == "get" ){
        $type = $input['type'];
        unset($input['type']);

        $id = $input['id'];
        unset($input['id']);

        $tableName = 'tb' . $type;

        $db->where($type.'AccountID', $_SESSION['accountID']);
        $db->where($type.'ID', $id);
        $data = $db->get($tableName);

        if( $db->count() > 0 ){
            $http = 200;
            $json = $data;
        }else{
            $http = 404;
        }
        goto OutputJSON;
    }

}

OutputJSON:
header('Content-Type: application/json');
http_response_code($http);
echo json_encode($json);
?>