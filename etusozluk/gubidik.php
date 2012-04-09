<?php
include_once 'common.php';
FB::info("PORTLET IN: Gubidik");

if ($MEMBER_LOGGED) { //Logged state of portlet
	$db = getPDO();
	
	/**
	 * Arkadaşlık potansiyeli = Beğendiğimiz aynı entry sayıysı - Beğenmediğimiz aynı entry sayısı 
	 * @var Query
	 */
	$query = "
		#Kimle arkadas olsam:
		SELECT
			Nick,
			Sum(AyniSevgi) AS AyniSevgi
		FROM
			(
				(
					SELECT
						seven2.Nick,
						Count(seven2.Nick) AS AyniSevgi
					FROM
						members AS seven1
					INNER JOIN memberlikes AS sevgi1 ON seven1.U_ID = sevgi1.U_ID,
					members AS seven2
					INNER JOIN memberlikes AS sevgi2 ON seven2.U_ID = sevgi2.U_ID
					WHERE
						sevgi1.E_ID = sevgi2.E_ID
					AND sevgi1.Begen = 1
					AND sevgi2.Begen = 1
					AND seven1.U_ID <> seven2.U_ID
					AND seven1.U_ID = $MEMBER->U_ID 
					GROUP BY
						seven2.Nick
					ORDER BY
						AyniSevgi DESC
				)
				UNION
				(
					SELECT
						seven2.Nick,
						Count(seven2.Nick) AS AyniSevgi
					FROM
						members AS seven1
					INNER JOIN memberlikes AS sevgi1 ON seven1.U_ID = sevgi1.U_ID,
					members AS seven2
					INNER JOIN memberlikes AS sevgi2 ON seven2.U_ID = sevgi2.U_ID
					WHERE
						sevgi1.E_ID = sevgi2.E_ID
					AND sevgi1.Begen = 0
					AND sevgi2.Begen = 0
					AND seven1.U_ID <> seven2.U_ID
					AND seven1.U_ID = $MEMBER->U_ID
					GROUP BY
						seven2.Nick
					ORDER BY
					AyniSevgi DESC
				)
			) sev
		GROUP BY
			Nick
		ORDER BY
			AyniSevgi DESC
		LIMIT 5";
	
	$arkadas_onerileri = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);
	
	/**
	 * Beğnilen entrylerim içinde en fazla entry beğenenimler.. Anlatamadım :)
	 * Örnek vereyim: 10 entryim var. Ali 8 tanesini beğenmiş, Metin 3 tanesini.
	 * @var Query
	 */
	$query = "
		#İlgi Duyanlar:	
		SELECT
		Count(sevenler.U_ID) AS sevgi,
		sevenler.Nick
		FROM
		memberlikes
		INNER JOIN entries ON entries.E_ID = memberlikes.E_ID
		INNER JOIN members AS sevenler ON sevenler.U_ID = memberlikes.U_ID
		WHERE
		entries.U_ID = $MEMBER->U_ID 
		AND memberlikes.Begen = 1
		GROUP BY
		sevenler.U_ID
		ORDER BY
		sevgi DESC
		LIMIT 5
	";
	
	$begenenler = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);
	
	$query = "
	#Giciklar:
	SELECT
	Count(sevenler.U_ID) AS sevgi,
	sevenler.Nick
	FROM
	memberlikes
	INNER JOIN entries ON entries.E_ID = memberlikes.E_ID
	INNER JOIN members AS sevenler ON sevenler.U_ID = memberlikes.U_ID
	WHERE
	entries.U_ID = $MEMBER->U_ID
	AND memberlikes.Begen = 0
	GROUP BY
	sevenler.U_ID
	ORDER BY
	sevgi DESC
	LIMIT 5
	";
	
	$giciklar = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);
	
	$query = "
	#EnBegendiklerim:
	SELECT
	begenilen.Nick,
	Count(begenilen.Sehir) AS begeni
	FROM
	memberlikes
	INNER JOIN entries ON memberlikes.E_ID = entries.E_ID
	INNER JOIN members AS begenilen ON entries.U_ID = begenilen.U_ID
	WHERE
	memberlikes.U_ID = $MEMBER->U_ID
	GROUP BY
	begenilen.U_ID,
	begenilen.Nick
	ORDER BY
	begeni DESC
	LIMIT 5
	";
	
	$begendiklerim = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);
	
	
	/**
	 * En beğendiği yazarın ben olduğum üyeler.
	 * Beğendiği farklı yazar sayısı 2 den fazla olan üyeler sayılıyor.
	 * @var Query
	 */
	$query = "
	#Fanlarım:
SELECT
	*
FROM
	(
		SELECT
			Count(entries.E_ID) AS Begeni,
			begenilen.Nick AS begenilen_adam,
			seven.Nick AS begenen_uye,
			seven.U_ID AS begenen_ID,
			begenilen.U_ID AS begenilen_ID
		FROM
			memberlikes
		INNER JOIN entries ON memberlikes.E_ID = entries.E_ID
		INNER JOIN members AS seven ON seven.U_ID = memberlikes.U_ID
		INNER JOIN members AS begenilen ON entries.U_ID = begenilen.U_ID
		WHERE
			memberlikes.Begen = 1
		GROUP BY
			entries.U_ID,
			seven.U_ID
		HAVING
			Begeni > 2
		ORDER BY
			Begeni DESC
	) t
GROUP BY
	begenen_uye
HAVING
	begenilen_ID = $MEMBER->U_ID AND
	begenen_ID <> $MEMBER->U_ID
ORDER BY
	Begeni DESC
	";
	
	$fanlar = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);
	
	FB::info($fanlar);
	
?>
<h3 style="text-align:left; padding-left:30px;">Sosyal Danışmanım</h3>
<div align="left">
  <ul>
    <li>Kimle Arkadaş Olsam
      <ul>
      	<?php foreach ($arkadas_onerileri as $uye) {?>
        <li><a href="yazar.php?y=<?php echo urlencode($uye['Nick']); ?>"><?php echo $uye['Nick'] ?></a>(<?php echo $uye['AyniSevgi'] ?>)</li>
        <?php } ?>
      </ul>
    </li>
    <li>En Beğendiklerim
      <ul>
        <?php foreach ($begendiklerim as $uye) {?>
        <li><a href="yazar.php?y=<?php echo urlencode($uye['Nick']); ?>"><?php echo $uye['Nick'] ?></a> (<?php echo $uye['begeni'] ?>)</li>
        <?php } ?>
      </ul>
    </li>
    <li>Fanlarım
      <ul>
        <?php foreach ($fanlar as $uye) {?>
        <li><a href="yazar.php?y=<?php echo urlencode($uye['begenen_uye']); ?>"><?php echo $uye['begenen_uye'] ?></a> (<?php echo $uye['Begeni'] ?>)</li>
        <?php } ?>
      </ul>
    </li>
    <li>Beğenenler
      <ul>
        <?php foreach ($begenenler as $uye) {?>
        <li><a href="yazar.php?y=<?php echo urlencode($uye['Nick']); ?>"><?php echo $uye['Nick'] ?></a> (<?php echo $uye['sevgi'] ?>)</li>
        <?php } ?>
      </ul>
    </li>
    <li>Bana gıcık olanlar
      <ul>
        <?php foreach ($giciklar as $uye) {?>
        <li><a href="yazar.php?y=<?php echo urlencode($uye['Nick']); ?>"><?php echo $uye['Nick'] ?></a> (<?php echo $uye['sevgi'] ?>)</li>
        <?php } ?>
      </ul>
    </li>
  </ul>
</div>

<?php 
FB::info("PORTLET OUT: Gubidik"); 
}

?>