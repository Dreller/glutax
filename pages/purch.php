<?php 
session_start();

$pageHeader = "New Purchase";

require_once('../php/gtDb.php');
$db = new gtDb();
?>

<h1 class="mt-5 text-white font-weight-light"><?php echo $pageHeader; ?> </h1>
<p class="lead text-white-50"></p>
<hr>

<div class="bg-light p-3 rounded shadow-sm">
<h4>Purchase infos</h4>
    <div class="row g-3">
        <div class="col-md-6">
            <label for="purchaseDate" class="form-label text-start">Date</label>
            <input type="date" id="purchaseDate" name="purchaseDate" value="" class="form-control">
        </div>
        <div class="col-md-6">
            <label for="purchaseStoreID" class="form-label">Store</label>
            <select id='purchaseStoreID' name='purchaseStoreID' class='form-select'>
                <?php 
                    $db->where('storeAccountID', $_SESSION['accountID']);
                    $db->orderBy('storeName', 'ASC');
                    $options = $db->get('tbStore');
                    if( $db->count > 1 ){
                        echo "<option value='0'>Choose...</option>";
                    }
                    foreach($options as $option){
                        echo '<option value="'.$option['storeID'].'">'.$option['storeName'].' - '.$option['storeAddress'].'</option>';
                    }
                ?>
            </select>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-6">
            <label for="purchasePersonID" class="form-label text-start">Person</label>
            <select id='purchasePersonID' name='purchasePersonID' class='form-select'>
                <option value='0'>Choose...</option>
                <?php 
                    $db->where('personAccountID', $_SESSION['accountID']);
                    $db->orderBy('personName', 'ASC');
                    $options = $db->get('tbPerson');
                    if( $db->count > 1 ){
                        echo "<option value='0'>Choose...</option>";
                    }
                    foreach($options as $option){
                        echo '<option value="'.$option['personID'].'">'.$option['personName'].'</option>';
                    }
                ?>
            </select>
        </div>
        <div class="col-md-6">
            <label for="purchaseReference" class="form-label">Reference</label>
            <input type="text" class="form-control" id="purchaseReference" name="purchaseReference">
        </div>
    </div>
</div>

<div class="bg-light p-3 rounded shadow-sm mt-2">
    <h4>Products</h4>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <th>
                    Gluten-free
                </th>
                <th>
                    Quantity
                </th>
                <th>
                    Price
                </th>
                <th>
                    Format
                </th>
                <th>
                    Regular
                </th>
                <th>
                    Price
                </th>
                <th>
                    Format
                </th>
                <th>
                    Extra
                </th>
            </thead>
        </table>
    </div>
    
    <button class="btn btn-primary" onclick="addProduct();">Add a product</button>

</div>

<div class="bg-light p-3 rounded shadow-sm mt-2">
    <h4>Summary</h4>

</div>


<!-- MODAL: Edit Product Line -->  
<div class="modal fade" id="modalProduct" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalProductTitle">(Title)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                
                <label for="popProductName" class="form-label text-start">Gluten-free Product</label>
                <input type="text" class="form-control" id="popProductName">

                

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="modalProductOK">(OK)</button>
            </div>
        </div>
    </div>
</div>

<script>
    function addProduct(){
        document.getElementById("modalProductTitle").innerHTML = "Add a Product";
        document.getElementById("modalProductOK").innerHTML = "Save";
        var myModal = new bootstrap.Modal(document.getElementById('modalProduct'), {
            keyboard: false,
            backdrop: 'static'
        });
        myModal.show();
    }
    function chgProduct(){
        document.getElementById("modalProductTitle").innerHTML = "Change a Product";
        document.getElementById("modalProductOK").innerHTML = "Update";
        var myModal = new bootstrap.Modal(document.getElementById('modalProduct'), {
            keyboard: false,
            backdrop: 'static'
        });
        myModal.show();
    }
</script>