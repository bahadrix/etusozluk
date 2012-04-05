<?php 
/**
* goster.php 
* entry veya başlık gösterme sayfası
* @version v0.5
*/
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
								
								if (!empty($_REQUEST['p']) && is_numeric($_REQUEST['p']))
									$p = $_REQUEST['p'];
								else								
									$p = 1;
										
								$baslikentry = preg_split('/\//',$t);
								$st = $link -> prepare("SELECT T_ID FROM titles WHERE Baslik = :baslik");
								$st -> bindValue(":baslik",$baslikentry[0]);
								$st -> execute();
																	
								if (!$st->rowCount()) {
									echo "<i>bütün uğraşlarımıza rağmen böyle bir başlık bulamadık.</i>";
									//benzer başlıkları göster eklenebilir
								} else {
									$baslikid = $st -> fetch(PDO::FETCH_ASSOC);
									
									$te = strpos($t,'/'); //t=baslik/bilgi şeklinde gelmiş olabilir.
									if ($te !== false) {										
										if (is_numeric($baslikentry[1])) { //t=baslik/entrynumarası biçimi
											$entryno = $baslikentry[1];
											$se = $link -> prepare("SELECT E_ID FROM entries WHERE E_ID=:eid AND T_ID=:tid");
											$se -> bindValue(":eid",$entryno);
											$se -> bindValue(":tid",$baslikid['T_ID']);
											$se -> execute();
											if ($se->rowCount()) {
												$var = true;
												entryGoster($entryno,$baslikentry[0]);
											}
											else
												echo "<i>bütün uğraşlarımıza rağmen bu başlıkta böyle bir entry bulamadık.</i>";
										}
										else if (substr($baslikentry[1],0,1)==="@") { //@ sonrası kullanıcı adı olucak. t=baslik/@nick biçimi
											$nick = substr($baslikentry[1],1);
											$sn = $link -> prepare("SELECT Nick FROM members WHERE Nick = :nick");
											$sn -> bindValue(":nick",$nick);
											$sn -> execute();
											if (!$sn->rowCount()) {
												echo "böyle bir kullanıcı yok.";
											}
											else { 
												if (!empty($_REQUEST['g'])) { //kullanıcı adı ve gün bilgisi varsa
													$g = $_REQUEST['g'];
													if ($g=="bg") {
														$gun = "bugun";
													} else if ($g=="d")  {
														$gun = "dun";
													} else if (strlen($g)==8 && is_numeric($g) && checkdate(substr($g,0,2),substr($g,2,2),substr($g,4))) {
														$gun = substr($g,4)."-".substr($g,2,2)."-".substr($g,0,2); //yıl-ay-gün
													} else //default
														$gun = "bugun";
													$var = true;
													entryGoster(null,$baslikentry[0],$nick,$gun,$p);
												}
												else { //sadece kullanıcı adı bilgisi varsa
													$var = true;
													entryGoster(null,$baslikentry[0],$nick,null,$p);
												}
											}
										}
									}						
									else if (!empty($_REQUEST['g'])) { //t=baslik&g=gun biçimi
										$g = $_REQUEST['g'];
										if ($g=="bg") {
											$gun = "bugun";
										} else if ($g=="d")  {
											$gun = "dun";
										} else if (strlen($g)==8 && is_numeric($g) && checkdate(substr($g,0,2),substr($g,2,2),substr($g,4))) {
											$gun = substr($g,4)."-".substr($g,2,2)."-".substr($g,0,2); //yıl-ay-gün
										} else //default
											$gun = "bugun";
										$var = true;
										entryGoster(null,$baslikentry[0],null,$gun);
									}
									else { //t=baslik 
										
										$sayfabasinagirdi = !empty($_SESSION['membil']) ? $_SESSION['membil']->Entry_Per_Page : 25;
										$sayfa = ($p-1)*$sayfabasinagirdi;
										$ee = $link -> prepare("SELECT E_ID,U_ID,Girdi,Tarih,Duzenleme,n.Nick from entries NATURAL JOIN (SELECT U_ID,Nick FROM members) as n WHERE T_ID=:tid AND Aktif=1 AND Thrash=0 ORDER BY Tarih LIMIT $sayfa,$sayfabasinagirdi");
										$ee -> bindValue(":tid",$baslikid['T_ID']);
										$ee -> execute();
										if (!$ee->rowCount()) //sayfa numarası fazla girildiyse vb.
											echo "<i>aradık taradık bi şey bulamadık.</i>";
										else {
											$girdiler = $ee -> fetchAll(PDO::FETCH_ASSOC);
											echo '<h3 style="text-align:left; margin-left:40px;">'.$baslikentry[0].'</h3><input type="hidden" value="'.$baslikentry[0].'" id="baslikd" /><ol class="girdiler">';
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
												echo '&nbsp;<button type="button" onClick="location.href=\'yazar.php?y='.yazarBoslukSil($girdiler[$i]['Nick']).'\'" id="eyh" class="minib" title="yazar hakkında">?</button>&nbsp;<button type="button" onClick="location.href=\'sikayet.php?e='.$girdiler[$i]['E_ID'].'\'" id="esb" class="minib" title="şikayet et">!</button>';
												echo '&nbsp;</div><div id="yazarmini"></div>';
												echo '</li><br />';
											}
											echo '<br /></ol>';
										}
									}
								}
							}
						}								
						else if (empty($_REQUEST['t']) && !empty($_REQUEST['e']) && is_numeric($_REQUEST['e'])) { //entry istenmiş mi?
							$entryno = $_REQUEST['e'];
							$se = $link -> prepare("SELECT E_ID FROM entries WHERE E_ID=:eid");
							$se -> bindValue(":eid",$entryno);
							$se -> execute();
							if ($se->rowCount()) {
								$var = true;
								entryGoster($entryno);
							}
							else
								echo "<i>bütün uğraşlarımıza rağmen böyle bir entry bulamadık.</i>";
						}
						else { //ne t ne e varsa direkt T_ID=1 olan başlığı göster.
							$baslikentry[0]="etüsözlük";
							entryGoster();
						}
						?>	
						<br /><div style="text-align:center;" id="hg"><?php if($var) { ?><button type="button" onClick="location.href='goster.php?t=<?php echo yazarBoslukSil($baslikentry[0]); ?>'" id="ehg">Hepsi Gelsin</button><?php } ?>
						<?php if ($MEMBER_LOGGED) { ?>
						<div style="text-align:left; padding-top:10px; padding-left:25px;">"<?php echo $baslikentry[0]; ?>" hakkında söylemek istediklerim var diyorsan durma:
						<form action="ekle.php" method="post" id="yenigirdi" name="yenigirdi"><input type="hidden" name="t" value="<?php echo $baslikentry[0]; ?>" /><div id="butonlar" style="text-align:left; width:100%; padding-top:10px;"><input type="button" id="bkz" value="(bkz: )" class="ebut" /><input type="button" id="gizlibkz" value="``" class="ebut"/><input type="button" id="spoiler" value="spoiler" class="ebut"/><input type="button" value="link" onclick="var a=prompt('link: (başında http:// olmalı)', 'http://');if(isURL(a))$('#entrytextarea').tae('url',a);" class="ebut"/></div><textarea id="entrytextarea" rows="10" cols="105" class="ygirdi" name="ygirdi"></textarea><input type="submit" value="böyle olur" class="ebut" /><input type="submit" value="bunu sonra gönderirim" class="ebut" name="kaydet" /></form></div><?php } ?></div></div>
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

<?php
/**
* entryGoster
* bütün başlık istenmediyse istenen durumdaki entry gösterme işini yapar.
* @param $eid : entry numarası
* @param $t : başlık adı
* @param $u : kullanıcı adı // bir başlıktaki $u'nun yazdıklarını göstermek için
* @param $g : gün //istenen bir gün varsa, dün, bugün, belirli bir gün gibi
* @param $p : sayfa numarası
* @version v0.5
*/
	function entryGoster($eid=null,$t=null,$u=null,$g=null,$p=null) {
		$MEMBER_LOGGED = isset($_SESSION['logged']) && $_SESSION['logged'];
		$link = getPDO();
		
		$sayfabasinagirdi = !empty($_SESSION['membil']) ? $_SESSION['membil']->Entry_Per_Page : 25;
		if (!empty($p)) {			
			$sayfa = ($p-1)*$sayfabasinagirdi;
		}
		else {
			$sayfa = 0;
		}
		
		$date_condition="";
		
		if (!empty($eid) && empty($u) && empty($g)) { //istenen tek bir entry ise
			$e = $eid;
			$baslik = !empty($t) ? $t : null;
			
			if ($baslik) {
				$es = $link -> prepare("SELECT E_ID,T_ID,U_ID,Girdi,Tarih,Duzenleme,n.Nick from entries NATURAL JOIN (SELECT U_ID,Nick FROM members) as n WHERE E_ID=:eid AND Aktif=1 AND Thrash=0");
				$es -> bindValue(":eid",$e);
			}
			else { //goster.php?e= şeklinde geldiyse buraya
				$es = $link -> prepare("SELECT E_ID,T_ID,U_ID,Girdi,Tarih,Duzenleme,n.Nick,b.Baslik from (entries NATURAL JOIN (SELECT U_ID,Nick FROM members) as n) NATURAL JOIN (SELECT T_ID, Baslik FROM titles) as b WHERE E_ID=:eid AND Aktif=1 AND Thrash=0");
				$es -> bindValue(":eid",$e);
			}
		}
		else if (empty($eid) && !empty($t) && !empty($u) && empty($g)) { //baslik ve nick
			$es = $link -> prepare("SELECT E_ID,T_ID,U_ID,Girdi,Tarih,Duzenleme,u.Nick,b.Baslik FROM entries NATURAL JOIN (SELECT U_ID,Nick FROM members WHERE Nick = :nick) as u NATURAL JOIN (SELECT T_ID,Baslik FROM titles WHERE Baslik=:baslik) as b WHERE U_ID=u.U_ID AND T_ID=b.T_ID AND Aktif=1 AND Thrash=0 ORDER BY Tarih LIMIT $sayfa,$sayfabasinagirdi");
			$es -> bindValue(":nick",$u);
			$es -> bindValue(":baslik",$t);
		}
		else if (empty($eid) && !empty($t) && !empty($u) && !empty($g)) { //baslik nick ve tarih
			if ($g=="dun") 
				$date_condition = "Tarih BETWEEN ADDDATE(CURDATE(), INTERVAL - 1 DAY) AND CURDATE()";
			else if ($g=="bugun") 
				$date_condition = "Tarih BETWEEN CURDATE() AND ADDDATE(CURDATE(), INTERVAL  1 DAY)";
			else {
				$date_condition = "Tarih BETWEEN '$g'  AND ADDDATE('$g', INTERVAL  1 DAY)";
			}
			$es = $link -> prepare("SELECT E_ID,T_ID,U_ID,Girdi,Tarih,Duzenleme,u.Nick,b.Baslik FROM entries NATURAL JOIN (SELECT U_ID,Nick FROM members WHERE Nick = :nick) as u NATURAL JOIN (SELECT T_ID,Baslik FROM titles WHERE Baslik=:baslik) as b WHERE U_ID=u.U_ID AND T_ID=b.T_ID AND Aktif=1 AND Thrash=0 AND $date_condition ORDER BY Tarih LIMIT $sayfa,$sayfabasinagirdi");
			$es -> bindValue(":nick",$u);
			$es -> bindValue(":baslik",$t);
		}
		else if (empty($eid) && !empty($t) && empty($u) && !empty($g)) { //baslik ve tarih
			if ($g=="dun") 
				$date_condition = "Tarih BETWEEN ADDDATE(CURDATE(), INTERVAL - 1 DAY) AND CURDATE()";
			else if ($g=="bugun") 
				$date_condition = "Tarih BETWEEN CURDATE() AND ADDDATE(CURDATE(), INTERVAL  1 DAY)"; 
			else {
				$date_condition = "Tarih BETWEEN '$g'  AND ADDDATE('$g', INTERVAL  1 DAY)";
			}
			$es = $link -> prepare("SELECT E_ID,T_ID,U_ID,Girdi,Tarih,Duzenleme,u.Nick,b.Baslik FROM ((entries NATURAL JOIN (SELECT T_ID,Baslik FROM titles WHERE Baslik=:baslik) as b) NATURAL JOIN (SELECT U_ID,Nick FROM members) as u) WHERE T_ID=b.T_ID AND Aktif=1 AND Thrash=0 AND $date_condition ORDER BY Tarih LIMIT $sayfa,$sayfabasinagirdi");
			$es -> bindValue(":baslik",$t);
		}
		else { //T_ID=1 olan başlığı göster -- daha sonra domain name'i içeren başlığı göstericek.
			$es = $link -> prepare("SELECT E_ID,T_ID,U_ID,Girdi,Tarih,Duzenleme,u.Nick,t.Baslik FROM ((entries NATURAL JOIN (SELECT U_ID,Nick FROM members) as u) NATURAL JOIN (SELECT T_ID,Baslik FROM titles WHERE T_ID=1) as t) WHERE T_ID=1 AND Aktif=1 AND Thrash=0 ORDER BY Tarih LIMIT $sayfa,$sayfabasinagirdi");
		}
		$es -> execute();
		if (!$es->rowCount())
			echo "<i>bütün uğraşlarımıza rağmen bu şartlara uyan entry bulamadık.</i>";
		else {			
			$girdi = $es -> fetchAll(PDO::FETCH_ASSOC);
						
			$baslikadi = !empty($baslik)?$baslik:$girdi[0]['Baslik'];
			
			echo '<h3 style="text-align:left; margin-left:40px;">'.$baslikadi.'</h3><input type="hidden" value="'.$baslikadi.'" id="baslikd" /><ol class="girdiler">';
			
			for ($i=0;$i<count($girdi);$i++) {
				$duzen = $girdi[$i]['Duzenleme'];
				if (!$duzen)
					$duzenleme = "";
				else {
					if (substr($duzen,0,10)==substr($girdi[$i]['Tarih'],0,10))
						$duzenleme = " ~ ".substr($duzen,11,5);
					else
						$duzenleme = " ~ ".substr($duzen,0,16);
				}
				if (!empty($eid) && empty($u) && empty($g)) { //tek entry
					$sk = $link -> query("SELECT COUNT(E_ID) as listnumber FROM entries WHERE T_ID=".$girdi[$i]['T_ID']." AND E_ID BETWEEN 1 AND ".$girdi[$i]['E_ID']." AND Aktif = 1 AND Thrash = 0 ORDER BY Tarih");
					$s = $sk -> fetch(PDO::FETCH_ASSOC);
				}
				else if (empty($eid) && !empty($t) && !empty($u) && empty($g)) { //başlık ve nick
					$sk = $link -> query("SELECT COUNT(E_ID) as listnumber FROM entries WHERE T_ID=".$girdi[$i]['T_ID']." AND E_ID BETWEEN 1 AND ".$girdi[$i]['E_ID']." AND Aktif = 1 AND Thrash = 0 ORDER BY Tarih");
					$s = $sk -> fetch(PDO::FETCH_ASSOC);
				}
				else if (empty($eid) && !empty($t) && !empty($u) && !empty($g)) { //başlık nick ve gün
					$sk = $link -> query("SELECT COUNT(E_ID) as listnumber FROM entries WHERE T_ID=".$girdi[$i]['T_ID']." AND E_ID BETWEEN 1 AND ".$girdi[$i]['E_ID']." AND $date_condition AND Aktif = 1 AND Thrash = 0 ORDER BY Tarih");
					$s = $sk -> fetch(PDO::FETCH_ASSOC);			
				}
				else if (empty($eid) && !empty($t) && empty($u) && !empty($g)) { //başlık ve gün
					$sk = $link -> query("SELECT COUNT(E_ID) as listnumber FROM entries WHERE T_ID=".$girdi[$i]['T_ID']." AND E_ID BETWEEN 1 AND ".$girdi[$i]['E_ID']." AND $date_condition AND Aktif = 1 AND Thrash = 0 ORDER BY Tarih");
					$s = $sk -> fetch(PDO::FETCH_ASSOC);
				}
				else { //T_ID=1
					$s = 1;
				}
				if ($s!=1) 
					$no = $s['listnumber'];
				else {
					$no = $sayfa + $i + 1;
				}
				echo '<li class="girdi" value="'.$no.'">';
				echo girdiControl($girdi[$i]['Girdi']);
				echo '<div class="yazarinfo">(<a href="goster.php?t='.yazarBoslukSil($girdi[$i]['Nick']).'" id="yazar" rel="'.$girdi[$i]['U_ID'].'">'.$girdi[$i]['Nick'].'</a>, '.substr($girdi[$i]['Tarih'],0,16).''.$duzenleme.')</div><div class="ymore"><a href="goster.php?e='.$girdi[$i]['E_ID'].'" id="entryid">#'.$girdi[$i]['E_ID'].'</a>';
				//butonlar
				if ($MEMBER_LOGGED) {
					if ($girdi[$i]['U_ID'] != $_SESSION['member']->U_ID) //kendine oy vermesin
						echo '&nbsp;<button type="button" onClick="ep(\'vote.php?id='.$girdi[$i]['E_ID'].'&o=1\')" class="minib" title="olmuş bu" id="+1">iyuf</button>&nbsp;<button type="button" onClick="ep(\'vote.php?id='.$girdi[$i]['E_ID'].'&o=-1\')" class="minib" title="böyle olmaz hacı" id="-1">ı ıh</button>';
					if ($girdi[$i]['U_ID'] == $_SESSION['member']->U_ID) //yazarın gördüğü
						echo '&nbsp;<button type="button" onClick="ep(\'edit.php?e='.$girdi[$i]['E_ID'].'\',\'820\',\'400\')" class="minib" id="eduz">düzelt</button>&nbsp;<button type="button" onClick="ep(\'del.php?e='.$girdi[$i]['E_ID'].'\')" class="minib" title="sil" id="esil">X</button>';
					else if ($_SESSION['membil']->Yetki>5) { //kendi yazmadığı entry ise moderasyonun gördüğü -> ilerki zamanlarda taşı da eklenebilir.
						echo '&nbsp;<button type="button" onClick="ep(\'edit.php?e='.$girdi[$i]['E_ID'].'\',\'820\',\'400\')" class="minib" id="eduz">düzelt</button>&nbsp;<button type="button" onClick="ep(\'del.php?e='.$girdi[$i]['E_ID'].'\')" class="minib" title="sil" id="esil">X</button>';
					echo '&nbsp;<button type="button" onClick="ep(\'fav.php?e='.$girdi[$i]['E_ID'].'\')" class="minib" title="favorilere ekle" id="efav">:D</button>&nbsp;<button type="button" onClick="ep(\'mesaj.php?y='.yazarBoslukSil($girdi[$i]['Nick']).'\',\'600\',\'400\')" class="minib" title="yazara mesaj atiyim" id="eymesaj">msj</button>';
					} else //diğer üyelerin gördüğü
					echo '&nbsp;<button type="button" onClick="ep(\'fav.php?e='.$girdi[$i]['E_ID'].'\')" class="minib" title="favorilere ekle" id="efav">:D</button>&nbsp;<button type="button" onClick="ep(\'mesaj.php?y='.yazarBoslukSil($girdi[$i]['Nick']).'\',\'600\',\'400\')" class="minib" title="yazara mesaj atiyim" id="eymesaj">msj</button>';
				}
				//herkesin gördüğü
				echo '&nbsp;<button type="button" onClick="location.href=\'yazar.php?y='.yazarBoslukSil($girdi[$i]['Nick']).'\'" id="eyh" class="minib" title="yazar hakkında">?</button>&nbsp;<button type="button" onClick="location.href=\'sikayet.php?e='.$girdi[$i]['E_ID'].'\'" id="esb" class="minib" title="şikayet et">!</button>';
				echo '&nbsp;</div><div id="yazarmini"></div>';
				echo '</li><br />';
				}
			echo '<br /></ol>';
		}
	}
?>