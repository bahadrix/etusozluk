<?php
include_once('data/core/db.php');
session_start();

$MEMBER_LOGGED = isset($_SESSION['logged']) && $_SESSION['logged'];

/**
 * Member object
 * @var modelMember
 */
$MEMBER = $MEMBER_LOGGED ? $_SESSION['member'] : null;

// Giriş yapan bir üye varsa bilgilerini debug'da göster
if (DEBUG_MODE) FB::info($MEMBER ? $MEMBER : "Yok","Giriş Yapan üye");

?>