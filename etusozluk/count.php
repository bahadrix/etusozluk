<?php
include 'common.php';

$say = $_GET['say'] or die("parametre giriniz");

$db = getPDO();

switch ($say) {
    
    case 'bugun':
        //$date_condition
    break;
    default:
        die('bilinmeyen tarih');
    
}


if ($say=="bugun")
echo 6;
else if ($say=="dun")
echo 8;
?>