<!DOCTYPE html>
<?php  
session_start();
include('php/lang/'.$_SESSION['accountLanguage'].'.php');

?>
<html>
<head>
<title><?= _NAME ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="css/bootstrap.min.css" rel="stylesheet">
<style>

@media (min-width: 992px) {
  .animate {
    animation-duration: 0.3s;
    -webkit-animation-duration: 0.3s;
    animation-fill-mode: both;
    -webkit-animation-fill-mode: both;
  }
}

@keyframes slideIn {
  0% {
    transform: translateY(1rem);
    opacity: 0;
  }
  100% {
    transform:translateY(0rem);
    opacity: 1;
  }
  0% {
    transform: translateY(1rem);
    opacity: 0;
  }
}

@-webkit-keyframes slideIn {
  0% {
    -webkit-transform: transform;
    -webkit-opacity: 0;
  }
  100% {
    -webkit-transform: translateY(0);
    -webkit-opacity: 1;
  }
  0% {
    -webkit-transform: translateY(1rem);
    -webkit-opacity: 0;
  }
}

.slideIn {
  -webkit-animation-name: slideIn;
  animation-name: slideIn;
}

/* Other styles for the page not related to the animated dropdown */

body {
  background: #007bff;
  background: linear-gradient(to right, #0062E6, #33AEFF);
}

</style>

</head>
<body>
<!-- Navigation bar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a id="glutaxTitle" class="navbar-brand" href="#"><?= _NAME ?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#Navbar" aria-controls="Navbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="Navbar">
            <ul class="navbar-nav">
            <!-- Shortcut to add a new expense -->
                <li class="nav-item">
                    <a id="NavNewPurchase" class="nav-link" href="#"><?= _NAVBAR_NEW_PURCHASE ?></a>
                </li>
            <!-- Reports menu -->
            <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarReports" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?= _NAVBAR_REPORT ?>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right animate slideIn" aria-labelledby="navbarReports">
                        <a class="dropdown-item navReport" href="#" data-table="purch-all"><?= _REPORT_PURCH_ALL ?></a>
                    </div>
                </li>
            <!-- Tables menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarTables" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?= _NAVBAR_TABLE ?>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right animate slideIn" aria-labelledby="navbarTables">
                        <a class="dropdown-item navTable" href="#" data-table="category"><?= _TABLE_CATEGORY ?></a>
                        <a class="dropdown-item navTable" href="#" data-table="person"><?= _TABLE_PERSON ?></a>
                        <a class="dropdown-item navTable" href="#" data-table="product"><?= _TABLE_PRODUCT ?></a>
                        <a class="dropdown-item navTable" href="#" data-table="store"><?= _TABLE_STORE ?></a>
                    </div>
                </li>
            <!-- Options menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarOptions" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?= _NAVBAR_OPTION ?>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right animate slideIn" aria-labelledby="navbarOptions">
                        <a class="dropdown-item" href="#" onclick="loadPage('settings','');"><?= _OPTION_SETTING ?></a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#"><?= _OPTION_LOGOUT ?></a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!-- END: Navigation Bar -->

<!-- Container -->
<div id="myBox" class="container text-center"></div>
<!-- END: Container -->

<!-- Toast -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 5">
    <div id="liveToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <img src="..." class="rounded me-2" alt="">
            <strong class="me-auto"><?= _NAME ?></strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="<?= _BUTTON_CLOSE ?>"></button>
        </div>
        <div class="toast-body" id="toast-message">

        </div>
    </div>
</div>
<!-- END: Toast -->

<!-- Modal / Purchase Receipt -->
<div class="modal fade" id="purchaseReceipt" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <?= _LABEL_PURCH_DETAILS_QUICK ?>
            </div>
            <div class="modal-content p-3" id="purchaseReceiptBody">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= _BUTTON_CLOSE ?></button>
            </div>
        </div>
    </div>
</div>
<!-- END: Modal / Purchase Receipt -->

<!-- JS Scripts imports -->
<script src="js/jquery_3.5.1.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
<script src="js/glutax.js"></script>

<!-- END: JS Scripts imports -->

<!-- JQuery/Other Script --> 
<script>
</script>


</body>
</html>