<?php include_once 'config.php';
/**
 * Veritabanı bağlantı kurulum ve erişim fonksiyonları dosyası.
 */

/**
 * Firebug kullanimi icin
 */
include 'loggedPDO.php';

/**
 * Basit model sınıfları
 */
include 'models.php';

/**
 * Veritabanına bağlantı kurarak bu bağlantıya ait PDO objesini döndürür.
 * Debugged paramteresi true yada false olursa global setting'e override eder
 * 
 * @param debugged true,false,null
 * @return PDO 
 */
function getPDO($debugged = null) {

    $dbhost = DBCONN::$dbhost;
    $db = DBCONN::$db;
    $dbuser = DBCONN::$dbuser;
    $dbpass = DBCONN::$dbpass;
    
    // Debug override edilmemişse global ayari kullan
    if ($debugged === null ? DEBUG_MODE : $debugged) {
        FB::log("AÇIK", "Veritabanı Debug Modu:");
        return new LoggedPDO( "mysql:host=$dbhost;dbname=$db;charset=utf8", $dbuser, $dbpass, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    } else {
        FB::log("Kapalı", "Veritabanı Debug Modu:");
        return new PDO( "mysql:host=$dbhost;dbname=$db;charset=utf8", $dbuser, $dbpass, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT));
    }   
}

/**
 * Veritabanında bulunan bir tablodaki belirtilen $duplicated_field için aynı veriyi taşıyan satırlardan ilki dışında hepsi silinir.
 * @param string $table
 * @param string $duplicated_field
 * @param string $primary_key
 */
function removeDuplicates($table, $duplicated_field, $primary_key) {
	
	$DBO = getPDO();
	$st = $DBO->query("SELECT $primary_key, $duplicated_field FROM $table ORDER BY $duplicated_field");
	$rows = $st->fetchAll(PDO::FETCH_ASSOC);
	
	$DBO->beginTransaction();
	for ($i=1; $i < $st->rowCount(); $i++) {
		
		if ($rows[$i][$duplicated_field] == $rows[$i - 1][$duplicated_field]) {
			
			$DBO->exec("DELETE FROM $table WHERE $primary_key = '"  . $rows[$i][$primary_key] . "'");
		}
		
	}
	$DBO->commit();
}


?>
