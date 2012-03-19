<?php include 'config.php';
/**
 * Veritabanı bağlantı kurulum ve erişim fonksiyonları dosyası.
 */

/**
 * Firebug kullanimi icin
 */
include 'loggedPDO.php';



/**
 * Veritabanına bağlantı kurarak bu bağlantıya ait PDO objesini döndürür.
 * @return PDO 
 */
function getPDO() {

    $dbhost = DBCONN::$dbhost;
    $db = DBCONN::$db;
    $dbuser = DBCONN::$dbuser;
    $dbpass = DBCONN::$dbpass;
    
    
    if (DEBUG_MODE) {
        FB::log("AÇIK", "Veritabanı Debug Modu:");
        return new LoggedPDO( "mysql:host=$dbhost;dbname=$db;charset=utf8", $dbuser, $dbpass, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        
    } else {
        FB::log("Kapalı", "Veritabanı Debug Modu:");
        return new PDO( "mysql:host=$dbhost;dbname=$db;charset=utf8", $dbuser, $dbpass, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT));
    }
    
}




?>
