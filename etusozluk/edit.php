<?php
	include_once('common.php');
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="tr-tr" lang="tr-tr" dir="ltr" >
<head>
<?php
	if ($MEMBER_LOGGED && !empty($_REQUEST['e']) && is_numeric($_REQUEST['e'])) {
		$e = $_REQUEST['e'];
		$link = getPDO();
		
		if (!empty($_POST['duzenle']) && !empty($_POST['ygirdi'])) {
			
			$entry = strtolower($_POST['ygirdi']);
			$entry = preg_replace('/(?:\n\s*){2,}/', "\n\n", $entry);
			$entry = preg_replace('/^(?:\n\s*)*/','',$entry);
			$entry = preg_replace('/(?:\n\s*)*$/','',$entry);
			$entry = preg_replace('/^(?:\s\s*)*/','',$entry);
			$entry = preg_replace('/(?:\s\s*)*$/','',$entry);
			
			//daha sonra kimin update ettiği eklenicek.
			
			$ud = $link -> prepare("UPDATE entries SET Girdi = :girdi, Duzenleme = NOW() WHERE E_ID = :eid");
			$ud -> bindValue(":girdi",$entry);
			$ud -> bindValue(":eid",$e);
			if ($ud -> execute()) {
			?>
		<title>ETÜ Sözlük</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link type="text/css" href="style/style.css" rel="stylesheet" />
	</head><script type="text/javascript">var sn=10;function cd(){ document.title = "kapanıyor: "+(sn--); if(sn>=0) setTimeout(cd,1000); else window.close();}</script>
    <body onload="setTimeout(cd,1000)"><div style="text-align:center"><br />
			<?php
				echo "şahane oldu.";
				echo '<br /><br /><button type="button" onClick="window.close()">tamamdır</button>';
			}
		}
		else {
		?>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />	
		<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
		<script type="text/javascript" src="js/pae.js"></script>
		<link type="text/css" href="style/style.css" rel="stylesheet" />
	</head>
	<body><div style="text-align:center"><br />
		<?php
			$st = $link -> prepare("SELECT entries.Girdi,entries.U_ID,titles.Baslik FROM entries NATURAL JOIN titles WHERE entries.E_ID = :eid");
			$st -> bindValue(":eid",$e);
			$st -> execute();
			if (!$st->rowCount()) {
				echo "kardeş kardeşe yapmaz bunu.";
			}
			else {
				$girdi = $st -> fetch(PDO::FETCH_ASSOC);
				if ($_SESSION['member']->U_ID == $girdi['U_ID'] || $_SESSION['membil']->Yetki > 5) { //5 ve üstü de düzenleyebilir. 5 değişebilir.
					if ($_SESSION['member']->U_ID==$girdi['U_ID']) $ek = "nızı"; else $ek = "yı";
					echo '"'.$girdi['Baslik'].'" hakkındaki yazı'.$ek.' düzenliyorsunuz: <br /><div style="text-align:left; padding-top:10px; padding-left:25px;"><form action="edit.php?e='.$e.'" method="post" name="girdiduzen"><div id="butonlar" style="text-align:left; width:100%; padding-top:10px;"><input type="button" id="bkz" value="(bkz: )" class="ebut" /><input type="button" id="gizlibkz" value="``" class="ebut"/><input type="button" id="spoiler" value="spoiler" class="ebut"/><input type="button" value="link" onclick="var a=prompt(\'link: (başında http:// olmalı)\', \'http://\');if(isURL(a))$(\'#entrytextarea\').tae(\'url\',a);" class="ebut"/></div><textarea id="entrytextarea" rows="10" cols="105" class="ygirdi" name="ygirdi">'.$girdi['Girdi'].'</textarea><input type="submit" value="bu daha iyi" name="duzenle" class="ebut" /></form></div>';
					echo '<br /><br /><button type="button" onClick="window.close()">vazgeçtim böyle kalsın</button>';
				} 
			}
		}
	}
	else
		echo 'yakışmadı bu.';
?>
</div>
</body>
</html>