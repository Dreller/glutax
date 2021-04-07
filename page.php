<!DOCTYPE html>
<?php  
session_start();

?>
<html>
<head>
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
        <a id="glutaxTitle" class="navbar-brand" href="#">GluTax</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#Navbar" aria-controls="Navbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="Navbar">
            <ul class="navbar-nav">
            <!-- Shortcut to add a new expense -->
                <li class="nav-item">
                    <a id="NavNewPurchase" class="nav-link" href="#">New Purchase</a>
                </li>
            <!-- Tables menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarTables" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Tables
                    </a>
                    <div class="dropdown-menu dropdown-menu-right animate slideIn" aria-labelledby="navbarTables">
                        <a class="dropdown-item navTable" href="#" data-table="category">Categories</a>
                        <a class="dropdown-item navTable" href="#" data-table="person">Persons</a>
                        <a class="dropdown-item navTable" href="#" data-table="product">Products</a>
                        <a class="dropdown-item navTable" href="#" data-table="store">Stores</a>
                    </div>
                </li>
            <!-- Options menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarOptions" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Options
                    </a>
                    <div class="dropdown-menu dropdown-menu-right animate slideIn" aria-labelledby="navbarOptions">
                        <a class="dropdown-item" href="#" onclick="loadPage('settings','');">Settings</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Log out</a>
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