<?
#########################################################################
#	Autor: Michał Klin  												#
#	Data: 30.10.2005        											#
#	Version: 1.4.1	        											#
#	Last change:														#
#				1.3.3 wszystkie typy danych z mysqla					#
#				1.4.1 pole typu file									#
#########################################################################
#	Opis:																#
#	Słuzy do generowanie gotowej tabeli edytora, z przyciskami itd.		#
#########################################################################
/*
co jeszcze:
- pole delete
- submity na <a>
- przerobić manage (sprawdzenie czy jest dana zmienna POST lub GET ma sie odbywac wew. funkcji)

wykryte zonki:
- jeśli zaznaczy się wszystkie checkboxy na ostatniej podstronie wyników to po usunieciu
  wraca na tą już pustą podstronę, w prawdzie nie powoduje to błędów, ale trzeba kliknąć poprzedni
  aby zobaczyć inne wyniki.
*/
#########################################################################

class edit_table {
	var $path;
	var $baza;
	var $tabela;
	var $klucz_glowny;
	var $tablica_kolumn;
	var $ilosc_kolumn;					//ilość kolumn widzialnych
	var $tablica_nazw_kolumn_wysw;
	var $tablica_wart_domyslnych;
	var $tabela_relacji;
	var $tab_rel_przedrostek;
	var $nazwa_klucz_obcy;
	var $nazwa_pole_klucza;
	var $nazwa_tabela_klucza;
	var $nazwa_pole_opcji;
	var $nazwa_tabela_glowna;
	var $pola_file_jpg;
	var $path_file_big;
	var $size_file_big;
	var $path_file_small;
	var $size_file_small;
	var $dodatkowe_funkcje;
	var $dodatkowe_funkcje_nazwy;
	
	var $where;
	var $slowniki;
	var $tab_odpowiednikow;
	var $tab_wart_skroconych;
	var $pole_select;
	var $single;
	var $kolumna_order;
	var $typ_order;
	var $liczba_wyswietlen_edit;
	var $liczba_wyswietlanych;
	var $left_checkbox;
	var $nazwa_dodaj = "Dodaj";
	var $nazwa_usun = "Usuń";
	var $nazwa_edytuj = "Edytuj";
	var $nazwa_powrot = "Powrót";
	var $nazwa_nastepny = "Następny";
	var $nazwa_poprzedni = "Poprzedni";
	var $nazwa_kolumna = "Kolumna";
	var $nazwa_wartosc = "Wartość";
	var $nazwa_wyb_date = "Wybierz datę";
	var $nazwa_wiersz_zmieniony = "Wiersz został zmieniony";
	var $nazwa_typ_nieznany = "ERROR - Typ nieznany!";
	var $nazwa_rekord_dodany = "Nowy rekord został dodany";
	var $nazwa_czy_usunac = "Czy na pewno usunąć poniższy wiersz?";
	var $nazwa_usuniety = "Wiersz został usunięty";
	var $nazwa_zachowaj_zmiany = "Zachowaj zmiany";

	//parametry konstruktora:
	// $path - aktualna ścieżka URL bez zbednych parametrów, tylko module
	// $baza - wskaźnik na bazę
	// $tabela - tabela z której będą pobierane informacje
	// $klucz_glowny - klucz główny tej tabeli
	// $tablica_kolumn - lista tylko tych kolumn które mają być widoczne i wyświetlone
	// $tablica_nazw_kolumn_wysw - tablica nazw jakie mają być wyswietlane
	// $tablica_wart_domyslnych - pola tabeli które nie są edytowalne, z góry są im przydzielone odpowiednie wartości
	// $tab_war_skroconych - określa pola, które będą wyświetlane w postaci skróconej (obciętej do 50 znaków)
	// $select - nazwa pola, która będzie ujeta w select.
	// $single - określa czy jeden czy więcej wierszy (null - dużo wierszy; yes - jeden wiersz)
	// $kolumna_order - nazwa kolumny z bazy według której nastąpi pierwsze sortwanie wyników
	// $typ_order - typ sortowania (ASC, DESC, null)
	// $liczba_wyswietlanych - domyślna liczba wyświetlanych rekordów wyników
	// $liczba_wyswietlen_edit - jeśli jest różna od null to na stronie pojawi sie pole do wybrania ilości wyników
	// $left_checkbox - jeśli jest różne od null będzie można usuwać wiersze zaznaczone checkboxem
	// $pola_file_jpg - pola typu zdjęcie
	// $path_file_big - ścieżka zdjęć 
	// $size_file_big - rozmiar zdjęć 
	// $path_file_small - ścieżka miniatur
	// $size_file_small - rozmiar miniatur
	// $dodatkowe_funkcje - rozmiar miniatur
	// $dodatkowe_funkcje_nazwy - rozmiar miniatur
	
	function edit_table($path, $baza, $tabela, $tabela_relacji, $tab_rel_przedrostek, $klucz_glowny, $tablica_kolumn, 
						$tablica_nazw_kolumn_wysw, $tablica_wart_domyslnych, $tab_war_skroconych=null, $select=null, 
						$single=null, $kolumna_order=null, $typ_order=null,$liczba_wyswietlanych=10,$liczba_wyswietlen_edit=null,
						$left_checkbox=null,$pola_file_jpg=null,$path_file_big=null,$size_file_big=null,$path_file_small=null,
						$size_file_small=null,$dodatkowe_funkcje=null,$dodatkowe_funkcje_nazwy=null){
		$this->path = $path;
		$this->baza = $baza;
		$this->tabela = $tabela;
		$this->klucz_glowny = $klucz_glowny;
		$this->tablica_kolumn = $tablica_kolumn;
		$this->tablica_nazw_kolumn_wysw = $tablica_nazw_kolumn_wysw;
		$this->ilosc_kolumn = sizeof($tablica_nazw_kolumn_wysw);
		$this->tablica_wart_domyslnych = $tablica_wart_domyslnych;
		$this->tab_rel_przedrostek = $tab_rel_przedrostek;
		$this->tabela_relacji = $tabela_relacji;
		$this->tab_wart_skroconych = $tab_war_skroconych;
		$this->pole_select = $select;
		$this->single = $single;
		$this->kolumna_order = $kolumna_order;
		$this->typ_order = $typ_order;
		$this->liczba_wyswietlanych = $liczba_wyswietlanych;
		$this->liczba_wyswietlen_edit = $liczba_wyswietlen_edit;
		$this->left_checkbox = $left_checkbox;
		$this->pola_file_jpg = $pola_file_jpg;
		$this->path_file_big = $path_file_big;
		$this->size_file_big = $size_file_big;
		$this->path_file_small = $path_file_small;
		$this->size_file_small = $size_file_small;
		$this->dodatkowe_funkcje = $dodatkowe_funkcje;
		$this->dodatkowe_funkcje_nazwy = $dodatkowe_funkcje_nazwy;
		
		$this->nazwa_tabela_glowna = $this->tab_rel_przedrostek."_primary_table";
		$this->nazwa_klucz_obcy = $this->tab_rel_przedrostek."_foreign_key";
		$this->nazwa_tabela_klucza = $this->tab_rel_przedrostek."_key_table";
		$this->nazwa_pole_klucza = $this->tab_rel_przedrostek."_key_field";
		$this->nazwa_pole_opcji = $this->tab_rel_przedrostek."_name_field";		

		//sprawdzamy powiązania z innymi tabelami, na podstawie tabeli relacji
		if ($this->tabela_relacji != null) {			
			$result = $this->baza->select("*",$this->tabela_relacji,$this->nazwa_tabela_glowna."='$this->tabela'");		
			$size_result = $this->baza->size_result($result);
			if ($size_result>0){
				for($i=0;$i<$size_result;$i++){
					$row = $this->baza->row($result);
					if ($i!=0){
						$where.=" AND ";
					}
					$tab_relacji.=" , ".$row[$this->nazwa_tabela_klucza];
					$where.=$row[$this->nazwa_klucz_obcy]."=".$row[$this->nazwa_pole_klucza];
					
					//ktore kolumny mają jakie odpoweidniki w tabeli relacji
					$tablica_odpowiednikow[$row[$this->nazwa_klucz_obcy]] = $row[$this->nazwa_pole_opcji];
				}
			}
		}
		$this->slowniki = $tab_relacji;
		$this->where = $where;
		$this->tab_odpowiednikow = $tablica_odpowiednikow;
	}
	
#################################################################################################################
	//zarządzanie edytowaniem tabeli
	function manage($liczba_wyswietlanych){
		//wyświetlenie wszystkich 
		if ($this->single == null){
			if (!isset($_GET[dodaj]) and !isset($_GET[usun]) and !isset($_GET[edytuj]) and !isset($_POST[usun_box])) {
				if (!isset($_GET[next]) and !isset($_GET[prev])){
					$this->display($liczba_wyswietlanych,0);
				}
				if (isset($_GET[next])){
					$this->display($liczba_wyswietlanych,$_GET[next]);
				}
				if (isset($_GET[prev])){
					$this->display($liczba_wyswietlanych,$_GET[prev]);
				}
			}
	
			//dodanie  formularz
			if (isset($_GET[dodaj]) and !isset($_POST[dodaj])){
				$this->dodaj();
			}
	
			//dodaj  do bazy
			if (isset($_GET[dodaj]) and isset($_POST[dodaj])){	
				$this->dodaj_baza($this->tablica_wart_domyslnych);
			}
	
			//usunięcie 
			if (isset($_GET[usun]) and !isset($_POST[usun])){
				$this->usun($_GET[usun]);
			}
	
			//usunięcie  z bazy
			if (isset($_GET[usun]) and isset($_POST[usun])){
				$this->usun_baza($_GET[usun]);
			}
	
			//edytowanie 
			if (isset($_GET[edytuj]) and !isset($_POST[edytuj])){
				$this->edytuj($_GET[edytuj]);
			}
	
			//edytowanie - zmiana w bazie
			if (isset($_GET[edytuj]) and isset($_POST[edytuj])){
				$this->edytuj_baza($_GET[edytuj]);
			}
			
			//usuwanie zaznaczonych checkbox
			if (isset($_POST[usun_box])){
				$this->usun_box();
			}
		}
		
		//pojedynczy wiersz
		else{
			if (!isset($_GET[dodaj]) and !isset($_GET[edytuj]) ) {
				$this->display_single();
			}
			//dodanie  formularz tylko gdy nie ma żadnego rekordu
			if (isset($_GET[dodaj]) and !isset($_POST[dodaj])){
				$this->dodaj(1);
			}	
			//dodaj  do bazy
			if (isset($_GET[dodaj]) and isset($_POST[dodaj])){	
				$this->dodaj_baza($this->tablica_wart_domyslnych);
			}
			//edytowanie 
			if (isset($_GET[edytuj]) and !isset($_POST[edytuj])){
				$this->edytuj($_GET[edytuj],1);
			}
			//edytowanie - zmiana w bazie
			if (isset($_GET[edytuj]) and isset($_POST[edytuj])){
				$this->edytuj_baza($_GET[edytuj]);
			}
		}
	}
#################################################################################################################
	//wyświetlenie tabeli
	function display($liczba_wierszy_na_stronie,$nr_wyswietlanego){	
		//echo $liczba_wierszy_na_stronie;
		$where_glowne = $this->where;
		//link do DODAJ
		echo "<a href=\"$this->path&#038;dodaj=nowy\">\n$this->nazwa_dodaj</a> ";

		//jeśli wciśnięto jakąś kolumnę to należy ułożyć zapytanie ORDER BY
		if (isset($_GET[kolumna])){
			$order = "ORDER BY ".$_GET[kolumna]." ".$_GET[kierunek];
		}
		//przy starcie edytora sprawdzamy czy nie wskazano kolumny i typu sortowania
		elseif ($this->kolumna_order!=null){
			$order = "ORDER BY ".$this->kolumna_order." ".$this->typ_order;
		}
		
		//wybranie wartości selected dla select
		if ((isset($_POST[pole_select]) and $_POST[pole_select]!=0) or (isset($_GET[pole_select]) and $_GET[pole_select]!=0)){
			$selected = $_POST[pole_select].$_GET[pole_select];
			if ($this->where==""){
				$this->where = " $this->pole_select=\"$selected\"";
			}
			else{
				$this->where .= " and $this->pole_select=\"$selected\"";
			}
		}
		
		$result = $this->baza->select("*",$this->tabela." ".$this->slowniki, $this->where, $order);		
		$size_result = $this->baza->size_result($result);
		
		if ($size_result>0){
			
			echo "<table border=\"0\" width=\"80%\">\n<tr><th style=\"margin:0px; padding:5px; border:0px; text-align:left;\">\n";
			//select z kolumnami - określenie adesu linku kolumny uwzględnienie select
			if (isset($_GET[pole_select]) or isset($_POST[pole_select])){
				$select_kol = "&pole_select=".$_GET[pole_select].$_POST[pole_select];
			}
			//ilosc wyświetleń z kolumnami - określenie adesu linku kolumny uwzględnienie ilość wyświetleń
			if (isset($_GET[liczba_wyswietlen]) or isset($_POST[liczba_wyswietlen])){
				$liczba_wier = "&liczba_wyswietlen=".$_GET[liczba_wyswietlen].$_POST[liczba_wyswietlen];
			}
			
			//wyświetl select
			if ($this->pole_select != null){
				//sprawdzenie czy pole select nie jest z tabeli relacji
				if ($this->tab_odpowiednikow[$this->pole_select]!=""){
					$pole_select = $this->tab_odpowiednikow[$this->pole_select];
				}
				else{
					$pole_select = $this->pole_select;
				}
				$result_s = $this->baza->select("DISTINCT $pole_select",$this->tabela." ".$this->slowniki,$where_glowne,"","");		
				$result_s2 = $this->baza->select("DISTINCT $this->pole_select",$this->tabela,"","","");		
				$size_result_s = $this->baza->size_result($result_s);
				if ($size_result_s>0){
					echo "<form method=\"post\" action=\"$this->path$liczba_wier\">\n";
					
					//sprawdzenie jaka jest nazwa dla kolumny select
					for ($h=0; $h<sizeof($this->tablica_kolumn); $h++){
						if ($this->tablica_kolumn[$h]==$this->pole_select){
							$ograniczenie = $this->tablica_nazw_kolumn_wysw[$h];
						}
					}
					
					echo "<p class=\"message\">\nOgranicz $ograniczenie do: <select name=\"pole_select\" onchange=\"this.form.submit()\">\n";// 
					echo "<option value=\"0\">\nWszystkie</option>";
					for ($i=0; $i<$size_result_s; $i++){
						//wynik gdy możliwa jest tabela relacji
						$row_s = $this->baza->row($result_s);
						//wynik do pobrania id (nieistotna tab relacji)
						$row_s2 = $this->baza->row($result_s2);
						$pole_s = $this->pole_select;
						
						//jeśli z tabeli relacji to inna wartość
						if ($this->tab_odpowiednikow[$this->pole_select]!=""){
							$wartosc = $row_s[$pole_select];
						}
						else{
							$wartosc = $row_s[$pole_s];
						}
						if ($_POST[pole_select]==$row_s2[$pole_s] or $_GET[pole_select]==$row_s2[$pole_s]) {
							echo "<option selected value=\"$row_s2[$pole_s]\">\n$wartosc</option>";
						}			
						else{	
							echo "<option value=\"$row_s2[$pole_s]\">\n$wartosc</option>";
						}
					}
					echo "</select></p>";
					echo "</form>";
				}
			}
			//koniec selest
			
			echo "</th><th style=\"margin:0px; padding:5px; border:0px; text-align:right;\">\n";
			
			//ilość wyświetleń na stronie
			if ($this->liczba_wyswietlen_edit!=null){
				echo "<form method=\"post\" action=\"$this->path$select_kol\">\n";
				if (!isset($_GET[liczba_wyswietlen]) and !isset($_POST[liczba_wyswietlen])){
					if ($this->liczba_wyswietlanych!=null){
						$ilosc_wysw = $this->liczba_wyswietlanych;
					}
					else{
						//jeśli nie podano domyślej wartości ilości wyświetlanej
						$ilosc_wysw = $liczba_wierszy_na_stronie;
					}
				}
				else{
					$ilosc_wysw = $_GET[liczba_wyswietlen] + $_POST[liczba_wyswietlen];
				}

				echo "Wyświetlaj: <input type=\"text\" name=\"liczba_wyswietlen\" value=\"$ilosc_wysw\" size=\"2\" onblur=\"this.form.submit()\">\n wierszy";				
				echo "</form>";
			}
			//koniec ilość wyśw
			
			//jeśli podano w edytorze ile wierszy na stronie
			if ($this->liczba_wyswietlanych!=null){
				$liczba_wierszy_na_stronie = $this->liczba_wyswietlanych;
			}
			
			//ile wyświetlić rekordów już dla tabeli
			if (isset($_GET[liczba_wyswietlen]) or isset($_POST[liczba_wyswietlen])){
				$liczba_wierszy_na_stronie = $_GET[liczba_wyswietlen] + $_POST[liczba_wyswietlen];
			}
			
			echo "</th></tr></table>\n";
			
			
			//form do usun box
			if ($this->left_checkbox!=null){
				echo "<form method=\"post\" name=\"pytanie_checkbox\">\n";
			}
			
			//tabela wyników
			echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"editTable\">\n";
			
			//wiersz nazw kolumn
			echo "<tr>";
			//jeśli mają być chceckboxy to nowa kolumna
			if ($this->left_checkbox!=null){
				echo "<th></th>";
			}
			echo "<th>LP</th>";
			for($i=0;$i<$this->ilosc_kolumn;$i++){
				//ustalenie kierunku sortowania
				$kierunek = "ASC";
				if (isset($_GET[kolumna]) and $_GET[kierunek]=="ASC" and $_GET[kolumna]==$this->tablica_kolumn[$i]){
					$kierunek = "DESC";
				}
				
				echo "<th><a href=\"$this->path&#038;kolumna=".$this->tablica_kolumn[$i]."&#038;kierunek=$kierunek$select_kol$liczba_wier\">\n".$this->tablica_nazw_kolumn_wysw[$i]."</a></th>";
			}
			echo "<th>Operacje</th></tr>";
			
			//wyliczenie ile ma być wyswietlonych rekordów
			$ile_wyswietlic = $liczba_wierszy_na_stronie;
			//echo $liczba_wierszy_na_stronie;
			if ($nr_wyswietlanego+$ile_wyswietlic>$size_result){
				$ile_wyswietlic = $size_result-$nr_wyswietlanego;
			}
			
			//przejśćie przez wiersze które i tak nie będą wyświetlane
			for($i=0;$i<$nr_wyswietlanego;$i++){
				$row = $this->baza->row($result);
				$licznik++;
			}
						
			//wiersze danych
			for($i=0;$i<$ile_wyswietlic;$i++){
				$row = $this->baza->row($result);
				$licznik++;
				$klucz = $this->klucz_glowny;
				$mod = $i%2;
				echo "<tr class=\"gray$mod\">";

				//jeśli mają być chceckboxy to nowa kolumna
				if ($this->left_checkbox!=null){
					echo "<td>\n<input type=\"checkbox\" name=\"checbox$row[$klucz]\">\n</td>\n";
				}
				
				echo "<td>\n$licznik</td>\n";
				for ($j=0; $j<$this->ilosc_kolumn;$j++){
					$kol = $this->tablica_kolumn[$j];
					//sprawdzenie czy ma być wyświetlane z głównej tabeli czy tabeli relacji
					if ($this->tab_odpowiednikow[$kol]==""){
						//sprawdzenie czy jest jakieś pole do skrócenia przy wyświetlaniu
						$jest_skrocony=false;
						if ($this->tab_wart_skroconych!=null){
							for ($k=0;$k<sizeof($this->tab_wart_skroconych); $k++){
								if ($this->tab_wart_skroconych[$k]==$this->tablica_kolumn[$j]){
									$jest_skrocony = true;
								}
							}
						}
						
						if ($jest_skrocony){
							echo "<td>\n";
							$this->obetnij_tekst($row[$kol],50);
							echo "</td>\n";
						}
						else{
							echo "<td>\n$row[$kol]</td>\n";
						}
					}
					else{
						$kol = $this->tab_odpowiednikow[$kol];
						echo "<td>\n$row[$kol]</td>\n";
					}
				}
				echo "<td>\n";
				if (isset($_GET[next])){
					$nextt = "&#038;next=$_GET[next]";
				}
				if (isset($_GET[prev])){
					$prevv = "&#038;prev=$_GET[prev]";
				}
				
				if ($row[$klucz]>0){
					echo "<a href=\"$this->path&#038;usun=$row[$klucz]$nextt$prevv$kolumna$select_kol$liczba_wier\">\n$this->nazwa_usun</a> ";
					echo "<a href=\"$this->path&#038;edytuj=$row[$klucz]$nextt$prevv$kolumna$select_kol$liczba_wier\">\n$this->nazwa_edytuj</a> ";
				}
				if (sizeof($this->dodatkowe_funkcje)>0){
					for ($i_dod=0; $i_dod<sizeof($this->dodatkowe_funkcje); $i_dod++){
						echo "<a href=\"$this->path&#038;".$this->dodatkowe_funkcje[$i_dod]."=$row[$klucz]$nextt$prevv$kolumna$select_kol$liczba_wier\">\n".$this->dodatkowe_funkcje_nazwy[$i_dod]."</a> ";
					}
				}
				echo "</td>\n</tr>";

			}
			//jeśli mają być chceckboxy to nowa kolumna
			if ($this->left_checkbox!=null){
				$liczba_wszyst_kol = $this->ilosc_kolumn+3;
				echo "<tr><td colspan=\"$liczba_wszyst_kol\" style=\"text-align:center;\">\n
				<input type=\"hidden\" name=\"usun_box\">\n
				<a onclick=\"document.pytanie_checkbox.submit();\" style=\"cursor:pointer;\" >Usuń zaznaczone</a>
				</td>\n</tr>";
			}
			echo "</table>\n";
			//form od chceckbox
			if ($this->left_checkbox!=null){
				echo "<input type=\"hidden\" name=\"ile_wszystkich\" value=\"$size_result\">\n";
				echo "</form>";
			}
			
			if (isset($_GET[kolumna])){
				$kolumna = "&#038;kolumna=".$_GET[kolumna]."&#038;kierunek=".$_GET[kierunek];
			}
			//linki next i prev
			if ($nr_wyswietlanego>0){
				$prev = $nr_wyswietlanego - $liczba_wierszy_na_stronie;
				echo "<a href=\"$this->path&#038;prev=$prev$kolumna$select_kol$liczba_wier\">\n$this->nazwa_poprzedni</a> ";
			}
			if ($nr_wyswietlanego<$size_result-$liczba_wierszy_na_stronie){
				$next = $nr_wyswietlanego + $liczba_wierszy_na_stronie;
				echo "<a href=\"$this->path&#038;next=$next$kolumna$select_kol$liczba_wier\">\n$this->nazwa_nastepny</a> ";
			}			
		}
	}
#################################################################################################################
	function display_single(){		
		$result = $this->baza->select("*",$this->tabela." ".$this->slowniki, $this->where, $order);		
		$size_result = $this->baza->size_result($result);
		
		if ($size_result>0){			
			//tabela wyników
			echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"editTable\">\n";
			
			//kolumna danych
			$row = $this->baza->row($result);
			
			//wiersz opcji
			echo "<tr><th colspan=\"2\">\n";
			$klucz = $this->klucz_glowny;
			echo "<a href=\"$this->path&#038;edytuj=$row[$klucz]\">\n$this->nazwa_edytuj</a> ";
			echo "</th></tr>";
			
			for ($j=0; $j<$this->ilosc_kolumn;$j++){
				echo "<tr>";
				echo "<th>".$this->tablica_nazw_kolumn_wysw[$j]."</th>";
				$kol = $this->tablica_kolumn[$j];
				//sprawdzenie czy ma być wyświetlane z głównej tabeli czy relacji
				if ($this->tab_odpowiednikow[$kol]==""){
					//sprawdzenie czy jest jakieś pole do skrócenia przy wyświetlaniu
					$jest_skrocony=false;
					if ($this->tab_wart_skroconych!=null){
						for ($k=0;$k<sizeof($this->tab_wart_skroconych); $k++){
							if ($this->tab_wart_skroconych[$k]==$this->tablica_kolumn[$j]){
								$jest_skrocony = true;
							}
						}
					}
						
					if ($jest_skrocony){
						echo "<td>\n";
						$this->obetnij_tekst($row[$kol],200);
						echo "</td>\n";
					}
					else{
						echo "<td>\n$row[$kol]</td>\n";
					}
				}
				else{
					$kol = $this->tab_odpowiednikow[$kol];
					echo "<td>\n$row[$kol]</td>\n";
				}
			echo "</tr>";
			}
			echo "</table>\n";			
		}
		else{
			//link do DODAJ
			echo "<a href=\"$this->path&#038;dodaj=nowy\">\n$this->nazwa_dodaj</a> ";
		}
	}	
#################################################################################################################
	//funkcja obcinająca tekst
	function obetnij_tekst($tekst,$dlugosc){
		if(strlen($tekst)<$dlugosc){
			echo $tekst;
		}
		else{
			$podstring=substr($tekst,0,$dlugosc-3)."...";
			$nowy_podstring=ereg_replace("<", "&#060;", $podstring);
			echo $nowy_podstring;
		}
	}
	
#################################################################################################################
	//dodaj nowy wiersz
	function dodaj($duzy=null){
		echo "<script language=JavaScript src=\"./javascript/kalendarz/kalendarz.js\" type=text/javascript></script>";

		//tablica asocjacyjna nazw kolumn i ich typów
		$kolumns = $this->baza->show_columns($this->tabela);
		$size_kolumns = $this->baza->size_result($kolumns);
		for ($i=0; $i<$size_kolumns; $i++) {
			$row = $this->baza->row($kolumns);
			$tab_kolumns[$row[0]] = $row[1];
		}
		
		echo "<form method=\"post\" name=\"pytanie\" enctype=\"multipart/form-data\">\n";
		$width="100%";
		echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"editTable\" width=\"$width\">\n";
		echo "<tr><th>$this->nazwa_kolumna</th><th>$this->nazwa_wartosc</th></tr>";
		for ($i=0;$i<$this->ilosc_kolumn;$i++){
			echo "<tr><th>".$this->tablica_nazw_kolumn_wysw[$i]."</th><td width=\"$width\">\n";
			
			//okreslenie czy jest pole typu file
			$jest_file = false;
			if ($this->pola_file_jpg!=null){
				for ($p=0; $p<sizeof($this->pola_file_jpg); $p++){
					if ($this->pola_file_jpg[$p]==$this->tablica_kolumn[$i]){
						$jest_file = true;
					}
				}
			}
			if ($jest_file==true){
				echo "<input type=\"file\" name=\"file_name_$i\" />\n";
			}			
			else{
				//rozbicie typu na nazwę typu i wielkość w danym typie
				$kol = $this->tablica_kolumn[$i];
				$typ = $tab_kolumns[$kol];
				$tab_typu = explode(")",$typ);
				$tab_typu = explode("(",$tab_typu[0]);
				$typ = $tab_typu[0];
				
				
				//wybór odpowiedniego typu pola formularza
				if ($typ=="varchar" or $typ=="float" or $typ=="char" or $typ=="double" or $typ=="datetime" or $typ=="timestamp" or $typ=="time" or $typ=="year"){
					echo "<input type=\"text\" name=\"pole_$i\">\n";
				}
				elseif ($typ == "date"){
					echo "<input type=\"text\" name=\"pole_$i\">\n";
					echo "<img onclick=javascript:rysuj(document.pytanie.pole_$i); alt=\"$this->nazwa_wyb_date\" src=\"../javascript/kalendarz/kalendarz.gif\" />\n (yyyy-mm-dd)";
				}
				elseif($typ=="text" or $typ=="blob" or $typ=="tinyblob" or $typ=="tinytext" or $typ=="mediumblob" or $typ=="mediumtext" or $typ=="mediumblob" or $typ=="longtext" or $typ=="longblob"){
					//$oFCKeditor = new FCKeditor("pole_$i") ;
					//$oFCKeditor->BasePath	= "./../javascript/edytor_www/";//$sBasePath ;
					//$oFCKeditor->Value		= 'dsafdsa' ;
					//$oFCKeditor->Create() ;
					if ($duzy==null){
						echo "<textarea name=\"pole_$i\" cols=\"45\" rows=\"5\">\n</textarea>";
					}
					else{
						echo "<textarea name=\"pole_$i\" cols=\"60\" rows=\"10\">\n</textarea>";
					}
				}
				elseif($typ=="enum" or $typ=="set"){
					echo "<select name=\"pole_$i\">\n";
					$wartosci=explode(",",$tab_typu[1]);
					for ($j=0;$j<sizeof($wartosci);$j++){		
						$wart=explode("'",$wartosci[$j]);
						echo "<option>$wart[1]</option>";
					}
					echo "</select>";	
				}
				elseif($typ=="tinyint" or $typ=="int" or $typ=="smallint" or $typ=="mediumint" or $typ=="bigint" or $typ=="decimal"){
					$size_result = 0;
					if ($this->tabela_relacji!=null) {
						$result = $this->baza->select("*",$this->tabela_relacji, $this->nazwa_tabela_glowna."=\"".$this->tabela."\" and ".$this->nazwa_klucz_obcy."=\"".$kol."\"");		
						$size_result = $this->baza->size_result($result);
					}
					if ($size_result>0){
						//wyciągniecie informacji o nazwach kolumn w odpowiednim słowniku
						$row = $this->baza->row($result);
						$pole_opcji = $row[$this->nazwa_pole_opcji];
						$pole_klucza = $row[$this->nazwa_pole_klucza];
						$tabela_klucza = $row[$this->nazwa_tabela_klucza];
						
						//zapytanie o wszystkie wiersze ze słownika
						$slownik = $this->baza->select("*",$tabela_klucza);
						$size_result = $this->baza->size_result($slownik);
						
						if ($size_result>0){
							echo "<select name=\"pole_$i\">\n";
							for ($j=0;$j<$size_result;$j++){
								$row = $this->baza->row($slownik);
								echo "<option value=\"$row[$pole_klucza]\">\n$row[$pole_opcji]</option>";
							}
							echo "</select>";
						}
					}
					else {
						echo "<input type=\"text\" name=\"pole_$i\">\n";
					}
				}
				else {
					//jesli nie wywryto co to za typ pola to komunikat błędu
					echo "<p class=\"message\">\n$this->nazwa_typ_nieznany</p>";				
				}
			}
			echo "</td>\n</tr>";
		}
		echo "</table>\n";
		echo "<p><input class=\"submit\" type=\"submit\" value=\"$this->nazwa_dodaj\" name=\"dodaj\">\n</p>";
		echo "</form>";
		
		//powrót do wyższej strony
		echo "<a href=\"$this->path\">\n$this->nazwa_powrot</a>";
	}
	
#################################################################################################################
	//dodaj w bazie
	function dodaj_baza($tabela_wart_domyslnych){
		//$ilosc_kolumn_niewidzialnych = sizeof($tabela_wart_domyslnych);
		//lista wszystkich kolumn
		$kolumns = $this->baza->show_columns($this->tabela);
		$size_kolumns = $this->baza->size_result($kolumns);

		for ($i=0; $i<$size_kolumns; $i++) {
			$row = $this->baza->row($kolumns);
			//tabela nazw wszystkich kolumn
			$tabela_wszystkich_kolumn[$i]=$row[0];
		}
		
		$licznik=0;
		for ($i=0; $i<$size_kolumns; $i++){
			$kolumna = $tabela_wszystkich_kolumn[$i];

			//przecinek przed każdym oprócz pierwszego
			if ($i!=0){
				$wartosci.=", ";
			}
			
			//jeśli znajduje się w tablicy domyślnych, to z tamtąd jest brana wartość
			if ($tabela_wart_domyslnych[$kolumna]!=""){
				$wartosci .= "\"".$tabela_wart_domyslnych[$kolumna]."\"";
			}
			
			//w przeciwnym wypadku ze zmienne $_POST
			else{
				//sprawdzenie czy nie jest to pole file
				$jest_file = false;
				if ($this->pola_file_jpg!=null){
					for ($p=0; $p<sizeof($this->pola_file_jpg); $p++){
						if ($this->pola_file_jpg[$p]==$kolumna){
							$jest_file = true;
							$offset_nr_zdjecia = $p;
							$sciezka_zdjec = $this->path_file_big[$p];
							$sciezka_zdjec_miniaturka = $this->path_file_small[$p];
							//Ustalamy rozmiar zdjec
							$rozmiar_b = $this->size_file_big[$p];
							$rozmiar_big = explode("x",$rozmiar_b);
							$x_big = $rozmiar_big[0];
							$y_big = $rozmiar_big[1];
							//Ustalamy rozmiar miniatur
							$rozmiar_s = $this->size_file_small[$p];
							$rozmiar_small = explode("x",$rozmiar_s);
							$x_small = $rozmiar_small[0];
							$y_small = $rozmiar_small[1];
						}
					}
				}
				$file_name = "file_name_".$licznik;
//				echo  $_FILES[$file_name]['tmp_name'];
				if ($jest_file==true and $_FILES[$file_name]['tmp_name']!=""){
					//przesłanie zdjęcia
					$nr_najwiekszego = $this->baza->get_max_id($this->tabela,$this->klucz_glowny);
					$nazwa_zdjecia = "";

					// Czesc zwiazana z dodawaniem zdjec na dysk serwera !!!
					if (is_uploaded_file($_FILES[$file_name]['tmp_name'])){
						$nr_najwiekszego = $nr_najwiekszego + 1;
						$nazwa_zdjecia="images_".$nr_najwiekszego."_$offset_nr_zdjecia.jpg"; //bierzemy sobie nazwe pliku do zapisania do bazy danych !!!
			
						//Tu wstawiamy normalne zdjecie
						move_uploaded_file($_FILES[$file_name]['tmp_name'],$sciezka_zdjec.$_FILES[$file_name]['name']);
						$this->zmniejszaj($sciezka_zdjec.$_FILES[$file_name]['name'],$x_big,$y_big,$sciezka_zdjec.$nazwa_zdjecia);
						unlink($sciezka_zdjec.$_FILES[$file_name]['name']);
						
						//Tu wstawiamy miniaturke
						if ($this->path_file_small!=null){
							$this->zmniejszaj($sciezka_zdjec.$nazwa_zdjecia,$x_small,$y_small,$sciezka_zdjec_miniaturka.$nazwa_zdjecia);					
						}
					}		
					$wartosci .= "\"".$nazwa_zdjecia."\"";
				}			
				else{ //zwykłe pole
					$wartosci .= "\"".$_POST[pole_.$licznik]."\"";
				}
				$licznik++;
			}
		}		
		
		//wstawienie w bazie nowego rekordu
		//echo $wartosci;
		
		$result = $this->baza->insert($this->tabela,$wartosci,"");
		if ($result){
			echo "<h4>$this->nazwa_rekord_dodany</h4>";
		}
		
		//powrót do wyższej strony
		$settings = $this->get_path_settings();
		echo "<a href=\"$this->path$settings\">\n$this->nazwa_powrot</a>";		
	}
	
#################################################################################################################
	//formularz do usuwania
	function usun($nr_wiersza){
		echo "<h4>$this->nazwa_czy_usunac</h4>";
		echo "<form method=\"post\">\n";
		
		//dodatkowy warunek określający tylko jeden rekord z tabeli - wiersz usuwany
		$where0 = $this->klucz_glowny."=".$nr_wiersza;
		//sprawdzenie czy warunek $where bedzie miał dalszą część
		if (sizeof($this->where)>0){
			$where0 .= " AND ";
		}
		
		//odpowiednie zapytanie do bazy
		$result = $this->baza->select("*",$this->tabela." ".$this->slowniki, $where0.$this->where);		
		$size_result = $this->baza->size_result($result);
		
		if ($size_result>0){
			echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"editTable\">\n";
			$row = $this->baza->row($result);
			
			//lecimy po ilości kolumn
			for($i=0;$i<$this->ilosc_kolumn;$i++){
				echo "<tr>";
				
				//nazwa kolumny
				echo "<th>".$this->tablica_nazw_kolumn_wysw[$i]."</th>";
				
				//wartość tej kolumny dla usuwanego wiersza
				$kol = $this->tablica_kolumn[$i];
				//brane z tabeli relacji
				if ($this->tab_odpowiednikow[$kol]==""){
					echo "<td>\n$row[$kol]</td>\n";
				}
				else{
					$kol = $this->tab_odpowiednikow[$kol];
					echo "<td>\n$row[$kol]</td>\n";
				}
				echo "</tr>";				
			}
			
		}
		echo "</table>\n";	
		echo "<p><input class=\"submit\" type=\"submit\" value=\"$this->nazwa_usun\" name=\"usun\">\n</p>";
		echo "</form>";
		
		//powrót do wyższej strony
		$settings = $this->get_path_settings();
		echo "<a href=\"$this->path$settings\">\n$this->nazwa_powrot</a>";		
	}	
#################################################################################################################
	//usuwanie z bazy
	function usun_baza($nr_wiersza){
		$where = $this->klucz_glowny."=".$nr_wiersza;
		$result = $this->baza->select("*",$this->tabela, $where);
		$size_result = $this->baza->size_result($result);
		if ($size_result>0){
			$row = $this->baza->row($result);
			if ($this->pola_file_jpg!=null){
				for ($p=0; $p<sizeof($this->pola_file_jpg); $p++){
					$nazwa_pola = $this->pola_file_jpg[$p];
					$nazwy_plikow[$p] = $row[$nazwa_pola];
				}
			}
		}
			
		$result = $this->baza->delete($this->tabela, $where);
		if ($result){
			echo "<h4>$this->nazwa_usuniety</h4>";
		}
		
		if ($this->pola_file_jpg!=null){
			for ($p=0; $p<sizeof($this->pola_file_jpg); $p++){
				if ($this->path_file_big[$p]!=null and $nazwy_plikow[$p]!=""){
					$sciezka_zdjec = $this->path_file_big[$p];
					unlink($sciezka_zdjec.$nazwy_plikow[$p]);
					//echo $sciezka_zdjec.$nazwy_plikow[$p];
				}
				if ($this->path_file_big[$p]!=null and $nazwy_plikow[$p]!=""){
					$sciezka_zdjec_min = $this->path_file_small[$p];
					unlink($sciezka_zdjec_min.$nazwy_plikow[$p]);
					//echo $sciezka_zdjec_min.$nazwy_plikow[$p];
				}
			}
		}

		//powrót do wyższej strony
		$settings = $this->get_path_settings();
		echo "<a href=\"$this->path$settings\">\n$this->nazwa_powrot</a>";		
	}	
#################################################################################################################
	//formularz edytowania
	function edytuj($nr_wiersza, $duzy=null){
		echo "<script language=JavaScript src=\"./javascript/kalendarz/kalendarz.js\" type=text/javascript></script>";

		//tablica asocjacyjna nazw kolumn i ich typów
		$kolumns = $this->baza->show_columns($this->tabela);
		$size_kolumns = $this->baza->size_result($kolumns);
		for ($i=0; $i<$size_kolumns; $i++) {
			$row = $this->baza->row($kolumns);
			$tab_kolumns[$row[0]] = $row[1];
		}

		$where0 = $this->klucz_glowny."=".$nr_wiersza;
		if (sizeof($this->where)>0){
			$where0 .= " AND ";
		}

		for ($i=0;$i<$this->ilosc_kolumn;$i++){
			if ($i!=0) {$pola .= ", ";}
			$pola .= $this->tablica_kolumn[$i];
		}
		
		$result = $this->baza->select($pola ,$this->tabela." ".$this->slowniki, $where0.$this->where);		
		//$size_result = $this->baza->size_result($result);
		$row = $this->baza->row($result);
		$licznik = 0;
		
		echo "<form method=\"post\" name=\"pytanie\" enctype=\"multipart/form-data\">\n";
		$width="100%";
		echo "<table cellpadding=\"0\" cellspacing=\"0\" class=\"editTable\" width=\"$width\">\n";
		echo "<tr><th>$this->nazwa_kolumna</th><th>$this->nazwa_wartosc</th></tr>";
		for ($i=0;$i<$this->ilosc_kolumn;$i++){
			echo "<tr><th>".$this->tablica_nazw_kolumn_wysw[$i]."</th><td width=\"$width\">\n";
			$jest_file = false;
			if ($this->pola_file_jpg!=null){
				for ($p=0; $p<sizeof($this->pola_file_jpg); $p++){
					if ($this->pola_file_jpg[$p]==$this->tablica_kolumn[$i]){
						$jest_file = true;
					}
				}
			}
			if ($jest_file==true){
				echo "<input type=\"file\" name=\"file_name_$i\" />\n<br />\n
				Jeśli zamierzasz zmienić zdjęcie wskaż je,<br />\nw przeciwnym przypadku pozostaw pole puste.";
				echo "<input type=\"hidden\" name=\"old_file_name_$i\" value=\"".$row[$licznik]."\" />\n";
			}			
			else{			
				//rozbicie typu na nazwę typu i wielkość w danym typie
				$kol = $this->tablica_kolumn[$i];
				$typ = $tab_kolumns[$kol];
				$tab_typu = explode(")",$typ);
				$tab_typu = explode("(",$tab_typu[0]);
				$typ = $tab_typu[0];
				
				//wybór odpowiedniego typu pola formularza
				if ($typ=="varchar" or $typ=="float" or $typ=="char" or $typ=="double" or $typ=="datetime" or $typ=="timestamp" or $typ=="time" or $typ=="year"){
					echo "<input type=\"text\" name=\"pole_$i\" value=\"".$row[$licznik]."\">\n";
				}
				elseif ($typ == "date"){
					echo "<input type=\"text\" name=\"pole_$i\" value=\"".$row[$licznik]."\">\n";
					echo "<img onclick=javascript:rysuj(document.pytanie.pole_$i); alt=\"$this->nazwa_wyb_date\" src=\"./../javascript/kalendarz/kalendarz.gif\" />\n (yyyy-mm-dd)";
				}
				elseif($typ=="text" or $typ=="blob" or $typ=="tinyblob" or $typ=="tinytext" or $typ=="mediumblob" or $typ=="mediumtext" or $typ=="mediumblob" or $typ=="longtext" or $typ=="longblob"){

//					$oFCKeditor = new FCKeditor("pole_$i") ;
//					$oFCKeditor->BasePath	= "./../javascript/edytor_www/";//$sBasePath ;
//					$oFCKeditor->Value		= $row[$licznik] ;
//					$oFCKeditor->Create() ;
					if ($duzy==null){
						echo "<textarea name=\"pole_$i\" cols=\"65\" rows=\"5\">\n$row[$licznik]</textarea>";
					}
					else{
						echo "<textarea name=\"pole_$i\" cols=\"60\" rows=\"10\">\n$row[$licznik]</textarea>";
					}
				}
				elseif($typ=="enum" or $typ=="set"){
					echo "<select name=\"pole_$i\">\n";
					$wartosci=explode(",",$tab_typu[1]);
					for ($j=0;$j<sizeof($wartosci);$j++){		
						$wart=explode("'",$wartosci[$j]);
						if ($row[$licznik]==$wart[1]){
							echo "<option selected>\n$wart[1]</option>";
						}
						else{
							echo "<option>$wart[1]</option>";
						}
					}
					echo "</select>";	
				}
				elseif($typ=="tinyint" or $typ=="int" or $typ=="smallint" or $typ=="mediumint" or $typ=="bigint" or $typ=="decimal"){
					$size_result=0;
					if ($this->tabela_relacji!=null) {						
						$result = $this->baza->select("*",$this->tabela_relacji, $this->nazwa_tabela_glowna."=\"".$this->tabela."\" and ".$this->nazwa_klucz_obcy."=\"".$kol."\"");		
						$size_result = $this->baza->size_result($result);
					}
					if ($size_result>0){
						//wyciągniecie informacji o nazwach kolumn w odpowiednim słowniku
						$row_tr = $this->baza->row($result);
						$pole_opcji = $row_tr[$this->nazwa_pole_opcji];
						$pole_klucza = $row_tr[$this->nazwa_pole_klucza];
						$tabela_klucza = $row_tr[$this->nazwa_tabela_klucza];
						
						//zapytanie o wszystkie wiersze ze słownika
						$slownik = $this->baza->select("*",$tabela_klucza);
						$size_result = $this->baza->size_result($slownik);
						
						if ($size_result>0){
							echo "<select name=\"pole_$i\">\n";						
							for ($j=0;$j<$size_result;$j++){
								$row_obcy = $this->baza->row($slownik);
								if ($row[$licznik]==$row_obcy[$pole_klucza]){
									echo "<option selected value=\"$row_obcy[$pole_klucza]\">\n$row_obcy[$pole_opcji]</option>";
								}
								else{
									echo "<option value=\"$row_obcy[$pole_klucza]\">\n$row_obcy[$pole_opcji]</option>";
								}
							}
							echo "</select>";
						}
					}
					else {
						echo "<input type=\"text\" name=\"pole_$i\" value=\"".$row[$licznik]."\">\n";
					}
				}
				else {
					//jesli nie wywryto co to za typ pola to komunikat błędu
					echo "<p class=\"message\">\n$this->nazwa_typ_nieznany</p>";				
				}
			}
			echo "</td>\n</tr>";
			$licznik++;
		}
//		echo "<tr><th>a</th><td width=\"100%\">\n<input type=\"text\">\n</td>\n</tr>";
		echo "</table>\n";
		echo "<p><input class=\"submit\" type=\"submit\" value=\"$this->nazwa_zachowaj_zmiany\" name=\"edytuj\">\n</p>";
		echo "</form>";		

		//powrót do wyższej strony
		$settings = $this->get_path_settings();
		echo "<a href=\"$this->path$settings\">\n$this->nazwa_powrot</a>";		
	}
#################################################################################################################
	//zmiana w bazie
	function edytuj_baza($nr_wiersza){
		for ($i=0;$i<$this->ilosc_kolumn;$i++){
			$kolumna = $this->tablica_kolumn[$i];
			if ($i!=0) {$pola_set .= ",";}
			
			//sprawdzenie czy nie jest to pole file
			$jest_file = false;
			if ($this->pola_file_jpg!=null){
				for ($p=0; $p<sizeof($this->pola_file_jpg); $p++){
					if ($this->pola_file_jpg[$p]==$kolumna){
						$jest_file = true;
						$offset_nr_zdjecia = $p;
						$sciezka_zdjec = $this->path_file_big[$p];
						$sciezka_zdjec_miniaturka = $this->path_file_small[$p];
						//Ustalamy rozmiar zdjec
						$rozmiar_b = $this->size_file_big[$p];
						$rozmiar_big = explode("x",$rozmiar_b);
						$x_big = $rozmiar_big[0];
						$y_big = $rozmiar_big[1];
						//Ustalamy rozmiar miniatur
						$rozmiar_s = $this->size_file_small[$p];
						$rozmiar_small = explode("x",$rozmiar_s);
						$x_small = $rozmiar_small[0];
						$y_small = $rozmiar_small[1];
					}
				}
			}

			if ($jest_file==true){
				$file_name = "file_name_".$i;
				//echo $_FILES[$file_name]['tmp_name'];
				if ($_FILES[$file_name]['tmp_name']!=""){
					//przesłanie zdjęcia
					$nr_najwiekszego = $this->baza->get_max_id($this->tabela,$this->klucz_glowny);
					$nazwa_zdjecia = "";

					// Czesc zwiazana z dodawaniem zdjec na dysk serwera !!!
					if (is_uploaded_file($_FILES[$file_name]['tmp_name'])){
						$nr_najwiekszego = $nr_wiersza;
						$nazwa_zdjecia="images_".$nr_najwiekszego."_$offset_nr_zdjecia.jpg"; //bierzemy sobie nazwe pliku do zapisania do bazy danych !!!
			
						//Tu wstawiamy normalne zdjecie
						move_uploaded_file($_FILES[$file_name]['tmp_name'],$sciezka_zdjec.$_FILES[$file_name]['name']);
						$this->zmniejszaj($sciezka_zdjec.$_FILES[$file_name]['name'],$x_big,$y_big,$sciezka_zdjec.$nazwa_zdjecia);
						unlink($sciezka_zdjec.$_FILES[$file_name]['name']);
						
						//Tu wstawiamy miniaturke
						if ($this->path_file_small!=null){
							$this->zmniejszaj($sciezka_zdjec.$nazwa_zdjecia,$x_small,$y_small,$sciezka_zdjec_miniaturka.$nazwa_zdjecia);					
						}
					}		
					//$wartosci .= "\"".$nazwa_zdjecia."\"";
					$pola_set .= $this->tablica_kolumn[$i]."=\"".$nazwa_zdjecia."\"";
				}
				else {
					$pola_set .= $this->tablica_kolumn[$i]."=\"".$_POST[old_file_name_.$i]."\"";
				}
			}			
			else{ //zwykłe pole
				$pola_set .= $this->tablica_kolumn[$i]."=\"".$_POST[pole_.$i]."\"";
			}		
		}				
		
		$where = $this->klucz_glowny."=".$nr_wiersza;		
		$result = $this->baza->update($this->tabela, $pola_set, $where, "");
		if ($result){
			echo "<h4>$this->nazwa_wiersz_zmieniony</h4>";
		}

		//powrót do wyższej strony
		$settings = $this->get_path_settings();
		echo "<a href=\"$this->path$settings\">\n$this->nazwa_powrot</a>";		
	}	
#################################################################################################################
	function usun_box(){
		$result = $this->baza->select("*",$this->tabela." ".$this->slowniki, $this->where, $order, "");		
		$size_result = $this->baza->size_result($result);
		
		if ($size_result>0){
			$klucz = $this->klucz_glowny;			
			for($i=0; $i<$size_result; $i++){
				$row = $this->baza->row($result);
			
				$nazwa = "checbox$row[$klucz]";
				if ($_POST[$nazwa]){
					$where = "$klucz = $row[$klucz]";

					$result2 = $this->baza->select("*",$this->tabela, $where);
					$size_result2 = $this->baza->size_result($result2);
					if ($size_result2>0){
						$row2 = $this->baza->row($result2);
						if ($this->pola_file_jpg!=null){
							for ($p=0; $p<sizeof($this->pola_file_jpg); $p++){
								$nazwa_pola = $this->pola_file_jpg[$p];
								$nazwy_plikow[$p] = $row2[$nazwa_pola];
							}
						}
					}

					//usunięcie z bazy
					$result_d = $this->baza->delete($this->tabela,$where);
		
					//usunięcie z serwera
					if ($this->pola_file_jpg!=null){
						for ($p=0; $p<sizeof($this->pola_file_jpg); $p++){
							if ($this->path_file_big[$p]!=null and $nazwy_plikow[$p]!=""){
								$sciezka_zdjec = $this->path_file_big[$p];
								unlink($sciezka_zdjec.$nazwy_plikow[$p]);
							}
							if ($this->path_file_big[$p]!=null and $nazwy_plikow[$p]!=""){
								$sciezka_zdjec_min = $this->path_file_small[$p];
								unlink($sciezka_zdjec_min.$nazwy_plikow[$p]);
							}
						}
					}				
				}
			}
			echo "<p class=\"message\">\nWskazane rekordy zostały usunięte</p>";
		}
			
		//powrót do wyższej strony
		$settings = $this->get_path_settings();
		echo "<a href=\"$this->path$settings\">\n$this->nazwa_powrot</a>";		
	}
#################################################################################################################
	function get_path_settings(){
		if (isset($_GET[kolumna])){
			$kolumna = "&#038;kolumna=".$_GET[kolumna];
		}
		if (isset($_GET[kierunek])){
			$kierunek = "&#038;kierunek=".$_GET[kierunek];
		}
		if (isset($_GET[pole_select])){
			$select_kol = "&#038;pole_select=".$_GET[pole_select];
		}
		if (isset($_GET[liczba_wyswietlen])){
			$liczba_wier = "&#038;liczba_wyswietlen=".$_GET[liczba_wyswietlen];
		}
		if (isset($_GET[next])){
			$nextt = "&#038;next=$_GET[next]";
		}
		if (isset($_GET[prev])){
			$prevv = "&#038;prev=$_GET[prev]";
		}
		$path = $kierunek.$kolumna.$select_kol.$liczba_wier.$nextt.$prevv;
		return $path;
	}
#################################################################################################################
	function zmniejszaj($IMAGE_SOURCE,$THUMB_X,$THUMB_Y,$OUTPUT_FILE){
		//Funkcja odpowiedzialna za zmiane wymiarow pliku graficznego JPG 
	  	$BACKUP_FILE = $OUTPUT_FILE . "_backup.jpg";
		copy($IMAGE_SOURCE,$BACKUP_FILE);
		$IMAGE_PROPERTIES = GetImageSize($BACKUP_FILE);
		if (!$IMAGE_PROPERTIES[2] == 2) {
			return(0);		  
		} 
		else {
			$SRC_IMAGE = ImageCreateFromJPEG($BACKUP_FILE);
		   	$SRC_X = ImageSX($SRC_IMAGE);
		   	$SRC_Y = ImageSY($SRC_IMAGE);
		   
		   	//Spradzamy czy zdjeci w poziomie czy w pionie ...
		   	if($SRC_X<$SRC_Y && $THUMB_X>$THUMB_Y){$tmp=$THUMB_Y; $THUMB_Y=$THUMB_X; $THUMB_X=$tmp;}
		   	if($SRC_X>$SRC_Y && $THUMB_X<$THUMB_Y){$tmp=$THUMB_Y; $THUMB_Y=$THUMB_X; $THUMB_X=$tmp;}
		      
		   	if (($THUMB_Y == "0") && ($THUMB_X == "0")) {
				return(0);
			} 
			elseif ($THUMB_Y == "0") {
		     	$SCALEX = $THUMB_X/($SRC_X-1);
		     	$THUMB_Y = $SRC_Y*$SCALEX;
		   	} 
		   	elseif ($THUMB_X == "0") {
		     	$SCALEY = $THUMB_Y/($SRC_Y-1);
		     	$THUMB_X = $SRC_X*$SCALEY;
		   	}
		   	$THUMB_X = (int)($THUMB_X);
		   	$THUMB_Y = (int)($THUMB_Y);
		   
		   	$DEST_IMAGE = imagecreatetruecolor($THUMB_X, $THUMB_Y);
		   
		   	unlink($BACKUP_FILE);
		    if (!imagecopyresampled($DEST_IMAGE, $SRC_IMAGE, 0, 0, 0, 0, $THUMB_X, $THUMB_Y, $SRC_X, $SRC_Y)){
		     	imagedestroy($SRC_IMAGE);
		     	imagedestroy($DEST_IMAGE);
		     	return(0);
		   	} 
		   	else {
		     	imagedestroy($SRC_IMAGE);
		     	if (ImageJPEG($DEST_IMAGE,$OUTPUT_FILE)) {
		       		imagedestroy($DEST_IMAGE);
		       		return(1);
		     	}
		     	imagedestroy($DEST_IMAGE);
		   	}
		   	return(0);
		}
	} # end zmniejszaj
###################################################################################
}
?>