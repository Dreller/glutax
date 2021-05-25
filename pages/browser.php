<?php
# Global Include.
include('../php/gtInclude.php');
?>
<!-- Header -->
<h1 class="mt-5 text-white font-weight-light"><?= _REPORT_BROWSER ?> </h1>
<p class="lead text-white-50"><?= _REPORT_BROWSER_SEL_HELP ?></p>
<hr>
<div class="bg-light p-3 rounded shadow-sm">
<h3><?= _REPORT_BROWSER_SEL_TITLE ?></h3>
<div class="row justify-content-around">
    <div class="col-md-4">
        <label for="txtStartDate" class="form-label text-start"><?= _REPORT_BROWSER_SEL_START ?></label>
        <input type="date" class="form-control" id="txtStartDate" value="<?php echo date('Y-m-01'); ?>">
        <label for="txtEndDate" class="form-label text-start"><?= _REPORT_BROWSER_SEL_END ?></label>
        <input type="date" class="form-control" id="txtEndDate" value="<?php echo date('Y-m-t'); ?>">
    </div>
    <div class="col-md-3">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="chkSummarize">
            <label class="form-check-label" for="chkSummarize">Purchase Summary</label>
        </div>
    </div>
</div>

<button type="button" class="btn btn-primary" onclick="buildQueryString();"><?= _REPORT_BROWSER_SEL_GO ?></button>

</div>

<script>

function buildQueryString(){
    var startDate = $("#txtStartDate").val();
    var endDate = $("#txtEndDate").val();
    var summary = "n";
    if( document.getElementById("chkSummarize").checked == true ){
        summary = "y";
    }

    var defn = "&start=" + startDate + "&end=" + endDate + "&summ=" + summary;

    var rpt = "custom";
    loadReport(rpt, defn);
}

</script>