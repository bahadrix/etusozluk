<?php
/**
* Yeni entry girme işlemini gerçekleştirir.
*
* @girdiControl fonksiyonu funct.php'ye taşındı. 
*
* @version 0.7
*/
	include ('common.php');
	
	try {
		if ($MEMBER_LOGGED) {
			if (!empty($_POST["t"]) && !empty($_POST["ygirdi"])) {
				$link = getPDO();
				$baslik = strtolower($_POST["t"]);
				$baslik = preg_replace('/\s\s+/',' ',$baslik);
				$baslik = preg_replace('/\t/',' ',$baslik);
				$baslik = preg_replace('/[^a-z0-9üçöğşı\'#$\.\-\+=@ ]/', '', $baslik);
				$entry = strtolower($_POST["ygirdi"]);
				
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
					$se = $link -> prepare("INSERT INTO titles (Baslik,Entry_Count) VALUES (:baslik,:post)");
					$se -> bindValue(":baslik",$baslik);
					$se -> bindValue(":post",0);
					$se -> execute();
				}
				//id'yi al
				$s -> execute();
				$sonuc = $s -> fetch(PDO::FETCH_ASSOC);
				$baslikid = $sonuc['T_ID'];
				$e = $link -> prepare("INSERT INTO entries (T_ID,U_ID,Girdi,Tarih,Aktif) VALUES (:bid,:uid,:girdi,NOW(),:aktif)");
				$e -> bindValue(":bid",$baslikid);
				$e -> bindValue(":uid",$_SESSION['member']->U_ID); //Uye ID Session'dan alınıcak
				$e -> bindValue(":girdi",$entry);
				$e -> bindValue(":aktif",$aktif);
									
				if ($e -> execute()) {
					$ba = $link -> prepare("UPDATE titles SET Entry_Count = Entry_Count+1 WHERE T_ID = :tid");
					$ba -> bindValue(":tid",$baslikid);
					$ba -> execute();
					if ($aktif == 1)
						header("Location: goster.php?t=".rawurlencode($baslik));
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
