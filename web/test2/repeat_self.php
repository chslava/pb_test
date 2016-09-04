<?php

$myCode = '
$a = ["banana", "apple", "potato"];
var_dump($a);
';
echo $myCode;
eval($myCode);