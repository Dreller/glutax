<?php 
session_start();

$tableCode = strtolower($_GET['t']);
$tableName = 'tb' . ucwords($tableCode);

# Initialize variables
$pageHeader = "Unknown item ($tableName)";
$foundFlag = true;

require_once('../php/gtForm.php');
$form = new gtForm();

/** Form properties */
switch($tableName){
    case "tbStore":
        $pageHeader = "Store";

        $a = Array(
            "name" => "storeName",
            "label" => "Store Name",
            "type" => "text"
        );$form->addControl($a);

        $a = Array(
            "name" => "storeAddress",
            "label" => "Address",
            "type" => "text"
        );$form->addControl($a);
        
        break;
    case "tbPerson":
        $pageHeader = "Person";

        $a = Array(
            "name" => "personName",
            "label" => "Name",
            "type" => "text"
        );$form->addControl($a);

        break;
    case "tbProduct":
        $pageHeader = "Product";

        $a = Array(
            "name" => "productSKU",
            "label" => "SKU (Barcode)",
            "type" => "text"
        );$form->addControl($a);

        $a = Array(
            "name" => "productName",
            "label" => "Name",
            "type" => "text"
        );$form->addControl($a);

        break;
    default:
        $foundFlag = false;
}

?>

<h1 class="mt-5 text-white font-weight-light"><?php echo $pageHeader; ?> </h1>
<hr>

<!-- Toolbar -->
<div id="TableItemToolbar" class="btn-group my-2" role="group" aria-label="toolbar">
    <button id="Delete" type="button" class="btn btn-light disabled">Delete</button>
    <button id="Save" type="button" class="btn btn-light">Save</button>
</div>

<div>
<?php 

if( $foundFlag ){

    echo $form->build();
    


    //require '../php/gtDb.php';
    //$db = new gtDb();

    
}

?>
</div>