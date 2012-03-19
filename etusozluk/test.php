<?php
include 'data/core/db.php';
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$DBO = getPDO();

$ST = $DBO->query("SELECT * FROM entries LIMIT 20");


print_r(json_encode($ST->fetchAll(PDO::FETCH_ASSOC)));
?>
