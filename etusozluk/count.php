<?php
include 'data/core/db.php';

$say = isset ($_GET['say']) ? $_GET['say'] : die("parametre giriniz");

$db = getPDO();

switch ($say) {
    
    case 'dun':
        $date_condition = "entries.Tarih BETWEEN ADDDATE(CURDATE(), INTERVAL - 1 DAY) AND CURDATE()";
        break;
    case 'bugun':
        $date_condition = "entries.Tarih BETWEEN CURDATE() AND ADDDATE(CURDATE(), INTERVAL  1 DAY)";
        break;
    default:
        die('bilinmeyen tarih');    
}

$sayi = $db->query("SELECT COUNT(E_ID) AS sayi FROM entries WHERE $date_condition")->fetch();
echo $sayi['sayi'];


?>