<?php
/**
* Ortak kullanılıcak fonksiyonlar
* @girdiControl entry gösterilicekken çağırılıcak.
* 	spoiler kontrolü eklendi.
*	TODO: + direkt yazılan linkleri de link haline çevir.
*		  + eğer domain içinde bir sayfaya link verilirse (bkz: )'a çevir.
*		  + ek kontroller eklenebilir.
* @version 0.1
*
*/
	function girdiControl($girdi) {
		$ygirdi = $girdi;
		$ygirdi = preg_replace('/&/', '&amp;',$ygirdi);
		$ygirdi = preg_replace('/</', '&lt;',$ygirdi);
		$ygirdi = preg_replace('/>/', '&gt;',$ygirdi);
		$ygirdi = preg_replace('/"/', '&quot;',$ygirdi);
		$ygirdi = preg_replace('/\'/', '&#39;',$ygirdi);
		$ygirdi = preg_replace('/\t/',' ',$ygirdi);
		$ygirdi = nl2br($ygirdi);
		$ygirdi = preg_replace('/(?:<br \/>\s*){2,}/', "<br /><br />", $ygirdi);
		$ygirdi = preg_replace('/\s\s+/',' ',$ygirdi);
		$b = preg_match_all('/\(bkz:\s?([a-z0-9ıüçöğş\^!$#€£~*\-_%!(&amp;)=?\/+(&quot;)(&#39;):;\.@ ]+)\)/', $ygirdi, $sonuc);
		if ($b) {
			$bkzs = $sonuc[0];
			for ($i=0;$i<$b;$i++) {
				$ygirdi = str_replace($bkzs[$i],'(bkz: <a href="goster.php?t='.rawurlencode(trim($sonuc[1][$i])).'">'.trim($sonuc[1][$i]).'</a>)',$ygirdi);
			}
		}
		$g = preg_match_all('/`\s?([a-z0-9ıüçöğş\^!$#€£~*\-_%!(&amp;)=?\/+(&quot;)(&#39;):;\.@ ]+)\s?`/', $ygirdi, $sonuc);
		if ($g) {
			$gbkzs = $sonuc[0];
			for ($i=0;$i<$g;$i++) {
				$ygirdi = str_replace($gbkzs[$i],'`<a href="goster.php?t='.rawurlencode(trim($sonuc[1][$i])).'">'.trim($sonuc[1][$i]).'</a>`',$ygirdi);
			}
		}
		//url kontrol, gözden geçirilmesi gerek
		$u = preg_match_all('/\[(https?|ftp):\/\/([a-z0-9\/?=%\-_!\.]+)\s?([a-z0-9ıüçöğş\^!$#€£~*_%!\-(&amp;)=?\/+(&quot;)(&#39;):;\.@ ]+)?\]/',$ygirdi,$sonuc);
		if ($u) {
			$urls = $sonuc[0];
			for ($i=0;$i<$u;$i++) {
				$url = trim($sonuc[1][$i]).'://'.trim($sonuc[2][$i]);
				if (!$sonuc[3][$i]=='') 
					$kelime = trim($sonuc[3][$i]);
				else
					$kelime = substr(trim($url),0,25).'...';
				if (filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED)) 
					$ygirdi = str_replace($urls[$i], '<a href="'.$url.'" target="_blank" class="url" rel="nofollow">'.$kelime.'</a> ',$ygirdi);
				else
					$ygirdi = str_replace($urls[$i],"$url " .trim($sonuc[3][$i])." ",$ygirdi);
			}
		}
		$s = preg_match_all('/\[(spoiler)\](\s|<br \/>)*([a-z0-9ıüçöğş\^!\'\"$#€£~*_%!&=?<>\/+\(\)\[\]\{\}\-(&amp;)(&lt;)(&gt;)(&quot;)(&#39;):;\.`@ ]+)(\s|<br \/>)*\[(\/spoiler)\]/',$ygirdi,$sonuc);
		if ($s) {
			$spoyler = $sonuc[0];
			for ($i=0;$i<$s;$i++) {
				$ygirdi = str_replace($spoyler[$i],'<div id="spoyler_container"><button title="şpoyleri göstermek/kapatmak için tıklayın." class="spoyl">spoiler</button><div id="spoyler">'.trim($sonuc[3][$i]).'</div></div>',$ygirdi);
			}
		}
		return $ygirdi;
	}
?>