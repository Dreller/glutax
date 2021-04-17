<?php  

$cols = Array(
    'Date',
    'Commerce'
);

?>
<table id="glutaxReport" class="display" cellspacing="0">
    <thead>
        <tr>
            <?php 
                foreach( $cols as $col ){
                    echo "<th>$col</th>";
                }
            ?>
        </tr>
    </thead>
</table>