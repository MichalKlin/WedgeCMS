<SCRIPT LANGUAGE="JavaScript"><!--
function check_form(form) {
 if(form.autor.value==""){
  alert("Musisz wypełnić pole autor!")
  return false
  }
 else {
  return true
 }
}
// -->
</SCRIPT>
<br>
<table class="ramka_contant" border="0" cellpadding="0" cellspacing="0" width="98%">
<tr>
<td class="ramka_text">
<?
$tabela = "mod_ksiega_gosci";
$path = "";

//stworzenie asocjacyjnej tabicy wartoĹci domyĹlnych, czyli takich ktĂłre nie byĹy w formularzu
$tablica_wart_domyslnych = array("mkg_id" => "0","mkg_active" => "YES");
$liczba_wyswietlanych = 20;		//liczba wyswietlanych wierszy na stronie

if (!isset($_GET[dodaj])) {
	if (!isset($_GET[next]) and !isset($_GET[prev])){
		display($tabela,$this->baza, $path,$liczba_wyswietlanych,0);
	}
	if (isset($_GET[next])){
		display($tabela,$this->baza, $path,$liczba_wyswietlanych,$_GET[next]);
	}
	if (isset($_GET[prev])){
		display($tabela,$this->baza, $path,$liczba_wyswietlanych,$_GET[prev]);
	}
}

//dodanie - formularz
if (isset($_GET[dodaj]) and !isset($_POST[dodaj])){
	dodaj($tabela,$this->baza, $path,$PAGE_NAME);
}

//dodaj do bazy
if (isset($_GET[dodaj]) and isset($_POST[dodaj])){	
	dodaj_baza($tabela,$this->baza, $path);
}
?>
</td>
</tr>
</table>
<?

/**/
###############################################################################################
function display($tabela,$baza, $path, $liczba_wierszy_na_stronie,$nr_wyswietlanego){	
	$nazwa_dodaj = "Dodaj nowy wpis";
	$nazwa_powrot = "Powrót";
	$nazwa_nastepny = "Następny";
	$nazwa_poprzedni = "Poprzedni";
	
	//link do DODAJ
	echo "<br /><h5 style=\"font-size: 14px; text-align: center;\"><a href=\"$path?dodaj=nowy\">$nazwa_dodaj</a></h5> ";

	$result = $baza->select("*",$tabela, "mkg_active=\"YES\"", "ORDER BY mkg_data DESC, mkg_id DESC");		
	$size_result = $baza->size_result($result);
		
	if ($size_result>0){						
		//wyliczenie ile ma byĂÂ wyswietlonych rekordÄĹw
		$ile_wyswietlic = $liczba_wierszy_na_stronie;
		if ($nr_wyswietlanego+$ile_wyswietlic>$size_result){
			$ile_wyswietlic = $size_result-$nr_wyswietlanego;
		}
		
		//przejÄąÂĂÂie przez wiersze ktÄĹre i tak nie bĂÂdĂÂ wyÄąÂwietlane
		for($i=0;$i<$nr_wyswietlanego;$i++){
			$row = $baza->row($result);
			$licznik++;
		}

		//wiersze danych
		if ($ile_wyswietlic>0){
			?>
			<br />
			<table width="98%" align="center" class="ksiega" cellpadding="0" cellspacing="0">			
			<?
			for($i=0;$i<$ile_wyswietlic;$i++){
				$row = $baza->row($result);
				$licznik++;
				?>
					<tr class="ramka_title">
						<td class="com_author">Autor: <strong><?=$row[mkg_autor]?></strong> </td>
						<td class="com_data" nowrap><?=$row[mkg_email]?>&nbsp;</td>
						<td class="com_data" nowrap><strong><?=$row[mkg_data]?></strong> </td>
						<!--td class="ramka_kom" style="border-right: 2px solid #000;">Typ: <strong><?=$row[mkg_typ]?></strong> </td-->
					</tr>
					<tr>
						<td class="com_content" colspan="4"><?=$row[mkg_tresc]?></td>
					</tr>
					<tr><td colspan="4" class="ramka_gwiazd" style="text-align: center;">* * *</td></tr>
				<?
			}
			?>
			</table>
			<?
		}
		
		//linki next i prev
		echo "<br /><h5>";
		if ($nr_wyswietlanego>0){
			$prev = $nr_wyswietlanego - $liczba_wierszy_na_stronie;
			echo "<a href=\"$path?prev=$prev\"> << $nazwa_poprzedni</a> ";
		}
		if ($nr_wyswietlanego<$size_result-$liczba_wierszy_na_stronie){
			$next = $nr_wyswietlanego + $liczba_wierszy_na_stronie;
			echo "<a href=\"$path?next=$next\">$nazwa_nastepny >> </a> ";
		}		
		echo "</h5><br />";	
	}
}

function dodaj($tabela,$baza, $path,$PAGE_NAME){
	$nazwa_dodaj = "Dodaj wpis";
	$nazwa_powrot = "Powrót";
	echo "<center><br><h5>Wpisując się do naszej księgi gości pamiętaj o podpisaniu się. <br>Podawanie adresu e-mail jest opcjonalne.</h5><br />
	<form method=\"post\" name=\"pytanie\" onSubmit='return check_form(this)'>";
	echo "<table border=\"0\" class=\"dodaj_ksiega_gosci\">";
	echo "<tr><th>Autor: </th><td class=\"left\"><input type=\"text\" name=\"autor\" size=\"35\"></td></tr>";
	//echo "<tr><th>Typ: </th><td class=\"left\"><select name=\"typ\">";
	//echo "<option>sympatyk</option> <option>kibic</option> <option>zawodnik</option> <option>przypadkowy</option> <option>przeciwnik</option>";
	//echo "</select></td></tr>";
	echo "<tr><th>E-mail: </th><td class=\"left\"><input type=\"text\" name=\"email\" size=\"35\"></td></tr>";
	echo "<tr><th>Treść: </th><td class=\"left\"><textarea name=\"tresc\" cols=\"30\" rows=\"5\"></textarea></td></tr>";
	echo "</table><br />";
	echo "<input type=\"submit\" value=\"$nazwa_dodaj\" name=\"dodaj\">";
	echo "</form>";
		
	//powrĂłt do wyĹĄszej strony
	echo "<br /><h5><a href=\"$PAGE_NAME.html\">$nazwa_powrot</a></center></h5><br />";
}

#################################################################################################################
//dodaj w bazie
function dodaj_baza($tabela,$baza,$path){
	$nazwa_powrot = "Powrót";
	$nazwa_rekord_dodany = "Nowy wpis został dodany. Dziękujemy.<br /> Po sprawdzeniu treści wpisu i uznaniu, że jest godny umieszczenia na stronie, nowy wpis zostanie wyświetlony.<br />";
	
	if ($_POST[autor]==""){
		echo "<center><h5>Nie wypełniłeś pola autor!</h5></center>";
	}
	else{
		$data = date("Y-m-d");
		$wartosci = "0, 
		\"".htmlspecialchars($_POST[autor])."\", 
		\"".htmlspecialchars($_POST[typ])."\", 
		\"".htmlspecialchars($_POST[email])."\", 
		\"".htmlspecialchars($data)."\", 
		\"".htmlspecialchars($_POST[tresc])."\", \"no\"";
//		echo $wartosci;
		$result = $baza->insert($tabela,$wartosci);
		if ($result){
			echo "<center><h5>$nazwa_rekord_dodany</h5>";
		}
		$result_m = $baza->select("value","cmsconfig","name='ADM_EMAIL'");
		$row_m = $baza->row($result_m);
		mail($row_m[value],"Nowy wpis - ksiega gosci","http://kometa.kryniczno.pl/adm/?modules=&manage=44");
		
		$url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; 
		$url = substr($url,0,strpos($url,"?"));
		reload_page($url,1);
	}
	
	//powrĂłt do wyĹĄszej strony
	echo "<br /><h5><a href=\"$path\">$nazwa_powrot</a></h5></center><br />";
}

?>