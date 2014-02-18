<?php
$nazwa_ankiety = $_SESSION['session_name']."_ankieta";

include_once("class/class_ankieta.php");
$width="90%";
$styl="";
$lista_ankiet = new lista_ankiet($this->baza, "mod_ankieta");
//wyświetlenie aktywnych ankiet
if (!isset($_POST[wyniki2])){
	$size = $lista_ankiet->get_size();
	$lista = $lista_ankiet->get_lista_ankiet();
	for($i=0;$i<$size;$i++){
		$ankieta = $lista[$i];
      //pobranie tablicy parametrow okreslajacej dana ankiete
      $tab=$ankieta->get();
      echo "<form name=\"ankiety\" method=\"post\">";
      echo "<table border=\"0\" width=\"$width\" align=\"center\">";
      echo "<tr><th class=\"temat\">$tab[1]</th></tr>";           
      echo "<tr><td class=\"wyroznienie\" >";
      
      //rozbicie listy odpowiedzi do tablicy
      $lista_odpowiedzi = explode("#",$tab[2]);
      $s = sizeof($lista_odpowiedzi);
     for ($jj=0; $jj<$s; $jj++){         
         echo "<input class=\"radio\" type=\"radio\" name=\"odpowiedz\" value=\"$jj\" ";          
         //pierwsza jest zaznaczona
         if ($jj==0){echo " checked ";}
         echo ">&nbsp;$lista_odpowiedzi[$jj]<br />";
      }
      echo "</td></tr>";
   
      echo "<tr><td class=\"center\"><center>
      <input type=\"hidden\" value=\"$tab[0]\" name=\"ankieta\">
      <input type=\"hidden\" value=\"$i\" name=\"nr_ankiety_w_liscie\">";
      
      if (!isset($_SESSION[$nazwa_ankiety])){
         echo "<input type=\"submit\" value=\"Głosuj\" name=\"glosuj\">";
      }
      echo "&nbsp;<input type=\"submit\" value=\"Wyniki\" name=\"wyniki2\"></center></td></tr>";
      echo "</table>"; 
      echo "</form>";
	}
}


//wyniki
if (isset($_POST[wyniki2])){
	$lista = $lista_ankiet->get_lista_ankiet();
	$tab=$lista[$_POST[nr_ankiety_w_liscie]]->get();
	$tablica_wynikow = explode("#",$tab[3]);
		
	//licznie sumy wszystkich odpowiedzi
	for ($i=0;$i<$tab[7];$i++){
		$suma+=$tablica_wynikow[$i];
	}
		
	echo "<table border=\"0\" width=\"$width\" align=\"center\">";
	echo "<tr><th class=\"temat\">$tab[1]</th></tr>";
				
	echo "<tr><td class=\"wyroznienie\">";
	$lista_odpowiedzi=explode("#",$tab[2]);
	echo "<table border=\"0\">";
	for ($jj=0;$jj<sizeof($lista_odpowiedzi);$jj++){
		echo "<tr><td class=\"wyroznienie\">";
		echo "$lista_odpowiedzi[$jj]";

		//wartość procentowa odpowiedzi
		$procent=$tablica_wynikow[$jj]*100/$suma;
		
		//wielkość wykresu
		$wykres=floor(($width-50)*$procent/100);
			
		if ($tablica_wynikow[$jj]==""){$tablica_wynikow[$jj]=0;}
			
		echo "&nbsp;-&nbsp;".floor($procent)."%($tablica_wynikow[$jj])<br />";
		echo "</td>
		</tr>
		<tr>
		<td class=\"left\">";
						
		//rysowanie wykresu
		echo "<img src=\"./modules/ankieta/images/ikona.jpg\" width=\"4\" height=\"10\">";
		for ($k=0;$k<$wykres;$k++){
			echo "<img src=\"./modules/ankieta/images/ikona.jpg\" width=\"5\" height=\"10\">";
		}
		//obliczenie wartości zaokrąglonej procentowej danej odpowiedzi
		echo "</td></tr>";
	}
	echo "</table>";
	echo "</td></tr>";
				
	echo "<tr><td class=\"center\" style=\"font-size: 12px;\"><center><a href=\"\">Powrót</a></center></td></tr>";
	echo "</table>";
}

//obsluga przycisku głosuj
if (isset($_POST[glosuj])){
	if(!isset($_SESSION[$nazwa_ankiety]) ){
		$lista_ankiet->glosuj($_POST[nr_ankiety_w_liscie], $_POST[ankieta], $_POST[odpowiedz]);
		$_SESSION[$nazwa_ankiety]="set";
	}
	else {
		echo "<h5 style=\"color:yellow; font-size: 12px;\" style=\"font-size: 12px;\">Już głosowałeś na tą ankietę</h5>";
	}
}
?>