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
	<script type="text/javascript">var sn=10;function cd(){ document.title = "kapanıyor: "+(sn--); if(sn>=0) setTimeout(cd,1000); else window.close();}</script>
    <body onload="setTimeout(cd,1000)"><div style="text-align:center">
<?php
	if ($MEMBER_LOGGED && !empty($_REQUEST['id']) && is_numeric($_REQUEST['id']) &&!empty($_REQUEST['o'])) {
		$eid = $_REQUEST['id'];
		$gerial = false;
		if (!empty($_REQUEST['op']) && $_REQUEST['op']=="g")
			$gerial = true;
		$link = getPDO();
		$st = $link -> prepare("SELECT E_ID FROM entries WHERE E_ID = :eid");
		$st -> bindValue(':eid',$eid);
		$st -> execute();
		if (!$st->rowCount())
			echo "Böyle bir entry yok?";
		else {
			$oy = $_REQUEST['o'];
			
			if (!is_numeric($oy))
				$oy = -1;
			if ($oy<=0) {
				$oy = -1;
				if ($gerial) {
					$komut = "Dislikes = Dislikes -1";
				} else
				$komut = "Dislikes = Dislikes + 1";
			}
			else {
				$oy = 1;
				if ($gerial) {
					$komut = "Likes = Likes - 1";
				} else
				$komut = "Likes = Likes + 1";
			}
		
			$sd = $link -> prepare("SELECT * FROM memberlikes WHERE U_ID = :uid AND E_ID = :eid");
			$sd -> bindValue(":uid",$_SESSION['member']->U_ID);
			$sd -> bindValue(":eid",$eid);
			$sd -> execute();
			if ($sd->rowCount()>0 && !$gerial) {
				echo "daha önce oy vermişsiniz";
			}
			else {
				$se = $link -> prepare("UPDATE entries SET $komut WHERE E_ID = :eid");
				$se -> bindValue(":eid",$eid);
				if ($gerial) {
					$su = $link -> prepare('DELETE FROM memberlikes WHERE U_ID=:uid AND E_ID=:eid');
					$su -> bindValue(":uid",$_SESSION['member']->U_ID);
					$su -> bindValue(":eid",$eid);
				}
				else {
					$su = $link -> prepare('INSERT INTO memberlikes (U_ID,E_ID,BEGEN,Tarih) VALUES (:uid,:eid,:begen,NOW())');
					$su -> bindValue(":uid",$_SESSION['member']->U_ID);
					$su -> bindValue(":eid",$eid);
					$su -> bindValue(":begen",$oy);
				}
				if ($se -> execute()) {
					if($su -> execute()) {
						if (!$gerial) {
							echo "Oyunuz kaydedildi <br />";
							echo '<a href="vote.php?id='.$eid.'&o='.$oy.'&op=g">geri al?<a>';
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
?>
</div>
</body>
</html>