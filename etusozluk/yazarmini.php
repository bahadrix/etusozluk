<?php
include_once('common.php');
include_once('funct.php');
$msg="";
if (!empty($_POST['yid'])) {
  $yid = $_POST['yid'];
  $link = getPDO();
  $st = $link -> prepare('SELECT U_ID,Nick,Uyelik_Tarihi,Sehir,Son_Online FROM members where U_ID=:uid');
  $st -> bindValue(":uid",$yid);
  $st -> execute();
  if ($st->rowCount() == 1) {
	$sonuc = $st -> fetch(PDO::FETCH_ASSOC);
    $msg .= '<a href="yazar.php?y='.yazarBoslukSil($sonuc['Nick']).'" style="color:#0081c6; font-weight:bold;">'.$sonuc['Nick'].'</a><hr />';
	if ($MEMBER_LOGGED && $_SESSION['member']->U_ID != $sonuc['U_ID'])
	  $msg .= '<a href="mesaj.php?y='.yazarBoslukSil($sonuc['Nick']).'">Mesaj Gönder</a><br />';
	$msg .= '<a href="baslik.php?y='.yazarBoslukSil($sonuc['Nick']).'">Son Yazdıkları</a><br />'; //baslik.php'ye burayı ekleriz sonra.
	$msg .= 'Son Giriş: '.substr($sonuc['Son_Online'],0,10).'<br />';
	$msg .= 'Üyelik Tarihi: '.substr($sonuc['Uyelik_Tarihi'],0,10).'<br />';
  } else
      $msg .= "Geçersiz Üye"; //buraya düşmemesi lazım.
} else
    $msg .= "Hatalı Sorgu";
echo $msg;
?>