<?php 
try {
	if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') { //şu an sadece js olduğu için bu kadar yeterli, diğer kısmını sonra yaparız
	include 'core/db.php';
	if (!empty($_GET['g']) || !empty($_GET['y']) || !empty($_GET['rast'])) {
		if (empty($_GET['page'])) 
			$page = 1;
		else
			$page = $_GET['page'];
		
		$DBO = getPDO();
		$per_page = 50; // Sayfa başına entry
		$next_start = ($page - 1) * $per_page;
		
		if (!empty($_GET['g']) && empty($_GET['y']) && empty($_GET['rast'])) { //gün feed
			$gun = $_GET['g'];
 

			$date_condition = $gun == 'gun' ? 
				getCondByDate($gun, 'tarih', $_GET['gun'], $_GET['ay'], $_GET['yil'] ) : 
				getCondByDate($gun, 'tarih');
		
			$titles = $DBO->query("SELECT titles.T_ID as id, titles.Baslik as baslik FROM titles WHERE $date_condition ORDER BY Tarih DESC LIMIT $next_start,$per_page");

			$count = $DBO->query("SELECT count(E_ID) as count,T_ID FROM entries WHERE $date_condition AND Aktif=1 AND Thrash=0 GROUP BY T_ID LIMIT $next_start,$per_page");

			$t=$titles->fetchAll(PDO::FETCH_ASSOC);
			$e=$count->fetchAll(PDO::FETCH_ASSOC);
			
			for ($i=0;$i<count($t);$i++) {
				for ($j=0;$j<count($t);$j++) {
					if ($t[$i]['id'] == $e[$j]['T_ID'])
						$t[$i]['count'] = $e[$j]['count'];
				}
			}
			
		} else if (empty($_GET['g']) && !empty($_GET['y']) && empty($_GET['rast'])) { //yazar feed
			$yazar = $_GET['y'];
			$yazar = preg_replace('/\+/',' ',$yazar);
			$y = $DBO->prepare("SELECT U_ID from members WHERE Nick=:nick");
			$y -> bindValue(":nick",$yazar);
			$y -> execute();
			if (!$y->rowCount()) {
				$t = "{'hata':'kullanıcı bulunamadı'}";
			} else {
				$yid = $y->fetch(PDO::FETCH_ASSOC);
				$y1 = $DBO -> prepare("SELECT T_ID as id, Baslik as baslik, count FROM (SELECT T_ID,Baslik FROM titles) as t NATURAL JOIN (SELECT U_ID, T_ID, count(E_ID) as count from entries WHERE U_ID=:uid AND Aktif=1 AND Thrash=0 GROUP BY T_ID) as e ORDER BY id LIMIT $next_start,$per_page");
				$y1 -> bindValue(":uid",$yid['U_ID']);
				$y1 -> execute();
			
				if (!$y1->rowCount())
					$t = "{'hata':'yazarın yazdığı başlık yok'}";
				else 
					$t = $y1->fetchAll(PDO::FETCH_ASSOC);
			}
		} else if (empty($_GET['g']) && empty($_GET['y']) && !empty($_GET['rast'])) { //rastgele
			$r = $DBO -> query("SELECT T_ID as id, Baslik as baslik, count FROM (SELECT T_ID as id, Baslik as baslik FROM titles ORDER BY RAND() LIMIT $per_page) as t NATURAL JOIN (SELECT T_ID, count(E_ID) as count from entries WHERE Aktif=1 AND Thrash=0 GROUP BY T_ID) as e WHERE e.T_ID = id ORDER BY RAND()");			
			if (!$r->rowCount())
				$t = "{'hata':'yazılmış başlık yok'}";
			else 
				$t = $r->fetchAll(PDO::FETCH_ASSOC);
			}
		echo json_encode(
			array("items" =>
			$t
			)
		);
		
	}
} else
	header("Location: ../index.php");

}catch (PDOException $e) {
	echo '{"items":[{"hata":"hata oluştu"}]}';
}
?>