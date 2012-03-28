<?php include 'core/db.php';

$page = $_GET['page'];
$gun = $_GET['g'];
$per_page = 3; // Per page records


$next_start = ($page - 1) * $per_page; 

$date_condition = $gun == 'gun' ? 
				getCondByDate($gun, 'titles.tarih', $_GET['gun'], $_GET['ay'], $_GET['yil'] ) : 
				getCondByDate($gun, 'titles.tarih');


$DBO = getPDO();
$titles = $DBO->query("SELECT titles.T_ID as id, titles.Baslik as baslik FROM titles WHERE $date_condition LIMIT $next_start,$per_page");


echo json_encode(
		array('items' =>
			$titles->fetchAll(PDO::FETCH_ASSOC)
				)
		);

?>