<?php
/**
 * Login ve logout işlemlerini gerçekleştirir.
 * Login de hata olursa (yanlış şifre vb) jException'dan jSON objesi basar.
 * Giriş başarılı olursa "OK" döndürür. Bu durumda mevcut sayfanın refresh edilmesi yeterlidir.
 * 
 * Bi ara brute force önlemi alınmalı
 * 
 * @version 0.1 
 * @throws jException
 */

include 'data/core/db.php';

try {	
	if (isset($_REQUEST['login'])) { // Login talep edilmiş
		// Parametre kontrol
		if (!isset($_REQUEST['username']) || !isset($_REQUEST['password'])) 
			throw new jException("Kullanıcı adı veya parola girilmemiş",1001);
		
		$user =& $_REQUEST['username'];
		$pass =& $_REQUEST['password'];
		
		$hash = md5($user . $pass);
		
		// Canavarı al
		$DO = getPDO();
		
		$st = $DO->prepare("SELECT * FROM members WHERE Nick = :nick");
		$st->bindValue(":nick", $user);
		$st->execute();
		
		if (!$st->rowCount()) // Böyle bir kullanıcı var mı?
			throw new jException("Böyle bir kullanıcı adı bulunamadı",1002);

		// Summon da creature!
		$member = new modelMember($st);
			
		if ($member->Sifre != $hash) // Şifre kontrol
			throw new jException("Yanlış şifre!", 1003);
		
		// Şifre de doğru!
		
		// Son Online'ı update et.
		$u = $DO->prepare("UPDATE members SET Son_Online = NOW() WHERE U_ID = :uid");
		$u->bindValue(":uid",$member->U_ID);
		$u->execute();
		
		session_cache_expire(30);
		session_start();
		$_SESSION['logged'] = true;
		$_SESSION['member'] = $member;	
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			$JO = array("durum"=>"OK","nick"=>" ".$member->Nick." ");
			echo json_encode($JO);
		}
		else
			header("Location: index.php");
		
	}

} catch (jException $e) {

	
	echo $e->encode();
	
	if (DEBUG_MODE) FB::error($e);
	
}
?>