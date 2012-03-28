<?php include 'core/db.php';

$page = $_GET['page'];
$gun = $_GET['g'];
$per_page = 3; // Per page records


$date_condition = $gun == 'gun' ? 
				getCondByDate($gun, 'titles.tarih', $_GET['gun'], $_GET['ay'], $_GET['yil'] ) : 
				getCondByDate($gun, 'titles.tarih');


$DBO = getPDO();
$titles = $DBO->query("SELECT titles.T_ID as id, titles.Baslik as baslik FROM titles LIMIT 50");


echo json_encode($titles->fetchAll(PDO::FETCH_ASSOC));




/*


$JSON["items"] = array();

if ($gun == "bugun") {
$arr = array();
if ($page==1) {
  $arr = array("id" => "1234", "baslik" => "ilk sayfa başlıkları", "count" => "10");
  array_push($JSON["items"],$arr);
  array_push($JSON["items"],$arr);
  array_push($JSON["items"],$arr);
  echo json_encode($JSON);
}
if ($page==2) {
  $arr = array("id" => "1324", "baslik" => "ikinci sayfa başlıkları", "count" => "2");
  array_push($JSON["items"],$arr);
  array_push($JSON["items"],$arr);
  array_push($JSON["items"],$arr);
  echo json_encode($JSON);
}
}
else if ($gun == "dun") {
$arr = array();
if ($page==1) {
  $arr = array("id" => "1234", "baslik" => "dün ilk sayfa başlıkları", "count" => "10");
  array_push($JSON["items"],$arr);
  array_push($JSON["items"],$arr);
  array_push($JSON["items"],$arr);
  echo json_encode($JSON);
}
if ($page==2) {
  $arr = array("id" => "1324", "baslik" => "dün ikinci sayfa başlıkları", "count" => "2");
  array_push($JSON["items"],$arr);
  array_push($JSON["items"],$arr);
  array_push($JSON["items"],$arr);
  echo json_encode($JSON);
}
if ($page==3) {
  $arr = array("id" => "1324", "baslik" => "dün üçüncü sayfa başlıkları", "count" => "1");
  array_push($JSON["items"],$arr);
  array_push($JSON["items"],$arr);
  echo json_encode($JSON);
}
}
*/
?>