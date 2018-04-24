<?php 
ob_start();
$a = "aaaasac";
echo $a;
echo "<hr/>";

$out = ob_get_contents();

echo $out;