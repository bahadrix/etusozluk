<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="tr-tr" lang="tr-tr" dir="ltr" >
<head>
		<title>ETÜ Sözlük</title>    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
		<script type="text/javascript" src="js/jquery.validate.min.js"></script>
		<script type="text/javascript" src="js/pae.js"></script>
		<link type="text/css" href="style/jquery-ui-1.8.18.custom.css" rel="stylesheet" />	
		<link type="text/css" href="style/style.css" rel="stylesheet" />
		<script>
		$(document).ready(function() {
			$("#register").validate({
				rules: {
					nick: {
						required:true,
						minlength:3
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
						minlength:5
					},
					ad : "required",
					soyad : "required",
					gun : "required",
					ay : "required",
					yil : "required"
				},
			messages: {
				ad: " Adınızı girin",
				soyad: " Soyadınızı girin",
				gun : " Gün seçilmesi zorunludur",
				ay : " Ay seçilmesi zorunludur",
				yil : " Yıl seçilmesi zorunludur",
				nick: {
					required: " Kod adı girin",
					minlength: jQuery.format(" En az {0} karakter gerekli")
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
		});
		</script>
	</head>
	<body>
		<div id="logo"><a href="index.htm" style="width: 200px; height: 90px; display: block;" title="Etü Sözlük"></a></div>
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
			</div>
			<li id="uyeol"><a href="javascript:void(0);"><span>Üye Ol/Giriş</span></a></li>
			<li id="bugun"><a href="javascript:void(0);"><span>Bugün</span></a><div id="gizlimenu">Bugün yazılanlar</div></li>
			<li id="dun"><a href="javascript:void(0);"><span>Dün</span></a><div id="gizlimenu">Dün yazılanlar</div></li>
			<li id="rastgele"><a href="javascript:void(0);"><span>Rastgele</span></a><div id="gizlimenu">Rastgele 50 başlık</div></li>
			<li id="hot"><a href="javascript:void(0);"><span>Hot</span></a><div id="gizlimenu">Sıcak sıcak yeni çıktı</div></li>
			<li id="iyuf"><a href="javascript:void(0);"><span>İyuf</span></a><div id="gizlimenu">Yok böyle girdi</div></li>
			<li id="da-fuq"><a href="javascript:void(0);"><span>Da-FAQ</span></a><div id="gizlimenu">Aga bu nedir?</div></li>
		</ul>
		<div id="header"></div>
		<div id="main">
			<table cellspacing="0" cellpadding="0">
				<tr>
					<td valign="top" width="800" style="border-right:1px dotted #666;">
						<div id="mainleft"><div id="entries"><h3 style="text-align:left; margin-left:40px;">Üye Olma Aparatı</h3>
						<ol style="color:#fff">
						<li>18 yaşından küçüklerin etü sözlük üyesi olması yasaktır.</li><li>3 ay boyunca kullanılmayan çaylak hesapları şişip doymayalım diye silinir.</li><li>e-mail adresinize aktivasyon maili gideceği için aktif bir email adresi girmelisiniz.</li><li>sözlüğe üye olan herkesin sözlük kurallarını bildiği farz edilmektedir. bu kurallara üye girişi yaptıktan sonra "panel" bölümünden ulaşabilirsiniz.</li><li>etü sözlük kullanıcılarından aldığı bilgileri sadece sistemin daha iyi işlemesi için kullanır. üçüncü kişilerle paylaşmaz. sadece hukuki durumlarda bu bilgiler gerekli mercilerle paylaşılır.</li><li>gerçek dışı kişisel bilgiler, türkiye cumhuriyeti yasalarına aykırı davranışlar, sözlük altyapısına yönelen saldırılar hesabınızın silinmesine yol açabilir.</li><li>aparatta istenen kişisel bilgiler hesabınızın başına bir şey gelmesi durumunda ilgili hesabın size ait olduğunu kanıtlamak için kullanılır.</li>
						</ol>
						<p style="color:#fff; margin-left:40px;">aşağıdaki formu doldurarak bu şartları kabul etmiş olursunuz.</p><br />
						<form action="uyeol.php?ol=true" id="register" method="post">
							<fieldset style="border:1px solid #ccc; -moz-border-radius: 10px; -webkit-border-radius: 10px; -khtml-border-radius: 10px; border-radius: 10px;">
								<legend style="font-family: Arial, sans-serif; font-size: 1.3em; font-weight:bold; color:#fff;">&nbsp;artık aparata geçelim&nbsp;</legend>
								<table style="width:800px, border:1px solid white; font-size:8pt">
									<tr>
										<td style="width:200px; text-align:right;">Nick:<em></em></td>
										<td style="width:600px;"><input id="nick" name="nick" size="40" maxlength="40" type="text" /></td>
									</tr>
									<tr>
										<td style="width:200px; text-align:right;">Şifre:</td>
										<td style="width:600px;"><input type="password" id="sifre" name="sifre" size="40" maxlength="20" /></td>
									</tr>
									<tr>
										<td style="width:200px; text-align:right;">Şifre Tekrar:</td>
										<td style="width:600px;"><input type="password" id="sifret" name="sifret" size="40" maxlength="20" /></td>
									</tr>
									<tr>
										<td style="width:200px; text-align:right;">Email:</td>
										<td style="width:600px;"><input type="text" id="email" name="email" size="40" maxlength="50"/></td>
									</tr>
									<tr>
										<td style="width:200px; text-align:right;">Ad:</td>
										<td style="width:600px;"><input type="text" id="ad" name="ad" size="40" maxlength="30" /></td>
									</tr>
									<tr>
										<td style="width:200px; text-align:right;">Soyad:</td>
										<td style="width:600px;"><input type="text" id="soyad" name="soyad" size="40" maxlength="30" /></td>
									</tr>
									<tr>
										<td style="width:200px; text-align:right;">Cinsiyet:</td>
										<td style="width:600px;"><select name="cinsiyet" id="cinsiyet" style="background-color:#222;border:1px solid #333;color:#fecc00;"><option></option><option value="erkek">erkek</option><option value="kadın">kadın</option></select></td>
									</tr>
									<tr>
										<td style="width:200px; text-align:right;">Doğum Tarihi:</td>
										<td style="width:600px"><select name="gun" id="gun" style="background-color:#222;border:1px solid #333;color:#fecc00;"><option value=""></option><option value='1'>1</option><option value='2'>2</option><option value='3'>3</option><option value='4'>4</option><option value='5'>5</option><option value='6'>6</option><option value='7'>7</option><option value='8'>8</option><option value='9'>9</option><option value='10'>10</option><option value='11'>11</option><option value='12'>12</option><option value='13'>13</option><option value='14'>14</option><option value='15'>15</option><option value='16'>16</option><option value='17'>17</option><option value='18'>18</option><option value='19'>19</option><option value='20'>20</option><option value='21'>21</option><option value='22'>22</option><option value='23'>23</option><option value='24'>24</option><option value='25'>25</option><option value='26'>26</option><option value='27'>27</option><option value='28'>28</option><option value='29'>29</option><option value='30'>30</option><option value='31'>31</option></select><select id="ay" name="ay" style="background-color:#222;border:1px solid #333;color:#fecc00;"><option value=""></option><option value='1'>ocak</option><option value='2'>şubat</option><option value='3'>mart</option><option value='4'>nisan</option><option value='5'>mayıs</option><option value='6'>haziran</option><option value='7'>temmuz</option><option value='8'>ağustos</option><option value='9'>eylül</option><option value='10'>ekim</option><option value='11'>kasım</option><option value='12'>aralık</option></select><select id="yil" name="yil" style="background-color:#222;border:1px solid #333;color:#fecc00;"><option value="">php ile min 18 yaş 1993-1950'ye kadar</option><option value="1993">1993</option></select></td>
									</tr>
									<tr>
										<td style="width:200px; text-align:right;">Ülke:</td>
										<td style="width:600px"><input type="text" name="ulke" size="40" maxlength="20"/></td>
									</tr>
									<tr>
										<td style="width:200px; text-align:right;">Şehir:</td>
										<td style="width:600px;"><select name="sehir" style="background-color:#222;border:1px solid #333;color:#fecc00;"><option value=""></option><option value="Adana">Adana</option><option value="Adıyaman">Adıyaman</option><option value="Afyon">Afyon</option><option value="Ağrı">Ağrı</option><option value="Aksaray">Aksaray</option><option value="Amasya">Amasya</option><option value="Ankara">Ankara</option><option value="Antalya">Antalya</option><option value="Ardahan">Ardahan</option><option value="Artvin">Artvin</option><option value="Aydın">Aydın</option><option value="Balıkesir">Balıkesir</option><option value="Bartın">Bartın</option><option value="Batman">Batman</option><option value="Bayburt">Bayburt</option><option value="Bilecik">Bilecik</option><option value="Bingöl">Bingöl</option><option value="Bitlis">Bitlis</option><option value="Bolu">Bolu</option><option value="Burdur">Burdur</option><option value="Bursa">Bursa</option><option value="Çanakkale">Çanakkale</option><option value="Çankırı">Çankırı</option><option value="Çorum">Çorum</option><option value="Denizli">Denizli</option><option value="Diyarbakır">Diyarbakır</option><option value="Düzce">Düzce</option><option value="Edirne">Edirne</option><option value="Elazığ">Elazığ</option><option value="Erzincan">Erzincan</option><option value="Erzurum">Erzurum</option><option value="Eskişehir">Eskişehir</option><option value="Gaziantep">Gaziantep</option><option value="Giresun">Giresun</option><option value="Gümüşhane">Gümüşhane</option><option value="Hakkari">Hakkari</option><option value="Hatay">Hatay</option><option value="Iğdır">Iğdır</option><option value="Isparta">Isparta</option><option value="İçel">İçel</option><option value="İstanbul">İstanbul</option><option value="İzmir">İzmir</option><option value="Kahramanmaraş">Kahramanmaraş</option><option value="Karabük">Karabük</option><option value="Karaman">Karaman</option><option value="Kars">Kars</option><option value="Kastamonu">Kastamonu</option><option value="Kayseri">Kayseri</option><option value="Kırıkkale">Kırıkkale</option><option value="Kırklareli">Kırklareli</option><option value="Kırşehir">Kırşehir</option><option value="Kilis">Kilis</option><option value="Kilis">Kocaeli</option><option value="Konya">Konya</option><option value="Kütahya">Kütahya</option><option value="Malatya">Malatya</option><option value="Manisa">Manisa</option><option value="Mardin">Mardin</option><option value="Muğla">Muğla</option><option value="Muş">Muş</option><option value="Nevşehir">Nevşehir</option><option value="Niğde">Niğde</option><option value="Ordu">Ordu</option><option value="Osmaniye">Osmaniye</option><option value="Rize">Rize</option><option value="Sakarya">Sakarya</option><option value="Samsun">Samsun</option><option value="Siirt">Siirt</option><option value="Sinop">Sinop</option><option value="Sivas">Sivas</option><option value="Şanlıurfa">Şanlıurfa</option><option value="Şırnak">Şırnak</option><option value="Tekirdağ">Tekirdağ</option><option value="Tokat">Tokat</option><option value="Trabzon">Trabzon</option><option value="Tunceli">Tunceli</option><option value="Uşak">Uşak</option><option value="Van">Van</option><option value="Yalova">Yalova</option><option value="Yozgat">Yozgat</option><option value="Zonguldak">Zonguldak</option></select></td>
									</tr>
									<tr>
										<td style="width:200px; text-align:right;">&nbsp;</td>
										<td style="width:600px"><input type="submit" class="submit" value="Üye Ol"/></td>
									</tr>
								</table>
							</fieldset>
						</form>
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
					<td style="text-align:center;"><input type="submit" name="gonder" value="Gönder" class="login" /></td>
					</tr>
			</table></form><font style="font-size:10px; margin-top:0px;">* Hatasız kul olmaz.</font>
		</div>
		<a class="aramenu" href="#">&nbsp;&nbsp;Ara&nbsp;</a>
		<a class="sikayetmenu" href="#">İstek</a>
	</body>
</html> 