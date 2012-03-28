<?php
/**
 * Mother Model!
 * Bir PDO Statement'ını doğrudan object almaya yarar.
 * Çok lezzetli oldu, bundan da bir proje olur ;)
 * 
 * @author Bahadir
 * @version 0.5
 *
 */

class motherModel {

	/**
	 * 
	 * @param PDOStatement $statement
	 */
	function __construct($statement) {
		$statement->setFetchMode(PDO::FETCH_INTO,$this);
		return $statement->fetch(PDO::FETCH_INTO); 
		
	}
}

class modelMember extends motherModel {
	/**
	 * User ID, primary key
	 * @var integer
	 */
	public $U_ID;
	/**
	 * Kullanıcı adı
	 * @var string
	 */
	public $Nick;
	/**
	 * Kullanıcı adı ile gerçek şifrenin MD5 ile haşlanmış hali
	 * @var string
	 */
	public $Sifre;
	/**
	 * Ad
	 * @var string
	 */
	public $Ad;
	/**
	 * Soyad
	 * @var string
	 */
	public $Soyad;
	/**
	 * E-Posta adresi
	 * @var string
	 */
	public $Email;
	/**
	 * Cinsiyet [KADIN,ERKEK]
	 * @var string
	 */
	public $Cinsiyet;
	/**
	 * Doğum tarihi
	 * @var unknown_type
	 */
	public $D_Tarihi;
	/**
	 * Üyelik tarihi
	 * @var date
	 */
	public $Uyelik_Tarihi;
	/**
	 * Üyenin son giriş yaptığı tarih
	 * @var datetime
	 */
	public $Son_Online;
	/**
	 * Bulunduğu şehir
	 * @var string
	 */
	public $Sehir;
	/**
	 * Üye aktif mi?
	 * @var boolean
	 */
	public $Aktif;
	
	
	
}

?>