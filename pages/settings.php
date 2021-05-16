<?php 
# Global Include
include('../php/gtInclude.php');
$db = new gtDb();
?>
<!-- Header -->
<h1 class="mt-5 text-white font-weight-light"><?= _SETTING_TITLE ?></h1>
<p class="lead text-white-50"><?= _SETTING_SUBTITLE ?></p>
<hr>
<!-- Settings Form -->
<div class="bg-light p-3 rounded shadow-sm mt-2 mb-2" id="gtForm">
<h4><?= _SETTING_YOU ?></h4>
    <div class="row mb-3">
        <div class="col">
            <label for="<?= _SQL_ACC_NAME ?>" class="form-label text-left"><?= _SETTING_YOU_NAME ?></label>
            <input type="text" id="<?= _SQL_ACC_NAME ?>" name="<?= _SQL_ACC_NAME ?>" value="<?php echo $_NAME; ?>" class="form-control" aria-describedby="<?= _SQL_ACC_NAME ?>Help">
            <div id="<?= _SQL_ACC_NAME ?>Help" class="form-text text-start"><?= _SETTING_YOU_NAME_HELP ?></div>
        </div>
        <div class="col">
            <label for="<?= _SQL_ACC_EMAIL ?>" class="form-label text-start"><?= _SETTING_YOU_EMAIL ?></label>
            <input type="email" id="<?= _SQL_ACC_EMAIL ?>" name="<?= _SQL_ACC_EMAIL ?>" value="<?php echo $_SESSION[_SQL_ACC_EMAIL]; ?>" class="form-control" aria-describedby="<?= _SQL_ACC_EMAIL ?>Help">
            <div id="<?= _SQL_ACC_EMAIL ?>Help" class="form-text text-start"><?= _SETTING_YOU_EMAIL_HELP ?></div>
        </div>
    </div>
<h4><?= _SETTING_REGION ?></h4>
    <div class="row mb-3">
        <div class="col">
            <label for="<?= _SQL_ACC_LANG ?>" class="form-label text-start"><?= _SETTING_REGION_LANG ?></label>
            <select id="<?= _SQL_ACC_LANG ?>" name="<?= _SQL_ACC_LANG ?>" class="form-select disabled" aria-describedby="<?= _SQL_ACC_LANG ?>Help">
                <?php 
                    $dir = "../php/lang";
                    $files = array_diff(scandir($dir), array('.', '..'));
                    foreach( $files as $file ){
                        $path = pathinfo($file);
                        $lang = $path['filename'];
                        $name = ucwords(Locale::getDisplayName($lang, $lang));
                        $selected = ( $_SESSION[_SQL_ACC_LANG]==$lang ? ' selected' : '' );
                        echo "<option value='$lang' $selected>$name</option>";
                    }
                ?>
            </select>
            <div id="<?= _SQL_ACC_LANG ?>Help" class="form-text text-start"><?= _SETTING_REGION_LANG_HELP ?></div>
        </div>
        <div class="col">
            <label for="<?= _SQL_ACC_LOCAL ?>" class="form-label text-start"><?= _SETTING_REGION_LOCALE ?></label>
            <select id="<?= _SQL_ACC_LOCAL ?>" name="<?= _SQL_ACC_LOCAL ?>" class="form-select" aria-describedby="<?= _SQL_ACC_LOCAL ?>Help">
                <option value="en-CA" <?php echo ($_SESSION[_SQL_ACC_LOCAL]=='en-CA'?' selected':''); ?>>English (Canada)</option>
                <option value="fr-CA" <?php echo ($_SESSION[_SQL_ACC_LOCAL]=='fr-CA'?' selected':''); ?>>Fran&ccedil;ais (Canada)</option>
            </select>
            <div id="<?= _SQL_ACC_LOCAL ?>Help" class="form-text text-start"><?= _SETTING_REGION_LOCALE_HELP ?></div>
        </div>
    </div>
    <h4><?= _SETTING_MISC ?></h4>
    <div class="row mb-3">
        <!--<div class="col">
            <label for="<?= _SQL_ACC_NAME ?>" class="form-label text-left"><?= _SETTING_YOU_NAME ?></label>
            <input type="text" id="<?= _SQL_ACC_NAME ?>" name="<?= _SQL_ACC_NAME ?>" value="<?php echo $_NAME; ?>" class="form-control" aria-describedby="<?= _SQL_ACC_NAME ?>Help">
            <div id="<?= _SQL_ACC_NAME ?>Help" class="form-text text-start"><?= _SETTING_YOU_NAME_HELP ?></div>
        </div>
        <div class="col">
            <label for="<?= _SQL_ACC_EMAIL ?>" class="form-label text-start"><?= _SETTING_YOU_EMAIL ?></label>
            <input type="email" id="<?= _SQL_ACC_EMAIL ?>" name="<?= _SQL_ACC_EMAIL ?>" value="<?php echo $_SESSION[_SQL_ACC_EMAIL]; ?>" class="form-control" aria-describedby="<?= _SQL_ACC_EMAIL ?>Help">
            <div id="<?= _SQL_ACC_EMAIL ?>Help" class="form-text text-start"><?= _SETTING_YOU_EMAIL_HELP ?></div>
        </div>-->
    </div>

<input type="hidden" name="method" id="method" value="updateProfile">
<button class="btn btn-primary" onclick="sendForm();"><?= _BUTTON_SAVE ?></button>

</div>