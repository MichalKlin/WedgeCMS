<center>
<hr width='90%'  color="green" height: 1px; style="color: green; background-color: green; border-color: green;">
<br />
Naszą stronę odwiedzono:</center>
<?php
$tabela = "mod_licznik";
$tabela_odw = "mod_odwiedziny";

$result = $this->baza->select("*",$tabela, "", "");	
$row = $this->baza->row($result);	

$nazwa_licznika = $_SESSION['session_name']."_licznik";

if(!isset($_SESSION[$nazwa_licznika])){
	$licznik = $row[mli_wartosc] + 1;
	$result = $this->baza->update($tabela,"mli_wartosc=$licznik","");
	
	$time=localtime();
	$rok=$time[5]+1900;
	$miesiac=$time[4]+1;
	$dz = $time[3];
	$godzina = $time[2];
	$minuta = $time[1];
	$dzien = $time[6];

	$ip = $_SERVER['REMOTE_ADDR'];
	$host = gethostbyaddr($_SERVER['REMOTE_ADDR']);
	//	$przegladarka = $_SERVER['HTTP_USER_AGENT'];
	$s = explode("MSIE",$_SERVER['HTTP_USER_AGENT']);
	if ($s[1]!=""){
		$przegladarka = "IE";
	}
	else {
		$p = explode("Firefox",$_SERVER['HTTP_USER_AGENT']);
		if ($p[1]!=""){
			$przegladarka = "Firefox";
		}
		else {
			$r = explode("Opera",$_SERVER['HTTP_USER_AGENT']);
			if ($r[1]!=""){
				$przegladarka = "Opera";
			}
			else{
				$przegladarka = $_SERVER['HTTP_USER_AGENT'];
			}
		}
	}
	
	$wartosci = "0,\"$ip\",\"$host\",\"$przegladarka\",$rok,$miesiac,$dz,$godzina,$minuta,$dzien";
	$result = $this->baza->insert($tabela_odw,$wartosci);

	$_SESSION[$nazwa_licznika] = time()+5*60; //ustawienie czasu wygaśnięcia sesji licznika
}
else{
	$licznik = $row[mli_wartosc];
}

echo "<span class=\"licznik\">".$licznik." razy</span><br /><br />";
$tabela_odw = "mod_odwiedziny";
$czas_licznika = 5*60;
$licznik_online = licznik_online($this->baza, $tabela_odw, $czas_licznika);
//echo "<center><hr color=\"brown\" height: 1px; style=\"color: brown; background-color: brown; border-color: brown; width: 50%;\">On-line: <strong>$licznik_online</strong></center>";


function licznik_online($baza, $tabela_odw, $czas_licznika){
	//aktualny czas
	$time=localtime();
	$rok=$time[5]+1900;
	$miesiac=$time[4]+1;
	$dzien = $time[3];
	$godzina = $time[2];
	$minuta = $time[1];
	$teraz = mktime($godzina, $minuta, 0, $miesiac, $dzien, $rok);

	$where = "modw_rok=\"".$rok."\" and modw_miesiac=\"".$miesiac."\" and modw_dzien=\"".$dzien."\"";
	$result = $baza->select("*",$tabela_odw, $where);	
	$size_result = $baza->size_result($result);
	if ($size_result>0){
		for ($i=0; $i<$size_result; $i++){
			$row = $baza->row($result);	
			$czas_z_bazy = mktime($row[modw_godzina],$row[modw_minuta],0,$row[modw_miesiac],$row[modw_dzien],$row[modw_rok]);
			
			//sprawdzenie czy prawdopodobnie jest online (jeśli czas nie większy od ustalonego)
			if ($czas_z_bazy + $czas_licznika >= $teraz){
				$licznik_online++;
			}		
		}
	}
	return $licznik_online;
}
?>