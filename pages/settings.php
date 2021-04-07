<?php 
session_start();

require_once('../php/gtDb.php');
$db = new gtDb();
?>

<h1 class="mt-5 text-white font-weight-light">Settings</h1>
<p class="lead text-white-50">Customize GluTax to makes it works the way <strong>you</strong> want!</p>
<hr>

<div class="bg-light p-3 rounded shadow-sm mt-2 mb-2" id="gtForm">

<h4>You</h4>
    <div class="row mb-3">
        <div class="col">
            <label for="accountName" class="form-label text-left">Your name</label>
            <input type="text" id="accountName" name="accountName" value="<?php echo $_SESSION['accountName']; ?>" class="form-control" aria-describedby="accountNameHelp">
            <div id="accountNameHelp" class="form-text text-start">This is the name that will be used on reports and to identify you.  You can enter your real name or a nickname, as you wish.</div>
        </div>
        <div class="col">
            <label for="accountEmail" class="form-label text-start">Your email address</label>
            <input type="email" id="accountEmail" name="accountEmail" value="<?php echo $_SESSION['accountEmail']; ?>" class="form-control" aria-describedby="accountEmailHelp">
            <div id="accountEmailHelp" class="form-text text-start">The email GluTax can use to reach you, in case you forget your password.  Your email will be kept private and will not be shared.</div>
        </div>
    </div>


<h4>Region</h4>
    <div class="row mb-3">
        <div class="col">
            <label for="accountLanguage" class="form-label text-start">Language</label>
            <select id="accountLanguage" name="accountLanguage" class="form-select disabled" aria-describedby="accountLanguageHelp">
                <option value="EN" selected>English</option>
                <option value="FR">Fran&ccedil;ais</option>
            </select>
            <div id="accountLanguageHelp" class="form-text text-start">GluTax will soon be available in other languages than English.</div>
        </div>
        <div class="col">
            <label for="accountLocale" class="form-label text-start">Local preference</label>
            <select id="accountLocale" name="accountLocale" class="form-select" aria-describedby="accountLocaleHelp">
                <option value="en-US" <?php echo ($_SESSION['accountLocale']=='en-US'?' selected':''); ?>>English (US)</option>
                <option value="en-CA" <?php echo ($_SESSION['accountLocale']=='en-CA'?' selected':''); ?>>English (CA)</option>
                <option value="fr-CA" <?php echo ($_SESSION['accountLocale']=='fr-CA'?' selected':''); ?>>Fran&ccedil;ais (CA)</option>
            </select>
            <div id="accountLocaleHelp" class="form-text text-start">Choose your local qualifier, to let GluTax know how to display amounts and dates.</div>
        </div>
    </div>

<input type="hidden" name="method" id="method" value="updateProfile">
<button class="btn btn-primary" onclick="sendForm();">Save</button>

</div>