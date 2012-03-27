<?php
/**
 * Dip-switches :)
 */
define ("DEBUG_MODE", true);

/**
 * INI
 */
class DBCONN {
    public static $dbhost = "localhost";
    public static $db = "etusozluk";
    public static $dbuser = "root";
    public static $dbpass = "";
}

/**
 * Includes
 */
include 'firephp/fb.php';
ob_start(); //olmayýnca exception atýyor.
?>
