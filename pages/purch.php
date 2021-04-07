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
        <table class="table table-hover" id="purchTable">
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
            <tbody id="purchTableBody">
            </tbody>
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
            <div class="modal-body" id="modalProductForm">

                <!-- Gluten-free Product -->
                    <h4>Glute-free Product</h4>
                <!-- Description -->
                    <div class="mb-3">
                        <label for="popProductName" class="form-label text-start">Description</label>
                        <input type="text" class="form-control" id="popProductName" value="Feuilles de laurier">
                    </div>
                <!-- GF: Quantity & Price -->
                    <div class="row mb-3 g-3">
                        <div class="col">
                            <label for="popProductQuantity" class="form-label text-start">Quantity</label>
                            <input type="number" min="0" class="form-control" id="popProductQuantity" value="1">
                        </div>
                        <div class="col">
                            <label for="popProductPrice" class="form-label text-start">Price per unit</label>
                            <input type="number" min="0" class="form-control" id="popProductPrice" value="4.99">
                        </div>
                    </div>
                <!-- GF: Size & Format -->
                    <div class="row mb-3 g-3">
                        <div class="col">
                            <label for="popProductSize" class="form-label text-start">Size</label>
                            <input type="number" min="0" class="form-control" id="popProductSize" value="10">
                        </div>
                        <div class="col">
                            <label for="popProductFormat" class="form-label text-start">Format</label>
                            <select class="form-select" id="popProductFormat">
                                <option value="g" selected>grams</option>
                                <option value="mL">milliliters</option>
                            </select>
                        </div>
                    </div>
                <!-- Equivalent (with gluten) product -->
                    <h4>Equivalent Product</h4>
                <!-- Description -->
                    <div class="mb-3">
                        <label for="popEquProductName" class="form-label text-start">Description</label>
                        <input type="text" class="form-control" id="popEquProductName" value="Nos Compliments">
                    </div>
                <!-- Price & Size -->
                    <div class="row mb-3 g-3">
                        <div class="col">
                            <label for="popEquProductPrice" class="form-label text-start">Price</label>
                            <input type="number" min="0" class="form-control" id="popEquProductPrice" value="2.79">
                        </div>
                        <div class="col">
                            <label for="popEquProductSize" class="form-label text-start">Size</label>
                            <input type="number" min="0" class="form-control" id="popEquProductSize" value="22">
                        </div>
                    </div>
                <!-- Note -->
                    <h4>Note</h4>
                    <div class="mb-3">
                        <input type="text" class="form-control" id="popProductNote">
                    </div>
                
                <!-- Hidden controls --> 
                    <input type="hidden" id="popCalcGF_PricePer100" value="0">
                    <input type="hidden" id="popCalcREG_PricePer100" value="0">
                    <input type="hidden" id="popCalcDiffPer100" value="0">
                    <input type="hidden" id="popCalcExtra" value="0">
                
                <!-- Calculations details --> 
                    <div id="calcDetails" class="alert alert-secondary" role="alert">

                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger disabled" id="modalProductDelete" onclick="deleteProduct();">Delete</button>
                <button type="button" class="btn btn-success" onclick="calc();">CALC</button>
                <button type="button" class="btn btn-primary" id="modalProductOK" onclick="saveProduct();">(OK)</button>
            </div>
        </div>
    </div>
</div>

<script>
var actualAction = "";
var actualID = "";
var myModal;

var formatter = new Intl.NumberFormat('en-CA', {
        style: 'currency',
        currency: 'CAD',
        currencyDisplay: 'narrowSymbol'
    });

    function calc(){
        var qty = $("#popProductQuantity").val();
        // Price per 100 for GF Product
        var GF_price = $("#popProductPrice").val();
        var GF_size = $("#popProductSize").val();
        var GF_100 = (GF_price/GF_size)*100;
        $("#popCalcGF_PricePer100").val(GF_100);

        // Price per 100 for Regular Product
        var REG_price = $("#popEquProductPrice").val();
        var REG_size = $("#popEquProductSize").val();
        var REG_100 = (REG_price/REG_size)*100;

        $("#popCalcREG_PricePer100").val(REG_100);

        // Show info
        document.getElementById('calcDetails').innerHTML = (GF_100.toFixed(2) + "$ vs. " + REG_100.toFixed(2) + "$ / 100 " + $("#popProductFormat").val());

        // Difference
        var DIFF_100 = (GF_100/100)- (REG_100/100);
        $("#popCalcDiffPer100").val(DIFF_100.toFixed(2));

        // Extra
        var Extra = (DIFF_100*GF_size)*qty;
        $("#popCalcExtra").val(Extra.toFixed(2));
    }

    function addProduct(){
        actualAction = "add";
        document.getElementById("modalProductTitle").innerHTML = "Add a Product";
        document.getElementById("modalProductOK").innerHTML = "Save";
        $("#modalProductDelete").addClass('disabled');
        myModal = new bootstrap.Modal(document.getElementById('modalProduct'), {
            keyboard: false,
            backdrop: 'static'
        });
        myModal.show();
    }
    function chgProduct(lineID){
        actualAction = "chg";
        actualID = lineID;
        document.getElementById("modalProductTitle").innerHTML = "Change a Product";
        document.getElementById("modalProductOK").innerHTML = "Update";
        $("#modalProductDelete").removeClass('disabled');
        document.getElementById("modalProductDelete").dataset.stage='';
        $("#modalProductDelete").html("Delete");
        myModal = new bootstrap.Modal(document.getElementById('modalProduct'), {
            keyboard: false,
            backdrop: 'static'
        });

        // Extract data from the line
        var dat = JSON.parse(document.getElementById(lineID).dataset.raw);
            // Set data in the modal form
            for( var key in dat ){
                if( dat.hasOwnProperty(key)){
                    $("#" + key).val(dat[key]);
                }
            }
        myModal.show();
    }

    function deleteProduct(){
        if( document.getElementById("modalProductDelete").dataset.stage !== 'ready'){
            $("#modalProductDelete").html("Are you sure?");
            document.getElementById("modalProductDelete").dataset.stage='ready';
        }else{
            $("#" + actualID).remove();
            myModal.hide();
        }
    }

    function saveProduct(){
        calc();
        var myData = wrapForm('modalProductForm');
        console.log("ACTION: " + actualAction);
        console.log(myData);

        if( actualAction == "add" ){
            addLine(myData);
        }else{
            chgLine(myData);
        }
    }

    function addLine(data){
        var dat = JSON.parse(data);
        var table = document.getElementById('purchTableBody');

        var row = table.insertRow(0);
        var newID = makeID();
        row.id = newID;
        row.style.cursor = 'pointer';
        row.onclick = function(){
            chgProduct(newID);
        }
        row.dataset.raw = data;

        // Insert Cells
            var cell_ProductName = row.insertCell(0);
            var cell_ProductQty = row.insertCell(1);
            var cell_ProductPrice = row.insertCell(2);
            var cell_ProductFormat = row.insertCell(3);
            var cell_EquName = row.insertCell(4);
            var cell_EquPrice = row.insertCell(5);
            var cell_EquFormat = row.insertCell(6);
            var cell_EquExtra = row.insertCell(7);


        // Gluten-Free 
            // Product Name/Description
                cell_ProductName.innerHTML = dat['popProductName'];
                cell_ProductName.classList.add(newID + 'popProductName');
            // Product Quantity
                cell_ProductQty.innerHTML = dat['popProductQuantity'];
                cell_ProductQty.classList.add(newID + 'popProductQuantity');
            // Price
                cell_ProductPrice.innerHTML = formatter.format(dat['popProductPrice']);
                cell_ProductPrice.classList.add(newID + 'popProductPrice');
            // Format
                cell_ProductFormat.innerHTML = dat['popProductSize'] + ' ' + dat['popProductFormat']+ '.';
                cell_ProductFormat.classList.add(newID + 'popProductSize');

        // Regular Product
            // Product Name/Description
                cell_EquName.innerHTML = dat['popEquProductName'];
                cell_EquName.classList.add(newID + 'popEquProductName');
            // Price
                cell_EquPrice.innerHTML = formatter.format(dat['popEquProductPrice']);
                cell_EquPrice.classList.add(newID + 'popEquProductPrice');
            // Format
                cell_EquFormat.innerHTML = dat['popEquProductSize'] + ' ' + dat['popProductFormat'] + '.';
                cell_EquFormat.classList.add(newID + 'popEquProductSize');
            
        // Extra Paid Amount
            cell_EquExtra.dataset.amount = dat['popCalcExtra'];
            cell_EquExtra.innerHTML = formatter.format(dat['popCalcExtra']);
            cell_EquExtra.classList.add(newID + 'popCalcExtra');

        myModal.hide();
    }
    function chgLine(data){
        var dat = JSON.parse(data);

        // Update raw data in the line
        document.getElementById(actualID).dataset.raw = data;

        // Update data in the table
            $('.' + actualID + 'popProductName').html(dat['popProductName']);

            $('.' + actualID + 'popProductQuantity').html(dat['popProductQuantity']);
            $('.' + actualID + 'popProductPrice').html(formatter.format(dat['popProductPrice']));
            $('.' + actualID + 'popProductSize').html(dat['popProductSize'] + ' ' + dat['popProductFormat'] + '.');

            $('.' + actualID + 'popEquProductName').html(dat['popEquProductName']);
            $('.' + actualID + 'popEquProductPrice').html(formatter.format(dat['popEquProductPrice']));
            $('.' + actualID + 'popEquProductSize').html(dat['popEquProductSize'] + ' ' + dat['popProductFormat'] + '.');

            $('.' + actualID + 'popCalcExtra').html(formatter.format(dat['popCalcExtra']));

        myModal.hide();
        
    }



    function makeID(){
        var result = [];
        var length = 5;
        var characters = "ABCDEF0123456789";
        var charactersLength = characters.length;
        for( var i = 0; i < length; i++ ){
            result.push(characters.charAt(Math.floor(Math.random()*charactersLength)))
        }
        return result.join('');
    }

    

</script>