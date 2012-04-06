<?php
	include_once 'common.php';
	if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
		$link = getPDO();
		if (!empty($_REQUEST['t'])) {
			$baslik = trim(strtolower($_REQUEST['t']));
			$arama = "%".$baslik."%";
			$sb = $link -> prepare("SELECT Baslik FROM titles WHERE Baslik LIKE :baslik LIMIT 20");
			$sb -> bindValue(":baslik",$arama);
			$sb -> execute();
			if ($sb->rowCount()) {
				$basliklar = $sb->fetchAll(PDO::FETCH_ASSOC);
				echo json_encode($basliklar);
			}
		}
	}
?>