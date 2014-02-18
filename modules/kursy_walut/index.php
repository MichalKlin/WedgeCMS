<center>
<?php
echo "<strong>Aktualny kurs walut NBP:</strong></br><spna style=\"font-weight: bold;\">";

$aktualny_kurs = nazwa_aktualnego_kursu();
$tresc         = file_get_contents($aktualny_kurs);

$xml = new SimpleXMLElement($tresc);
foreach ($xml->pozycja as $pozycja) {
		if ($pozycja->kod_waluty == 'USD' or $pozycja->kod_waluty == 'EUR' or $pozycja->kod_waluty == 'CHF'or $pozycja->kod_waluty == 'GBP'or $pozycja->kod_waluty == 'JPY'){
	
        echo $pozycja->kod_waluty.' = ';
        echo $pozycja->kurs_sredni." PLN</br>";
		}
}

function nazwa_aktualnego_kursu() {
  $tresc   = file_get_contents('http://nbp.pl/Kursy/KursyA.html');
  $wzorzec = '/xml\/[\d\w]+\.xml/';
  $sukces  = preg_match($wzorzec, $tresc, $pasujace);

  if (empty($pasujace))
    exit('Blad: Nie znaleziono tabeli kursow.');

  return 'http://nbp.pl/Kursy/'.$pasujace[0];
}

?>
</span>
<br/>
<?php readfile ('http://cw.money.pl/wykres_wig20_m.html', true) ?>  
</center>