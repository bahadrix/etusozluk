<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head><body>
<?php
$msg="";
if (isset($_POST['yid'])) {
  $yid = $_POST['yid'];
  if ($yid == "1234") {
	$msg .= "<a href=yazar.php?nick=veritas>veritas</a> <br />";
	$msg .= "Mesaj Gönder <br />"; //giriş yapıldıysa
	$msg .= "Son Giriş: Tarih <br />";
	$msg .= "Son Girdi <br />";
  }
  else {
	$msg .= "<a href=yazar.php?nick=tahrik+eden+cisim>tahrik eden cisim</a> <br />";
	$msg .= "Mesaj Gönder <br />"; //giriş yapıldıysa
	$msg .= "Son Giriş: Tarih <br />";
	$msg .= "Son Girdi <br />";
  }
  }
else {
  $msg .= "Hatalı Sorgu";
  }
echo $msg;
echo '</body></html>';
?>
</body></html>