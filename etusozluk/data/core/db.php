<?php
/**
 * Veritabanı bağlantı kurulum ve erişim fonksiyonları dosyası.
 */



/**
 * Veritabanına bağlantı kurarak bu bağlantıya ait PDO objesini döndürür.
 * @return PDO 
 */
function getPDO() {

    $dbhost = "bahadir.me";
    $db = "bahadir_etusozluk";
    $dbuser = "bahadir_esozluk";
    $dbpass = "sozluk1231";
    
    return new PDO("mysql:host=$dbhost;dbname=$db;charset=utf8", $dbuser, $dbpass, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    
}




?>
