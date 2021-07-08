<?php
# Global Include.
include('../php/gtInclude.php');
?>
<!-- Header -->
<h1 class="mt-5 text-white font-weight-light"><?php echo _HELP_CALC; ?> </h1>
<p class="lead text-white-50"><?php echo _HELP_CALC_HELP; ?></p>
<hr>
<div class="bg-light p-3 rounded shadow-sm text-start">
<?php  
if( $_LANG == "FR" ){
    echo "<p>Chaque produit sans gluten est compar&eacute; &agrave; un &eacute;quivalent avec gluten.  Chacun des produits est ramen&eacute; &agrave; un prix par unit&eacute;, ";
    echo "par exemple, un prix par gramme.  Ce premier calcul permet d'obtenir un prix pour une m&ecirc;me quantit&eacute;.</p><p>La ";
    echo "diff&eacute;rence entre les deux prix normalis&eacute;s est calcul&eacute;e, puis, multipli&eacute;e par la quantit&eacute; du produit sans gluten.</p>";
    echo "<p>Cette m&eacute;thode permet de calculer la diff&eacute;rence pay&eacute;e pour le produit sans gluten, &agrave; quantit&eacute; et poids &eacute;gal.</p>"; 
    echo "<h3>Exemple</h3>";
    echo "<dl class='row'><dt class='col-sm-2'>Sans gluten</dt><dd class='col-sm-10'>Biscuits sandwichs &agrave; l'&eacute;rable Great Value sans gluten<br>3,97 $<br>325 g<br><strong>3,97 $ &divide; (325 g. &divide; 100 g.) = <u>1,22 $</u> par 100 grammes</strong>.</dd></dt>";
    echo "<dt class='col-sm-2'>Avec gluten</dt><dd class='col-sm-10'>Biscuits Tradition de Leclerc en forme de feuille d'&eacute;rable<br>2,67 $<br>350 g<br><strong>2,67 $ &divide; (350 g. &divide; 100 g.) = <u>0,76 $</u> par 100 grammes</strong>.</dd></dt></dl>";
    echo "Mon produit co&ucirc;te 0,46$ de plus par 100 grammes (1,22$ - 0,76$ = 0,46$).<br>";
    echo "Mon produit est un format de 325 grammes:  325 g. &divide; 100 g. = 3,25 &times; 0,46$ = 1,495 $ &asymp; <u>1,49$</u> pay&eacute; en extra.";
}else{
    echo "<p>Each gluten-free product is compared with a with-gluten product.  Each product is reduced to a common unit price, e.g. a price per gram.  ";
    echo "This first step allow us to get the price of each products on a same basis.</p><p>The ";
    echo "discrepancy between both normalized prices is calculated, and then, extended to the gluten-free product quantity.</p>";
    echo "<p>This method return the accurate amount paid in extra for the gluten-free alternative, for the same quantity and weigth.</p>"; 
    echo "<h3>Example</h3>";
    echo "<dl class='row'><dt class='col-sm-2'>Gluten-free</dt><dd class='col-sm-10'>Great Value Gluten Free Maple Cream Sandwich Cookies<br>$3.97<br>325 g<br><strong>$3.97 &divide; (325 g. &divide; 100 g.) = <u>$1.22</u> per 100 grams</strong>.</dd></dt>";
    echo "<dt class='col-sm-2'>With gluten</dt><dd class='col-sm-10'>Tradition Maple Leaf Cookies<br>$2.67<br>350 g<br><strong>$2.67 &divide; (350 g. &divide; 100 g.) = <u>$0.76</u> per 100 grams</strong>.</dd></dt></dl>";
    echo "My product cost $0.46 more per 100 grams ($1.22 - $0.76 = $0.46).<br>";
    echo "My product is a format of 325 grams:  325 g. &divide; 100 g. = 3.25 &times; $0.46 = $1.495 &asymp; <u>$1.49</u> paid in extra.";
}


?>
</div>