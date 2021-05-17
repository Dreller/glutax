<?php 
# Master Script to include in all pages.
# =========================================================
session_start();
# Handle situations where a session is not set.
if( !isset($_SESSION['accountID']) || empty($_SESSION['accountID']) ){
    session_destroy();
    header('Location: https://dreller.dev/glutax');
    exit();
}
# =========================================================
# Enable short-code opening
ini_set('short_open_tag', 'On');
# =========================================================
# Application 
$appVersion = "0.07";

# =========================================================
# Shortcuts for Sessions
$_ACCT = $_SESSION['accountID'];
$_NAME = $_SESSION['accountName'];
$_LOCALE = $_SESSION['accountLocale'];
$_LANG = $_SESSION['accountLanguage'];
    if( $_LANG == "" ){
        $_LANG = "FR";
    }
# =========================================================
# Display every error messages
if( $_SERVER['REMOTE_ADDR'] == '::1' || $_SERVER['REMOTE_ADDR'] == '127.0.0.1'){
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}
# =========================================================
# Load Translation
$_langSource = '../php/lang/' . $_LANG . '.php';
if( !file_exists($_langSource) ){
    $_langSource = 'php/lang/' . $_LANG .'.php';
}
include( $_langSource );
# =========================================================
# Load SQL Names Map
$_mapSource = '../php/gtMap.php';
if( !file_exists($_mapSource) ){
    $_mapSource = 'php/gtMap.php';
}
include( $_mapSource );
# =========================================================
# Load Database Class
$_dbSource = '../php/gtDb.php';
if( !file_exists($_dbSource) ){
    $_dbSource = 'php/gtDb.php';
}
require_once($_dbSource);
# =========================================================
# Formatters
$_CURRENCY = new NumberFormatter($_LOCALE, NumberFormatter::CURRENCY);
$_DATE = new IntlDateFormatter($_LOCALE, IntlDateFormatter::MEDIUM, IntlDateFormatter::NONE, 'America/Toronto', IntlDateFormatter::GREGORIAN);
# =========================================================
# General Arrays
$_UM = Array(
    "g" => _LABEL_G,
    "mL" => _LABEL_ML,
    "u" => _LABEL_U
);
?>