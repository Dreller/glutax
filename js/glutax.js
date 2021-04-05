$(document).ready(function(){
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

function launch(el){
    var action = $(el).data("action");
    var type = $(el).data("type");
    console.log(">> Launching: " + type + " " + action);
    window["gt_" + action](type);
}

function gt_add(type){
    loadPage("editItem", "t=" + type);
}

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

function loading(){
    $("#myBox").html("<div class='d-flex justify-content-center'><div class='spinner-border text-light mt-5' role='status'><span class='visually hidden'></span></div></div>");
}