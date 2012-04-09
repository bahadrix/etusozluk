<?php 
/**
* goster.php 
* entry veya başlık gösterme sayfası
* @version v1.0rc2
*/
include_once 'common.php';
include_once 'funct.php';
 
/* global değişkenler */
$link = getPDO();

/* /global değişkenler */
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') { //json 
	if ($MEMBER_LOGGED) 
		$log=1;
	else
		$log=0;
	if (!empty($_REQUEST['t']) || !empty($_REQUEST['e'])) { //başlık veya entry istenmiş mi?
		if (!empty($_REQUEST['t']) && empty($_REQUEST['e'])) { //başlık istenmiş mi?
			$t = $_REQUEST['t'];
			
			if (!empty($_REQUEST['p']) && is_numeric($_REQUEST['p']))
				$p = $_REQUEST['p'];
			else								
				$p = 1;
										
			$baslikentry = preg_split('/\//',$t);
			$te = strpos($t,'/'); //t=baslik/bilgi şeklinde gelmiş olabilir.
			if ($te!==false && substr($baslikentry[1],0,1)!="@" && substr($baslikentry[1],0,1)!="$") {
				$baslikentry[0]=$baslikentry[0]."/".$baslikentry[1]; //search'e 1/2 yazılırsa 1 yerine 1/2 aransın.
			}
			$st = $link -> prepare("SELECT T_ID FROM titles WHERE Baslik = :baslik");
			$st -> bindValue(":baslik",$baslikentry[0]);
			$st -> execute();
																	
			if (!$st->rowCount()) {
				echo '{"code":"1001","message":"bütün uğraşlarımıza rağmen böyle bir başlık bulamadık.","baslik":"'.$t.'","hgj":"0","log":"'.$log.'"}';
				//benzer başlıkları göster eklenebilir
			} else {
				$baslikid = $st -> fetch(PDO::FETCH_ASSOC);
									
				$te = strpos($t,'/'); //t=baslik/bilgi şeklinde gelmiş olabilir.
				if ($te !== false) {										
					if (substr($baslikentry[1],0,1)=="$" && is_numeric(substr($baslikentry[1],1))) { //t=baslik/$entrynumarası biçimi
						$entryno = substr($baslikentry[1],1);
						$se = $link -> prepare("SELECT E_ID FROM entries WHERE E_ID=:eid AND T_ID=:tid");
						$se -> bindValue(":eid",$entryno);
						$se -> bindValue(":tid",$baslikid['T_ID']);
						$se -> execute();
						if ($se->rowCount()) {
							$var = true;
							entryGoster($entryno,$baslikentry[0],null,null,null,true);
						}
						else
							echo '{"code":"1002","message":"bütün uğraşlarımıza rağmen bu başlıkta böyle bir entry bulamadık.","baslik":"'.$t.'","hgj":"1","log":"'.$log.'"}';
					}
					else if (substr($baslikentry[1],0,1)==="@") { //@ sonrası kullanıcı adı olucak. t=baslik/@nick biçimi
						$nick = substr($baslikentry[1],1);
						$sn = $link -> prepare("SELECT Nick FROM members WHERE Nick = :nick");
						$sn -> bindValue(":nick",$nick);
						$sn -> execute();
						if (!$sn->rowCount()) {
							echo '{"code":"1002","message":"böyle bir kullanıcı yok.","baslik":"'.$t.'","hgj":"1","log":"'.$log.'"}';
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
								entryGoster(null,$baslikentry[0],$nick,$gun,$p,true);
							}
							else { //sadece kullanıcı adı bilgisi varsa
								entryGoster(null,$baslikentry[0],$nick,null,$p,true);
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
						entryGoster(null,$baslikentry[0],null,$gun,$p,true);
					}
				else { //t=baslik
					entryGoster(null,$t,null,null,$p,true);
				}
			}
		}
		else if (empty($_REQUEST['t']) && !empty($_REQUEST['e']) && is_numeric($_REQUEST['e'])) { //entry istenmiş mi?
			$entryno = $_REQUEST['e'];
			$se = $link -> prepare("SELECT e.E_ID,t.Baslik FROM (SELECT E_ID,T_ID FROM entries WHERE E_ID=:eid) as e NATURAL JOIN (SELECT T_ID,Baslik FROM titles) as t");
			$se -> bindValue(":eid",$entryno);
			$se -> execute();
			if ($se->rowCount()) {
				entryGoster($entryno,null,null,null,null,true);
			}
			else
				echo '{"code":"1002","message":"bütün uğraşlarımıza rağmen böyle bir entry bulamadık.","baslik":"","hgj":"0","log":"'.$log.'"}';
			}
			else { //ne t ne e varsa direkt T_ID=1 olan başlığı göster.
			entryGoster(null,null,null,null,null,true);
			}
		}
}
else { //normal görünüm
?>
<?php
	include 'ust.php';
?>
			<table cellspacing=0 cellpadding=0>
				<tr>
					<td valign=top width=800 style="border-right:1px dotted #666;">
						<div id="mainleft"><div id="entries">
						<?php
						$var = false; //başlığın tamamı gösterilmediği durumda true olucak.
						if (!empty($_REQUEST['t']) || !empty($_REQUEST['e'])) { //başlık veya entry istenmiş mi?
							if (!empty($_REQUEST['t']) && empty($_REQUEST['e'])) { //başlık istenmiş mi?
								$t = $_REQUEST['t'];
								
								if (!empty($_REQUEST['p']) && is_numeric($_REQUEST['p']))
									$p = $_REQUEST['p'];
								else								
									$p = 1;
										
								$baslikentry = preg_split('/\//',$t);
								$te = strpos($t,'/'); //t=baslik/bilgi şeklinde gelmiş olabilir.
								if ($te!==false && substr($baslikentry[1],0,1)!="@" && substr($baslikentry[1],0,1)!="$") {
									$baslikentry[0]=$baslikentry[0]."/".$baslikentry[1]; //search'e 1/2 yazılırsa 1 yerine 1/2 aransın.
								}
								$st = $link -> prepare("SELECT T_ID FROM titles WHERE Baslik = :baslik");
								$st -> bindValue(":baslik",$baslikentry[0]);
								$st -> execute();
																	
								if (!$st->rowCount()) {
									echo "<i>bütün uğraşlarımıza rağmen böyle bir başlık bulamadık.</i>";
									//benzer başlıkları göster eklenebilir
								} else {
									$baslikid = $st -> fetch(PDO::FETCH_ASSOC);
									
									
									if ($te !== false) {										
										if (substr($baslikentry[1],0,1)=="$" && is_numeric(substr($baslikentry[1],1))) { //t=baslik/$entrynumarası biçimi
											$entryno = substr($baslikentry[1],1);
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
										entryGoster(null,$baslikentry[0],null,$gun,$p);
									}
									else { //t=baslik 
										entryGoster(null,$t,null,null,$p);
									}
								}
							}
							else if (empty($_REQUEST['t']) && !empty($_REQUEST['e']) && is_numeric($_REQUEST['e'])) { //entry istenmiş mi?
							
								$entryno = $_REQUEST['e'];
								$se = $link -> prepare("SELECT e.E_ID,t.Baslik FROM (SELECT E_ID,T_ID FROM entries WHERE E_ID=:eid) as e NATURAL JOIN (SELECT T_ID,Baslik FROM titles) as t");
								$se -> bindValue(":eid",$entryno);
								$se -> execute();
								if ($se->rowCount()) {
									$be = $se -> fetch(PDO::FETCH_ASSOC);
									$baslikentry[0]=$be["Baslik"];
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
						}
						?>	
						<br /><div style="text-align:center;" id="hg"><?php if($var) { ?><button type="button" rel="goster.php?t=<?php echo yazarBoslukSil($baslikentry[0]); ?>" id="ehg">Hepsi Gelsin</button><?php } ?>
						<?php if ($MEMBER_LOGGED) { ?>
						<div style="text-align:left; padding-top:10px; padding-left:25px;">"<?php echo $baslikentry[0]; ?>" hakkında söylemek istediklerim var diyorsan durma:
						<form action="ekle.php" method="post" id="yenigirdi" name="yenigirdi"><input type="hidden" name="t" value="<?php echo $baslikentry[0]; ?>" /><div id="butonlar" style="text-align:left; width:100%; padding-top:10px;"><input type="button" id="bkz" value="(bkz: )" class="ebut" /><input type="button" id="gizlibkz" value="``" class="ebut"/><input type="button" id="spoiler" value="spoiler" class="ebut"/><input type="button" value="link" onclick="var a=prompt('link: (başında http:// olmalı)', 'http://');if(isURL(a))$('#entrytextarea').tae('url',a);" class="ebut"/></div><textarea id="entrytextarea" rows="10" cols="105" class="ygirdi" name="ygirdi"></textarea><input type="submit" value="böyle olur" class="ebut" /><input type="submit" value="bunu sonra gönderirim" class="ebut" name="kaydet" /></form></div><?php } ?></div></div>
					</td>
					<td valign=top width=400>
						<div id="mainright"><div id="basliklar" style="text-align:left;"></div><input type="hidden" name="page_count" id="page_count" value="1" /></div>
					</td>
				</tr>
			</table>

<?php
include 'alt.php';
}
/**
* entryGoster
* bütün başlık istenmediyse istenen durumdaki entry gösterme işini yapar.
* @param $eid : entry numarası
* @param $t : başlık adı
* @param $u : kullanıcı adı -> bir başlıktaki $u'nun yazdıklarını göstermek için
* @param $g : gün -> istenen bir gün varsa, dün, bugün, belirli bir gün gibi
* @param $p : sayfa numarası
* @param $j : json çıktı kontrolü -> true/false
* @version v0.92
*/
	function entryGoster($eid=null,$t=null,$u=null,$g=null,$p=null,$j=false) {
		$MEMBER_LOGGED = isset($_SESSION['logged']) && $_SESSION['logged'];
		$link = getPDO();
		
		if ($MEMBER_LOGGED) //json için
			$log=1;
		else
			$log=0;
		
		$hgj = 0; //json için hepsi geldin değeri
		
		$url = $_SERVER['REQUEST_URI'];
		$url = preg_replace('/\&p(?:=[0-9])*/','',$url);
		
		$sayfabasinagirdi = !empty($_SESSION['membil']) ? $_SESSION['membil']->Entry_Per_Page : 10;
		if (!empty($p)) {			
			$sayfa = ($p-1)*$sayfabasinagirdi;
		}
		else {
			$sayfa = 0;
		}
		
		$date_condition="";
		
		/* girdi çekme kısmı */
		if (!empty($eid) && empty($u) && empty($g)) { //istenen tek bir entry ise
			$hgj=1;
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
			$hgj=1;
			$es = $link -> prepare("SELECT E_ID,T_ID,U_ID,Girdi,Tarih,Duzenleme,u.Nick,b.Baslik FROM entries NATURAL JOIN (SELECT U_ID,Nick FROM members WHERE Nick = :nick) as u NATURAL JOIN (SELECT T_ID,Baslik FROM titles WHERE Baslik=:baslik) as b WHERE U_ID=u.U_ID AND T_ID=b.T_ID AND Aktif=1 AND Thrash=0 ORDER BY Tarih LIMIT $sayfa,$sayfabasinagirdi");
			$es -> bindValue(":nick",$u);
			$es -> bindValue(":baslik",$t);
		}
		
		else if (empty($eid) && !empty($t) && !empty($u) && !empty($g)) { //baslik nick ve tarih
			$hgj=1;
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
			$hgj=1;
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
		
		else if (empty($eid) && !empty($t) && empty($u) && empty($g)) { //sadece baslik
			$baslik = $t; 
			$baslikidcek = $link -> prepare("SELECT T_ID FROM titles WHERE Baslik=:tname");
			$baslikidcek -> bindValue(":tname",$t);
			$baslikidcek -> execute();
			$bic = $baslikidcek->fetch(PDO::FETCH_ASSOC);
			$bi = $bic['T_ID'];
			$es = $link -> prepare("SELECT E_ID,T_ID,U_ID,Girdi,Tarih,Duzenleme,n.Nick from entries NATURAL JOIN (SELECT U_ID,Nick FROM members) as n WHERE T_ID=:tid AND Aktif=1 AND Thrash=0 ORDER BY Tarih LIMIT $sayfa,$sayfabasinagirdi");
			$es -> bindValue(":tid",$bi);
		}
		
		else { //T_ID=1 olan başlığı göster -- daha sonra domain name'i içeren başlığı göstericek.
			$es = $link -> prepare("SELECT E_ID,T_ID,U_ID,Girdi,Tarih,Duzenleme,u.Nick,t.Baslik FROM ((entries NATURAL JOIN (SELECT U_ID,Nick FROM members) as u) NATURAL JOIN (SELECT T_ID,Baslik FROM titles WHERE T_ID=1) as t) WHERE T_ID=1 AND Aktif=1 AND Thrash=0 ORDER BY Tarih LIMIT $sayfa,$sayfabasinagirdi");
		}
		
		$es -> execute();
		/* /girdi çekme kısmı */
		
		if (!$es->rowCount())
			if ($j===true)
				echo '{"code":"1004","message":"bütün uğraşlarımıza rağmen bu şartlara entry bulamadık.","baslik":"'.$t.'","hgj":"'.$hgj.'","log":"'.$log.'"}';
			else
				echo "<i>bütün uğraşlarımıza rağmen bu şartlara uyan entry bulamadık.</i>";
		else {			
			$girdi = $es -> fetchAll(PDO::FETCH_ASSOC);
			
			/* sayfa sayma */
			if (!empty($eid) && empty($u) && empty($g)) { //tek entry
				$toplamgirdi=1;
			}
			
			else if (empty($eid) && !empty($t) && !empty($u) && empty($g)) { //başlık ve nick
				$say = $link -> prepare("SELECT COUNT(E_ID) as ttl FROM entries WHERE T_ID=:tid AND U_ID=:uid AND Aktif = 1 AND Thrash = 0");
				$say -> bindValue(":tid",$girdi[0]['T_ID']);
				$say -> bindValue(":uid",$girdi[0]['U_ID']);
				$say -> execute();
				$sayf = $say->fetch(PDO::FETCH_ASSOC);
				$toplamgirdi = $sayf['ttl'];
			}
			
			else if (empty($eid) && !empty($t) && !empty($u) && !empty($g)) { //başlık nick ve gün
				$say = $link -> prepare("SELECT COUNT(E_ID) as ttl FROM entries WHERE T_ID=:tid AND U_ID=:uid AND $date_condition AND Aktif = 1 AND Thrash = 0");
				$say -> bindValue(":tid",$girdi[0]['T_ID']);
				$say -> bindValue(":uid",$girdi[0]['U_ID']);
				$say -> execute();
				$sayf = $say->fetch(PDO::FETCH_ASSOC);
				$toplamgirdi = $sayf['ttl'];			
			}
			
			else if (empty($eid) && !empty($t) && empty($u) && !empty($g)) { //başlık ve gün
				$say = $link -> prepare("SELECT COUNT(E_ID) as ttl FROM entries WHERE T_ID=:tid AND $date_condition AND Aktif = 1 AND Thrash = 0");
				$say -> bindValue(":tid",$girdi[0]['T_ID']);
				$say -> execute();
				$sayf = $say->fetch(PDO::FETCH_ASSOC);
				$toplamgirdi = $sayf['ttl'];
			}
			
			else if (empty($eid) && !empty($t) && empty($u) && empty($g)) { //sadece baslik
				$say = $link -> prepare("SELECT COUNT(E_ID) as ttl FROM entries WHERE T_ID=:tid AND Aktif=1 AND Thrash=0");
				$say -> bindValue(":tid",$girdi[0]['T_ID']);
				$say -> execute();
				$sayf = $say->fetch(PDO::FETCH_ASSOC);
				$toplamgirdi = $sayf['ttl'];
			}
			
			else { //T_ID=1
				$say = $link -> prepare("SELECT COUNT(E_ID) as ttl FROM entries WHERE T_ID=1 AND Aktif = 1 AND Thrash = 0");
				$say -> execute();
				$sayf = $say->fetch(PDO::FETCH_ASSOC);
				$toplamgirdi = $sayf['ttl'];
			}
			/* /sayfa sayma */
			
			$baslikadi = !empty($baslik)?$baslik:$girdi[0]['Baslik'];
			
			if ($j===true) { //json çıkaran kısım
				$toplamsayfa = ceil($toplamgirdi/$sayfabasinagirdi);
				
				$jsonarray = array("log"=>"$log","baslik"=>"$baslikadi","girdiler"=>"","ts"=>"$toplamsayfa","p"=>"$p","url"=>"$url","hgj"=>"$hgj");
				$girdiarray = array();
				
				for ($i=0;$i<count($girdi);$i++) {
			
					/* düzenleme */
					$duzen = $girdi[$i]['Duzenleme'];
					if (!$duzen)
						$duzenleme = "";
					else {
						if (substr($duzen,0,10)==substr($girdi[$i]['Tarih'],0,10))
							$duzenleme = " ~ ".substr($duzen,11,5);
						else
							$duzenleme = " ~ ".substr($duzen,0,16);
					}
					/* /düzenleme */
				
					/* liste numarası alma */
					if ((!empty($eid) && empty($u) && empty($g)) || (empty($eid) && !empty($t) && !empty($u) && empty($g)) || (empty($eid) && !empty($t) && !empty($u) && !empty($g)) || (empty($eid) && !empty($t) && empty($u) && !empty($g))) { //entry, başlık ve nick, başlık nick ve gün, başlık gün
						$sk = $link -> prepare("SELECT COUNT(E_ID) as listnumber FROM entries WHERE T_ID=:tid AND E_ID BETWEEN 1 AND :eid AND Aktif = 1 AND Thrash = 0 ORDER BY Tarih");
						$sk -> bindValue(":tid",$girdi[$i]['T_ID']);
						$sk -> bindValue(":eid",$girdi[$i]['E_ID']);
						$sk -> execute();
						$s = $sk -> fetch(PDO::FETCH_ASSOC);
					}
				
					else { //sadece başlık veya T_ID=1 durumu
						$s = 1;
					}			
				
					if ($s!=1) 
						$no = $s['listnumber'];
					else {
						$no = $sayfa + $i + 1;
					}
					/* /liste numarası alma */
					
					/* butonlar */
					$iyuf = 0;
					$iih = 0;
					$duzenle = 0;
					$sil = 0;
					$favori = 0;
					$mesaj = 0;
					
					
					
					if ($MEMBER_LOGGED) {
						if ($girdi[$i]['U_ID'] != $_SESSION['member']->U_ID) { //kendine oy vermesin
							$iyuf=1;
							$iih=1;
						}
						
						if ($girdi[$i]['U_ID'] == $_SESSION['member']->U_ID) { //yazarın gördüğü
							$duzenle=1;
							$sil=1;
						}
						else if ($_SESSION['membil']->Yetki>5) { //kendi yazmadığı entry ise moderasyonun gördüğü -> ilerki zamanlarda taşı da eklenebilir.
							$duzenle=1;
							$sil=1;
							$favori=1;
							$mesaj=1;
						} else { //diğer üyelerin gördüğü
							$favori=1;
							$mesaj=1;
						}
					}					
					$entry = array("eid"=>$girdi[$i]['E_ID'],"listnumber"=>"$no","nick"=>$girdi[$i]['Nick'],"id"=>$girdi[$i]['U_ID'],"girdi"=>girdiControl($girdi[$i]['Girdi']),"tarih"=>substr($girdi[$i]['Tarih'],0,16),"duzen"=>"$duzenleme","iyuf"=>"$iyuf","iih"=>"$iih","duzenle"=>"$duzenle","sil"=>$sil,"favori"=>"$favori","mesaj"=>"$mesaj");
					array_push($girdiarray,$entry);
				}
				$jsonarray['girdiler']=$girdiarray;
				echo json_encode($jsonarray);
			}
			
			else { //normal görüntü
				echo '<h3 style="text-align:left; margin-left:40px;">'.$baslikadi.'</h3><input type="hidden" value="'.$baslikadi.'" id="baslikd" />';
			
				/* üst kısım sayfalama */
				if ($toplamgirdi>$sayfabasinagirdi) {
					$toplamsayfa = ceil($toplamgirdi/$sayfabasinagirdi);
					if ($toplamsayfa>1) {
						echo '<div id="sayfalar" style="position:absolute;right:0;top:0;font-size:8pt;">';
					if ($p>1) {
						$syf = $p-1;
						echo '<a href="'.$url.'&p='.$syf.'">&lt;&lt</a>';
					}
					echo '<select class="sayfa" style="font-size:8pt;" onChange="location.href=\''.$url.'&p=\'+(this.selectedIndex+1)" name="p">';
					for ($i=1;$i<=$toplamsayfa;$i++) {
						if ($i==$p)
							$ekle = " selected";
						else
							$ekle = "";
						echo '<option value="'.$i.'"'.$ekle.'>'.$i.'</option>';
					}
					echo '</select>';
					if ($p != $toplamsayfa) {
					$syf = $p+1;
					echo '<a href="'.$url.'&p='.$syf.'">&gt;&gt</a>';
					}
					echo '</div>';
					}
				}
				/* /üst kısım sayfalama */
				echo'<ol class="girdiler">';
			
				for ($i=0;$i<count($girdi);$i++) {
			
					/* düzenleme */
					$duzen = $girdi[$i]['Duzenleme'];
					if (!$duzen)
						$duzenleme = "";
					else {
						if (substr($duzen,0,10)==substr($girdi[$i]['Tarih'],0,10))
							$duzenleme = " ~ ".substr($duzen,11,5);
						else
							$duzenleme = " ~ ".substr($duzen,0,16);
					}
					/* /düzenleme */
				
					/* liste numarası alma */
					if ((!empty($eid) && empty($u) && empty($g)) || (empty($eid) && !empty($t) && !empty($u) && empty($g)) || (empty($eid) && !empty($t) && !empty($u) && !empty($g)) || (empty($eid) && !empty($t) && empty($u) && !empty($g))) { //entry, başlık ve nick, başlık nick ve gün, başlık gün
						$sk = $link -> prepare("SELECT COUNT(E_ID) as listnumber FROM entries WHERE T_ID=:tid AND E_ID BETWEEN 1 AND :eid AND Aktif = 1 AND Thrash = 0 ORDER BY Tarih");
						$sk -> bindValue(":tid",$girdi[$i]['T_ID']);
						$sk -> bindValue(":eid",$girdi[$i]['E_ID']);
						$sk -> execute();
						$s = $sk -> fetch(PDO::FETCH_ASSOC);
					}
				
					else { //sadece başlık veya T_ID=1 durumu
						$s = 1;
					}			
				
					if ($s!=1) 
						$no = $s['listnumber'];
					else {
						$no = $sayfa + $i + 1;
					}
					/* /liste numarası alma */
				
					/* görünüm */
					echo '<li class="girdi" value="'.$no.'">';
					echo girdiControl($girdi[$i]['Girdi']);
					echo '<div class="yazarinfo">(<a href="goster.php?t='.yazarBoslukSil($girdi[$i]['Nick']).'" id="yazar" rel="'.$girdi[$i]['U_ID'].'">'.$girdi[$i]['Nick'].'</a>, '.substr($girdi[$i]['Tarih'],0,16).''.$duzenleme.')</div><div class="ymore"><a href="#" id="entryid">#'.$girdi[$i]['E_ID'].'</a>';
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
				/* /görünüm */
			
				/* alt kısım sayfalama */
				if ($toplamgirdi>$sayfabasinagirdi) {
					$toplamsayfa = ceil($toplamgirdi/$sayfabasinagirdi);
					if ($toplamsayfa>1) {
						echo '<div id="sayfalar" style="position:absolute;right:0;font-size:8pt;">';
					if ($p>1) {
						$syf = $p-1;
						echo '<a href="'.$url.'&p='.$syf.'">&lt;&lt</a>';
					}
					echo '<select class="sayfa" style="font-size:8pt;" onChange="location.href=\''.$url.'&p=\'+(this.selectedIndex+1)" name="p">';
					for ($i=1;$i<=$toplamsayfa;$i++) {
						if ($i==$p)
							$ekle = " selected";
						else
							$ekle = "";
						echo '<option value="'.$i.'"'.$ekle.'>'.$i.'</option>';
					}
					echo '</select>';
					if ($p != $toplamsayfa) {
					$syf = $p+1;
					echo '<a href="'.$url.'&p='.$syf.'">&gt;&gt</a>';
					}
					echo '</div>';
					}
				}
				/* /alt kısım sayfalama */
			
				echo '<br /></ol>';
			}
		}
	}
?>