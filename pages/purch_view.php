<?php 
include('../php/gtInclude.php');

$fmt_cur = new NumberFormatter($_SESSION['accountLocale'], NumberFormatter::CURRENCY);

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

    <dt class="col-sm-3 text-start"><?= _LABEL_PURCH_EXTRA_SHORT ?></dt>
    <dd class="col-sm-9 text-start"><strong><?php echo $fmt_cur->format($purch['purchaseAmountExtra']); ?></strong></dd>

    <div class="card">
    <ul class="list-group list-group-flush">
        <!-- Products --> 
        <?php
        $db->where('expenseAccountID', $_SESSION['accountID']);
        $db->where('expensePurchaseID', $purch['purchaseID']);
        $products = $db->get('tbExpense');
        foreach( $products as $product ){
            $prodGF_per100 = ($product['expensePrice']/$product['expenseProductSize'])*100;
            $prodEQU_per100 = ($product['expenseEquPrice']/$product['expenseEquProductSize'])*100;
            echo '<li class="list-group-item">'.$product['expenseQuantity'].' x <strong>'.$product['expenseProductName'].'</strong>
                  <span class="text-muted">'.$product['expenseProductSize'].' '.$product['expenseProductFormat'] .'</span> @ '.$fmt_cur->format($product['expensePrice']).' ('.$fmt_cur->format($prodGF_per100).' / 100 '.$product['expenseProductFormat'].')<br>
                  <small>'.$product['expenseEquName'].' '.$product['expenseEquProductSize'].' '.$product['expenseProductFormat'].' @ '.$fmt_cur->format($product['expenseEquPrice']).' ('.$fmt_cur->format($prodEQU_per100).' / 100 '.$product['expenseProductFormat'].')</small>
            </li>';
        }
        ?>
    </ul>
    </div>
</dl>
