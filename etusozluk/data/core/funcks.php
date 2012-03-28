<?php

function getCondByDate($tip, $date_field, $gun = null, $ay= null, $yil=null) {
	try {
		switch ($tip) {
			case 'dun':
				$date_condition = "$date_field BETWEEN ADDDATE(CURDATE(), INTERVAL - 1 DAY) AND CURDATE()";
				break;
			case 'gun':
				// parametreler girilmiş mi?
				if (!$gun || !$ay || !$yil)
					throw new jException("Tarih parametresi gerekiyor", 2001);
				// dogru mu girilmis peki?
				if (!checkdate($gun, $ay, $yil)) 
					throw new jException("Geçersiz tarih", 2002);
				// tarihi olustur
				$tarih = "$yil-$ay-$gun";
				// yardir
				$date_condition = "$date_field BETWEEN '$tarih'  AND ADDDATE('$tarih', INTERVAL  1 DAY)";
				break;
			case 'bugun':
				$date_condition = "$date_field BETWEEN CURDATE() AND ADDDATE(CURDATE(), INTERVAL  1 DAY)";
				break;
			default:
				throw new jException("Bilinmeyen tarih tipi", 2003);
		}
	} catch (jException $e) {
		echo $e->encode();
		if (DEBUG_MODE) FB::error($e);
	}
	
	return $date_condition;
}
?>