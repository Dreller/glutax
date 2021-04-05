<?php 
require_once('gtForm.php');

$form = new gtForm();

$temp = Array(
    "name" => "test1",
    "label" => "Mon test #1",
    "type" => "text",
    "value" => "ABC"
);
$form->addControl($temp);

$temp = Array(
    "name" => "test2",
    "label" => "Mon test #2",
    "type" => "text",
    "value" => "xyz"
);
$form->addControl($temp);



$form->debug();

print "<hr>";

print $form->build();


?>