<?php 
# Global Include
include('../php/gtInclude.php');
# Initialise Database
$db = new gtDb();
# Retrieve the Purchase Infos
$db->where(_SQL_PUR_ACCOUNT, $_ACCT);
$db->where(_SQL_PUR_ID, $_GET['i']);
$db->join(_SQL_STO, _SQL_PUR_STORE.' = '._SQL_STO_ID, 'LEFT');
$db->join(_SQL_PER, _SQL_PUR_PERSON.' = '._SQL_PER_ID, 'LEFT');
$purch = $db->getOne(_SQL_PUR);
# If nothing is found...
if( $db->count == 0 ){
    echo 'Error: This purchase is not yours.';
    exit();
}
?>
<!-- Purchase infos -->
<dl class="row">
    <!-- Purchase Date -->
    <dt class="col-sm-3 text-start"><?= _LABEL_PURCH_DATE ?></dt>
    <dd class="col-sm-9 text-start"><?php echo $_DATE->format(strtotime($purch[_SQL_PUR_DATE])); ?></dd>
    <!-- Purchase Buyer -->
    <dt class="col-sm-3 text-start"><?= _LABEL_PERSON ?></dt>
    <dd class="col-sm-9 text-start"><?php 
        if( $purch[_SQL_PUR_PERSON] == 0 ){
            echo '('._SETTING_YOU.') '.$_NAME;
        }else{
            echo $purch[_SQL_PER_NAME];
        }
    ?></dd>
    <!-- Store -->
    <dt class="col-sm-3 text-start"><?= _LABEL_STORE ?></dt>
    <dd class="col-sm-9 text-start"><?php 
        echo $purch[_SQL_STO_NAME];
        if( $purch[_SQL_STO_ADDRESS] != '' ){
            echo '<br>' . $purch[_SQL_STO_ADDRESS];
        }
    ?></dd>
    <!-- Reference -->
    <dt class="col-sm-3 text-start"><?= _LABEL_REF ?></dt>
    <dd class="col-sm-9 text-start"><?php echo $purch[_SQL_PUR_REF]; ?></dd>
    <!-- Total of Extra Amount -->
    <dt class="col-sm-3 text-start"><?= _LABEL_PURCH_EXTRA_SHORT ?></dt>
    <dd class="col-sm-9 text-start"><strong><?php echo $_CURRENCY->format($purch[_SQL_PUR_AMT_EXTRA]); ?></strong></dd>
    <!-- List of products in separate cards -->
    <div class="card">
    <ul class="list-group list-group-flush">
        <!-- Products --> 
        <?php
        $db->where(_SQL_EXP_ACCOUNT, $_ACCT);
        $db->where(_SQL_EXP_PURCHASE, $purch[_SQL_PUR_ID]);
        $products = $db->get(_SQL_EXP);
        foreach( $products as $product ){
            $prodGF_per100 = ($product[_SQL_EXP_PRO_PRICE]/$product[_SQL_EXP_PRO_SIZE])*100;
            $prodEQU_per100 = ($product[_SQL_EXP_EQU_PRICE]/$product[_SQL_EXP_EQU_SIZE])*100;
            $exQuantity = $product[_SQL_EXP_QUANTITY];
            $exProName = $product[_SQL_EXP_PRO_NAME];
            $exProSize = $product[_SQL_EXP_PRO_SIZE];
            $exProFormat = $product[_SQL_EXP_PRO_FORMAT];
            $exProPrice = $_CURRENCY->format($product[_SQL_EXP_PRO_PRICE]);
            $exPro100 = $_CURRENCY->format($prodGF_per100);
            $exEquName = $product[_SQL_EXP_EQU_NAME];
            $exEquSize = $product[_SQL_EXP_EQU_SIZE];
            $exEquPrice = $_CURRENCY->format($product[_SQL_EXP_EQU_PRICE]);
            $exEqu100 = $_CURRENCY->format($prodEQU_per100);

            echo '<li class="list-group-item">'.$exQuantity.' x <strong>'.$exProName.'</strong>
                  <span class="text-muted">'.$exProSize.' '.$exProFormat .'</span> @ '.$exProPrice.' ('.$exPro100.' / 100 '.$exProFormat.')<br>
                  <small>'.$exEquName.' '.$exEquSize.' '.$exProFormat.' @ '.$exEquPrice.' ('.$exEqu100.' / 100 '.$exProFormat.')</small>
            </li>';
        }
        ?>
    </ul>
    </div>
</dl>
