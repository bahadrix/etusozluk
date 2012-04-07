<?php 
include_once 'common.php';
include_once 'funct.php'; 
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="tr-tr" lang="tr-tr" dir="ltr" >
<head>
		<title>ETÜ Sözlük</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui-1.8.18.custom.min.js"></script>
		<script type="text/javascript" src="js/pae.js"></script>
		<link type="text/css" href="style/jquery-ui-1.8.18.custom.css" rel="stylesheet" />	
		<link type="text/css" href="style/style.css" rel="stylesheet" />
	</head>
	<body>
		<div id="logo"><a href="index.php" style="width: 200px; height: 90px; display: block;" title="Etü Sözlük"></a></div>
		<ul id="menu">
		<div id="loginbox">
				<?php if (!$MEMBER_LOGGED) { ?>
				<p class="lgbaslik">Giriş</p>
				<form action="login.php" method="post" name="loginform">
				<table style="width:270px; text-align:center; margin-left:5px;">
					<tr>
					<td style="text-align:left; padding-left: 7px; width:100px;">Kullanıcı Adı:</td>
					<td style="text-align:left; padding-left: 10px; width:100px;">Şifre:</td>
					<td>&nbsp;</td>
					</tr>
					<tr>
					<td style="padding-right: 2px;"><input type="text" name="username" size="15" maxlength="40" value="" class="login" /></td>
					<td style="padding-right: 2px;"><input type="password" name="password" size="15" maxlength="32" value="" class="login" /></td>
					<td style="text-align:left;"><input type="submit" name="login" value="Gir" class="submit" /></td>
					</tr>
			</table>
			<div style="text-align: center; padding-top: 5px;">
			<label><input type="checkbox" name="autologin" style="vertical-align: bottom;"/> Hatırla Sevgili</label>
			<hr class="lg"/>
			<a href="login.php?mode=unut">Neydi benim bilgiler?</a><br/>
			<a href="uyeol.php">Üye Ol!</a>
			</div></form>
			<?php } else { ?>
			<p class="lgbaslik"><?php echo $_SESSION['member']->Nick; ?></p><hr class="lg"/><p style="text-align:left; padding-left:50px; margin:0;"><a href="hq.php">HQ</a><br /><a href="mesaj.php">Mesajlar</a><br /><a href="getir.php?mode=ark">Arkadaşlar</a><br /><a href="getir.php?mode=kenar">Kenarda Duranlar</a><br /><a href="getir.php?mode=yeni">Yeni</a><br /><a href="login.php?logout">Çıkış</a></p>
			<?php } ?>
			</div>
			<li id="uyeol"><a href="javascript:void(0);"><span><?php if (!$MEMBER_LOGGED) { ?>Üye Ol/Giriş<?php } else { ?>Ben<?php } ?></span></a></li>
			<li id="bugun"><a href="javascript:void(0);"><span>Bugün</span></a><div id="gizlimenu">Bugün yazılanlar</div></li>
			<li id="dun"><a href="javascript:void(0);"><span>Dün</span></a><div id="gizlimenu">Dün yazılanlar</div></li>
			<li id="rastgele"><a href="javascript:void(0);"><span>Rastgele</span></a><div id="gizlimenu">Rastgele 50 başlık</div></li>
			<li id="hot"><a href="javascript:void(0);"><span>Hot</span></a><div id="gizlimenu">Sıcak sıcak yeni çıktı</div></li>
			<li id="iyuf"><a href="javascript:void(0);"><span>İyuf</span></a><div id="gizlimenu">Yok böyle girdi</div></li>
			<li id="da-ara"><a href="javascript:void(0);" class="aramenu"><span>Ara</span></a></li>
		</ul>
		<div id="header"><div id="baslikarama" style="position:relative;left:40%;width:280px;margin:0;padding:0;"><form name="baslikara" action="goster.php"><div id="basara"></div><input type="text" value="Başlık Getir" id="titlea" name="t" size="30" maxlength="70" style="width:215px;" /><input type="submit" value="ara" /></form></div></div>
		<div id="main">