<?php 
/**
* goster.php 
* entry veya başlık gösterme sayfası
* version v0.01
*/
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
		<div id="header"><form name="baslikara" action="ara.php?op=baslikara"><input type="text" value="Başlık Getir" id="titlea" name="title" size="30" maxlength="70" />&nbsp;<input type="submit" value="ara" /></form></div>
		<div id="main">
			<table cellspacing=0 cellpadding=0>
				<tr>
					<td valign=top width=800 style="border-right:1px dotted #666;">
						<div id="mainleft"><div id="entries">
						<?php
							$link = getPDO();
							$var = false; //başlığın tamamı gösterilmediği durumda true olucak.
							if (!empty($_REQUEST['t']) || !empty($_REQUEST['e'])) { //başlık veya entry istenmiş mi?
								if (!empty($_REQUEST['t']) && empty($_REQUEST['e'])) { //başlık istenmiş mi?
									$t = $_REQUEST['t'];
									//$t = preg_replace('/\+/g',' ',$t); //+'ları boşluğa çevir
									//$t = urldecode($t); //$_REQUEST ile çağrılanlar zaten urldecode ediliyormuş?
									
									$st = $link -> prepare("SELECT T_ID FROM titles WHERE Baslik = :baslik");
									$st -> bindValue(":baslik",$t);
									$st -> execute();
									if (!$st->rowCount()) {
										echo "<i>bütün uğraşlarımıza rağmen böyle bir başlık bulamadık.</i>";
										//benzer başlıkları göster eklenebilir
									} else {									
										$baslikid = $st -> fetch(PDO::FETCH_ASSOC);
										
										$te = strpos($t,'/'); //t=baslik/entrynumarası şeklinde gelmiş olabilir.
										if ($te !== false) {
											$entryno = preg_split('/\//',$t);
											$entryno = $entryno[1];
											if (isNumeric($entryno)) {
												$se = $link -> prepare("SELECT E_ID FROM entries WHERE E_ID=:eid AND T_ID=:tid");
												$se -> bindValue(":eid",$entryno);
												$se -> bindValue(":tid",$baslikid['T_ID']);
												$se -> execute();
												if ($se->rowCount())
													entryGoster($t,$entryno);
												else
													echo "<i>bütün uğraşlarımıza rağmen bu başlıkta böyle bir entry bulamadık.</i>";
											}
										}
										if (!empty($_REQUEST['p']) && is_numeric($_REQUEST['p']))
											$p = $_REQUEST['p'];
										else								
											$p = 1;
										
										$sayfabasinagirdi = !empty($_SESSION['membil']) ? $_SESSION['membil']->Entry_Per_Page : 25;
										$sayfa = ($p-1)*$sayfabasinagirdi;
										$ee = $link -> prepare("SELECT E_ID,U_ID,Girdi,Tarih,Duzenleme,n.Nick from entries NATURAL JOIN (SELECT U_ID,Nick FROM members) as n WHERE T_ID=:tid AND Aktif=1 AND Thrash=0 ORDER BY Tarih LIMIT $sayfa,$sayfabasinagirdi");
										$ee -> bindValue(":tid",$baslikid['T_ID']);
										$ee -> execute();
										if (!$ee->rowCount()) //sayfa numarası fazla girildiyse vb.
											echo "<i>yakışmadı bu.</i>";
										else {
											$girdiler = $ee -> fetchAll(PDO::FETCH_ASSOC);
											echo '<h3 style="text-align:left; margin-left:40px;">'.$t.'</h3><input type="hidden" value="'.$t.'" id="baslikd" /><ol class="girdiler">';
											//dök entryleri
											for ($i=0;$i<count($girdiler);$i++) {
												$duzen = $girdiler[$i]['Duzenleme'];
												if (!$duzen)
													$duzenleme = "";
												else {
													if (substr($duzen,0,10)==substr($girdiler[$i]['Tarih'],0,10))
														$duzenleme = " ~ ".substr($duzen,11,5);
													else
														$duzenleme = " ~ ".substr($duzen,0,16);
												}
												$no = $sayfa+$i+1;
												echo '<li class="girdi" value="'.$no.'">';
												echo girdiControl($girdiler[$i]['Girdi']);
												echo '<div class="yazarinfo">(<a href="goster.php?t='.yazarBoslukSil($girdiler[$i]['Nick']).'" id="yazar" rel="'.$girdiler[$i]['U_ID'].'">'.$girdiler[$i]['Nick'].'</a>, '.substr($girdiler[$i]['Tarih'],0,16).''.$duzenleme.')</div><div class="ymore"><a href="goster.php?e='.$girdiler[$i]['E_ID'].'" id="entryid">#'.$girdiler[$i]['E_ID'].'</a>';
												//butonlar
												if ($MEMBER_LOGGED) {
													if ($girdiler[$i]['U_ID'] != $_SESSION['member']->U_ID) //kendine oy vermesin
														echo '&nbsp;<button type="button" onClick="ep(\'vote.php?id='.$girdiler[$i]['E_ID'].'&o=1\')" class="minib" title="olmuş bu" id="+1">iyuf</button>&nbsp;<button type="button" onClick="ep(\'vote.php?id='.$girdiler[$i]['E_ID'].'&o=-1\')" class="minib" title="böyle olmaz hacı" id="-1">ı ıh</button>';
													if ($girdiler[$i]['U_ID'] == $_SESSION['member']->U_ID) //yazarın gördüğü
														echo '&nbsp;<button type="button" onClick="ep(\'edit.php?e='.$girdiler[$i]['E_ID'].'\',\'820\',\'400\')" class="minib" id="eduz">düzelt</button>&nbsp;<button type="button" onClick="ep(\'del.php?e='.$girdiler[$i]['E_ID'].'\')" class="minib" title="sil" id="esil">X</button>';
													else if ($_SESSION['membil']->Yetki>5) { //kendi yazmadığı entry ise moderasyonun gördüğü -> ilerki zamanlarda taşı da eklenebilir.
														echo '&nbsp;<button type="button" onClick="ep(\'edit.php?e='.$girdiler[$i]['E_ID'].'\',\'820\',\'400\')" class="minib" id="eduz">düzelt</button>&nbsp;<button type="button" onClick="ep(\'del.php?e='.$girdiler[$i]['E_ID'].'\')" class="minib" title="sil" id="esil">X</button>';
														echo '&nbsp;<button type="button" onClick="ep(\'fav.php?e='.$girdiler[$i]['E_ID'].'\')" class="minib" title="favorilere ekle" id="efav">:D</button>&nbsp;<button type="button" onClick="ep(\'mesaj.php?y='.yazarBoslukSil($girdiler[$i]['Nick']).'\',\'600\',\'400\')" class="minib" title="yazara mesaj atiyim" id="eymesaj">msj</button>';
													} else //diğer üyelerin gördüğü
														echo '&nbsp;<button type="button" onClick="ep(\'fav.php?e='.$girdiler[$i]['E_ID'].'\')" class="minib" title="favorilere ekle" id="efav">:D</button>&nbsp;<button type="button" onClick="ep(\'mesaj.php?y='.yazarBoslukSil($girdiler[$i]['Nick']).'\',\'600\',\'400\')" class="minib" title="yazara mesaj atiyim" id="eymesaj">msj</button>';
												}
												//herkesin gördüğü
												echo '&nbsp;<button type="button" onClick="location.href=\'$girdiler[$i][\'Nick\'].php?y='.yazarBoslukSil($girdiler[$i]['Nick']).'\'" id="eyh" class="minib" title="$girdiler[$i][\'Nick\'] hakkında">?</button>&nbsp;<button type="button" onClick="location.href=\'sikayet.php?e='.$girdiler[$i]['E_ID'].'\'" id="esb" class="minib" title="şikayet et">!</button>';
												echo '&nbsp;</div><div id="yazarmini"></div>';
												echo '</li><br />';
											}
											echo '<br /></ol>';
										}
									}
								}
							}
						?>	
						<br /><div style="text-align:center;" id="hg"><?php if($var) { ?><button type="button" onClick="location.href='goster.php?t=<?php echo yazarBoslukSil($baslikadi); ?>'" id="ehg">Hepsi Gelsin</button><?php } ?>
						<?php if ($MEMBER_LOGGED) { ?>
						<div style="text-align:left; padding-top:10px; padding-left:25px;">"<?php echo $t; ?>" hakkında söylemek istediklerim var diyorsan durma:
						<form action="ekle.php" method="post" id="yenigirdi" name="yenigirdi"><input type="hidden" name="t" value="<?php echo $t; ?>" /><div id="butonlar" style="text-align:left; width:100%; padding-top:10px;"><input type="button" id="bkz" value="(bkz: )" class="ebut" /><input type="button" id="gizlibkz" value="``" class="ebut"/><input type="button" id="spoiler" value="spoiler" class="ebut"/><input type="button" value="link" onclick="var a=prompt('link: (başında http:// olmalı)', 'http://');if(isURL(a))$('#entrytextarea').tae('url',a);" class="ebut"/></div><textarea id="entrytextarea" rows="10" cols="105" class="ygirdi" name="ygirdi"></textarea><input type="submit" value="böyle olur" class="ebut" /><input type="submit" value="bunu sonra gönderirim" class="ebut" name="kaydet" /></form></div><?php } ?></div></div>
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