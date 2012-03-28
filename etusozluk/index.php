<?php include 'common.php'; 
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
			<div id="loginboxx">
				<p class="lgbaslik">Nick</p><hr class="lg"/><p style="text-align:left; padding-left:50px; margin:0;"><a href="ben.php">Karargah</a><br /><a href="mesaj.php">Mesajlar</a><br /><a href="getir.php?mode=ark">Arkadaşlar</a><br /><a href="getir.php?mode=yeni">Yeni</a><br /><a href="login.php?mode=cikis">Çıkış</a></p>
			</div>
			<?php if (!$MEMBER_LOGGED) { ?><li id="uyeol"><a href="javascript:void(0);"><span>Üye Ol/Giriş</span></a></li><?php }?>
			<li id="bugun"><a href="javascript:void(0);"><span>Bugün</span></a><div id="gizlimenu">Bugün yazılanlar</div></li>
			<li id="dun"><a href="javascript:void(0);"><span>Dün</span></a><div id="gizlimenu">Dün yazılanlar</div></li>
			<li id="rastgele"><a href="javascript:void(0);"><span>Rastgele</span></a><div id="gizlimenu">Rastgele 50 başlık</div></li>
			<li id="hot"><a href="javascript:void(0);"><span>Hot</span></a><div id="gizlimenu">Sıcak sıcak yeni çıktı</div></li>
			<li id="iyuf"><a href="javascript:void(0);"><span>İyuf</span></a><div id="gizlimenu">Yok böyle girdi</div></li>
			<li id="da-ara"><a href="javascript:void(0);" class="aramenu"><span>Ara</span></a></li>
			<?php if ($MEMBER_LOGGED) { ?><li id="ben"><a href="javascript:void(0);"><span>Ben</span></a></li><?php }?>
		</ul>
		<div id="header"><form name="baslikara" action="ara.php?op=baslikara"><input type="text" value="Başlık Getir" id="titlea" name="title" size="30" maxlength="70" />&nbsp;<input type="submit" value="ara" /></form></div>
		<div id="main">
			<table cellspacing=0 cellpadding=0>
				<tr>
					<td valign=top width=800 style="border-right:1px dotted #666;">
						<div id="mainleft"><div id="entries"><h3 style="text-align:left; margin-left:40px;">Random Entry Başlığı</h3><ol class=girdiler><li class=girdi value="1">sivas katliam'ından, davanın zamanaşımına uğramasından; insanların suçları ispatlanmadan, hatta suçlarının bile ne olduğunu tam olarak ortaya konulamadan bir yıl hapiste tutulmalarından rahatsız olmayıp "birbiri ile alakasız konuların bir araya getirilmesinden" rahatsız olmuş bir adamın yazısı. 

aynı adamın mantığına göre bir yerlerde bir yanlış varsa onu düzeltmek sadece o dönemde işbaşında olanların işi. yani sivas katliamında erdal inönü, demirel, dyp, chp kılını kıpırdatmadıysa bu sadece onların suçudur, bu olay sadece onların sorumluluğundadır. 

onlar duyarsızdı, suçluydu zarttı zurttu; e sen yap görevini, sen sağla adaleti. yok "onlar adil değildi, ben niye adil olayım, bana niye adil ol diyorsun" diye sızlanıyor adam. yaşananların vahşilik, suç vs. olduğunu düşünmesine rağmen başbakanın çıkıp "sivas davası milletimize hayırlı olsun" demesinin sebebi budur. böyle dediği için başbakana bir şey denilmemelidir bu adamın mantığına göre.

sonuç olarak ilkokul çocuğu düzeyinde bir mantığa sahip olan bir adamın yazdığı, yine o mantığa sahip olanların da ayar sandığı yazıdır.<div class="yazarinfo">(<a href="goster.php?baslik=tahrik+eden+cisim" id="yazar" rel="12345">tahrik eden cisim</a>, 17.03.2012 12:47)<div class="ymore"><a href="goster.php?eid=1234" id="entryid">@1234</a>&nbsp;<button type="button" value="entry id" class="minib">iyuf</button>&nbsp;<button type="button" value="entry id" class="minib">ı ıh</button></div><div id="yazarmini"></div></div></li><br /></ol>
						<br /><div style="text-align:center;"><button type="button" value="Başlık ID">Hepsi Gelsin</button>
						<div style="text-align:left; padding-top:10px; padding-left:25px;">"Random Başlık" hakkında söylemek istediklerim var diyorsan hadi durma:
						<form action="ekle.php" method="post" id="yenigirdi"><input type="hidden" name="t" value="random başlık" /><div id="butonlar" style="text-align:left; width:100%; padding-top:10px;"><input type="button" id="bkz" value="(bkz: )" class="ebut" /><input type="button" id="gizlibkz" value="``" class="ebut"/><input type="button" id="spoiler" value="spoiler" class="ebut"/><input type="button" value="link" onclick="var a=prompt('link: (başında http:// olmalı)', 'http://');if(isURL(a))$('#entrytextarea').tae('url',a);" class="ebut"/></div><textarea id="entrytextarea" rows="10" cols="105" class="ygirdi"></textarea><input type="submit" value="böyle olur" class="ebut" /><input type="submit" value="bunu sonra gönderirim" class="ebut" name="kaydet" /></form></div></div></div></div>
					</td>
					<td valign=top width=400>
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
					<td style="text-align:center;"><input type="submit" name="gonder" value="Gönder" class="login"></td>
					</tr>
			</table></form><font style="font-size:10px; margin-top:0px;">* Hatasız kul olmaz.</font>
		</div>
		<a class="sikayetmenu" href="#">İstek</a>
		<a href="javascript:void(0);" id="top-link">Başa Zıpla</a>
	</body>
</html> 