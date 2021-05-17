<?php 
include('../php/gtInclude.php');
?>
<!-- Header -->
<h1 class="mt-5 text-white font-weight-light"><?php echo _HOME_WELCOME . ' ' . $_NAME; ?> !</h1>
<p class="lead text-white-50"><?= _HOME_SHORT_MESSAGE ?></p>
<hr>
<!-- Welcome Page -->

<!-- Metrics --> 

    <!-- YTD Metrics --> 
    <div class="card text-dark bg-light mb-3" style="max-width: 18rem;">
        <div class="card-header"><?= _LABEL_YTD . " (" . date("Y") . ")" ?></div>
        <div class="card-body">
            <h5 class="card-title">
                <?php  
                     $db = new gtDb();
                     $db->where(_SQL_PUR_ACCOUNT, $_ACCT);
                     $db->where('YEAR('._SQL_PUR_DATE.')', date("Y"));

                     $metrics = $db->rawQueryOne("SELECT Count("._SQL_PUR_ID.") AS pCount, Sum("._SQL_PUR_AMT_EXTRA.") AS pSum FROM "._SQL_PUR." WHERE YEAR("._SQL_PUR_DATE.") = ".date('Y')." AND "._SQL_PUR_ACCOUNT."=".$_ACCT);
                     echo $_CURRENCY->format($metrics["pSum"]);
                ?>
            </h5>
            <p class="card-text"><?php printf(_LABEL_METRICS_PAID_ON_PURCH, $metrics["pCount"]); ?></p>
        </div>
    </div>

<!-- List of recent purchases -->
<div class="bg-light p-3 rounded shadow-sm mt-2">
    <h4><?= _HOME_RECENT_PURCH ?></h4>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <th>#</th>
                <th><?= _LABEL_PURCH_DATE ?></th>
                <th><?= _LABEL_STORE ?></th>
                <?php  
                    if($_SESSION[_SQL_ACC_USE_PERSONS] == 1){
                        echo '<th>' . _LABEL_PERSON . '</th>';
                    }
                ?>
                <th><?= _LABEL_PURCH_EXTRA ?></th>
            </thead>
            <tbody>
            <?php
                $db = new gtDb();
                $db->where(_SQL_PUR_ACCOUNT, $_ACCT);
                $db->orderBy(_SQL_PUR_DATE, 'DESC');
                $db->join(_SQL_STO, _SQL_STO_ID .' = '. _SQL_PUR_STORE, 'LEFT');
                $db->join(_SQL_PER, _SQL_PER_ID .' = '. _SQL_PUR_PERSON, 'LEFT');
                $lines = $db->get(_SQL_PUR, $_SESSION[_SQL_ACC_LINES_WELCOME]);
                foreach($lines as $line){
                    $lineID = $line[_SQL_PUR_ID];
                    $lineNo = $line[_SQL_PUR_NUMBER];
                    $lineDate = $_DATE->format(strtotime($line[_SQL_PUR_DATE]));
                    $lineStoreName = $line[_SQL_STO_NAME];
                    $linePersonName = $line[_SQL_PER_NAME];
                    $lineAmount = $_CURRENCY->format($line[_SQL_PUR_AMT_EXTRA]);

                    echo '<tr style="cursor:pointer;" onclick="purchReceipt('.$lineID.');"><td>'.$lineNo.'</td><td>'.$lineDate.'</td><td>'.$lineStoreName;
                    echo '</td>';
                    if($_SESSION[_SQL_ACC_USE_PERSONS] == 1){
                        echo '<td>'.$linePersonName.'</td>';
                    }
                    echo '<td>'.$lineAmount.'</td></tr>';
                }
            ?>
            </tbody>
        </table>
    </div>
</div>