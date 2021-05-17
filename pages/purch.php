<?php 
# Include
require_once('../php/gtInclude.php');
# Initialise a database
$db = new gtDb();
# Set as if we are in ADD mode.
$mode = "new";
$purchID = 0;
$purchNumber = 0;
$pageTitle = _LABEL_PURCH_NEW;

$workDate = new DateTime(date('Y-m-d'));
date_sub($workDate, date_interval_create_from_date_string(abs($_SESSION[_SQL_ACC_DEF_PDATE_OS]) . ' days'));
$purchDate = date_format($workDate, 'Y-m-d');

$purchStore = 0;
$purchBuyer = 0;
$purchRef = "";

# Override variables if we are in EDIT mode.
if( isset($_GET['p']) && $_GET['p'] != ''){
    $purchID = $_GET['p'];
    # Retrieve Purchase infos and validate if it's owned 
    # by the active user.
    $db->where(_SQL_PUR_ACCOUNT, $_ACCT);
    $db->where(_SQL_PUR_ID, $purchID);
    $purch = $db->getOne(_SQL_PUR);
    # If nothing selected, force ADD mode
    if($db->count == 0){
        $mode = "new";
    }else{
        $mode = "edit";
        $pageTitle = _LABEL_PURCH_EDIT;

        $purchNumber = $purch[_SQL_PUR_NUMBER];
        $purchDate = $purch[_SQL_PUR_DATE];
        $purchStore = $purch[_SQL_PUR_STORE];
        $purchBuyer = $purch[_SQL_PUR_PERSON];
        $purchRef = $purch[_SQL_PUR_REF];
    }
}
?>
<!-- Header -->
<h1 class="mt-5 text-white font-weight-light"><?php echo $pageTitle; ?> </h1>
<p class="lead text-white-50"></p>
<hr>
<!-- Form -->
<div class="bg-light p-3 rounded shadow-sm">
<h4><?= _LABEL_PURCH_INFO ?></h4>
    <div class="row g-3">
        <div class="col-md-6">
            <label for="<?= _SQL_PUR_DATE ?>" class="form-label text-start"><?= _LABEL_PURCH_DATE ?></label>
            <input type="date" id="<?= _SQL_PUR_DATE ?>" name="<?= _SQL_PUR_DATE ?>" value="<?= $purchDate ?>" class="form-control">
        </div>
        <div class="col-md-6">
            <label for="<?= _SQL_PUR_STORE ?>" class="form-label"><?= _LABEL_STORE ?></label>
            <select id='<?= _SQL_PUR_STORE ?>' name='<?= _SQL_PUR_STORE ?>' class='form-select' onchange='triggerNewStore();'>
                <?php 
                    $db->where(_SQL_STO_ACCOUNT, $_ACCT);
                    $db->orderBy(_SQL_STO_NAME, 'ASC');
                    $options = $db->get(_SQL_STO);
                    if( $db->count > 1 ){
                        echo "<option value='0'>" . _LABEL_CHOOSE . "</option>";
                    }
                    foreach($options as $option){
                        $selected = '';
                        if($purchStore != 0 && $option[_SQL_STO_ID]==$purchStore){
                            $selected = ' selected';
                        }
                        echo '<option value="'.$option[_SQL_STO_ID].'"'.$selected.'>'.$option[_SQL_STO_NAME].' - '.$option[_SQL_STO_ADDRESS].'</option>';
                    }
                    echo '<option value="NEW">'._BUTTON_ADD_NEW.'...</option>';
                ?>
            </select>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-6">
            <label for="<?= _SQL_PUR_PERSON ?>" class="form-label text-start"><?= _LABEL_PERSON ?></label>
            <select id='<?= _SQL_PUR_PERSON ?>' name='<?= _SQL_PUR_PERSON ?>' class='form-select'>
                <option value='0'>(<?= _SETTING_YOU ?>) <?php echo $_NAME; ?></value>
                <?php 
                    $db->where(_SQL_PER_ACCOUNT, $_ACCT);
                    $db->orderBy(_SQL_PER_NAME, 'ASC');
                    $options = $db->get(_SQL_PER);
                    if( $db->count > 1 ){
                        echo "<option value='0'>" . _LABEL_CHOOSE . "</option>";
                    }
                    foreach($options as $option){
                        $selected = '';
                        if($purchBuyer != 0 && $option[_SQL_PER_ID]==$purchBuyer){
                            $selected = ' selected';
                        }
                        echo '<option value="'.$option[_SQL_PER_ID].'"'.$selected.'>'.$option[_SQL_PER_NAME].'</option>';
                    }
                ?>
            </select>
        </div>
        <div class="col-md-6">
            <label for="<?= _SQL_PUR_REF ?>" class="form-label"><?= _LABEL_REF ?></label>
            <input type="text" class="form-control" id="<?= _SQL_PUR_REF ?>" name="<?= _SQL_PUR_REF ?>" value="<?= $purchRef ?>">
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
                    <?php  

                        # If in EDIT mode, load products from this purchase.
                        if($mode == 'edit'){
                            $db->where(_SQL_EXP_ACCOUNT, $_ACCT);
                            $db->where(_SQL_EXP_PURCHASE, $purch[_SQL_PUR_ID]);
                            $exps = $db->get(_SQL_EXP);
                            foreach($exps as $exp){
                                $trID = $exp[_SQL_EXP_LINE];
                                $trJSON = Array(
                                    'popProductName' => htmlspecialchars($exp[_SQL_EXP_PRO_NAME], ENT_QUOTES),
                                    'popProductQuantity' => $exp[_SQL_EXP_QUANTITY],
                                    'popProductPrice' => $exp[_SQL_EXP_PRO_PRICE],
                                    'popProductSize' => $exp[_SQL_EXP_PRO_SIZE],
                                    'popProductFormat' => $exp[_SQL_EXP_PRO_FORMAT],
                                    'popEquProductName' => htmlspecialchars($exp[_SQL_EXP_EQU_NAME], ENT_QUOTES),
                                    'popEquProductPrice' => $exp[_SQL_EXP_EQU_PRICE],
                                    'popEquProductSize' => $exp[_SQL_EXP_EQU_SIZE],
                                    'popProductNote' => htmlspecialchars($exp[_SQL_EXP_NOTE], ENT_QUOTES),
                                    'popCalcGF_PricePer100' => $exp[_SQL_EXP_PRO_PP100],
                                    'popCalcREG_PricePer100' => $exp[_SQL_EXP_EQU_PP100],
                                    'popCalcDiffPer100' => $exp[_SQL_EXP_DIFF_PP100],
                                    'popCalcExtra' => $exp[_SQL_EXP_EXTRA]
                                );
                                $trJSON = json_encode($trJSON, JSON_FORCE_OBJECT);
                                
                                echo "<tr id='".$trID."' data-raw='".$trJSON."' data-extra='".$exp[_SQL_EXP_EXTRA]."' style='cursor:pointer;' onclick='chgProduct(\"".$trID."\");'>";
                                echo '<td class="'.$trID.'popProductName">'.$exp[_SQL_EXP_PRO_NAME].'</td>';
                                echo '<td class="'.$trID.'popProductQuantity">'.$exp[_SQL_EXP_QUANTITY].'</td>';
                                echo '<td class="'.$trID.'popProductPrice">'.$_CURRENCY->format($exp[_SQL_EXP_PRO_PRICE]).'</td>';
                                echo '<td class="'.$trID.'popProductSize">'.$exp[_SQL_EXP_PRO_SIZE].' '.$exp[_SQL_EXP_PRO_FORMAT].'.</td>';
                                echo '<td class="'.$trID.'popEquProductName">'.$exp[_SQL_EXP_EQU_NAME].'</td>';
                                echo '<td class="'.$trID.'popEquProductPrice">'.$_CURRENCY->format($exp[_SQL_EXP_EQU_PRICE]).'</td>';
                                echo '<td class="'.$trID.'popEquProductSize">'.$exp[_SQL_EXP_EQU_SIZE].' '.$exp[_SQL_EXP_PRO_FORMAT].'.</td>';
                                echo '<td class="'.$trID.'popCalcExtra" data-amount="'.$exp[_SQL_EXP_EXTRA].'">'.$_CURRENCY->format($exp[_SQL_EXP_EXTRA]).'</td>';
                                echo '</tr>';
                            }
                        }
                    ?>
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

    <?php 
        if( $mode == "edit"){
            echo '<button class="btn btn-danger" onclick="deletePurchase();">' . _BUTTON_DELETE . '</button>';
        }
    ?>
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
                                <?php  
                                    foreach($_UM as $key => $value ){
                                        echo "<option value='$key'>$value</option>";
                                    }
                                ?>
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
                        <a class="nav-link active" id="tabProductLoadList" data-bs-toggle="tab" data-bs-target="#prodLoadList" type="button" role="tab" aria-controls="prodLoadList" aria-selected="true" href="#"><?= _LABEL_LOAD_LIST ?></a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="tabProductLoadCode" data-bs-toggle="tab" data-bs-target="#prodLoadCode" type="button" role="tab" aria-controls="prodLoadCode" aria-selected="false" href="#"><?= _LABEL_LOAD_CODE ?></a>
                    </li>
                </ul>

                <div class="tab-content mt-1" id="tabsProductLoadContent">
                    <div class="tab-pane fade show active" id="prodLoadList" role="tabpanel" aria-labelledby="tabProductLoadList">
                        <label for="loadProduct" class="form-label">&nbsp;</label>
                        <select class="form-select" id="loadProduct" onchange="displayProductToLoad();">
                        <option value="0"><?= _LABEL_CHOOSE ?></option>
                            <?php  
                                $db->where(_SQL_PRO_ACCOUNT, $_ACCT);
                                $db->orderBy(_SQL_PRO_NAME, 'ASC');
                                $products = $db->get(_SQL_PRO);
                                foreach( $products as $product ){
                                    $pID = $product[_SQL_PRO_ID];
                                    if( $product[_SQL_PRO_SKU] !== '' ){
                                        $pID = 'sku_'.$product[_SQL_PRO_SKU];
                                    }
                                    $pName = $product[_SQL_PRO_NAME];
                                    $pSize = $product[_SQL_PRO_SIZE];
                                    $pPrice = $product[_SQL_PRO_PRICE];
                                    $pFormat = $product[_SQL_PRO_FORMAT];
                                    $eName = $product[_SQL_EQU_NAME];
                                    $eSize = $product[_SQL_EQU_SIZE];
                                    $ePrice = $product[_SQL_EQU_PRICE];

                                    echo '<option id="option_' . $pID  . '" value="option_'. $pID;
                                    echo '" data-equProd="' . $eName . '" data-equSize="' . $eSize . '" ';
                                    echo 'data-format="' . $pFormat . '" data-product="' . $pName . '" ';
                                    echo 'data-size="' . $pSize . '" data-price="'. $pPrice .'" data-equprice="'. $ePrice .'">';
                                    echo $pName . ' (' . $pSize . ' ' . $pFormat . ')</option>';
                                }
                            ?>
                        </select>
                    </div>
                    <div class="tab-pane fade show" id="prodLoadCode" role="tabpanel" aria-labelledby="tabProductLoadCode">
                        <label for="loadProductCode" class="form-label">&nbsp;</label>
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
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="clearModal();"><?= _BUTTON_CANCEL?></button>
                <button type="button" class="btn btn-primary" id="modalLoadProductOK" onclick="loadProduct();"><?= _BUTTON_NEXT ?></button>
            </div>
            <div class="modal-footer" id="modalFooterProductEntry">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="clearModal();"><?= _BUTTON_CANCEL?></button>
                <button type="button" class="btn btn-danger disabled" id="modalProductDelete" onclick="deleteProduct();"><?= _BUTTON_DELETE?></button>
                <!--<button type="button" class="btn btn-success" onclick="calc();">CALC</button>-->
                <button type="button" class="btn btn-primary" id="modalProductOK" onclick="saveProduct();">(OK)</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL: Text input --> 
<div class="modal fade" id="modalInputText" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- First body for manual product entry -->
            <div class="modal-body" id="modalInputTextForm">
                <label for="txtGenericInput" class="form-label"><?php echo _LABEL_NEW_STORE; ?></label>
                <input type="text" id="txtGenericInput" class="form-control" autocomplete="off">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= _BUTTON_CANCEL?></button>
                <button type="button" class="btn btn-primary" id="modalInputTextOK" onclick="handleNewStore();">OK</button>
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
var modalInputText;

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

    modalInputText = new bootstrap.Modal(document.getElementById('modalInputText'),{
        keyboard: false,
        backdrop: 'static'
    });


    document.getElementById("summaryExtraAmount").innerHTML = formatter.format(0);
    // Should I calculate the summary ?
    var phpPurchID = "<?php echo $purchID; ?>";
    if( phpPurchID != "0" ){
        calcSummary();
    }
});

function clearModal(){
    $("#loadProduct").val(0);
    $("#loadProductCode").val('');
    $("#displayEquivalent").html('');
    $('[id^=pop]').val('');
}

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
        loadProductPrice = 0;
        loadProductEquName = '';
        loadProductEquSize = '';
        loadProductEquPrice = 0;

        var enteredCode = $("#loadProductCode").val() + "";
        if( enteredCode == "" ){
            display = 'NO PRODUCT CODE ENTERED';
        }else{
            var el = document.getElementById('option_sku_' + enteredCode);
            if( el === null ){
                display = '<?= _TABLE_PRODUCT ?> <strong>' + enteredCode + '</strong> <?= _LABEL_NOT_FOUND ?>';
            }else{
                var option = el.dataset;
                    var display = '<dl class="row">';
                    display += '<dt class="col-sm-5 text-end"><?= _LABEL_PRODUCT_GF ?></dt><dd class="col-sm-7 text-start">' + option.product + '</dd>';
                    display += '<dt class="col-sm-5 text-end"><?= _LABEL_FORMAT ?></dt><dd class="col-sm-7 text-start">' + option.size + ' ' + option.format + '</dd>';
                    display += '<dt class="col-sm-5 text-end"><?= _LABEL_PRICE ?></dt><dd class="col-sm-7 text-start">' + formatter.format(option.price) + '</dd>';
                    display += '<dt class="col-sm-5 text-end"><?= _LABEL_PRODUCT_EQU ?></dt><dd class="col-sm-7 text-start">' + option.equprod + '</dd>';
                    display += '<dt class="col-sm-5 text-end"><?= _LABEL_FORMAT ?></dt><dd class="col-sm-7 text-start">' + option.equsize + ' ' + option.format + '</dd>';
                    display += '<dt class="col-sm-5 text-end"><?= _LABEL_PRICE ?></dt><dd class="col-sm-7 text-start">' + formatter.format(option.equprice) + '</dd></dl>';

                    loadProductName = option.product;
                    loadProductSize = option.size;
                    loadProductFormat = option.format;
                    loadProductPrice = option.price;
                    loadProductEquName = option.equprod;
                    loadProductEquSize = option.equsize;
                    loadProductEquPrice = option.equprice;
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
            loadProductPrice = 0;
            loadProductEquName = '';
            loadProductEquSize = '';
            loadProductEquPrice = 0;

        }else{
            var option = document.getElementById(selectValue).dataset;
            var display = '<dl class="row">';
                    display += '<dt class="col-sm-5 text-end"><?= _LABEL_PRODUCT_GF ?></dt><dd class="col-sm-7 text-start">' + option.product + '</dd>';
                    display += '<dt class="col-sm-5 text-end"><?= _LABEL_FORMAT ?></dt><dd class="col-sm-7 text-start">' + option.size + ' ' + option.format + '</dd>';
                    display += '<dt class="col-sm-5 text-end"><?= _LABEL_PRICE ?></dt><dd class="col-sm-7 text-start">' + formatter.format(option.price) + '</dd>';
                    display += '<dt class="col-sm-5 text-end"><?= _LABEL_PRODUCT_EQU ?></dt><dd class="col-sm-7 text-start">' + option.equprod + '</dd>';
                    display += '<dt class="col-sm-5 text-end"><?= _LABEL_FORMAT ?></dt><dd class="col-sm-7 text-start">' + option.equsize + ' ' + option.format + '</dd>';
                    display += '<dt class="col-sm-5 text-end"><?= _LABEL_PRICE ?></dt><dd class="col-sm-7 text-start">' + formatter.format(option.equprice) + '</dd></dl>';

            loadProductName = option.product;
            loadProductSize = option.size;
            loadProductFormat = option.format;
            loadProductPrice = option.price;
            loadProductEquName = option.equprod;
            loadProductEquSize = option.equsize;
            loadProductEquPrice = option.equprice;
        }
        
        document.getElementById('displayEquivalent').innerHTML = display;
    }

    function loadProduct(){
        $("#popProductName").val(loadProductName);
        $("#popProductSize").val(loadProductSize);
        $("#popProductFormat").val(loadProductFormat);
        $("#popProductPrice").val(loadProductPrice);
        $("#popEquProductName").val(loadProductEquName);
        $("#popEquProductSize").val(loadProductEquSize);
        $("#popEquProductPrice").val(loadProductEquPrice);
        
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
            clearModal();
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
        clearModal();
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
        
        calcSummary();
        clearModal();
        modalProductEntry.hide();
        
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
            method: '<?php echo $mode; ?>Purchase',
            purchaseDate: $("#purchaseDate").val(),
            purchaseStoreID: $("#purchaseStoreID").val(),
            purchasePersonID: $("#purchasePersonID").val(),
            purchaseReference: $("#purchaseReference").val(),
            purchaseID: <?php echo $purchID; ?>,
            purchaseNumber: <?php echo $purchNumber; ?>
        }

        var myExpenses = {};
        $('#purchTable > tbody  > tr').each(function() {
            myExpenses[$(this).attr('id')] = $(this).data('raw');
        });

        myData.expenses = myExpenses;

        var json = JSON.stringify(myData);
        console.log(json);
        
        $.ajax({
        type: "POST",
        url: "php/gtEngine.php",
        data: json,
        success: function(result){
            processResult(result);
        }
        });
    }

    function deletePurchase(){
        var resp = confirm("Delete this purchase ?");
        if ( resp === true ){
            var myData = {
                method: 'deletePurchase',
                purchaseID: <?php echo $purchID; ?>,
                purchaseNumber: <?php echo $purchNumber; ?>
            }
            var json = JSON.stringify(myData);
            $.ajax({
                type: "POST",
                url: "php/gtEngine.php",
                data: json,
                success: function(result){
                    processResult(result);
                }
            });
        }else{
            return false;
        }
    }

    function triggerNewStore(){
        var myStoreID = document.getElementById("<?php echo _SQL_PUR_STORE; ?>").value;
        if( myStoreID == "NEW" ){
            modalInputText.show();
        }
    }

    function handleNewStore(){
        var myStoreName = document.getElementById("txtGenericInput").value;

        modalInputText.hide();
        
        var newOption = document.createElement("option");
        newOption.text = myStoreName;
        var storeVal = encodeURIComponent(myStoreName);
        newOption.value = storeVal;

        var selStore = document.getElementById("<?php echo _SQL_PUR_STORE; ?>");
        var ndx = selStore.length - 1;

        selStore.add(newOption, ndx);
        selStore.value = storeVal;
    }


    

</script>