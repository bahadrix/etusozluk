<?php
include 'data/core/db.php';

$say = !empty($_GET['say']) ? $_GET['say'] : die("parametre giriniz");

$db = getPDO();

if ($say=="yazar" && !empty($_GET['y'])) {
	$yazar = $_GET['y'];
	$yazar = preg_replace('/\+/',' ',$yazar);
	$y = $db->prepare("SELECT U_ID from members WHERE Nick=:nick");
	$y -> bindValue(":nick",$yazar);
	$y -> execute();
	if (!$y->rowCount()) {
		$UID = 1;
	} 
	else {
		$yid = $y->fetch(PDO::FETCH_ASSOC);
		$UID = $yid['U_ID'];
	}
	$y1 = $db -> prepare("SELECT count(DISTINCT T_ID) as sayi from entries WHERE U_ID=:uid GROUP BY U_ID");
	$y1 -> bindValue(":uid",$UID);
	$y1 -> execute();
	if (!$y1->rowCount())
		echo "0";
	else  {
		$sayi = $y1->fetch(PDO::FETCH_ASSOC);
		echo $sayi['sayi'];
	}
} else {
$date_condition = $say == 'gun' ? getCondByDate($say, 'Tarih',$_GET['gun'], $_GET['ay'], $_GET['yil'] ) : getCondByDate($say, 'Tarih');


$sayi = $db->query("SELECT COUNT(T_ID) AS sayi FROM titles WHERE $date_condition")->fetch();
echo $sayi['sayi'];
}
?>