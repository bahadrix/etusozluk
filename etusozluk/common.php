<?php
include_once('data/core/db.php');
session_start();

$MEMBER_LOGGED = isset($_SESSION['logged']) && $_SESSION['logged'];

/**
 * Member object
 * @var modelMember
 */
$MEMBER = $MEMBER_LOGGED ? $_SESSION['member'] : null;

if (DEBUG_MODE) FB::info($MEMBER,"Giriş Yapan üye");

?>