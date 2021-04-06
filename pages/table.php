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
        $tblColumns = Array("Category", "Product", "Equivalent");
        $tblSqlID = "productID";
        $tblSqlCol = Array("(SELECT categoryName FROM tbCategory where categoryID = productCategoryID) As Category", "CONCAT(productName, ' (', productSize, ' ', productFormat, ')') As GFProduct", "CONCAT(productEquName, ' (', productEquSize, ' ', productFormat, ')') AS GProduct");
        break;
    case "tbCategory":
        $pageHeader = "Categories";
        $pageSubHeader = "Add a category to each of your purchases, for a better summary on reports.";
        $tblColumns = Array("Name");
        $tblSqlID = "categoryID";
        $tblSqlCol = Array("categoryName");
        break;
    default:
        $foundFlag = false;
}

?>

<h1 class="mt-5 text-white font-weight-light"><?php echo $pageHeader; ?> </h1>
<p class="lead text-white-50"><?php echo $pageSubHeader; ?></p>
<hr>

<!-- Toolbar -->
<div class="container text-end">
    <div id="TableToolbar" class="btn-group my-2" role="group" aria-label="toolbar">
        <button data-type="<?php echo $tableCode; ?>" data-action="export" type="button" class="btn btn-light disabled" onclick="launch(this);">Export</button>
        <button data-type="<?php echo $tableCode; ?>" data-action="add" type="button" class="btn btn-light" onclick="launch(this);">Add new</button>
    </div>
</div>


<div class="bg-light p-3 rounded shadow-sm">
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