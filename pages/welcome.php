<?php 
include('../php/gtInclude.php');
?>
<!-- Header -->
<h1 class="mt-5 text-white font-weight-light"><?php echo _HOME_WELCOME . ' ' . $_NAME; ?> !</h1>
<p class="lead text-white-50"><?= _HOME_SHORT_MESSAGE ?></p>
<hr>
<!-- Welcome Page -->
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
                $db = new gtDb();
                $db->where(_SQL_PUR_ACCOUNT, $_ACCT);
                $db->orderBy(_SQL_PUR_DATE, 'DESC');
                $db->join(_SQL_STO, _SQL_STO_ID .' = '. _SQL_PUR_STORE, 'LEFT');
                $db->join(_SQL_PER, _SQL_PER_ID .' = '. _SQL_PUR_PERSON, 'LEFT');
                $lines = $db->get(_SQL_PUR, 20);
                foreach($lines as $line){
                    $lineID = $line[_SQL_PUR_ID];
                    $lineDate = $_DATE->format(strtotime($line[_SQL_PUR_DATE]));
                    $lineStoreName = $line[_SQL_STO_NAME];
                    $linePersonName = $line[_SQL_PER_NAME];
                    $lineAmount = $_CURRENCY->format($line[_SQL_PUR_AMT_EXTRA]);

                    echo '<tr style="cursor:pointer;" onclick="purchReceipt('.$lineID.');"><td>'.$lineDate.'</td><td>'.$lineStoreName;
                    echo '</td><td>'.$linePersonName.'</td><td>'.$lineAmount.'</td></tr>';
                }
            ?>
            </tbody>
        </table>
    </div>
</div>