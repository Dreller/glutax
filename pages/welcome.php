<?php 
session_start();
include('../php/lang/'.$_SESSION['accountLanguage'].'.php');

?>

<h1 class="mt-5 text-white font-weight-light"><?php echo _HOME_WELCOME . ' ' . $_SESSION['accountName']; ?> !</h1>
<p class="lead text-white-50"><?= _HOME_SHORT_MESSAGE ?></p>
<hr>

<div class="bg-light p-3 rounded shadow-sm mt-2">
    <h4><?= _HOME_RECENT_PURCH ?></h4>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <th><?= _LABEL_PURCH_DATE ?></th>
                <th><?= _LABEL_STORE ?></th>
                <th><?= _LABEL_PERSON ?></th>
                <th><?= _LABEL_PURCH_EXTRA ?></th>
            </thead>
            <tbody>
            <?php

                $fmt_cur = new NumberFormatter($_SESSION['accountLocale'], NumberFormatter::CURRENCY);
                $fmt_date= new IntlDateFormatter($_SESSION['accountLocale'], IntlDateFormatter::MEDIUM, IntlDateFormatter::MEDIUM);

                require_once('../php/gtDb.php');
                $db = new gtDb();
                $db->where('purchaseAccountID', $_SESSION['accountID']);
                $db->orderBy('purchaseDate', 'DESC');
                $db->join('tbStore', 'storeID = purchaseStoreID', 'LEFT');
                $db->join('tbPerson', 'personID = purchasePersonID', 'LEFT');
                $lines = $db->get('tbPurchase', 20);
                foreach($lines as $line){
                    echo '<tr><td>'.$line['purchaseDate'].'</td><td>'.$line['storeName'].'</td><td>'.$line['personName'].'</td><td>'.$fmt_cur->format($line['purchaseAmountExtra']).'</td></tr>';
                }
            ?>
            </tbody>
        </table>
    </div>
</div>