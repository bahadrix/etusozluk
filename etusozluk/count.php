<?php
include 'data/core/db.php';

$say = isset ($_GET['say']) ? $_GET['say'] : die("parametre giriniz");

$db = getPDO();

switch ($say) {
    
    case 'dun':
        $date_condition = "entries.Tarih BETWEEN ADDDATE(CURDATE(), INTERVAL - 1 DAY) AND CURDATE()";
        break;
    case 'gun':
        // parametreler girilmiş mi?
        if (!isset($_GET['gun']) || !isset($_GET['ay']) || !isset($_GET['yil'])) die('tarih parametresi eksik');
        // dogru mu girilmis peki?
        if (!checkdate($_GET['ay'], $_GET['gun'], $_GET['yil'])) die('gecersiz tarih');
        // tarihi olustur
        $tarih = "{$_GET['yil']}-{$_GET['ay']}-{$_GET['gun']}";
        // yardir
        $date_condition = "entries.Tarih BETWEEN '$tarih'  AND ADDDATE('$tarih', INTERVAL  1 DAY)";
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