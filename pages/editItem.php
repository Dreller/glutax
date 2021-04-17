<?php 
session_start();
include('../php/lang/'.$_SESSION['accountLanguage'].'.php');
include('../php/gtMap.php');

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
        insertInForm(_SQL_STO_NAME, _LABEL_NAME, "text");
        insertInForm(_SQL_STO_ADDRESS, _LABEL_ADDRESS, "text");
        break;
    case "tbPerson":
        $pageHeader = _TABLE_PERSON;
        insertInForm(_SQL_PER_NAME, _LABEL_NAME, "text");
        break;
    case "tbProduct":
        $pageHeader = _TABLE_PRODUCT;
        insertSectionInForm(_LABEL_PRODUCT_GF);
        insertInForm(_SQL_PRO_NAME, _LABEL_NAME, "text");
        insertInForm(_SQL_PRO_CATEGORY, _LABEL_CATEGORY, "product-category");
        insertInForm(_SQL_PRO_SKU, _LABEL_SKU, "text");
        insertInForm(_SQL_PRO_SIZE, _LABEL_SIZE_HELP, "number");
        insertInForm(_SQL_PRO_FORMAT, _LABEL_FORMAT_HELP, "list-measure");
        insertInForm(_SQL_PRO_PRICE, _LABEL_PRICE_UNIT, "number");
        insertSectionInForm(_LABEL_PRODUCT_EQU);
        insertInForm(_SQL_EQU_NAME, _LABEL_NAME, "text");
        insertInForm(_SQL_EQU_SIZE, _LABEL_SIZE_HELP, "number");
        insertInForm(_SQL_EQU_PRICE, _LABEL_PRICE_UNIT, "number");
        break;
    case "tbCategory":
        $pageHeader = _TABLE_CATEGORY;
        insertInForm(_SQL_CAT_NAME, _LABEL_NAME, "text");
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

<div class="bg-light p-3 rounded shadow-sm text-start mb-3">
<?php 

if( $foundFlag ){
    $form->addHidden("method", $method);
    $form->addHidden("type", $tableCode);
    echo $form->build();
}

print "<hr>";

?>
</div>