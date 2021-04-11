<?php 
session_start();
include('../php/lang/'.$_SESSION['accountLanguage'].'.php');

require_once('../php/gtDb.php');
$db = new gtDb();
?>

<h1 class="mt-5 text-white font-weight-light"><?= _LABEL_PURCH_NEW ?> </h1>
<p class="lead text-white-50"></p>
<hr>

<div class="bg-light p-3 rounded shadow-sm">
<h4><?= _LABEL_PURCH_INFO ?></h4>
    <div class="row g-3">
        <div class="col-md-6">
            <label for="purchaseDate" class="form-label text-start"><?= _LABEL_PURCH_DATE ?></label>
            <input type="date" id="purchaseDate" name="purchaseDate" value="" class="form-control">
        </div>
        <div class="col-md-6">
            <label for="purchaseStoreID" class="form-label"><?= _LABEL_STORE ?></label>
            <select id='purchaseStoreID' name='purchaseStoreID' class='form-select'>
                <?php 
                    $db->where('storeAccountID', $_SESSION['accountID']);
                    $db->orderBy('storeName', 'ASC');
                    $options = $db->get('tbStore');
                    if( $db->count > 1 ){
                        echo "<option value='0'>" . _LABEL_CHOOSE . "</option>";
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
            <label for="purchasePersonID" class="form-label text-start"><?= _LABEL_PERSON ?></label>
            <select id='purchasePersonID' name='purchasePersonID' class='form-select'>
                <option value='0'>(<?= _SETTING_YOU ?>) <?php echo $_SESSION['accountName']; ?></value>
                <?php 
                    $db->where('personAccountID', $_SESSION['accountID']);
                    $db->orderBy('personName', 'ASC');
                    $options = $db->get('tbPerson');
                    if( $db->count > 1 ){
                        echo "<option value='0'>" . _LABEL_CHOOSE . "</option>";
                    }
                    foreach($options as $option){
                        echo '<option value="'.$option['personID'].'">'.$option['personName'].'</option>';
                    }
                ?>
            </select>
        </div>
        <div class="col-md-6">
            <label for="purchaseReference" class="form-label"><?= _LABEL_REF ?></label>
            <input type="text" class="form-control" id="purchaseReference" name="purchaseReference">
        </div>
    </div>
</div>

<div class="bg-light p-3 rounded shadow-sm mt-2">
    <h4><?= _LABEL_PRODUCTS ?></h4>

    <div class="table-responsive">
        <table class="table table-hover" id="purchTable">
            <thead>
                <th>
                    <?= _LABEL_PRODUCT_GF_SHORT ?>
                </th>
                <th>
                    <?= _LABEL_QUANTIY ?>
                </th>
                <th>
                    <?= _LABEL_PRICE ?>
                </th>
                <th>
                    <?= _LABEL_SIZE ?>
                </th>
                <th>
                    <?= _LABEL_PRODUCT_EQU_SHORT ?>
                </th>
                <th>
                    <?= _LABEL_PRICE ?>
                </th>
                <th>
                    <?= _LABEL_SIZE ?>
                </th>
                <th>
                    <?= _LABEL_PURCH_EXTRA_SHORT ?>
                </th>
            </thead>
            <tbody id="purchTableBody">
            </tbody>
        </table>
    </div>
    
    <button class="btn btn-info" onclick="openProductLoader();"><?= _BUTTON_ADD_PRODUCT_TABLE ?></button>
    <button class="btn btn-primary" onclick="addProduct();"><?= _BUTTON_ADD_PRODUCT ?></button>

</div>

<div class="bg-light p-3 rounded shadow-sm my-2">
    <h4><?= _LABEL_SUMMARY ?></h4>
    
        <dl class="row mt-3 mb-3">
            <dt class="col-sm-6 text-end"><?= _LABEL_SUMMARY_COUNT_PRODUCT ?></dt>
            <dd id="summaryProdCount" class="col-sm-6 text-start">0</dd>
            
            <dt class="col-sm-6 text-end"><?= _LABEL_SUMMARY_TOTAL_EXTRA ?></dt>
            <dd id="summaryExtraAmount" class="col-sm-6 text-start"></dd>
        </dl>


    <button class="btn btn-primary" onclick="savePurchase();"><?= _BUTTON_SAVE ?></button>
</div>


<!-- MODAL: Edit Product Line -->  
<div class="modal fade" id="modalProduct" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalProductTitle">(Title)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- First body for manual product entry -->
            <div class="modal-body" id="modalProductForm">
                <!-- Gluten-free Product -->
                    <h4><?= _LABEL_PRODUCT_GF ?></h4>
                <!-- Description -->
                    <div class="mb-3">
                        <label for="popProductName" class="form-label"><?= _LABEL_DESCRIPTION ?></label>
                        <input type="text" class="form-control" id="popProductName" value="">
                    </div>
                <!-- GF: Quantity & Price -->
                    <div class="row mb-3 g-3">
                        <div class="col">
                            <label for="popProductQuantity" class="form-label text-start"><?= _LABEL_QUANTIY ?></label>
                            <input type="number" min="0" class="form-control" id="popProductQuantity" value="">
                        </div>
                        <div class="col">
                            <label for="popProductPrice" class="form-label text-start"><?= _LABEL_PRICE_UNIT ?></label>
                            <input type="number" min="0" class="form-control" id="popProductPrice" value="">
                        </div>
                    </div>
                <!-- GF: Size & Format -->
                    <div class="row mb-3 g-3">
                        <div class="col">
                            <label for="popProductSize" class="form-label text-start"><?= _LABEL_SIZE ?></label>
                            <input type="number" min="0" class="form-control" id="popProductSize" value="">
                        </div>
                        <div class="col">
                            <label for="popProductFormat" class="form-label text-start"><?= _LABEL_FORMAT ?></label>
                            <select class="form-select" id="popProductFormat">
                                <option value="g"><?= _LABEL_G ?></option>
                                <option value="mL"><?= _LABEL_ML ?></option>
                            </select>
                        </div>
                    </div>
                <!-- Equivalent (with gluten) product -->
                    <h4><?= _LABEL_PRODUCT_EQU ?></h4>
                <!-- Description -->
                    <div class="mb-3">
                        <label for="popEquProductName" class="form-label text-start"><?= _LABEL_DESCRIPTION ?></label>
                        <input type="text" class="form-control" id="popEquProductName" value="">
                    </div>
                <!-- Price & Size -->
                    <div class="row mb-3 g-3">
                        <div class="col">
                            <label for="popEquProductPrice" class="form-label text-start"><?= _LABEL_PRICE_UNIT ?></label>
                            <input type="number" min="0" class="form-control" id="popEquProductPrice" value="">
                        </div>
                        <div class="col">
                            <label for="popEquProductSize" class="form-label text-start"><?= _LABEL_SIZE ?></label>
                            <input type="number" min="0" class="form-control" id="popEquProductSize" value="">
                        </div>
                    </div>
                <!-- Note -->
                    <h4><?= _LABEL_NOTE ?></h4>
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
            <!-- Additional body to select a product -->
            <div class="modal-body d-none" id="modalLoadProductForm">

                <ul class="nav nav-tabs" id="tabsProductLoad" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="tabProductLoadList" data-bs-toggle="tab" data-bs-target="#prodLoadList" type="button" role="tab" aria-controls="prodLoadList" aria-selected="true" href="#">Choose from list</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="tabProductLoadCode" data-bs-toggle="tab" data-bs-target="#prodLoadCode" type="button" role="tab" aria-controls="prodLoadCode" aria-selected="false" href="#">Enter a code</a>
                    </li>
                </ul>

                <div class="tab-content mt-1" id="tabsProductLoadContent">
                    <div class="tab-pane fade show active" id="prodLoadList" role="tabpanel" aria-labelledby="tabProductLoadList">
                        <label for="loadProduct" class="form-label">Product to load</label>
                        <select class="form-select" id="loadProduct" onchange="displayProductToLoad();">
                        <option value="0"><?= _LABEL_CHOOSE ?></option>
                            <?php  
                                $db->where('productAccountID', $_SESSION['accountID']);
                                $db->orderBy('productName', 'ASC');
                                $products = $db->get('tbProduct');
                                foreach( $products as $product ){
                                    $pID = $product['productID'];
                                    if( $product['productSKU'] !== '' ){
                                        $pID = 'sku_'.$product['productSKU'];
                                    }
                                    echo '<option id="option_' . $pID  . '" value="option_'. $pID;
                                    echo '" data-equProd="' . $product['productEquName'] . '" data-equSize="' . $product['productEquSize'] . '" ';
                                    echo 'data-format="' . $product['productFormat'] . '" data-product="' . $product['productName'] . '" ';
                                    echo 'data-size="' . $product['productSize'] . '" >';
                                    echo $product['productName'] . ' (' . $product['productSize'] . ' ' . $product['productFormat'] . ')</option>';
                                }
                            ?>
                        </select>
                    </div>
                    <div class="tab-pane fade show" id="prodLoadCode" role="tabpanel" aria-labelledby="tabProductLoadCode">
                        <label for="loadProductCode" class="form-label">Product code or SKU</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" id="loadProductCode">
                                <button class="btn btn-outline-primary" type="button" id="btnAddOnProdCode" onclick="searchProductToLoad();">&#128269;</button>
                            </div>
                    </div>
                </div>

                <hr>
                <div id="displayEquivalent"></div>
            </div>
            <div class="modal-footer d-none" id="modalFooterProductLoad">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= _BUTTON_CANCEL?></button>
                <button type="button" class="btn btn-primary" id="modalLoadProductOK" onclick="loadProduct();"><?= _BUTTON_NEXT ?></button>
            </div>
            <div class="modal-footer" id="modalFooterProductEntry">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= _BUTTON_CANCEL?></button>
                <button type="button" class="btn btn-danger disabled" id="modalProductDelete" onclick="deleteProduct();"><?= _BUTTON_DELETE?></button>
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

var loadProductName = "";
var loadProductSize = 0;
var loadProductFormat = "";
var loadProductEquName = "";
var loadProductEquSize = "";

var modalProductEntry;
var modalProductLoad;

var formatter = new Intl.NumberFormat('<?php echo $_SESSION['accountLocale']; ?>', {
        style: 'currency',
        currency: 'CAD',
        currencyDisplay: 'narrowSymbol'
    });

$(document).ready(function(){
    modalProductEntry = new bootstrap.Modal(document.getElementById('modalProduct'), {
            keyboard: false,
            backdrop: 'static'
        });

    modalProductLoad = new bootstrap.Modal(document.getElementById('modalLoadProduct'),{
        keyboard: false,
        backdrop: 'static'
    });

    document.getElementById("summaryExtraAmount").innerHTML = formatter.format(0);
});



function calcSummary(){
    var runCount = 0;
    var runTotal = 0;

    $("#purchTableBody > tr").each(function(){
        runCount ++;
        runTotal += $(this).data('extra');
    });
    document.getElementById("summaryProdCount").innerHTML = runCount;
    document.getElementById("summaryExtraAmount").innerHTML = formatter.format(runTotal);
}

    function prepareFormForLoad(){
        $("#modalFooterProductLoad").removeClass("d-none");
        $("#modalFooterProductEntry").addClass("d-none");
        $("#modalLoadProductForm").removeClass("d-none");
        $("#modalProductForm").addClass("d-none");
    }

    function prepareFormForEntry(){
        $("#modalFooterProductLoad").addClass("d-none");
        $("#modalFooterProductEntry").removeClass("d-none");
        $("#modalLoadProductForm").addClass("d-none");
        $("#modalProductForm").removeClass("d-none");
    }

    function searchProductToLoad(){
        loadProductName = '';
        loadProductSize = '';
        loadProductFormat = '';
        loadProductEquName = '';
        loadProductEquSize = '';

        var enteredCode = $("#loadProductCode").val() + "";
        if( enteredCode == "" ){
            display = 'NO PRODUCT CODE ENTERED';
        }else{
            var el = document.getElementById('option_sku_' + enteredCode);
            if( el === null ){
                display = 'Product <strong>' + enteredCode + '</strong> not found!';
            }else{
                var option = el.dataset;
                    var display = '<dl class="row">';
                    display += '<dt class="col-sm-5 text-end"><?= _LABEL_PRODUCT_GF ?></dt><dd class="col-sm-7 text-start">' + option.product + '</dd>';
                    display += '<dt class="col-sm-5 text-end"><?= _LABEL_FORMAT ?></dt><dd class="col-sm-7 text-start">' + option.size + ' ' + option.format + '</dd>';
                    display += '<dt class="col-sm-5 text-end"><?= _LABEL_PRODUCT_EQU ?></dt><dd class="col-sm-7 text-start">' + option.equprod + '</dd>';
                    display += '<dt class="col-sm-5 text-end"><?= _LABEL_FORMAT ?></dt><dd class="col-sm-7 text-start">' + option.equsize + ' ' + option.format + '</dd></dl>';

                    loadProductName = option.product;
                    loadProductSize = option.size;
                    loadProductFormat = option.format;
                    loadProductEquName = option.equprod;
                    loadProductEquSize = option.equsize;
                }
            }
        
        document.getElementById('displayEquivalent').innerHTML = display;
    }

    function displayProductToLoad(){
        var selectValue = $('#loadProduct').val();

        if( selectValue == '0' ){
            display = '';

            loadProductName = '';
            loadProductSize = '';
            loadProductFormat = '';
            loadProductEquName = '';
            loadProductEquSize = '';

        }else{
            var option = document.getElementById(selectValue).dataset;
            var display = '<dl class="row">';
            display += '<dt class="col-sm-5 text-end"><?= _LABEL_PRODUCT_GF ?></dt><dd class="col-sm-7 text-start">' + option.product + '</dd>';
            display += '<dt class="col-sm-5 text-end"><?= _LABEL_FORMAT ?></dt><dd class="col-sm-7 text-start">' + option.size + ' ' + option.format + '</dd>';
            display += '<dt class="col-sm-5 text-end"><?= _LABEL_PRODUCT_EQU ?></dt><dd class="col-sm-7 text-start">' + option.equprod + '</dd>';
            display += '<dt class="col-sm-5 text-end"><?= _LABEL_FORMAT ?></dt><dd class="col-sm-7 text-start">' + option.equsize + ' ' + option.format + '</dd></dl>';

            loadProductName = option.product;
            loadProductSize = option.size;
            loadProductFormat = option.format;
            loadProductEquName = option.equprod;
            loadProductEquSize = option.equsize;
        }
        
        document.getElementById('displayEquivalent').innerHTML = display;
    }

    function loadProduct(){
        $("#popProductName").val(loadProductName);
        $("#popProductSize").val(loadProductSize);
        $("#popProductFormat").val(loadProductFormat);
        $("#popEquProductName").val(loadProductEquName);
        $("#popEquProductSize").val(loadProductEquSize);
        
        prepareFormForEntry();
    }


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

    function openProductLoader(){
        prepareFormForLoad();
        actualAction = "add";
        document.getElementById("modalProductTitle").innerHTML = "<?= _LABEL_PURCH_ADD_PRODUCT ?>";
        document.getElementById("modalProductOK").innerHTML = "<?= _BUTTON_SAVE ?>";
        $("#modalProductDelete").addClass('disabled');
        modalProductEntry.toggle();
        modalProductEntry.handleUpdate();
    }

    function addProduct(){
        prepareFormForEntry();
        actualAction = "add";
        document.getElementById("modalProductTitle").innerHTML = "<?= _LABEL_PURCH_ADD_PRODUCT ?>";
        document.getElementById("modalProductOK").innerHTML = "<?= _BUTTON_SAVE ?>";
        $("#modalProductDelete").addClass('disabled');
        modalProductEntry.toggle();
        modalProductEntry.handleUpdate();
    }
    function chgProduct(lineID){
        prepareFormForEntry();
        actualAction = "chg";
        actualID = lineID;
        document.getElementById("modalProductTitle").innerHTML = "<?= _LABEL_PURCH_CHG_PRODUCT ?>";
        document.getElementById("modalProductOK").innerHTML = "<?= _BUTTON_UPDATE ?>";
        $("#modalProductDelete").removeClass('disabled');
        document.getElementById("modalProductDelete").dataset.stage='';
        $("#modalProductDelete").html("Delete");
        
        // Extract data from the line
        var dat = JSON.parse(document.getElementById(lineID).dataset.raw);
            // Set data in the modal form
            for( var key in dat ){
                if( dat.hasOwnProperty(key)){
                    $("#" + key).val(dat[key]);
                }
            }
        modalProductEntry.show();
    }

    function deleteProduct(){
        if( document.getElementById("modalProductDelete").dataset.stage !== 'ready'){
            $("#modalProductDelete").html("<?= _BUTTON_CONFIRM ?>");
            document.getElementById("modalProductDelete").dataset.stage='ready';
        }else{
            $("#" + actualID).remove();
            modalProductEntry.hide();
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
        row.dataset.extra = dat['popCalcExtra'];

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

        // Calculate Purchase Summary
        calcSummary();
        modalProductEntry.hide();
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

    function savePurchase(){
        var myData = {
            method: 'newPurchase',
            purchaseDate: $("#purchaseDate").val(),
            purchaseStoreID: $("#purchaseStoreID").val(),
            purchasePersonID: $("#purchasePersonID").val(),
            purchaseReference: $("#purchaseReference").val()
        }

        var myExpenses = {};
        $('#purchTable > tbody  > tr').each(function() {
            myExpenses[$(this).attr('id')] = $(this).data('raw');
        });

        myData.expenses = myExpenses;

        var json = JSON.stringify(myData);
        
        $.ajax({
        type: "POST",
        url: "php/gtEngine.php",
        data: json,
        success: function(result){
            processResult(result);
        }
        });
    }




    

</script>