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


if( getenv('REQUEST_METHOD') == 'POST' ){
    $raw = file_get_contents("php://input");

    $input = json_decode($raw, true);

    # Encode all values to their HTML equivalent.
    foreach( $input as $key => $value ){    
        if( is_string($value) ){
            $input[$key] = htmlspecialchars($value, ENT_QUOTES);
        }
    }

    $method = $input['method'];
    unset($input['method']);
        

 
    if( $method == "updateProfile" ){
        $db->where(_SQL_ACC_ID, $_SESSION['accountID']);
        $db->update(_SQL_ACC, $input);

        foreach($input as $key=>$value){
            $_SESSION[$key] = $value;
        }

        $http = 200;
        $json['status'] = "callback";
        $json['cb_fct'] = "bigRefresh";
        goto OutputJSON;
    }
    if( $method == "deletePurchase"){
        # Check if this user is the owner of the purchase
        $db->where("purchaseAccountID", $_SESSION['accountID']);
        $db->where("purchaseID", $input['purchaseID']);
        if( $db->delete(_SQL_PUR) ){
                $db->where("expenseAccountID", $_SESSION['accountID']);
                $db->where("expensePurchaseID", $input['purchaseID']);
                $db->delete(_SQL_EXP);
            $http = 200;
            $json['status'] = "callback";
            $json['cb_fct'] = "goHome";
            $json['toast'] = "Purchase deleted";
            goto OutputJSON;
        }else{
            $http = 200;
            $json['status'] = "error";
            $json['error'] = $db->getLastError;
            $json['toast'] = "ERROR DELETING THIS PURCHASE";
            goto OutputJSON;
        }
    }
    if( $method == "editPurchase" ){
        # Array of the purchase
        $updated = Array(
            "purchaseDate" => $input['purchaseDate'],
            "purchaseReference" => $input['purchaseReference'],
            "purchasePersonID" => $input['purchasePersonID'],
            "purchaseStoreID" => $input['purchaseStoreID']
        );

        # Store Purchase number.
        $thisNumber = $input['purchaseNumber'];

        $db->where("purchaseAccountID", $_SESSION['accountID']);
        $db->where("purchaseID", $input['purchaseID']);
        $db->update(_SQL_PUR, $updated);

        # Update lines
        $upd = Array(
            "purchaseAmountNormal" => 0,
            "purchaseAmountGF" => 0,
            "purchaseAmountExtra" => 0
        );

        foreach( $input['expenses'] as $key=>$exp ){
                $new = Array(
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

                # Determine if it already exists
                $db->where(_SQL_EXP_ACCOUNT, $_SESSION['accountID']);
                $db->where(_SQL_EXP_PURCHASE, $input['purchaseID']);
                $db->where(_SQL_EXP_LINE, $key);
                $db->get(_SQL_EXP);

                if( $db->count == 0 ){
                    $new['expenseAccountID'] = $_SESSION['accountID'];
                    $new['expensePurchaseID'] = $input['purchaseID'];
                    $new['expenseLineID'] = $key;
                    $db->insert(_SQL_EXP, $new);
                }else{
                    $db->where(_SQL_EXP_ACCOUNT, $_SESSION['accountID']);
                    $db->where(_SQL_EXP_PURCHASE, $input['purchaseID']);
                    $db->where(_SQL_EXP_LINE, $key);
                    $db->update(_SQL_EXP, $new);
                }

                # Add to totals
                $upd["purchaseAmountNormal"] = floatval($upd["purchaseAmountNormal"]) + floatval(($exp['popCalcREG_PricePer100']/100)*$exp['popProductSize']);
                $upd["purchaseAmountGF"] = floatval($upd["purchaseAmountGF"]) + floatval(($exp['popCalcGF_PricePer100']/100)*$exp['popProductSize']);
                $upd["purchaseAmountExtra"] = floatval($upd["purchaseAmountExtra"]) + floatval($exp['popCalcExtra']);
            }

            $db->where('purchaseID', $input['purchaseID']);
            $db->update(_SQL_PUR, $upd);

            $http = 200;
            $json['status'] = "callback";
            $json['cb_fct'] = "goHome";
            $json['toast'] = "Purchase #$thisNumber updated";
            goto OutputJSON;
    }
    if( $method == "newPurchase" ){
        # Get the next purchase number for this account
        $thisNumber = ($_SESSION[_SQL_ACC_NEXT_PURCH]);
        $_SESSION[_SQL_ACC_NEXT_PURCH] = $thisNumber + 1;

        $new = Array(
            _SQL_ACC_NEXT_PURCH => $_SESSION[_SQL_ACC_NEXT_PURCH]
        );

        $db->where(_SQL_ACC_ID, $_SESSION[_SQL_ACC_ID]);
        $db->update(_SQL_ACC, $new);

        # Create the purchase
        $new = Array(
            "purchaseAccountID" => $_SESSION['accountID'],
            "purchaseNumber" => $thisNumber,
            "purchaseDate" => $input['purchaseDate'],
            "purchaseReference" => $input['purchaseReference'],
            "purchasePersonID" => $input['purchasePersonID'],
            "purchaseStoreID" => $input['purchaseStoreID']
        );
        $id = $db->insert(_SQL_PUR, $new);

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
            $ids = $db->insertMulti(_SQL_EXP, $new);
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
                $db->update(_SQL_PUR, $upd);

                $http = 201;
                $json['status'] = "callback";
                $json['cb_fct'] = "loadPage";
                $json['toast'] = _TOAST_PURCH_ADDED . " (# $thisNumber )";
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