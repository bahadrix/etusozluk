<?php
	include 'ust.php';
?>
			<table cellspacing=0 cellpadding=0>
				<tr>
					<td valign=top width=800 style="border-right:1px dotted #666;">
						<div id="mainleft"><div id="entries">
						<?php
						$link = getPDO();
						//random entry çek
						$se = $link -> query("SELECT E_ID,T_ID,U_ID,Girdi,Tarih,Duzenleme FROM entries WHERE Aktif = 1 AND Thrash = 0 ORDER BY RAND() LIMIT 1");
						$var = true; //gösterilen bir şey var mı?
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
						$sk = $link -> query ("SELECT COUNT(E_ID) as listnumber FROM entries WHERE T_ID=".$rentry['T_ID']." AND E_ID BETWEEN 1 AND ".$rentry['E_ID']." AND Aktif = 1 AND Thrash = 0 ORDER BY Tarih");
						$s = $sk -> fetch(PDO::FETCH_ASSOC);
						$sayi = $s['listnumber'];
						echo '<input type="hidden" value="'.$baslikadi.'" id="baslikd" />'; //bu satır index.php için
						//görünüm
						echo '<h3 style="text-align:left; margin-left:40px;">'.$baslikadi.'</h3><ol class=girdiler><li class="girdi" value="'.$sayi.'">';
						echo girdiControl($rentry['Girdi']);
						echo '<div class="yazarinfo">(<a href="goster.php?t='.yazarBoslukSil($yazar).'" id="yazar" rel="'.$rentry['U_ID'].'">'.$yazar.'</a>, '.substr($rentry['Tarih'],0,16).''.$duzenleme.')</div><div class="ymore"><a href="#" id="entryid">#'.$rentry['E_ID'].'</a>';
						//butonlar
						if ($MEMBER_LOGGED) {
							if ($rentry['U_ID'] != $_SESSION['member']->U_ID) //kendine oy vermesin
								echo '&nbsp;<button type="button" onClick="ep(\'vote.php?id='.$rentry['E_ID'].'&o=1\')" class="minib" title="olmuş bu" id="+1">iyuf</button>&nbsp;<button type="button" onClick="ep(\'vote.php?id='.$rentry['E_ID'].'&o=-1\')" class="minib" title="böyle olmaz hacı" id="-1">ı ıh</button>';
							if ($rentry['U_ID'] == $_SESSION['member']->U_ID) //yazarın gördüğü
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
						$baslikadi = preg_replace('/ /','+',$baslikadi); //hepsi gelsin butonu için gerekiyor?
						?>
						<br /><div style="text-align:center;" id="hg"><?php if($var) { ?><button type="button" rel="goster.php?t=<?php echo $baslikadi; ?>" id="ehg">Hepsi Gelsin</button>
						<?php if ($MEMBER_LOGGED) { ?>
						<div style="text-align:left; padding-top:10px; padding-left:25px;">"<?php echo $baslikadi; ?>" hakkında söylemek istediklerim var diyorsan durma:
						<form action="ekle.php" method="post" id="yenigirdi" name="yenigirdi"><input type="hidden" name="t" value="<?php echo $baslikadi; ?>" /><div id="butonlar" style="text-align:left; width:100%; padding-top:10px;"><input type="button" id="bkz" value="(bkz: )" class="ebut" /><input type="button" id="gizlibkz" value="``" class="ebut"/><input type="button" id="spoiler" value="spoiler" class="ebut"/><input type="button" value="link" onclick="var a=prompt('link: (başında http:// olmalı)', 'http://');if(isURL(a))$('#entrytextarea').tae('url',a);" class="ebut"/></div><textarea id="entrytextarea" rows="10" cols="105" class="ygirdi" name="ygirdi"></textarea><input type="submit" value="böyle olur" class="ebut" /><input type="submit" value="bunu sonra gönderirim" class="ebut" name="kaydet" /></form></div><?php } } ?></div></div></div>
					</td>
					<td valign=top width=400>
						<div id="mainright"><div id="basliklar" style="text-align:left;"></div><input type="hidden" name="page_count" id="page_count" value="1" /></div>
					</td>
				</tr>
			</table>
<?php
	include 'alt.php';
?>