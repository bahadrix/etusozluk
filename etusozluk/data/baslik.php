<?php include 'core/db.php';
try {
if (empty($_GET['g']))
	$gun = "bugun";
else
	$gun = $_GET['g'];
if (empty($_GET['page'])) 
	$page = 1;
else
	$page = $_GET['page'];

$per_page = 50; // Per page records


$next_start = ($page - 1) * $per_page; 

$date_condition = $gun == 'gun' ? 
				getCondByDate($gun, 'tarih', $_GET['gun'], $_GET['ay'], $_GET['yil'] ) : 
				getCondByDate($gun, 'tarih');
				

$DBO = getPDO();
$titles = $DBO->query("SELECT titles.T_ID as id, titles.Baslik as baslik FROM titles WHERE $date_condition LIMIT $next_start,$per_page");

$count = $DBO->query("SELECT count(E_ID) as count FROM entries WHERE $date_condition GROUP BY T_ID");

$t=$titles->fetchAll(PDO::FETCH_ASSOC);
$e=$count->fetchAll(PDO::FETCH_ASSOC);

for ($i=0;$i<count($t);$i++) {
	$t[$i]['count'] = $e[$i]['count'];
}
echo json_encode(
		array("items" =>
			$t
				)
		);
} catch (PDOException $e) {
	echo '{"items":[{"hata":"1"}]}';
}
?>