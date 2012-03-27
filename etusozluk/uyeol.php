<?php
include('data/core/db.php');
try {
$link = getPDO();
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	if (!empty($_POST['nick']) && !empty($_POST['sifre']) && !empty($_POST['sifret']) && !empty($_POST['email']) && !empty($_POST['ad']) && !empty($_POST['soyad']) && !empty($_POST['cinsiyet']) && !empty($_POST['gun']) && !empty($_POST['ay']) && !empty($_POST['yil']) && !empty($_POST['sehir'])) {
		
        $nick = strtolower($_POST['nick']);
		$sifre = $_POST['sifre'];
		$sifret = $_POST['sifret'];
		$email = $_POST['email'];
		$ad = $_POST['ad'];
		$soyad = $_POST['soyad'];
		$cinsiyet = $_POST['cinsiyet'];
		$gun = $_POST['gun'];
		$ay = $_POST['ay'];
		$yil = $_POST['yil'];
		$sehir = $_POST['sehir'];
							
		if ($sifret != $sifre)
			echo 'Şifreler aynı olmalı';
		else if (!checkdate($ay, $gun, $yil))
			echo 'Geçersiz tarih'; //gereksiz kontrol
		else if (strlen($nick)<3 || strlen($nick)>25)
			echo 'Nick 3-25 karakter arası olmalı';
		else if (!preg_match("/^([a-zşçüıöğ0-9]+\s?)*$/", $nick))
			echo 'Nick sadece a-z0-9 ve boşluk içerebilir';
		else if (!filter_var($email,FILTER_VALIDATE_EMAIL))
			echo 'Geçersiz email adresi';
		else if (strlen($sifre)<3 || strlen($sifre)>20)
			echo 'Şifre 3-20 karakter arası olmalı';
		else if (strlen($ad)<3 || strlen($ad)>20)
			echo 'Ad 3-20 karakter arası olmalı';
		else if (!preg_match("/^([a-zŞşÇçÜüİıÖöĞğ]+\s?)*$/i", $ad))
			echo 'Ad sadece harf ve boşluk içerebilir';
		else if (strlen($soyad)<2 || strlen($soyad)>25)
			echo 'Soyad 2-25 karakter arası olmalı';
		else if (!preg_match("/^[a-zŞşÇçÜüİıÖöĞğ]+$/i", $soyad))
			echo 'Soyad sadece harf içerebilir';
		else {
			//$link = new PDO("mysql:host=localhost;dbname=bahadir_etusozluk", $u,$p);
                                
			$nickkontrol = $link->prepare("SELECT Nick FROM members WHERE Nick = :nick");
			$nickkontrol->bindValue(':nick',$nick,PDO::PARAM_STR);
			$nickkontrol->execute();
			if ($nickkontrol->rowCount() > 0)
				echo 'Bu kullanıcı adı daha önceden alınmış.';
			else {
				$emailkontrol = $link->prepare("SELECT Email FROM members WHERE Email = :email");
				$emailkontrol->bindValue(":email",$email,PDO::PARAM_STR);
				$emailkontrol->execute();
				if ($emailkontrol->rowCount() > 0)
					echo 'Bu email adresi daha önceden alınmış.';
				else {
					$ad = strtoupper(substr($ad,0,1)).strtolower(substr($ad,1));
					$soyad = strtoupper(substr($soyad,0,1)).strtolower(substr($soyad,1));
					$tarih = "{$yil}-{$ay}-{$gun}";
					$sifre = md5($nick.$sifre);
					$uyeet = $link->prepare("INSERT INTO members (Nick,Sifre,Ad,Soyad,Email,Cinsiyet,D_Tarihi,Uyelik_Tarihi,Sehir) VALUES (:nick,:sifre,:ad,:soyad,:email,:cinsiyet,:tarih,NOW(),:sehir)");
					$uyeet->bindValue(":nick",$nick);
					$uyeet->bindValue(":sifre",$sifre);
					$uyeet->bindValue(":ad",$ad);
					$uyeet->bindValue(":soyad",$soyad);
					$uyeet->bindValue(":email",$email);
					$uyeet->bindValue(":cinsiyet",$cinsiyet);
					$uyeet->bindValue(":tarih",$tarih);
					$uyeet->bindValue(":sehir",$sehir);
					if ($uyeet->execute())
						echo "true";
					else {
						echo "Hata oluştu, <br />";
						$arr = $uyeet->errorInfo();
						print_r($arr);
						}
					}
				}
			}							
	} else 
		echo 'Geçersiz';
	}
	else {
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="tr-tr" lang="tr-tr" dir="ltr" >
<head>
		<title>ETÜ Sözlük</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
		<script type="text/javascript" src="js/jquery.validate.min.js"></script>
		<script type="text/javascript" src="js/pae.js"></script>
		<link type="text/css" href="style/jquery-ui-1.8.18.custom.css" rel="stylesheet" />	
		<link type="text/css" href="style/style.css" rel="stylesheet" />
		<script type="text/javascript">
		function enableButton(){
		$('#registersub').removeAttr('disabled');
		$('#registersub').removeClass('disabled');
		};
		$(document).ready(function() {
			$("#register").validate({
				rules: {
					nick: {
						required:true,
						rangelength: [3,25],
						nkontrol: true
					},
					sifre: {
						required:true,
						rangelength: [3,20]
					},
					sifret: {
						required:true,
						rangelength: [3,20],
						equalTo: "#sifre"
					},
					email: {
						required:true,
						email:true,
						minlength:6
					},
					ad : {
						required:true,
						akontrol:true,
						rangelength: [3,20]
					},
					soyad : {
						required:true,
						lettersonly:true,
						rangelength: [2,25]
					},
					gun : "required",
					ay : "required",
					yil : "required",
					cinsiyet : "required",
					sehir : "required"
				},
			messages: {
				ad: {
					required: " Adınızı girin",
					rangelength: jQuery.format(" Ad {0}-{1} karakter olmalı")
				},
				soyad: {
					required: " Soyadınızı girin",
					rangelength: jQuery.format(" Soyad {0}-{1} karakter olmalı")
				},
				gun : " *",
				ay : " *",
				yil : " *",
				cinsiyet : " *",
				sehir : " *",
				nick: {
					required: " Kod adı girin",
					rangelength: jQuery.format(" Nick {0}-{1} karakter olmalı")
				},
				sifre: {
					required: " Şifre girin",
					rangelength: jQuery.format(" Şifre {0}-{1} karakter olmalı")
				},
				sifret: {
					required: " Şifreyi tekrar girin",
					rangelength: jQuery.format(" Şifre {0}-{1} karakter olmalı"),
					equalTo: " Şifre ile aynı olmalı"
				},
				email: {
					required: "Email adresinizi girin",
					minlength: "Geçerli bir email adresi girin",
					email: " Geçerli bir email adresi girin"
				}
			}
		});
		$("#register").submit(function(e){
			e.preventDefault();
			$('#registersub').attr("disabled", "disabled");
			$('#registersub').addClass('disabled');
			setTimeout('enableButton()', 7500);
			$.ajax({
			type: "POST",
			url: "uyeol.php",
			data: $("#register").serialize(),
			success: function(data) {
				if (data==="true") {
					$("#register").remove();
					$("#ek").remove();
					$("p:last").append("<p style='color:#fecc00>Üyeliğiniz açıldı. Lütfen email adresinize gönderdiğimiz aktivasyon mailini onaylayıp giriş yapınız.</p>");
				}
				else {
					$("#ek").remove();
					$("p:last").append("<p style='color:#e2001a' id='ek'>"+data+"</p>");
				}
			}
			});
			});
	});
		</script>
	</head>
	<body>
		<div id="logo"><a href="index.php" style="width: 200px; height: 90px; display: block;" title="Etü Sözlük"></a></div>
		<ul id="menu">
		<div id="loginbox">
				<p class="lgbaslik">Giriş</p>
				<form action="login.php" method="post" name="login">
				<table style="width:270px; text-align:center; margin-left:5px;">
					<tr>
					<td style="text-align:left; padding-left: 7px; width:100px;">Kullanıcı Adı:</td>
					<td style="text-align:left; padding-left: 10px; width:100px;">Şifre:</td>
					<td>&nbsp;</td>
					</tr>
					<tr>
					<td style="padding-right: 2px;"><input type="text" name="username" size="15" maxlength="25" value="" class="login" /></td>
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
			</div>
			<li id="uyeol"><a href="javascript:void(0);"><span>Üye Ol/Giriş</span></a></li>
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
						<div id="mainleft"><div id="entries"><h3 style="text-align:left; margin-left:40px;">Üye Olma Aparatı</h3>
						<ol style="color:#fff">
						<li>18 yaşından küçüklerin etü sözlük üyesi olması yasaktır.</li><li>3 ay boyunca kullanılmayan çaylak hesapları şişip doymayalım diye silinir.</li><li>e-mail adresinize aktivasyon maili gideceği için aktif bir email adresi girmelisiniz.</li><li>sözlüğe üye olan herkesin sözlük kurallarını bildiği farz edilmektedir. bu kurallara üye girişi yaptıktan sonra "panel" bölümünden ulaşabilirsiniz.</li><li>etü sözlük kullanıcılarından aldığı bilgileri sadece sistemin daha iyi işlemesi için kullanır. üçüncü kişilerle paylaşmaz. sadece hukuki durumlarda bu bilgiler gerekli mercilerle paylaşılır.</li><li>gerçek dışı kişisel bilgiler, türkiye cumhuriyeti yasalarına aykırı davranışlar, sözlük altyapısına yönelen saldırılar hesabınızın silinmesine yol açabilir.</li><li>aparatta istenen kişisel bilgiler hesabınızın başına bir şey gelmesi durumunda ilgili hesabın size ait olduğunu kanıtlamak için kullanılır.</li><li>aşağıdaki bütün alanların doldurulması zorunludur.</li>
						</ol>
						<?php
							if (!empty($_POST['nick']) && !empty($_POST['sifre']) && !empty($_POST['sifret']) && !empty($_POST['email']) && !empty($_POST['ad']) && !empty($_POST['soyad']) && !empty($_POST['cinsiyet']) && !empty($_POST['gun']) && !empty($_POST['ay']) && !empty($_POST['yil']) && !empty($_POST['sehir'])) {
								$nick = strtolower($_POST['nick']);
								$sifre = $_POST['sifre'];
								$sifret = $_POST['sifret'];
								$email = $_POST['email'];
								$ad = $_POST['ad'];
								$soyad = $_POST['soyad'];
								$cinsiyet = $_POST['cinsiyet'];
								$gun = $_POST['gun'];
								$ay = $_POST['ay'];
								$yil = $_POST['yil'];
								$sehir = $_POST['sehir'];
							
								if ($sifret != $sifre)
									echo 'Şifreler aynı olmalı';
								else if (!checkdate($ay, $gun, $yil))
									echo 'Geçersiz tarih'; //gereksiz kontrol
								else if (strlen($nick)<3 || strlen($nick)>25)
									echo 'Nick 3-25 karakter arası olmalı';
								else if (!preg_match("/^([a-zşçüıöğ0-9]+\s?)*$/", $nick))
									echo 'Nick sadece a-z0-9 ve boşluk içerebilir';
								else if (!filter_var($email,FILTER_VALIDATE_EMAIL))
									echo 'Geçersiz email adresi';
								else if (strlen($sifre)<3 || strlen($sifre)>20)
									echo 'Şifre 3-20 karakter arası olmalı';
								else if (strlen($ad)<3 || strlen($ad)>20)
									echo 'Ad 3-20 karakter arası olmalı';
								else if (!preg_match("/^([a-zŞşÇçÜüİıÖöĞğ]+\s?)*$/i", $ad))
									echo 'Ad sadece harf ve boşluk içerebilir';
								else if (strlen($soyad)<2 || strlen($soyad)>25)
									echo 'Soyad 2-25 karakter arası olmalı';
								else if (!preg_match("/^[a-zŞşÇçÜüİıÖöĞğ]+$/i", $soyad))
									echo 'Soyad sadece harf içerebilir';
								else {
									$nickkontrol = $link->prepare("SELECT Nick FROM members WHERE Nick = :nick");
									$nickkontrol->bindValue(':nick',$nick,PDO::PARAM_STR);
									$nickkontrol->execute();
									if ($nickkontrol->rowCount() > 0)
										echo $nick. ' kullanıcı adı daha önceden alınmış.';
									else {
										$emailkontrol = $link->prepare("SELECT Email FROM members WHERE Email = :email");
										$emailkontrol->bindValue(":email",$email,PDO::PARAM_STR);
										$emailkontrol->execute();
										if ($emailkontrol->rowCount() > 0)
											echo $email. ' email adresi daha önceden alınmış.';
										else {
											$ad = strtoupper(substr($ad,0,1)).strtolower(substr($ad,1));
											$soyad = strtoupper(substr($soyad,0,1)).strtolower(substr($soyad,1));
											$tarih = "{$yil}-{$ay}-{$gun}";
											$sifre = md5($nick.$sifre);
											$uyeet = $link->prepare("INSERT INTO members (Nick,Sifre,Ad,Soyad,Email,Cinsiyet,D_Tarihi,Uyelik_Tarihi,Sehir) VALUES (:nick,:sifre,:ad,:soyad,:email,:cinsiyet,:tarih,NOW(),:sehir)");
											$uyeet->bindValue(":nick",$nick);
											$uyeet->bindValue(":sifre",$sifre);
											$uyeet->bindValue(":ad",$ad);
											$uyeet->bindValue(":soyad",$soyad);
											$uyeet->bindValue(":email",$email);
											$uyeet->bindValue(":cinsiyet",$cinsiyet);
											$uyeet->bindValue(":tarih",$tarih);
											$uyeet->bindValue(":sehir",$sehir);
											if ($uyeet->execute())
												echo 'Üyeliğiniz açıldı. Lütfen email adresinize gönderdiğimiz aktivasyon mailini onaylayıp giriş yapınız.';
											else
												echo 'Hata oluştu. Lütfen tekrar deneyin';
										}
									}
								}							
							}
							else {
						?>
						<p style="color:#fff; margin-left:40px;">aşağıdaki formu doldurarak bu şartları kabul etmiş olursunuz.</p><br />
						<form action="uyeol.php" id="register" method="post">
							<fieldset style="border:1px solid #ccc; -moz-border-radius: 10px; -webkit-border-radius: 10px; -khtml-border-radius: 10px; border-radius: 10px;">
								<legend style="font-family: Arial, sans-serif; font-size: 1.3em; font-weight:bold; color:#fff;">&nbsp;artık aparata geçelim&nbsp;</legend>
								<table style="width:800px, border:1px solid white; font-size:8pt">
									<tr>
										<td style="width:200px; text-align:right;">Nick:</td>
										<td style="width:600px;"><input id="nick" name="nick" size="40" maxlength="25" type="text"/></td>
									</tr>
									<tr>
										<td style="width:200px; text-align:right;">Şifre:</td>
										<td style="width:600px;"><input type="password" id="sifre" name="sifre" size="40" maxlength="20"/></td>
									</tr>
									<tr>
										<td style="width:200px; text-align:right;">Şifre Tekrar:</td>
										<td style="width:600px;"><input type="password" id="sifret" name="sifret" size="40" maxlength="20"/></td>
									</tr>
									<tr>
										<td style="width:200px; text-align:right;">Email:</td>
										<td style="width:600px;"><input type="text" id="email" name="email" size="40" maxlength="50"/></td>
									</tr>
									<tr>
										<td style="width:200px; text-align:right;">Ad:</td>
										<td style="width:600px;"><input type="text" id="ad" name="ad" size="40" maxlength="20"/></td>
									</tr>
									<tr>
										<td style="width:200px; text-align:right;">Soyad:</td>
										<td style="width:600px;"><input type="text" id="soyad" name="soyad" size="40" maxlength="25"/></td>
									</tr>
									<tr>
										<td style="width:200px; text-align:right;">Cinsiyet:</td>
										<td style="width:600px;"><select name="cinsiyet" id="cinsiyet" style="background-color:#222;border:1px solid #333;color:#fecc00;"><option></option><option value="erkek">erkek</option><option value="kadın">kadın</option></select></td>
									</tr>
									<tr>
										<td style="width:200px; text-align:right;">Doğum Tarihi:</td>
										<td style="width:600px"><select name="gun" id="gun" style="background-color:#222;border:1px solid #333;color:#fecc00;"><option value=""></option><option value='1'>1</option><option value='2'>2</option><option value='3'>3</option><option value='4'>4</option><option value='5'>5</option><option value='6'>6</option><option value='7'>7</option><option value='8'>8</option><option value='9'>9</option><option value='10'>10</option><option value='11'>11</option><option value='12'>12</option><option value='13'>13</option><option value='14'>14</option><option value='15'>15</option><option value='16'>16</option><option value='17'>17</option><option value='18'>18</option><option value='19'>19</option><option value='20'>20</option><option value='21'>21</option><option value='22'>22</option><option value='23'>23</option><option value='24'>24</option><option value='25'>25</option><option value='26'>26</option><option value='27'>27</option><option value='28'>28</option><option value='29'>29</option><option value='30'>30</option><option value='31'>31</option></select><select id="ay" name="ay" style="background-color:#222;border:1px solid #333;color:#fecc00;"><option value=""></option><option value='1'>ocak</option><option value='2'>şubat</option><option value='3'>mart</option><option value='4'>nisan</option><option value='5'>mayıs</option><option value='6'>haziran</option><option value='7'>temmuz</option><option value='8'>ağustos</option><option value='9'>eylül</option><option value='10'>ekim</option><option value='11'>kasım</option><option value='12'>aralık</option></select><select id="yil" name="yil" style="background-color:#222;border:1px solid #333;color:#fecc00;"><option value=""></option><?php $yil=date("Y"); for ($i=$yil;$i>$yil-70;$i-=1) { echo "<option value='$i'>$i</option>"; }?></select></td>
									</tr>
									<tr>
										<td style="width:200px; text-align:right;">Şehir:</td>
										<td style="width:600px;"><select name="sehir" style="background-color:#222;border:1px solid #333;color:#fecc00;"><option value=""></option><option value="yurt dışı">yurt dışı</option><option value="adana">adana</option><option value="adıyaman">adıyaman</option><option value="afyon">afyon</option><option value="ağrı">ağrı</option><option value="aksaray">aksaray</option><option value="amasya">amasya</option><option value="ankara">ankara</option><option value="antalya">antalya</option><option value="ardahan">ardahan</option><option value="artvin">artvin</option><option value="aydın">aydın</option><option value="balıkesir">balıkesir</option><option value="bartın">bartın</option><option value="batman">batman</option><option value="bayburt">bayburt</option><option value="bilecik">bilecik</option><option value="bingöl">bingöl</option><option value="bitlis">bitlis</option><option value="bolu">bolu</option><option value="burdur">burdur</option><option value="bursa">bursa</option><option value="çanakkale">çanakkale</option><option value="çankırı">çankırı</option><option value="çorum">çorum</option><option value="denizli">denizli</option><option value="diyarbakır">diyarbakır</option><option value="düzce">düzce</option><option value="edirne">edirne</option><option value="elazığ">elazığ</option><option value="erzincan">erzincan</option><option value="erzurum">erzurum</option><option value="eskişehir">eskişehir</option><option value="gaziantep">gaziantep</option><option value="giresun">giresun</option><option value="gümüşhane">gümüşhane</option><option value="hakkari">hakkari</option><option value="hatay">hatay</option><option value="iğdır">iğdır</option><option value="isparta">isparta</option><option value="içel">içel</option><option value="istanbul">istanbul</option><option value="izmir">izmir</option><option value="kahramanmaraş">kahramanmaraş</option><option value="karabük">karabük</option><option value="karaman">karaman</option><option value="kars">kars</option><option value="kastamonu">kastamonu</option><option value="kayseri">kayseri</option><option value="kırıkkale">kırıkkale</option><option value="kırklareli">kırklareli</option><option value="kırşehir">kırşehir</option><option value="kilis">kilis</option><option value="kilis">kocaeli</option><option value="konya">konya</option><option value="kütahya">kütahya</option><option value="malatya">malatya</option><option value="manisa">manisa</option><option value="mardin">mardin</option><option value="muğla">muğla</option><option value="muş">muş</option><option value="nevşehir">nevşehir</option><option value="niğde">niğde</option><option value="ordu">ordu</option><option value="osmaniye">osmaniye</option><option value="rize">rize</option><option value="sakarya">sakarya</option><option value="samsun">samsun</option><option value="siirt">siirt</option><option value="sinop">sinop</option><option value="sivas">sivas</option><option value="şanlıurfa">şanlıurfa</option><option value="şırnak">şırnak</option><option value="tekirdağ">tekirdağ</option><option value="tokat">tokat</option><option value="trabzon">trabzon</option><option value="tunceli">tunceli</option><option value="uşak">uşak</option><option value="van">van</option><option value="yalova">yalova</option><option value="yozgat">yozgat</option><option value="zonguldak">zonguldak</option></select></td></tr>
									<tr>
										<td style="width:200px; text-align:right;">&nbsp;</td>
										<td style="width:600px"><input id="registersub" type="submit" class="submit" value="Üye Ol"/></td>
									</tr>
								</table>
							</fieldset>
						</form>
						<?php 
						}
						?>
						</div></div>
					</td>
					<td valign="top" width="400">
						<div id="mainright"><div id="basliklar" style="text-align:left;"></div><input type="hidden" name="page_count" id="page_count" /></div>
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
					<td style="text-align:center;"><input type="submit" name="gonder" value="Gönder" class="login"/></td>
					</tr>
			</table></form><font style="font-size:10px; margin-top:0px;">* Hatasız kul olmaz.</font>
		</div>
		<a class="sikayetmenu" href="#">İstek</a>
	</body>
</html> 
<?php
}
$link = null;
} catch (PDOException $e) {
	echo "Hata: ". $e->getMessage();
	die();
}
?>