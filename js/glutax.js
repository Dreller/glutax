var modalPurchReceipt;

$(document).ready(function(){
    modalPurchReceipt = new bootstrap.Modal(document.getElementById('purchaseReceipt'), {
        keyboard: false
    });
    loadPage();
});


// Click on the Website title in the Top NavBar
$("#glutaxTitle").on("click", function(){
    loadPage();
});
// Click on an item in the Top NavBar Menu "Tables"
$(".navTable").on("click", function(){
    loadPage('table', 't=' + $(this).data("table"));
});
// Click on an item in the Top NavBar Menu "Reports"
$(".navReport").on("click", function(){
    loadReport($(this).data("report"));
});
$("#NavNewPurchase").on("click", function(){
    editPurch(0);
});

/**
 * Refresh the whole page (F5)
 */
function bigRefresh(){
    location.reload();
}

/**
 * Reload to the home page.
 */
function goHome(){
    loadPage();
}

/**
 * Load the page 'table.php' and pass the table to display on screen.
 * @param {String} type Table Type -> person, product, store.
 */
function loadTable(type){
    loadPage('table', 't=' + type);
}

/**
 * Launch an action (JS Function).  The function have to exists under the
 * name of:  gt_{action}({type}).  Both action and type have to be stored
 * in the element in data-action="" and data-type="". 
 * Called like that:  onclick="launch(this);".
 * @param {Element} el Element ID.
 */
function launch(el){
    if( typeof($(el).data("action")) == 'undefined' ){
        return false;
    }
    var action = $(el).data("action");
    var type = $(el).data("type");

    var id = 0;
    if( typeof($(el).data("id")) !== 'undefined' ){
        id = $(el).data("id");
    }
    console.log(">> Launching: " + type + " " + action + " (id = " + id + " )");
    window["gt_" + action](type, id);
}

function editPurch(id){
    loadPage('purch', 'i=' + id);
}

/**
 * Add a new element in a table.
 * @param {String} type: one of: person, product, store.
 */
function gt_add(type, id = 0){
    loadPage("editItem", "t=" + type);
}

function gt_chg(type, id){
    loadPage("editItem", "t=" + type + "&i=" + id);
}

/**
 * Load a page in the main container.
 * @param {String} page 
 * @param {String} args 
 */
function loadPage(page, args){
    // Call the loading indicator
    loading();

    // Set default values if there is nothing passed.
    if(typeof page === "undefined"){ page = "welcome";}
    if(typeof args === "undefined"){ args = ""; }

    // Build the URL string
    myURL = 'pages/' + page + '.php';
    // Add arguments
    if( args !== "" ){
        myURL += '?' + args;
    }

    console.log("(url) " + myURL);

    $("#myBox").load(myURL);
}

var myPurchaseID;
function purchReceipt(i){
    myPurchaseID = i;
    modalPurchReceipt.show();
    $("#purchaseReceiptBody").html("<div class='d-flex justify-content-center'><div class='spinner-border text-primary mt-5' role='status'><span class='visually hidden'></span></div></div>");
    myURL = 'pages/purch_view.php?i=' + i;
    $("#purchaseReceiptBody").load(myURL);
}

/**
 * Display a spinning loader in the main container.
 * Is called by 'loadPage' to show a progress indicator.
 */
function loading(){
    $("#myBox").html("<div class='d-flex justify-content-center'><div class='spinner-border text-light mt-5' role='status'><span class='visually hidden'></span></div></div>");
}

/**
 * Send 'gtForm' to the Engine.
 */
function sendForm(){
    var myData = wrapForm();
    $.ajax({
        type: "POST",
        url: "php/gtEngine.php",
        data: myData,
        success: function(result){
            processResult(result);
        }
    });
}

/**
 * Wrap controls found in div 'gtForm' to send it to the Engine.
 */
function wrapForm(formID){
    var myForm = "gtForm";
    if( typeof(formID) !== "undefined" ){
        myForm = formID;
    }
    var myArray = {};
    $('#' + myForm).find('input,textarea,select').each(function(){
        myArray[$(this).attr('id')] = $(this).val();
    });
    var jsonWIP = JSON.stringify(myArray);
    return jsonWIP;
}

/**
 * Catch the response from GT Engine and process it.
 * The 'status' will be one of:
 *   - ok: request completed.
 *   - error: there was an error handling the resquest, see 'message'.
 *   - callback: a function to start after getting the success message.
 *  
 * Other data:
 *   - toast: If present, its content will be shown in a toast message.
 *   - toastType: Type of toast to display (error, success...).
 * 
 * @param {JSON} myData 
 */
 function processResult(myData){

    if( myData['status'] == 'error' ){
        console.log(myData);
        alert("AN ERROR OCCURED.  SEE DEBUG FOR DETAILS.");
        return false;
    }

    // Special treatments
    if( myData['status'] == 'callback' ){
        var functionToCall = myData['cb_fct'];
        var functionArg = null;
        if( myData['cb_arg'] !== "undefined" ){
            functionArg = myData['cb_arg'];
        }
        window[functionToCall](functionArg);
    }

    // Toast
    if( myData['toast'] != undefined ){
        var tType = "";
        if( myData['toastType'] != undefined ){
            tType = myData['toastType'];
        }
        toast(myData['toast']);
    }

    // Tell 
    if( myData['tell'] != undefined ){
        tell(myData['tell']);
    }
}

/**
 * Display a message as a toast.
 * @param {String} message Message to show in the Toast.
 */
function toast(message){

    document.getElementById('toast-message').innerHTML = message;

    var toastElList = [].slice.call(document.querySelectorAll('.toast'))
    var toastList = toastElList.map(function (toastEl) {
        return new bootstrap.Toast(toastEl)
    });
    toastList.forEach(toast => toast.show());
}

function tell(message){

    document.getElementById('tellModalText').innerHTML = message;
    var tellModal = new bootstrap.Modal(document.getElementById('tellModal'));
    tellModal.show();

}


function loadReport(report){
    $("#myBox").load('php/rpt-frame.php?r=' + report, function(){
    
        var langPack = ""
    $('#glutaxReport').DataTable({
            "processing": true,
            "language":{
                "url": "DataTables/lang/" + myLang + ".json"
            },
            "pageLength": myReportLines,
            "sAjaxSource":"php/rpt-data.php?r=" + report,
            "dom":"Bfrtip",
            "buttons":[
                {
                    extend: 'collection',
                    text: 'Options',
                    buttons: [
                        'colvis',
                        'colvisRestore',
                        'pageLength'
                    ]
                },
                {
                    extend: 'collection',
                    text: 'Exporter',
                    buttons: [
                        'copy',
                        'excel',
                        'csv',
                        'pdf',
                        'print'
                    ]
                }
            ]
        });


    });
    
}