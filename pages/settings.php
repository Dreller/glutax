<?php 
session_start();
include('../php/lang/'.$_SESSION['accountLanguage'].'.php');

require_once('../php/gtDb.php');
$db = new gtDb();
?>

<h1 class="mt-5 text-white font-weight-light"><?= _SETTING_TITLE ?></h1>
<p class="lead text-white-50"><?= _SETTING_SUBTITLE ?></p>
<hr>

<div class="bg-light p-3 rounded shadow-sm mt-2 mb-2" id="gtForm">

<h4><?= _SETTING_YOU ?></h4>
    <div class="row mb-3">
        <div class="col">
            <label for="accountName" class="form-label text-left"><?= _SETTING_YOU_NAME ?></label>
            <input type="text" id="accountName" name="accountName" value="<?php echo $_SESSION['accountName']; ?>" class="form-control" aria-describedby="accountNameHelp">
            <div id="accountNameHelp" class="form-text text-start"><?= _SETTING_YOU_NAME_HELP ?></div>
        </div>
        <div class="col">
            <label for="accountEmail" class="form-label text-start"><?= _SETTING_YOU_EMAIL ?></label>
            <input type="email" id="accountEmail" name="accountEmail" value="<?php echo $_SESSION['accountEmail']; ?>" class="form-control" aria-describedby="accountEmailHelp">
            <div id="accountEmailHelp" class="form-text text-start"><?= _SETTING_YOU_EMAIL_HELP ?></div>
        </div>
    </div>


<h4><?= _SETTING_REGION ?></h4>
    <div class="row mb-3">
        <div class="col">
            <label for="accountLanguage" class="form-label text-start"><?= _SETTING_REGION_LANG ?></label>
            <select id="accountLanguage" name="accountLanguage" class="form-select disabled" aria-describedby="accountLanguageHelp">
                <?php 
                    $dir = "../php/lang";
                    $files = array_diff(scandir($dir), array('.', '..'));
                    foreach( $files as $file ){
                        $path = pathinfo($file);
                        $lang = $path['filename'];
                        $name = ucwords(Locale::getDisplayName($lang, $lang));
                        $selected = ( $_SESSION['accountLanguage']==$lang ? ' selected' : '' );
                        echo "<option value='$lang' $selected>$name</option>";
                    }
                ?>
            </select>
            <div id="accountLanguageHelp" class="form-text text-start"><?= _SETTING_REGION_LANG_HELP ?></div>
        </div>
        <div class="col">
            <label for="accountLocale" class="form-label text-start"><?= _SETTING_REGION_LOCALE ?></label>
            <select id="accountLocale" name="accountLocale" class="form-select" aria-describedby="accountLocaleHelp">
                <option value="en-US" <?php echo ($_SESSION['accountLocale']=='en-US'?' selected':''); ?>>English (US)</option>
                <option value="en-CA" <?php echo ($_SESSION['accountLocale']=='en-CA'?' selected':''); ?>>English (CA)</option>
                <option value="fr-CA" <?php echo ($_SESSION['accountLocale']=='fr-CA'?' selected':''); ?>>Fran&ccedil;ais (CA)</option>
            </select>
            <div id="accountLocaleHelp" class="form-text text-start"><?= _SETTING_REGION_LOCALE_HELP ?></div>
        </div>
    </div>

<input type="hidden" name="method" id="method" value="updateProfile">
<button class="btn btn-primary" onclick="sendForm();"><?= _BUTTON_SAVE ?></button>

</div>