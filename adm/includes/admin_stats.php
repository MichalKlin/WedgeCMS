<center>
			<table border="0" cellpadding="0" cellspacing="0" id="page_menu">
				<tr>
					<td 
					<?if (isset($_GET[stat_dni])) echo "class=\"page_menu_item2\""; 
					else echo "class=\"page_menu_item\"";?>
					><a href="?admin=&statystyki=&stat_dni=">Dzienne</a></td>
					<td 
					<?if (isset($_GET[stat_tygod])) echo "class=\"page_menu_item2\""; 
					else echo "class=\"page_menu_item\"";?>
					><a href="?admin=&statystyki=&stat_tygod=">Dni tygodnia</a></td>
					<td 
					<?if (isset($_GET[stat_godz])) echo "class=\"page_menu_item2\""; 
					else echo "class=\"page_menu_item\"";?>
					><a href="?admin=&statystyki=&stat_godz=">Godziny</a></td>
					<td 
					<?if (isset($_GET[stat_source])) echo "class=\"page_menu_item2\""; 
					else echo "class=\"page_menu_item\"";?>
					><a href="?admin=&statystyki=&stat_source=">Źródła</a></td>
					<td 
					<?if (isset($_GET[stat_przegl])) echo "class=\"page_menu_item2\""; 
					else echo "class=\"page_menu_item\"";?>
					><a href="?admin=&statystyki=&stat_przegl=">Przeglądarka</a></td>
					<td 
					<?if (isset($_GET[stat_page])) echo "class=\"page_menu_item2\""; 
					else echo "class=\"page_menu_item\"";?>
					><a href="?admin=&statystyki=&stat_page=">Strony</a></td>
				</tr>
			</table>
			
			
<h3>Statystyki odwiedzin stron</h3>

<?
	global $miesiace, $ikona_powrot, $nazwa_powrot, $ikona_tekst;
	
	$ikona_powrot="./images/back.png";
	$nazwa_powrot="Powrót";
	$ikona_tekst="tekst_ikona";
	$miesiace[1]="styczeń";$miesiace[2]="luty";$miesiace[3]="marzec";
	$miesiace[4]="kwiecień";$miesiace[5]="maj";$miesiace[6]="czerwiec";
	$miesiace[7]="lipiec";$miesiace[8]="sierpień";$miesiace[9]="wrzesień";
	$miesiace[10]="październik";$miesiace[11]="listopad";$miesiace[12]="grudzień";	
	
	$sciezka=ustaw_sciezke();
	
	if (isset($_GET['stat_dni']))	
		stat_dni($sciezka."&stat_dni=");
	if (isset($_GET['stat_tygod']))	
		stat_tygod($sciezka);
	if (isset($_GET['stat_godz']))	
		stat_godz($sciezka);
	if (isset($_GET['stat_source']))	
		stat_source($sciezka);
	if (isset($_GET['stat_przegl']))	
		stat_przegl($sciezka);
	if (isset($_GET['stat_page']))	
		stat_page($sciezka);

############################# FUNKCJE ############################
##################################################################
function ustaw_sciezke(){
	if(isset($_SESSION["sciezka"])) {unset($_SESSION["sciezka"]);}
	$_SESSION["sciezka"]=$_SERVER[REQUEST_URI];
	$sciezka=$_SESSION["sciezka"];
	$nowa_sc=explode("&",$sciezka);
	$_SESSION["sciezka"]="$nowa_sc[0]";
	$sciezka=$_SESSION["sciezka"]."&statystyki=";
	return $sciezka;
}
##################################################################
function stat_page($sciezka){
	$z = "select 1 from mod_odwiedziny_page";
	$ws=mysql_query($z);
	$ile_all=mysql_num_rows($ws);

	$zapytanie="select DISTINCT mop_page from mod_odwiedziny_page order by mop_page";
	$wynik_szukania=mysql_query($zapytanie);
	$ile=mysql_num_rows($wynik_szukania);
	for ($i=0; $i<$ile; $i++){
		$wiersz=mysql_fetch_array($wynik_szukania);
		$table[$i][0]=$wiersz;
		$z = "select 1 from mod_odwiedziny_page where mop_page=\"$wiersz[mop_page]\"";
		$ws=mysql_query($z);
		$il=mysql_num_rows($ws);
		$table[$i][1]=$il;
	}
	
	//sortowanie
	for ($i=0; $i<$ile; $i++){
		for ($j=$ile-1;$j>$i;$j--){
			if ($table[$j][1]>$table[$j-1][1]){
				$wiersz=$table[$j];
				$table[$j]=$table[$j-1];
				$table[$j-1]=$wiersz;
			}
		}
	}
	
	?>
	<table border="1">
		<tr>
			<th>Strona</th>
			<th>Ile wejść</th>
			<th>Procent</th>
		</tr>
	<?
	for ($i=0; $i<$ile; $i++){
		$wiersz=$table[$i][0];
		$il = $table[$i][1];
		$proc = floor($il/$ile_all*100);
		?>
		<tr>
			<td><?=$wiersz[mop_page]?></td>
			<td><?=$il?></td>
			<td><?
			echo "<img style=\"border:0; vertical-align:bottom;\" src=\"./images/tlo.jpg\" border=\"0\" width=\"1\" height=\"10\">";
			for($j=0;$j<$proc;$j++){
				echo "<img style=\"border:0; vertical-align:bottom;\" src=\"./images/tlo.jpg\" border=\"0\" width=\"3\" height=\"10\">";
			}
			?></td>
		</tr>
		<?
	}
	?>
	</table>
	<?
}
function stat_dni($sciezka){
	if (!isset($_GET[dzien]) and !isset($_GET[godzina])){
		$zapytanie="select * from mod_odwiedziny";
		$wynik_szukania=mysql_query($zapytanie);
		if (!$wynik_szukania){echo "ERROR 1"; break;}
		$licznik=mysql_fetch_array($wynik_szukania);
		$size_result = mysql_num_rows($wynik_szukania);
		echo "<p class=\"message\">Stronę odwiedzono łącznie: $size_result razy.</p>";
		
		statystyki_miesieczne($sciezka);	
	}
	modw_dzienne($sciezka);
	modw_dzienne_szczegoly($sciezka);
}
function stat_tygod($sciezka){
	if (!isset($_GET[dzien]) and !isset($_GET[godzina])){
		$zapytanie="select * from mod_odwiedziny";
		$wynik_szukania=mysql_query($zapytanie);
		if (!$wynik_szukania){echo "ERROR 1"; break;}
		$licznik=mysql_fetch_array($wynik_szukania);
		$size_result = mysql_num_rows($wynik_szukania);
		echo "<p class=\"message\">Stronę odwiedzono łącznie: $size_result razy.</p>";
		
		statystyki_tygodniowe($sciezka);
	}
}
function stat_godz($sciezka){
	if (!isset($_GET[dzien]) and !isset($_GET[godzina])){
		$zapytanie="select * from mod_odwiedziny";
		$wynik_szukania=mysql_query($zapytanie);
		if (!$wynik_szukania){echo "ERROR 1"; break;}
		$licznik=mysql_fetch_array($wynik_szukania);
		$size_result = mysql_num_rows($wynik_szukania);
		echo "<p class=\"message\">Stronę odwiedzono łącznie: $size_result razy.</p>";
		
		statystyki_godzinowe($sciezka);
	}
}
function stat_source($sciezka){
	if (!isset($_GET[dzien]) and !isset($_GET[godzina])){
		$zapytanie="select * from mod_odwiedziny";
		$wynik_szukania=mysql_query($zapytanie);
		if (!$wynik_szukania){echo "ERROR 1"; break;}
		$licznik=mysql_fetch_array($wynik_szukania);
		$size_result = mysql_num_rows($wynik_szukania);
		echo "<p class=\"message\">Stronę odwiedzono łącznie: $size_result razy.</p>";
		
		hosty();
	}
}
function stat_przegl($sciezka){
	if (!isset($_GET[dzien]) and !isset($_GET[godzina])){
		$zapytanie="select * from mod_odwiedziny";
		$wynik_szukania=mysql_query($zapytanie);
		if (!$wynik_szukania){echo "ERROR 1"; break;}
		$licznik=mysql_fetch_array($wynik_szukania);
		$size_result = mysql_num_rows($wynik_szukania);
		echo "<p class=\"message\">Stronę odwiedzono łącznie: $size_result razy.</p>";
		
		przegladarki();
	}
}
##################################################################
function statystyki_miesieczne($sciezka){	
	global $miesiace;
	
	if(!isset($_GET[miesiac])){
		$rok=date("Y");
		$miesiac=date("n");
		$dzien=date("d");
	}
	if(isset($_GET[miesiac])){
		$rok=$_GET[rok];
		$miesiac=$_GET[miesiac];
	}
	$zapytanie="select * from mod_odwiedziny where modw_rok=$rok and modw_miesiac=$miesiac";
	$wynik_szukania=mysql_query($zapytanie);
	if (!$wynik_szukania){echo "ERROR 2"; break;}
	$ile=mysql_num_rows($wynik_szukania);
	
	if($miesiac==1){
		$wczesniejszy_miesiac=12;
		$w_rok=$rok-1;
		$pozniejszy_miesiac=$miesiac+1;
		$p_rok=$rok;
	}
	elseif($miesiac==12){
		$wczesniejszy_miesiac=$miesiac-1;
		$w_rok=$rok;
		$pozniejszy_miesiac=1;
		$p_rok=$rok+1;
	}
	else{
		$wczesniejszy_miesiac=$miesiac-1;
		$w_rok=$rok;
		$pozniejszy_miesiac=$miesiac+1;
		$p_rok=$rok;
	}
	$wczesniejszy_rok=$rok-1;
	$pozniejszy_rok=$rok+1;
	
	echo "<h2><a href=\"$sciezka&miesiac=$wczesniejszy_miesiac&rok=$w_rok\"><img style=\"border:0;\" style=\"border:0;\" style=\"border:0;\" src=\"./images/prev.png\" alt=\"preview\"/></a> $miesiace[$miesiac]
	<a href=\"$sciezka&miesiac=$pozniejszy_miesiac&rok=$p_rok\"><img style=\"border:0;\" src=\"./images/next.png\" alt=\"next\"/></a>
	
	<a href=\"$sciezka&miesiac=$miesiac&rok=$wczesniejszy_rok\"><img style=\"border:0;\" src=\"./images/prev.png\" alt=\"preview\"/></a> $rok
	<a href=\"$sciezka&miesiac=$miesiac&rok=$pozniejszy_rok\"><img style=\"border:0;\" src=\"./images/next.png\" alt=\"next\"/></a> </h2>";
	echo "<p class=\"message\">Stronę w tym miesiącu odwiedzono: $ile razy.</p>";
	
	$ile_dni_w_miesiacu = date ("t", mktime (0,0,0,$miesiac,1,$rok));

	
	//tabela wyników odwiedzin w danym miesicu
	echo "<table class=\"tabela_link\" border=\"1\">";
	$colspan=$ile_dni_w_miesiacu+1;
	echo "<tr><th colspan=\"$colspan\">Dzienny rozkład odwiedzin strony w miesiącu:</th></tr>";
	echo "<tr><th>Dzień</th>";
	for($i=1;$i<=$ile_dni_w_miesiacu;$i++){
		if($i<10)$liczba="0$i"; else $liczba=$i;
		echo "<td><a href=\"$sciezka&dzien=$i&miesiac=$miesiac&rok=$rok\">$liczba</a></td>";
	}
	echo "</tr>";
	echo "<tr><th>Wykres</th>";
	for($i=1;$i<=$ile_dni_w_miesiacu;$i++){
		$zapytanie="select * from mod_odwiedziny where modw_rok=$rok and modw_miesiac=$miesiac and modw_dzien=$i";
		$wynik_szukania=mysql_query($zapytanie);
		if (!$wynik_szukania){echo "ERROR 3"; break;}
		$ile=mysql_num_rows($wynik_szukania);
		$tablica[$i]=$ile;
		echo "<td style=\" vertical-align:bottom;\">";
		echo "<img style=\"border:0; vertical-align:bottom;\" src=\"./images/tlo.jpg\" border=\"0\" width=\"10\" height=\"1\"><br />";
		for($j=0;$j<$tablica[$i]/4;$j++){
			echo "<img style=\"border:0; vertical-align:bottom;\" src=\"./images/tlo.jpg\" border=\"0\" width=\"10\" height=\"4\"><br />";
		}
		echo "</td>";
	}
	echo "</tr>";
	echo "<tr><th>Ilość</th>";
	for($i=1;$i<=$ile_dni_w_miesiacu;$i++){
		echo "<td>$tablica[$i]</td>";
	}
	echo "</tr>";
	echo "</table>";	
}
##################################################################
function statystyki_tygodniowe($sciezka){
	if(!isset($_GET[miesiac])){
		$rok=date("Y");
		$miesiac=date("n");
		$dzien=date("d");
	}
	if(isset($_GET[miesiac])){
		$rok=$_GET[rok];
		$miesiac=$_GET[miesiac];
	}

	$zapytanie="select * from mod_odwiedziny";
	$wynik_szukania=mysql_query($zapytanie);
	if (!$wynik_szukania){echo "ERROR 4"; break;}
	$ile_wszystkich=mysql_num_rows($wynik_szukania);

	echo "<table border=\"1\">";
	$colspan=$ile_dni_w_miesiacu+1;
	echo "<tr><th colspan=\"8\">Procentowy rozkład odwiedzin strony w różnych dniach tygodnia</th></tr>";
	echo "<tr><th>Dzień</th>";
	echo "<td>Niedziela</td><td>Poniedziałek</td><td>Wtorek</td><td>Środa</td><td>Czwartek</td><td>Piątek</td><td>Sobota</td>";
	echo "</tr>";
	echo "<tr><th>Wykres</th>";
	for($i=0;$i<7;$i++){
		$zapytanie="select * from mod_odwiedziny where modw_dzien_tygodnia=$i";
		$wynik_szukania=mysql_query($zapytanie);
		if (!$wynik_szukania){echo "ERROR 5"; break;}
		$ile=mysql_num_rows($wynik_szukania);
		if ($ile_wszystkich!=0){
			$obcinane=explode(".",$ile*100/$ile_wszystkich);
		}
		$procent[$i]=$obcinane[0];
		echo "<td style=\" vertical-align:bottom; text-align: center;\">";
		echo "<img style=\"border:0; vertical-align:bottom;\" src=\"./images/tlo2.jpg\" border=\"0\" width=\"30\" height=\"1\"><br />";
		for($j=0;$j<$procent[$i];$j++){
			echo "<img style=\"border:0; vertical-align:bottom;\" src=\"./images/tlo2.jpg\" border=\"0\" width=\"30\" height=\"2\"><br />";
		}
		echo "</td>";
	}
	echo "</tr>";
	echo "<tr><th>Procent</th>";
	for($i=0;$i<7;$i++){
		echo "<td style=\"vertical-align:bottom; text-align: center;\">$procent[$i] %</td>";
	}
	echo "</tr>";
	echo "</table>";	
}
##################################################################
function modw_dzienne($sciezka){
	global $miesiace, $ikona_powrot, $nazwa_powrot;
	
	if (isset($_GET[dzien]) and !isset($_GET[godzina])){
		$dzien=$_GET[dzien];
		$rok=$_GET[rok];
		$miesiac=$_GET[miesiac];
		
		$zapytanie="select * from mod_odwiedziny where modw_rok=$rok and modw_miesiac=$miesiac and modw_dzien=$dzien";
		$wynik_szukania=mysql_query($zapytanie);
		if (!$wynik_szukania){echo "ERROR 6"; break;}
		$ile=mysql_num_rows($wynik_szukania);
	
		if($dzien==1 and $miesiac==1){
			$w_dzien = 31;
			$w_miesiac=12;
			$w_rok=$rok-1;
			$p_dzien = 2;
			$p_miesiac=1;
			$p_rok=$rok;			
		}
		elseif($dzien==1 and $miesiac>1 and $miesiac<12){
			$w_dzien = date ("t", mktime (0,0,0,$miesiac-1,1,$rok));
			$w_miesiac=$miesiac-1;
			$w_rok=$rok;
			$p_dzien = 2;
			$p_miesiac=$miesiac+1;
			$p_rok=$rok;			
		}
		elseif($dzien==31 and $miesiac==12){
			$w_dzien = 30;
			$w_miesiac=12;
			$w_rok=$rok;
			$p_dzien = 1;
			$p_miesiac=1;
			$p_rok=$rok+1;			
		}
		elseif($dzien<31 and $dzien>1 and $miesiac==12){
			$w_dzien = $dzien-1;
			$w_miesiac=12;
			$w_rok=$rok;
			$p_dzien = $dzien+1;
			$p_miesiac=12;
			$p_rok=$rok;			
		}
		elseif($dzien==date ("t", mktime (0,0,0,$miesiac,1,$rok))){
			$w_dzien = date ("t", mktime (0,0,0,$miesiac,1,$rok))-1;
			$w_miesiac=$miesiac;
			$w_rok=$rok;
			$p_dzien = 1;
			$p_miesiac=$miesiac+1;
			$p_rok=$rok;			
		}
		else{
			$w_dzien = $dzien-1;
			$w_miesiac=$miesiac;
			$w_rok=$rok;
			$p_dzien = $dzien+1;
			$p_miesiac=$miesiac;
			$p_rok=$rok;			
		}
		
		if($miesiac==1){
			$ww_miesiac=12;
			$ww_rok=$rok-1;
			$pp_miesiac=$miesiac+1;
			$pp_rok=$rok;
		}
		elseif($miesiac==12){
			$ww_miesiac=$miesiac-1;
			$ww_rok=$rok;
			$pp_miesiac=1;
			$pp_rok=$rok+1;
		}
		else{
			$ww_miesiac=$miesiac-1;
			$ww_rok=$rok;
			$pp_miesiac=$miesiac+1;
			$pp_rok=$rok;
		}
		$www_rok=$rok-1;
		$ppp_rok=$rok+1;
	
		echo "<h2>
		<a href=\"$sciezka&dzien=$w_dzien&miesiac=$w_miesiac&rok=$w_rok\"><img style=\"border:0;\" src=\"./images/prev.png\" alt=\"preview\"/></a> $dzien
		<a href=\"$sciezka&dzien=$p_dzien&miesiac=$p_miesiac&rok=$p_rok\"><img style=\"border:0;\" src=\"./images/next.png\" alt=\"next\"/></a>
		<a href=\"$sciezka&dzien=$dzien&miesiac=$ww_miesiac&rok=$ww_rok\"><img style=\"border:0;\" src=\"./images/prev.png\" alt=\"preview\"/></a> $miesiace[$miesiac]
		<a href=\"$sciezka&dzien=$dzien&miesiac=$pp_miesiac&rok=$pp_rok\"><img style=\"border:0;\" src=\"./images/next.png\" alt=\"next\"/></a>
		<a href=\"$sciezka&dzien=$dzien&miesiac=$miesiac&rok=$www_rok\"><img style=\"border:0;\" src=\"./images/prev.png\" alt=\"preview\"/></a> $rok
		<a href=\"$sciezka&dzien=$dzien&miesiac=$miesiac&rok=$ppp_rok\"><img style=\"border:0;\" src=\"./images/next.png\" alt=\"next\"/></a> </h2>";
		echo "<p class=\"message\">Stronę w tym dniu odwiedzono: $ile razy.</p>";
		
		//tabela wynikďż˝ odwiedzin w danym dniu
		echo "<table  class=\"tabela_link\" border=\"1\">";
		echo "<tr><th colspan=\"25\">Godzinowy rozkład odwiedzin strony w danym dniu w miesiącu:</th></tr>";
		echo "<tr><th>Godzina</th>";
		for($i=0;$i<=23;$i++){
			if($i<10)$liczba="0$i"; else $liczba=$i;
			echo "<td><a href=\"$sciezka&godzina=$i&dzien=$dzien&miesiac=$miesiac&rok=$rok\">$liczba</a></td>";
		}
		echo "</tr>";
		echo "<tr><th>Wykres</th>";
		for($i=0;$i<=23;$i++){
			$zapytanie="select * from mod_odwiedziny where modw_rok=$rok and modw_miesiac=$miesiac and modw_dzien=$dzien and modw_godzina=$i";
			$wynik_szukania=mysql_query($zapytanie);
			if (!$wynik_szukania){echo "ERROR 7"; break;}
			$ile=mysql_num_rows($wynik_szukania);
			$tablica[$i]=$ile;
			echo "<td style=\" vertical-align:bottom;\">";
			echo "<img style=\"border:0; vertical-align:bottom;\" src=\"./images/tlo3.jpg\" border=\"0\" width=\"10\" height=\"1\"><br />";
			for($j=0;$j<$tablica[$i];$j++){
				echo "<img style=\"border:0; vertical-align:bottom;\" src=\"./images/tlo3.jpg\" border=\"0\" width=\"10\" height=\"3\"><br />";
			}
			echo "</td>";
		}
		echo "</tr>";
		echo "<tr><th>Ilość</th>";
		for($i=0;$i<=23;$i++){
			echo "<td>$tablica[$i]</td>";
		}
		echo "</tr>";
		echo "</table>";
		
		echo "<h6>[ ";
		$adres="$sciezka";
		wyswietl_link_tekst($adres, $ikona_powrot, $nazwa_powrot);
		echo " ]</h6>";
	}	
}
##################################################################
function statystyki_godzinowe($sciezka){
	if(!isset($_GET[miesiac])){
		$rok=date("Y");
		$miesiac=date("n");
		$dzien=date("d");
	}
	if(isset($_GET[miesiac])){
		$rok=$_GET[rok];
		$miesiac=$_GET[miesiac];
	}

	$zapytanie="select * from mod_odwiedziny";
	$wynik_szukania=mysql_query($zapytanie);
	if (!$wynik_szukania){echo "ERROR 8"; break;}
	$ile_wszystkich=mysql_num_rows($wynik_szukania);

	echo "<table border=\"1\">";
	echo "<tr><th colspan=\"25\">Procentowy rozkład odwiedzin strony w zależności od godziny odwiedzin:</th></tr>";
	echo "<tr><th>Godzina</th>";
	for($i=0;$i<24;$i++){
		echo "<td>$i</td>";
	}
	echo "</tr>";
	echo "<tr><th>Wykres</th>";
	for($i=0;$i<24;$i++){
		$zapytanie="select * from mod_odwiedziny where modw_godzina=$i";
		$wynik_szukania=mysql_query($zapytanie);
		if (!$wynik_szukania){echo "ERROR 9"; break;}
		$ile=mysql_num_rows($wynik_szukania);
		if ($ile_wszystkich!=0){
			$obcinane=explode(".",$ile*100/$ile_wszystkich);
		}
		$procent[$i]=$obcinane[0];
		echo "<td style=\" vertical-align:bottom;\">";
		echo "<img style=\"border:0; vertical-align:bottom;\" src=\"./images/tlo3.jpg\" border=\"0\" width=\"15\" height=\"1\"><br />";
		for($j=0;$j<$procent[$i];$j++){
			echo "<img style=\"border:0; vertical-align:bottom;\" src=\"./images/tlo3.jpg\" border=\"0\" width=\"15\" height=\"2\"><br />";
		}
		echo "</td>";
	}
	echo "</tr>";
	echo "<tr><th>Procent</th>";
	for($i=0;$i<24;$i++){
		echo "<td>$procent[$i] %</td>";
	}
	echo "</tr>";
	echo "</table>";	
}
##################################################################
function modw_dzienne_szczegoly($sciezka){
	global $miesiace, $ikona_powrot, $nazwa_powrot;

	if (isset($_GET[godzina])){
		$godzina=$_GET[godzina];
		$dzien=$_GET[dzien];
		$rok=$_GET[rok];
		$miesiac=$_GET[miesiac];

		$zapytanie="select * from mod_odwiedziny where modw_rok=$rok and modw_miesiac=$miesiac and modw_dzien=$dzien and modw_godzina=$godzina order by modw_minuta";
		$wynik_szukania=mysql_query($zapytanie);
		if (!$wynik_szukania){echo "ERROR 10"; break;}
		$ile=mysql_num_rows($wynik_szukania);

		$nastepna_godzina=$_GET[godzina]+1;
		echo "<h2>Wykaz odwiedzin stron w dniu $dzien $miesiace[$miesiac] $rok<br />między $godzina:00 a $nastepna_godzina:00</h2>";
		echo "<p class=\"message\">Stronę o tej godzinie odwiedził(o): $ile użytkownik(ów).</p>";
		
		if($ile>0){
			echo "<table border=\"1\">";
			echo "<tr><th>Lp</th><th>Godzina</th><th>Host</th><th>IP</th><th>System</th></tr>";
			for($i=1;$i<=$ile;$i++){
				$wiersz=mysql_fetch_array($wynik_szukania);
				echo "<tr>";
				if ($wiersz[modw_minuta]<10){$minuta = "0".$wiersz[modw_minuta];}
				else {$minuta = $wiersz[modw_minuta];}
				echo "<td>$i</td><td>$godzina:$minuta</td><td>$wiersz[modw_host]</td><td>$wiersz[modw_ip]</td><td>$wiersz[modw_przegladarka]</td>";
				echo "</tr>";
			}
			echo "</table>";
		}

		echo "<h6>[ ";
		$adres="javascript:history.go(-1)";
		wyswietl_link_tekst($adres, $ikona_powrot, $nazwa_powrot);
		echo " ]</h6>";
	}	
}
##################################################################
function hosty(){
	if(!isset($_GET[miesiac])){
		$rok=date("Y");
		$miesiac=date("n");
		$dzien=date("d");
	}
	if(isset($_GET[miesiac])){
		$rok=$_GET[rok];
		$miesiac=$_GET[miesiac];
	}

//	$zapytanie="select * from mod_odwiedziny where modw_rok=$rok and modw_miesiac=$miesiac";
	$zapytanie="select * from mod_odwiedziny order by modw_host";
	$wynik_szukania=mysql_query($zapytanie);
	if (!$wynik_szukania){echo "ERROR 11"; }
	$ile=mysql_num_rows($wynik_szukania);
	
	$licznik_hostow=0;
	$ile_odwiedzin=1;
	$wiersz=mysql_fetch_array($wynik_szukania);
	$nazwa_hostu=$wiersz[modw_host];	
	for($i=0;$i<$ile;$i++){
		$wiersz=mysql_fetch_array($wynik_szukania);
		if($wiersz[modw_host]==$nazwa_hostu){
			$ile_odwiedzin++;
		}
		else{
//			$nazwa_hostu=$wiersz[modw_host];	
			$tablica_hostow_nazw[$licznik_hostow]=$nazwa_hostu;
			$tablica_hostow[$licznik_hostow]=$ile_odwiedzin;
			$licznik_hostow++;
			$ile_odwiedzin=1;
		}
		$nazwa_hostu=$wiersz[modw_host];	
	}

	//poszeregowanie według ilości odwiedzin - bąbelkowo
	for ($j=0;$j<$licznik_hostow;$j++){
		for ($i=0;$i<$licznik_hostow;$i++){
			$k=$i+1;
			if ($tablica_hostow[$i]<$tablica_hostow[$k]){
				$pomoc_ilosc=$tablica_hostow[$i];
				$tablica_hostow[$i]=$tablica_hostow[$k];
				$tablica_hostow[$k]=$pomoc_ilosc;
				$pomoc_nazwa=$tablica_hostow_nazw[$i];
				$tablica_hostow_nazw[$i]=$tablica_hostow_nazw[$k];
				$tablica_hostow_nazw[$k]=$pomoc_nazwa;
			}
		}	
	}
	
	echo "<table border=\"1\"><tr><th colspan=\"2\">Źródła odwiedzin - 20 najczęściej wywoływanych lokacji</th></tr>
	<tr><th>liczba odwiedzin</th><th>nazwa hosta</th></tr>";

	for ($i=0;$i<$licznik_hostow && $i<20;$i++){
		echo "<tr><td>$tablica_hostow[$i]</td><td>$tablica_hostow_nazw[$i]</td></tr>";
	}

	echo "</table>";
}
##################################################################
function przegladarki(){
	$zapytanie="select * from mod_odwiedziny";
	$wynik_szukania=mysql_query($zapytanie);
	if (!$wynik_szukania){echo "ERROR prze"; }
	$ile=mysql_num_rows($wynik_szukania);
	if ($ile>0){
		echo "<br /><br />";
		$firefox =0;
		$ie = 0;
		$opera = 0;
		$mozilla = 0;
		$msnbot = 0;
		$inna = 0;
		
		for ($i=0; $i<$ile; $i++){
			$wiersz=mysql_fetch_array($wynik_szukania);
			$e = explode("/",$wiersz['modw_przegladarka']);
			if ($wiersz[modw_przegladarka]=="Firefox"){
				$firefox+=1;
			}
			elseif ($wiersz[modw_przegladarka]=="IE"){
				$ie+=1;
			}
			elseif ($wiersz[modw_przegladarka]=="Opera"){
				$opera+=1;
			}
			elseif ($wiersz[modw_przegladarka]=="Mozilla" or $e[0]=="Mozilla"){
				$mozilla+=1;
			}
			elseif ($wiersz[modw_przegladarka]=="msnbot" or $e[0]=="msnbot"){
				$msnbot+=1;
			}
			else {
				$inna+=1;
			}
		}
		
		//wykres przeglądarek
		$suma = $firefox + $ie + $opera + $inna + $mozilla + $msnbot;
		echo "<table border=\"1\">";
		echo "<tr><th colspan=\"7\">Procentowy rozkład odwiedzin strony w różnych przeglądarek:</th></tr>";
		echo "<tr><th>Przeglądarka</th>";
		echo "<td>Firefox</td><td style=\" vertical-align:bottom; text-align: center;\">IE</td><td>Opera</td><td>Mozilla</td><td style=\" vertical-align:bottom; text-align: center;\">msnbot</td><td style=\" vertical-align:bottom; text-align: center;\">Inna</td>";
		echo "</tr>";
		echo "<tr><th>Wykres</th>";

		$obcinane=explode(".",$firefox*100/$suma);
		$procent[0]=$obcinane[0];
		echo "<td style=\" vertical-align:bottom; text-align: center;\">";
		echo "<img style=\"border:0; vertical-align:bottom;\" src=\"./images/tlo2.jpg\" border=\"0\" width=\"30\" height=\"1\"><br />";
		for($j=0;$j<$obcinane[0];$j++){
			echo "<img style=\"border:0; vertical-align:bottom;\" src=\"./images/tlo2.jpg\" border=\"0\" width=\"30\" height=\"2\"><br />";
		}
		echo "</td>";
		$obcinane=explode(".",$ie*100/$suma);
		$procent[1]=$obcinane[0];
		echo "<td  style=\" vertical-align:bottom;\">";
		echo "<img style=\"border:0; vertical-align:bottom;\" src=\"./images/tlo2.jpg\" border=\"0\" width=\"30\" height=\"1\"><br />";
		for($j=0;$j<$obcinane[0];$j++){
			echo "<img style=\"border:0; vertical-align:bottom;\" src=\"./images/tlo2.jpg\" border=\"0\" width=\"30\" height=\"2\"><br />";
		}
		echo "</td>";
		$obcinane=explode(".",$opera*100/$suma);
		$procent[2]=$obcinane[0];
		echo "<td style=\" vertical-align:bottom;\">";
		echo "<img style=\"border:0; vertical-align:bottom;\" src=\"./images/tlo2.jpg\" border=\"0\" width=\"30\" height=\"1\"><br />";
		for($j=0;$j<$obcinane[0];$j++){
			echo "<img style=\"border:0; vertical-align:bottom;\" src=\"./images/tlo2.jpg\" border=\"0\" width=\"30\" height=\"2\"><br />";
		}
		echo "</td>";
		$obcinane=explode(".",$mozilla*100/$suma);
		$procent[3]=$obcinane[0];
		echo "<td style=\" vertical-align:bottom;\">";
		echo "<img style=\"border:0; vertical-align:bottom;\" src=\"./images/tlo2.jpg\" border=\"0\" width=\"30\" height=\"1\"><br />";
		for($j=0;$j<$obcinane[0];$j++){
			echo "<img style=\"border:0; vertical-align:bottom;\" src=\"./images/tlo2.jpg\" border=\"0\" width=\"30\" height=\"2\"><br />";
		}
		echo "</td>";
		$obcinane=explode(".",$msnbot*100/$suma);
		$procent[4]=$obcinane[0];
		echo "<td style=\" vertical-align:bottom;\">";
		echo "<img style=\"border:0; vertical-align:bottom;\" src=\"./images/tlo2.jpg\" border=\"0\" width=\"30\" height=\"1\"><br />";
		for($j=0;$j<$obcinane[0];$j++){
			echo "<img style=\"border:0; vertical-align:bottom;\" src=\"./images/tlo2.jpg\" border=\"0\" width=\"30\" height=\"2\"><br />";
		}
		echo "</td>";
		$obcinane=explode(".",$inna*100/$suma);
		$procent[5]=$obcinane[0];
		echo "<td  style=\" vertical-align:bottom;\">";
		echo "<img style=\"border:0; vertical-align:bottom;\" src=\"./images/tlo2.jpg\" border=\"0\" width=\"30\" height=\"1\"><br />";
		for($j=0;$j<$obcinane[0];$j++){
			echo "<img style=\"border:0; vertical-align:bottom;\" src=\"./images/tlo2.jpg\" border=\"0\" width=\"30\" height=\"2\"><br />";
		}
		echo "</td>";
		
		
		echo "</tr>";
		echo "<tr><th>Procent</th>";
		for($i=0;$i<6;$i++){
			echo "<td>$procent[$i] %</td>";
		}
		echo "</tr>";
		echo "</table>";	
	}
}
##################################################################
function wyswietl_link_tekst($adres, $ikona, $tekst){
	global $ikona_tekst;

	if($ikona_tekst=="tekst"){
		echo "<a href=\"$adres\">$tekst</a>";
	}
	if($ikona_tekst=="ikona"){
		echo "<a href=\"$adres\"><img style=\"border:0;\" src=\"$ikona\" alt=\"ikona\" /></a>";
	}
	if($ikona_tekst=="tekst_ikona"){
		echo "<a href=\"$adres\">$tekst <img style=\"border:0;\" src=\"$ikona\" alt=\"ikona\" /></a>";
	}
}
##################################################################

?>
</center>