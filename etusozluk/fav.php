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
	<script type="text/javascript">var sn=15;function cd(){ document.title = "kapanıyor: "+(sn--); if(sn>=0) setTimeout(cd,1000); else window.close();}</script>
    <body onload="setTimeout(cd,1000)"><div style="text-align:center">
<?php
	if ($MEMBER_LOGGED && !empty($_REQUEST['e']) && is_numeric($_REQUEST['e'])) {
		$eid = $_REQUEST['e'];
		$gerial = false;
		if (!empty($_REQUEST['op']) && $_REQUEST['op']=="g")
			$gerial = true;
			
		$link = getPDO();
		//entry'yi kontrol et ve varsa u_id'yi çek
		$st = $link -> prepare("SELECT U_ID FROM entries WHERE E_ID = :eid");
		$st -> bindValue(':eid',$eid);
		$st -> execute();
		if (!$st->rowCount())
			echo "Böyle bir entry yok?";
		else {
			$memuid = $st->fetch(PDO::FETCH_ASSOC);
			if ($memuid['U_ID'] == $_SESSION['member']->U_ID) {
				echo "bu kadan egoist olunmaz.";
			} else {
			if ($gerial) {
				$komut = "DELETE FROM faventries WHERE U_ID = :uid and E_ID = :eid LIMIT 1";
			}
			else {
				$komut = "INSERT INTO faventries VALUES(:uid,:eid)";
			}
		
			$sd = $link -> prepare("SELECT * FROM faventries WHERE U_ID = :uid AND E_ID = :eid");
			$sd -> bindValue(":uid",$_SESSION['member']->U_ID);
			$sd -> bindValue(":eid",$eid);
			$sd -> execute();
			if ($sd->rowCount()>0 && !$gerial) {
				echo "daha önceden favori etmişsiniz. <br />";
				echo '<a href="fav.php?e='.$eid.'&op=g">geri al?</a>';
			}
			else {
				$se = $link -> prepare($komut);
				$se -> bindValue(":eid",$eid);
				$se -> bindValue(":uid",$_SESSION['member']->U_ID);
				if ($se -> execute()) {
					if (!$gerial) {
						echo "favorilerinize eklendi <br />";
						echo '<a href="fav.php?e='.$eid.'&op=g">geri al?</a>';
					}
					else
						echo "tamam geçti korkma";		
				}
			}
		}
	}
}
	else
		echo "Yakışmadı bu.";
	echo '<br /><button type="button" onClick="window.close()">kapat valla üşüdüm</button>';
	echo '<p style="font-size:8pt">Sayfa 15 saniye içinde kapanıcak.</p>';
?>
</div>
</body>
</html>