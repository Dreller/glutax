<?php 
session_start();
include('../php/lang/'.$_SESSION['accountLanguage'].'.php');

require_once('../php/gtDb.php');
$db = new gtDb();

# Retrieve the Purchase Infos
$db->where('purchaseAccountID', $_SESSION['accountID']);
$db->where('purchaseID', $_GET['i']);
$db->join('tbStore', 'purchaseStoreID = storeID', 'LEFT');
$db->join('tbPerson', 'purchasePersonID = personID', 'LEFT');
$purch = $db->getOne('tbPurchase');

if( $db->count == 0 ){
    echo 'Error: This purchase is not yours.';
    exit();
}
?>

<dl class="row">

    <dt class="col-sm-3 text-start"><?= _LABEL_PURCH_DATE ?></dt>
    <dd class="col-sm-9 text-start"><?php echo $purch['purchaseDate']; ?></dd>

    <dt class="col-sm-3 text-start"><?= _LABEL_PERSON ?></dt>
    <dd class="col-sm-9 text-start"><?php 
        if( $purch['purchasePersonID'] == 0 ){
            echo '('._SETTING_YOU.') '.$_SESSION['accountName'];
        }else{
            echo $purch['personName'];
        }
    ?></dd>

    <dt class="col-sm-3 text-start"><?= _LABEL_STORE ?></dt>
    <dd class="col-sm-9 text-start"><?php 
        echo $purch['storeName'];
        if( $purch['storeAddress'] != '' ){
            echo '<br>' . $purch['storeAddress'];
        }
    ?></dd>

    <dt class="col-sm-3 text-start"><?= _LABEL_REF ?></dt>
    <dd class="col-sm-9 text-start"><?php echo $purch['purchaseReference']; ?></dd>

    <hr class="mt-3">

    <!-- Products --> 
    

</dl>
