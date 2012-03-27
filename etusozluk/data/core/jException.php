<?php
/**
 * Normal Exception objesini json çıktısı verebilecek şekilde genişletir.
 * @author Bahadir
 *
 */

class jException extends Exception {
	/**
	 * Exception'a ait code, message, previous elemanlarını jSON objesi olarak döndürür
	 * @example {"code":1003,"message":"Boyle sifre mi olur kardesim!?","previous":null}
	 */
	public function encode() {
		
		$jSONError['code']		= $this->getCode();
		$jSONError['message']	= $this->getMessage();
		$jSONError['previous']	= $this->getPrevious();
		
		// Hatayı json objesi olarak bas
		return json_encode($jSONError);
		
	}
	
	
}

?>