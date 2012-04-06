<?php
/**
* Yeni entry girme işlemini gerçekleştirir.
*
* 
*
* @version 0.72
*/
	include ('common.php');
	include ('funct.php');
	
	try {
		if ($MEMBER_LOGGED) {
			if (!empty($_POST["t"]) && !empty($_POST["ygirdi"])) {
				$link = getPDO();
				$baslik = strtolower($_POST["t"]);
				
				
				$entry = strtolower($_POST["ygirdi"]);
				$entry = preg_replace('/(?:\n\s*){2,}/', "\n\n", $entry);
				$entry = preg_replace('/^(?:\n\s*)*/','',$entry);
				$entry = preg_replace('/(?:\n\s*)*$/','',$entry);
				$entry = preg_replace('/^(?:\s\s*)*/','',$entry);
				$entry = preg_replace('/(?:\s\s*)*$/','',$entry);
				
				if (isset($_REQUEST['kaydet']))
					$aktif = 0;
				else
					$aktif = 1;
				//başlık var mı?
				$s = $link->prepare("SELECT T_ID FROM titles WHERE Baslik = :baslik");
				$s -> bindValue(":baslik",$baslik);
				$s -> execute();
				//yoksa ekle
				if (!$s->rowCount()) {
					$baslik = preg_replace('/\s\s+/',' ',$baslik);
					$baslik = preg_replace('/\t/',' ',$baslik);
					$baslik = preg_replace('/[^a-z0-9üçöğşı\'#$\.\-\+= ]/', '', $baslik);
					$se = $link -> prepare("INSERT INTO titles (Baslik,Entry_Count,Tarih) VALUES (:baslik,0,NOW())");
					$se -> bindValue(":baslik",$baslik);
					$se -> execute();
				}
				//id'yi al
				$s -> execute();
				$sonuc = $s -> fetch(PDO::FETCH_ASSOC);
				$baslikid = $sonuc['T_ID'];
				$e = $link -> prepare("INSERT INTO entries (T_ID,U_ID,Girdi,Tarih,Aktif) VALUES (:bid,:uid,:girdi,NOW(),:aktif)");
				$e -> bindValue(":bid",$baslikid);
				$e -> bindValue(":uid",$_SESSION['member']->U_ID);
				$e -> bindValue(":girdi",$entry);
				$e -> bindValue(":aktif",$aktif);
									
				if ($e -> execute()) {
					$ba = $link -> prepare("UPDATE titles SET Entry_Count = Entry_Count + 1, Tarih = NOW() WHERE T_ID = :tid");
					$ba -> bindValue(":tid",$baslikid);
					$ba -> execute();
					$ue = $link -> prepare("UPDATE memberinfo SET Post_Count = Post_Count + 1 WHERE U_ID = :uid");
					$ue -> bindValue(":uid",$_SESSION['member']->U_ID);
					$ue -> execute();
					if ($aktif == 1)
						header("Location: goster.php?t=".yazarBoslukSil($baslik));
					else
						header("Location: index.php"); //daha sonra kenar.php'de entry'ye gidicek.
				}
				else {
						echo "Hata oluştu, lütfen tekrar deneyin";
				}
			}		
		}
		else
			header("Location: index.php"); //nothing to see here.
	}
	catch (PDOException $e) {
		echo "Hata oluştu lütfen tekrar deneyin";
	}
?>
