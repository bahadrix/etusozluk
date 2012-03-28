<?php
include 'data/core/db.php';

$say = isset ($_GET['say']) ? $_GET['say'] : die("parametre giriniz");

$db = getPDO();

$date_condition = $say == 'gun' ? getCondByDate($say, 'entries.Tarih',$_GET['gun'], $_GET['ay'], $_GET['yil'] ) : getCondByDate($say, 'entries.Tarih');


$sayi = $db->query("SELECT COUNT(E_ID) AS sayi FROM entries WHERE $date_condition")->fetch();
echo $sayi['sayi'];


?>