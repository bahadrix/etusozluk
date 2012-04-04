<?php 
include 'common.php';
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
			<p class="lgbaslik"><?php echo $_SESSION['member']->Nick; ?></p><hr class="lg"/><p style="text-align:left; padding-left:50px; margin:0;"><a href="hq.php">HQ</a><br /><a href="mesaj.php">Mesajlar</a><br /><a href="getir.php?mode=ark">Arkadaşlar</a><br /><a href="getir.php?mode=kenar">Kenarda Duranlar</a><br /><a href="getir.php?mode=yeni">Yeni</a><br /><a href="login.php?mode=cikis">Çıkış</a></p>
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
		<div id="header"><form name="baslikara" action="ara.php?op=baslikara"><input type="text" value="Başlık Getir" id="titlea" name="title" size="30" maxlength="70" />&nbsp;<input type="submit" value="ara" /></form></div>
		<div id="main">
			<table cellspacing=0 cellpadding=0>
				<tr>
					<td valign=top width=800 style="border-right:1px dotted #666;">
						<div id="mainleft"><div id="entries">
						<?php
						$link = getPDO();
						//random entry çek
						$se = $link -> query("SELECT * FROM entries WHERE Aktif = 1 AND Thrash = 0 ORDER BY RAND() LIMIT 1");
						$var = true;
						if (!$se->rowCount()) {
							echo "Yuh yazmadınız mı daha.";
							$var = false;
						}
						else { 
						$rentry = $se->fetch(PDO::FETCH_ASSOC);
						
						//başlık adını çek
						$st = $link -> query("SELECT Baslik FROM titles WHERE T_ID = ".$rentry['T_ID']);
						$b = $st -> fetch(PDO::FETCH_ASSOC);
						$baslikadi = $b["Baslik"];
						
						//kullanıcı adını çek
						$sy = $link -> query("SELECT Nick FROM members WHERE U_ID = ".$rentry['U_ID']);
						$y = $sy -> fetch(PDO::FETCH_ASSOC);
						$yazar = $y['Nick'];
						
						//düzenleme varsa eklemek için
						$duzen = $rentry['Duzenleme'];
						if (!$duzen)
							$duzenleme = "";
						else {
							if (substr($duzen,0,10)==substr($rentry['Tarih'],0,10))
								$duzenleme = " ~ ".substr($duzen,11,5);
							else
								$duzenleme = " ~ ".substr($duzen,0,16);
						}
						
						//o başlıktaki kaçıncı girdi
						$sk = $link -> query ("SELECT COUNT(E_ID) as listnumber FROM entries WHERE T_ID=".$rentry['T_ID']." AND E_ID BETWEEN 1 AND ".$rentry['E_ID']." ORDER BY Tarih");
						$s = $sk -> fetch(PDO::FETCH_ASSOC);
						$sayi = $s['listnumber'];
						echo '<input type="hidden" value="'.$baslikadi.'" id="baslikd" />'; //bu satır index.php için
						//görünüm
						echo '<h3 style="text-align:left; margin-left:40px;">'.$baslikadi.'</h3><ol class=girdiler><li class=girdi value="'.$sayi.'">';
						echo girdiControl($rentry['Girdi']);
						echo '<div class="yazarinfo">(<a href="goster.php?t='.yazarBoslukSil($yazar).'" id="yazar" rel="'.$rentry['U_ID'].'">'.$yazar.'</a>, '.substr($rentry['Tarih'],0,16).''.$duzenleme.')</div><div class="ymore"><a href="goster.php?e='.$rentry['E_ID'].'" id="entryid">#'.$rentry['E_ID'].'</a>';
						//butonlar
						if ($MEMBER_LOGGED) {
							echo '&nbsp;<button type="button" onClick="ep(\'vote.php?id='.$rentry['E_ID'].'&o=1\',\'400\',\'400\')" class="minib" title="olmuş bu" id="+1">iyuf</button>&nbsp;<button type="button" onClick="ep(\'vote.php?id='.$rentry['E_ID'].'&o=-1\',\'400\',\'400\')" class="minib" title="böyle olmaz hacı" id="-1">ı ıh</button>';
							if ($_SESSION['member']->U_ID == $rentry['U_ID']) //yazarın gördüğü
								echo '&nbsp;<button type="button" onClick="ep(\'edit.php?e='.$rentry['E_ID'].'\',\'820\',\'400\')" class="minib" id="eduz">düzelt</button>&nbsp;<button type="button" onClick="ep(\'del.php?e='.$rentry['E_ID'].'\')" class="minib" title="sil" id="esil">X</button>';
							else if ($_SESSION['membil']->Yetki>5) { //kendi yazmadığı entry ise moderasyonun gördüğü -> ilerki zamanlarda taşı da eklenebilir.
								echo '&nbsp;<button type="button" onClick="ep(\'edit.php?e='.$rentry['E_ID'].'\',\'820\',\'400\')" class="minib" id="eduz">düzelt</button>&nbsp;<button type="button" onClick="ep(\'del.php?e='.$rentry['E_ID'].'\')" class="minib" title="sil" id="esil">X</button>';
								echo '&nbsp;<button type="button" onClick="ep(\'fav.php?e='.$rentry['E_ID'].'\')" class="minib" title="favorilere ekle" id="efav">:D</button>&nbsp;<button type="button" onClick="ep(\'mesaj.php?y='.yazarBoslukSil($yazar).'\',\'600\',\'400\')" class="minib" title="yazara mesaj atiyim" id="eymesaj">msj</button>';
							} else //diğer üyelerin gördüğü
								echo '&nbsp;<button type="button" onClick="ep(\'fav.php?e='.$rentry['E_ID'].'\')" class="minib" title="favorilere ekle" id="efav">:D</button>&nbsp;<button type="button" onClick="ep(\'mesaj.php?y='.yazarBoslukSil($yazar).'\',\'600\',\'400\')" class="minib" title="yazara mesaj atiyim" id="eymesaj">msj</button>';
							}
						//herkesin gördüğü
						echo '&nbsp;<button type="button" onClick="location.href=\'yazar.php?y='.yazarBoslukSil($yazar).'\'" id="eyh" class="minib" title="yazar hakkında">?</button>&nbsp;<button type="button" onClick="location.href=\'sikayet.php?e='.$rentry['E_ID'].'\'" id="esb" class="minib" title="şikayet et">!</button>';
						echo '&nbsp;</div><div id="yazarmini"></div>';
						echo '</li><br /></ol>';
						}
						?>
						<br /><?php if($var) { ?><div style="text-align:center;" id="hg"><button type="button" onClick="location.href='goster.php?t=<?php echo yazarBoslukSil($baslikadi); ?>'" id="ehg">Hepsi Gelsin</button>
						<?php if ($MEMBER_LOGGED) { ?>
						<div style="text-align:left; padding-top:10px; padding-left:25px;">"<?php echo $baslikadi; ?>" hakkında söylemek istediklerim var diyorsan durma:
						<form action="ekle.php" method="post" id="yenigirdi" name="yenigirdi"><input type="hidden" name="t" value="<?php echo $baslikadi; ?>" /><div id="butonlar" style="text-align:left; width:100%; padding-top:10px;"><input type="button" id="bkz" value="(bkz: )" class="ebut" /><input type="button" id="gizlibkz" value="``" class="ebut"/><input type="button" id="spoiler" value="spoiler" class="ebut"/><input type="button" value="link" onclick="var a=prompt('link: (başında http:// olmalı)', 'http://');if(isURL(a))$('#entrytextarea').tae('url',a);" class="ebut"/></div><textarea id="entrytextarea" rows="10" cols="105" class="ygirdi" name="ygirdi"></textarea><input type="submit" value="böyle olur" class="ebut" /><input type="submit" value="bunu sonra gönderirim" class="ebut" name="kaydet" /></form></div><?php } } ?></div></div></div>
					</td>
					<td valign=top width=400>
						<div id="mainright"><div id="basliklar" style="text-align:left;"></div><input type="hidden" name="page_count" id="page_count" value="1" /></div>
					</td>
				</tr>
			</table>
		</div>
		<div class="aramenupanel"><h3 style="text-align:left;">Ara Bebeğim Aramalara Doyma</h3><form action="ara.php?op=arama" method="post" name="arama">
				<table style="width:270px; text-align:center;">
					<tr>
					<td style="text-align:left; width:80px;">Ne:</td>
					<td style="text-align:left; width:190px;"><input type="text" name="ne" maxlength="50" value="" class="ara"/></td>
					</tr>
					<tr>
					<td style="text-align:left; width:80px;">Kim:</td>
					<td style="text-align:left; width:190px;"><input type="text" name="kim" maxlength="50" value="" class="ara"/></td>
					</tr>
					<tr>
					<td style="text-align:left; width:80px;">Ne Zaman:</td>
					<td style="text-align:left; width:190px;"><select name="sugun" class="sayfa"><option></option><option>1</option><option>2</option><option>3</option><option>4</option><option>5</option><option>6</option><option>7</option><option>8</option><option>9</option><option>10</option><option>11</option><option>12</option><option>13</option><option>14</option><option>15</option><option>16</option><option>17</option><option>18</option><option>19</option><option>20</option><option>21</option><option>22</option><option>23</option><option>24</option><option>25</option><option>26</option><option>27</option><option>28</option><option>29</option><option>30</option><option>31</option></select><select name="suay" class="sayfa"><option></option><option>1</option><option>2</option><option>3</option><option>4</option><option>5</option><option>6</option><option>7</option><option>8</option><option>9</option><option>10</option><option>11</option><option>12</option></select><select name="suyil" class="sayfa"><option></option><option>2012</option></select></td>
					</tr>
					<tr>
					<td style="text-align:left; font-weight:bold; color:#fecc00; font-size:10pt; width:80px;">Nasıl?</td>
					<td style="margin-left:0; width:190px;">&nbsp;</td>
					</tr>
					<tr>
					<td style="text-align:left; width:80px;"><label><input type="radio" name="order" value="abc" checked="checked" />&nbsp;abcd</label></td><td style="text-align:left; width:190px;"><label><input type="radio" name="order" value="yenieski" />&nbsp;yeni-eski</label></td>
					</tr>
					<tr>
					<td style="text-align:left; width:80px;"><label><input type="radio" name="order" value="say" />&nbsp;baştan say</label></td><td style="text-align:left; width:190px;"><label><input type="radio" name="order" value="random" />&nbsp;randım</label></td>
					</tr>
					<tr>
					<td style="text-align:left; width:80px;"><input type="submit" name="ara" value="Ara" class="login" /></td>
					<td style="text-align:left; width:190px;">&nbsp;</td>
					</tr>
			</table></form>
		</div>
		<div class="sikayetmenupanel"><h3 style="text-align:left;">Araman İçin Hata mı Yapmam Gerek</h3><br /><form action="sikayet.php" method="post" name="skyt">
				<table style="width:270px; text-align:center;">
					<tr>
					<td style="text-align:left;">Email:</td>
					</tr>
					<tr>
					<td style="text-align:left;"><input type="text" name="email" maxlength="50" value="" class="sikayet"/></td>
					</tr>
					<tr>
					<td style="text-align:left;">Konu:</td>
					</tr>
					<tr>
					<td style="text-align:left;"><input type="text" name="konu" maxlength="50" value="" class="sikayet"/></td>
					</tr>
					<tr>
					<td style="text-align:left;">İstek-Şikayet: </td>
					</tr>
					<tr>
					<td style="margin-left:0;"><textarea id="txt1" name="sikayet" class="sikayet"></textarea></td>
					</tr>
					<tr>
					<td style="text-align:center;"><input type="submit" name="gonder" value="Gönder" class="login"></td>
					</tr>
			</table></form><font style="font-size:10px; margin-top:0px;">* Hatasız kul olmaz.</font>
		</div>
		<a class="sikayetmenu" href="#">İstek</a>
		<a href="javascript:void(0);" id="top-link">Başa Zıpla</a>
		<?php include 'footer.php'; ?>
	</body>
</html>
