<?php
/**
 * Login ve logout işlemlerini gerçekleştirir.
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
		
		// Şifrede doğru!
		
		/*
		 * Burayı da yazcam ama uykum geldi! Devam edicem..
		 */	
		
		
	}

} catch (jException $e) {

	
	echo $e->encode();
	
	if (DEBUG_MODE) FB::error($e);
	
}

?>