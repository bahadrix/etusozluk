<?php
/**
* Yeni entry girme işlemini gerçekleştirir.
* @version 0.51
*/
	include ('data/core/db.php');
	
	function girdiControl($girdi) {
		$ygirdi = $girdi;
		$ygirdi = preg_replace('/&/', '&amp;',$ygirdi);
		$ygirdi = preg_replace('/</', '&lt;',$ygirdi);
		$ygirdi = preg_replace('/>/', '&gt;',$ygirdi);
		$ygirdi = preg_replace('/"/', '&quot;',$ygirdi);
		$ygirdi = preg_replace('/\'/', '&#39;',$ygirdi);
		$ygirdi = preg_replace('/\t/',' ',$ygirdi);
		$ygirdi = nl2br($ygirdi);
		$ygirdi = preg_replace('/[<br \/>\s?]{2,}/','<br /><br />',$ygirdi);
		$ygirdi = preg_replace('/\s\s+/',' ',$ygirdi);
		$b = preg_match_all('/\(bkz:\s?([a-z0-9ıüçöğş\^!\'$#€£~*_%!&=?\/+ ]+)\)/', $ygirdi, $sonuc);
		if ($b) {
			$bkzs = $sonuc[0];
			for ($i=0;$i<$b;$i++) {
				$ygirdi = str_replace($bkzs[$i],'(bkz: <a href="goster.php?t='.rawurlencode(trim($sonuc[1][$i])).'">'.trim($sonuc[1][$i]).'</a>)',$ygirdi);
			}
		}
		$g = preg_match_all('/`\s?([a-z0-9ıüçöğş\^!\'$#~*_%!&=?\/ ]+)\s?`/', $ygirdi, $sonuc);
		if ($g) {
			$gbkzs = $sonuc[0];
			for ($i=0;$i<$g;$i++) {
				$ygirdi = str_replace($gbkzs[$i],'`<a href="goster.php?t='.rawurlencode(trim($sonuc[1][$i])).'">'.trim($sonuc[1][$i]).'</a>`',$ygirdi);
			}
		}
		//url kontrol, gözden geçirilmesi gerek
		$u = preg_match_all('/\[(https?|ftp):\/\/([a-z0-9\/?=%-_!^]+)\s?([a-z0-9ıüçöğş\^!\'$#~*_%!&=?\/ ]+)?\]/',$ygirdi,$sonuc);
		if ($u) {
			$urls = $sonuc[0];
			for ($i=0;$i<$u;$i++) {
				$url = trim($sonuc[1][$i].'://'.$sonuc[2][$i]);
				if (!$sonuc[3][$i]=='') 
					$kelime = trim($sonuc[3][$i]);
				else
					$kelime = substr(trim($url),0,25).'...';
				if (filter_var($url,FILTER_VALIDATE_URL)) 
					$ygirdi = str_replace($urls[$i], '<a href="'.$url.'" target="_blank">'.$kelime.'</a>',$ygirdi);
				else
					$ygirdi = str_replace($urls[$i],"[$url $kelime]",$ygirdi);
			}
		}
		//spoiler kontrolü daha sonra, sıktı regexp şu an :) 
		return $ygirdi;
	}
	
	try {
		/*Login kontrol buraya*/
			if (!empty($_POST["t"]) && !empty($_POST["ygirdi"])) {
				$link = getPDO();
				$baslik = strtolower($_POST["t"]);
				$baslik = preg_replace('/\s\s+/',' ',$baslik);
				$baslik = preg_replace('/\t/',' ',$baslik);
				$baslik = preg_replace('/[^a-z0-9üçöğşı\'#$\.\-\+= ]/', '', $baslik);
				$entry = girdiControl(strtolower($_POST["ygirdi"]));			
				
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
				$s -> bindValue(":baslik",$baslik); //gereksiz olabilir.
				$s -> execute();
				$sonuc = $s -> fetch(PDO::FETCH_ASSOC);
				$baslikid = $sonuc['T_ID'];
				$e = $link -> prepare("INSERT INTO entries (T_ID,U_ID,Girdi,Tarih,Aktif) VALUES (:bid,:uid,:girdi,NOW(),:aktif)");
				$e -> bindValue(":bid",$baslikid);
				$e -> bindValue(":uid",1); //Uye ID Session'dan alınıcak
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
						echo "Hata oluştu, <br />";
						$arr = $e->errorInfo();
						print_r($arr);
				}
			}
		
	}
	catch (PDOException $e) {
		echo "Hata oluştu lütfen tekrar deneyin";
	}
?>
