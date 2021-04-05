<?php 
session_start();

$tableCode = strtolower($_GET['t']);
$tableName = 'tb' . ucwords($tableCode);

# Initialize variables
$pageHeader = "Unknown table ($tableName)";
$pageSubHeader = "This table is not handled by the current code.";
$foundFlag = true;

/** Table properties */
switch($tableName){
    case "tbStore":
        $pageHeader = "Stores";
        $pageSubHeader = "Record informations about stores you often go, to avoid re-entering the same infos again and again.";
        $tblColumns = Array("Name", "Address");
        $tblSqlID = "storeID";
        $tblSqlCol = Array("storeName", "storeAddress");
        break;
    case "tbPerson":
        $pageHeader = "Persons";
        $pageSubHeader = "You may often buy products by yourself, and sometime, other can buy them for you.  Track who is the buyer of each expenses for a more accurage tracking.";
        $tblColumns = Array("Name");
        $tblSqlID = "personID";
        $tblSqlCol = Array("personName");
        break;
    case "tbProduct":
        $pageHeader = "Products";
        $pageSubHeader = "Record informations about products you often buy, to avoid re-entering the same infos again and again.";
        $tblColumns = Array("SKU", "Name", "Category", "Size");
        $tblSqlID = "productID";
        $tblSqlCol = Array("productSKU", "productName", "productCategoryID", "productSize");
        break;
    default:
        $foundFlag = false;
}

?>

<h1 class="mt-5 text-white font-weight-light"><?php echo $pageHeader; ?> </h1>
<p class="lead text-white-50"><?php echo $pageSubHeader; ?></p>
<hr>

<!-- Toolbar -->
<div id="TableToolbar" class="btn-group my-2" style="float:right;" role="group" aria-label="toolbar">
    <button data-type="<?php echo $tableCode; ?>" data-action="export" type="button" class="btn btn-light disabled" onclick="launch(this);">Export</button>
    <button data-type="<?php echo $tableCode; ?>" data-action="add" type="button" class="btn btn-light" onclick="launch(this);">Add new</button>
</div>



<?php 

if( $foundFlag ){
    require '../php/BootstrapTable.php';
    $table = new BootstrapTableHelper();
    $table->hover()
    ->striped()
    ->columns($tblColumns);

    require '../php/gtDb.php';
    $db = new gtDb();
    $db->where($tableCode . "AccountID", $_SESSION['accountID']);
    $datas = $db->get($tableName, null, $tblSqlCol);
    foreach($datas as $data){
        $table->addRow($data);
    }

    echo $table->make();
    
}

?>
