<?php
	include_once('common.php');
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="tr-tr" lang="tr-tr" dir="ltr" >
<head>
		<title>ETÜ Sözlük</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />	
		<link type="text/css" href="style/style.css" rel="stylesheet" />
	</head>
<?php
	if ($MEMBER_LOGGED && !empty($_REQUEST['e']) && is_numeric($_REQUEST['e'])) {
		$eid = $_REQUEST['e'];
		
		$link = getPDO();
		$st = $link -> prepare("SELECT T_ID,U_ID FROM entries WHERE E_ID = :eid");
		$st -> bindValue(':eid',$eid);
		$st -> execute();
		if (!$st->rowCount())
			echo "Böyle bir entry yok?";
		else {
			$ebilgi = $st -> fetch(PDO::FETCH_ASSOC);
			$bilgi = $link -> query("SELECT Yetki FROM memberinfo WHERE U_ID=".$ebilgi['U_ID'])->fetch(PDO::FETCH_ASSOC); //yetki bilgisini al
			if ($_SESSION['membil']->Yetki<$bilgi['Yetki']) {
				echo "sizden yetkili bir abinin girdisini silmeye çalışıyorsunuz.";
			}
			else {
			if (($ebilgi['U_ID']==$_SESSION['member']->U_ID || ($_SESSION['membil']->Yetki > 5 && $_SESSION['membil']->Yetki > $bilgi['Yetki'])) && empty($_POST['sil'])) {
			?>
			<body><div style="text-align:center"><p>entry silme aparatı: silinen entry <a href="goster.php?e=<?php echo $eid; ?>" target=_blank>bu</a>.</p>
				<form action="del.php?e=<?php echo $eid; ?>" method="post"><input type="submit" onClick="return confirm('entry siliniyor?')" name="sil" value="geri dönüşü olmayan bir yola giriyoruz?"/>&nbsp;&nbsp;<input type="button" onClick="window.close()" value="yokum diyorum" /></form>
			<?php
			} else if (($ebilgi['U_ID']==$_SESSION['member']->U_ID || ($_SESSION['membil']->Yetki > 5 && $_SESSION['membil']->Yetki > $bilgi['Yetki'])) && !empty($_POST['sil'])) {
				$durum = false; //entry direkt silinsin mi yoksa çöp'e mi gönderilsin 0=çöp-1=direkt
				
				if ($ebilgi['U_ID']!=$_SESSION['member']->U_ID) {
					$durum = false; //yetkili abi çöpe atsın
				}
				else {
					$durum = $_SESSION['membil']->Sil; //seçeneği session'dan al
				}
				//entry'yi sil
				if ($durum) {
					$sd = $link -> prepare("DELETE FROM entries WHERE E_ID=:eid LIMIT 1");
					$sd -> bindValue(":eid",$eid);
				}
				else {
					$sd = $link -> prepare("UPDATE entries SET Thrash = 1 WHERE E_ID = :eid");
					$sd -> bindValue(":eid",$eid);
				}
				if ($sd -> execute()) {				
				//başlıktaki entry_count'ı azalt ve Tarih'i bir önceki entry'nin tarihine çek
					$ged = $link -> prepare("SELECT Tarih FROM entries WHERE T_ID=:tid AND Aktif=1 AND Thrash=0 ORDER BY Tarih DESC");
					$ged -> bindValue(":tid",$ebilgi['T_ID']);
					$ged -> execute();
					$bsil = true;
					$bioncekitarih = array();
					if ($ged->rowCount()) { //silinen entry ilk entry olabilir.--aktif=0 olan entry varsa onlar ne olacak?
						$bsil = false;
						$bioncekitarih = $ged->fetch(PDO::FETCH_ASSOC); //ilk veriyi çek
					}
					if ($bsil===true) {
						$sb = $link -> prepare("DELETE FROM titles WHERE T_ID = :tid LIMIT 1");
						$sb -> bindValue(":tid",$ebilgi['T_ID']);
					}
					else {
						$sb = $link -> prepare("UPDATE titles SET Entry_Count = Entry_Count-1, Tarih = :tarih WHERE T_ID = :tid");
						$sb -> bindValue(":tid",$ebilgi['T_ID']);
						$sb -> bindValue(":tarih",$bioncekitarih['Tarih']);
					}
					if($sb -> execute()) {
						if ($durum) 
							$mesaj = "entry silindi.";
						else 
							$mesaj = "çöp kutusuna taşındı.";
					}
					else {
						$mesaj = "hata oluştu";
					}
				}
				else
					$mesaj = "hata oluştu";
				?>
				<script type="text/javascript">var sn=10;function cd(){ document.title = "kapanıyor: "+(sn--); if(sn>=0) setTimeout(cd,1000); else window.close();}</script>
				<body onload="setTimeout(cd,1000)"><div style="text-align:center">
				<?php
					echo $mesaj;
					echo '<br /><button type="button" onClick="window.close()">kapat valla üşüdüm</button>';
					echo '<p style="font-size:8pt">bu pencere 10 saniye içinde kapanıcak.</p>';
			}
			}
		}			
	}
	else
		echo "yakışmadı bu.";
?>
</div>
</body>
</html>