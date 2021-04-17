<?php
# Global Include.
include('../php/gtInclude.php');
# Create the Table Name from incomming parameter.
$tableCode = strtolower($_GET['t']);
$tableName = 'tb' . ucwords($tableCode);
# Initialize variables.
$pageHeader = "Unknown table ($tableName)";
$pageSubHeader = "This table is not handled by the current code.";
$foundFlag = true;
# Replace variables according to the Table.
switch($tableName){
    case "tbStore":
        $pageHeader = _TABLE_STORE."s";
        $pageSubHeader = _TABLE_STORE_HELP;
        $tblColumns = Array(_LABEL_NAME, _LABEL_ADDRESS);
        $tblSqlID = _SQL_STO_ID;
        $tblSqlCol = Array(_SQL_STO_NAME, _SQL_STO_ADDRESS);
        break;
    case "tbPerson":
        $pageHeader = _TABLE_PERSON."s";
        $pageSubHeader = _TABLE_PERSON_HELP;
        $tblColumns = Array(_LABEL_NAME);
        $tblSqlID = _SQL_PER_ID;
        $tblSqlCol = Array(_SQL_PER_NAME);
        break;
    case "tbProduct":
        $pageHeader = _TABLE_PRODUCT."s";
        $pageSubHeader = _TABLE_PRODUCT_HELP;
        $tblColumns = Array(_LABEL_CATEGORY, _LABEL_PRODUCT_GF, _LABEL_PRODUCT_EQU);
        $tblSqlID = _SQL_PRO_ID;
        $tblSqlCol = Array("(SELECT "._SQL_CAT_NAME." FROM "._SQL_CAT." WHERE "._SQL_CAT_ID." = "._SQL_PRO_CATEGORY.") AS Category", "CONCAT("._SQL_PRO_NAME.", ' (', "._SQL_PRO_SIZE.", ' ', "._SQL_PRO_FORMAT.", ')') As GFProduct", "CONCAT("._SQL_EQU_NAME.", ' (', "._SQL_EQU_SIZE.", ' ', "._SQL_PRO_FORMAT.", ')') AS GProduct");
        break;
    case "tbCategory":
        $pageHeader = _TABLE_CATEGORY."s";
        $pageSubHeader = _TABLE_CATEGORY_HELP;
        $tblColumns = Array(_LABEL_NAME);
        $tblSqlID = _SQL_CAT_ID;
        $tblSqlCol = Array(_SQL_CAT_NAME);
        break;
    default:
        $foundFlag = false;
}
?>
<!-- Header -->
<h1 class="mt-5 text-white font-weight-light"><?php echo $pageHeader; ?> </h1>
<p class="lead text-white-50"><?php echo $pageSubHeader; ?></p>
<hr>
<!-- Toolbar -->
<div class="container text-end">
    <div id="TableToolbar" class="btn-group my-2" role="group" aria-label="toolbar">
        <button data-type="<?php echo $tableCode; ?>" data-action="export" type="button" class="btn btn-light disabled" onclick="launch(this);"><?= _BUTTON_EXPORT ?></button>
        <button data-type="<?php echo $tableCode; ?>" data-action="add" type="button" class="btn btn-light" onclick="launch(this);"><?= _BUTTON_ADD_NEW ?></button>
    </div>
</div>
<!-- Table -->
<div class="bg-light p-3 rounded shadow-sm">
<?php 
if( $foundFlag ){
    require '../php/BootstrapTable.php';
    $table = new BootstrapTableHelper();
    $table->hover()
    ->striped()
    ->columns($tblColumns);

    $db = new gtDb();
    $db->where($tableCode . "AccountID", $_ACCT);
    array_push($tblSqlCol, $tblSqlID);
    $datas = $db->get($tableName, null, $tblSqlCol);
    foreach($datas as $data){
        $data['data-id'] = $data[$tblSqlID];
        unset($data[$tblSqlID]);
        $data['data-type'] = $tableCode;
        $table->addRow($data);
    }
    echo $table->make(); 
}
?>
</div>