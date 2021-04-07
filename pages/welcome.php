<?php 
session_start();
?>

<h1 class="mt-5 text-white font-weight-light">Welcome <?php echo $_SESSION['accountName']; ?> !</h1>
<p class="lead text-white-50">Short message to the user here.</p>
<hr>

<div class="bg-light p-3 rounded shadow-sm mt-2">
    <h4>Recent purchases</h4>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <th>Date</th>
                <th>Store</th>
                <th>Person</th>
                <th>Extra Amount</th>
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
