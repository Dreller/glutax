<?php 
session_start();
include('../php/lang/'.$_SESSION['accountLanguage'].'.php');

$tableCode = strtolower($_GET['t']);
$tableName = 'tb' . ucwords($tableCode);

# Initialize variables
$pageHeader = "Unknown item ($tableName)";
$foundFlag = true;
$method = "add";

require_once('../php/gtForm.php');
$form = new gtForm();

# If we are in EDIT mode, retrieve actual data from DB.
if( isset($_GET['i']) ){
    require_once('../php/gtDb.php');
    $db = new gtDb();
    $db->where($tableCode."AccountID", $_SESSION['accountID']);
    $db->where($tableCode."ID", $_GET['i']);
    $ad = $db->getOne($tableName);

    if( $db->count == 1 ){
        $method = "chg";
        $form->addHidden('id', $_GET['i']);
    }
}


/** Form properties */
switch($tableName){
    case "tbStore":
        $pageHeader = _TABLE_STORE;
        insertInForm("storeName", "Store Name", "text");
        insertInForm("storeAddress", "Address or Location", "text");
        break;
    case "tbPerson":
        $pageHeader = _TABLE_PERSON;
        insertInForm("personName", "Name", "text");
        break;
    case "tbProduct":
        $pageHeader = _TABLE_PRODUCT;
        insertSectionInForm("Gluten-free Product");
        insertInForm("productName", "Name or Description", "text");
        insertInForm("productCategoryID", "Category", "product-category");
        #insertInForm("productSKU", "SKU (Barcode)", "text");
        insertInForm("productSize", "Size (eg. 200 ml, enter 200)", "number");
        insertInForm("productFormat", "Format, measure (eg. 200 ml, choose ml)", "list-measure");
        insertSectionInForm("Equivalent Product");
        insertInForm("productEquName", "Name or Description", "text");
        #insertInForm("productEquSKU", "SKU (Barcode)", "text");
        insertInForm("productEquSize", "Size (eg. 200 ml, enter 200)", "number");
        break;
    case "tbCategory":
        $pageHeader = _TABLE_CATEGORY;
        insertInForm("categoryName", "Name", "text");
        break;
    default:
        $foundFlag = false;
}

function insertInForm($name, $label, $type){
    global $ad;
    global $form;
    $a = Array(
        "name" => $name,
        "label" => $label,
        "type" => $type,
        "value" => (isset($ad[$name])?$ad[$name]:'')
    );
    $form->addControl($a);
}

function insertSectionInForm($text){
    global $form;
    $a = Array(
        "name" => "section",
        "label" => $text,
        "type" => "section"
    );
    $form->addControl($a);
}

?>

<h1 class="mt-5 text-white font-weight-light"><?php echo $pageHeader; ?></h1>
<hr>

<!-- Toolbar -->
<div class="container text-end">
    <div id="TableItemToolbar" class="btn-group my-2" role="group" aria-label="toolbar">
        <button id="Delete" type="button" class="btn btn-light disabled"><?= _BUTTON_DELETE ?></button>
        <button id="Cancel" type="button" class="btn btn-light" onclick="loadTable('<?php echo $tableCode; ?>');"><?= _BUTTON_CANCEL ?></button>
        <button id="Save" type="button" class="btn btn-light" onclick="sendForm();"><?= _BUTTON_SAVE ?></button>
    </div>
</div>

<div class="bg-light p-3 rounded shadow-sm text-start">
<?php 

if( $foundFlag ){
    $form->addHidden("method", $method);
    $form->addHidden("type", $tableCode);
    echo $form->build();
}

print "<hr>";

?>
</div>