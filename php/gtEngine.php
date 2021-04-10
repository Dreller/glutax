<?php 
session_start();
include_once( 'lang/' . $_SESSION['accountLanguage'] . '.php' );

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

    if( $method == "updateProfile" ){
        $db->where('accountID', $_SESSION['accountID']);
        $db->update('tbAccount', $input);

        foreach($input as $key=>$value){
            $_SESSION[$key] = $value;
        }

        $http = 200;
        $json['status'] = "callback";
        $json['cb_fct'] = "bigRefresh";
        goto OutputJSON;
    }

    if( $method == "newPurchase" ){
        # Create the purchase
        $new = Array(
            "purchaseAccountID" => $_SESSION['accountID'],
            "purchaseDate" => $input['purchaseDate'],
            "purchaseReference" => $input['purchaseReference'],
            "purchasePersonID" => $input['purchasePersonID'],
            "purchaseStoreID" => $input['purchaseStoreID']
        );
        $id = $db->insert('tbPurchase', $new);

        if( $id ){
            # Multi Insert
            $new = Array();
            $upd = Array(
                "purchaseAmountNormal" => 0,
                "purchaseAmountGF" => 0,
                "purchaseAmountExtra" => 0
            );

            foreach( $input['expenses'] as $key=>$exp ){
                $new[] = Array(
                    "expenseAccountID" => $_SESSION['accountID'],
                    "expensePurchaseID" => $id,
                    "expenseLineID" => $key,
                    "expenseProductName" => $exp['popProductName'],
                    "expenseQuantity" => $exp['popProductQuantity'],
                    "expenseProductSize" => $exp['popProductSize'],
                    "expenseProductFormat" => $exp['popProductFormat'],
                    "expensePrice" => $exp['popProductPrice'],
                    "expenseEquName" => $exp['popEquProductName'],
                    "expenseEquProductSize" => $exp['popEquProductSize'],
                    "expenseEquPrice" => $exp['popEquProductPrice'],
                    "expenseExtra" => $exp['popCalcExtra']
                );

                # Add to totals
                $upd["purchaseAmountNormal"] = floatval($upd["purchaseAmountNormal"]) + floatval(($exp['popCalcREG_PricePer100']/100)*$exp['popProductSize']);
                $upd["purchaseAmountGF"] = floatval($upd["purchaseAmountGF"]) + floatval(($exp['popCalcGF_PricePer100']/100)*$exp['popProductSize']);
                $upd["purchaseAmountExtra"] = floatval($upd["purchaseAmountExtra"]) + floatval($exp['popCalcExtra']);
            }
            $ids = $db->insertMulti('tbExpense', $new);
            if( !$ids ){
                $http = 500;
                $json['status'] = "error";
                $json['error'] = $db->getLastError();
                $json['query'] = $db->getLastQuery();
                $json['toast'] = "Unable to save Expenses for Purchase # $id .";
                goto OutputJSON;
            }else{
                
                # Add totals to purchase record
                $db->where('purchaseID', $id);
                $db->where('purchaseAccountID', $_SESSION['accountID']);
                $db->update('tbPurchase', $upd);

                $http = 201;
                $json['status'] = "callback";
                $json['cb_fct'] = "loadPage";
                $json['toast'] = _TOAST_PURCH_ADDED;
                goto OutputJSON;
            }
        }else{
            $http = 500;
            $json['status'] = "error";
            $json['error'] = $db->getLastError();
            $json['query'] = $db->getLastQuery();
            $json['toast'] = "Unable to create the new Purchase.  Expenses wasn't saved either.";
        }
        goto OutputJSON;
    }

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
            $json['toast'] = _TOAST_TABLE_ADDED;
        }else{
            $http = 500;
            $json['status'] = "error";
            $json['error'] = $db->getLastError();
            $json['query'] = $db->getLastQuery();
            $json['toast'] = "Unable to insert new record in table '$tableName'.";
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
            $json['toast'] = _TOAST_TABLE_UPDATED;
        }else{
            $http = 500;
            $json['status'] = "error";
            $json['error'] = $db->getLastError();
            $json['query'] = $db->getLastQuery();
            $json['toast'] = "ERROR: Unable to update record # $id in table '$tableName'.";
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