###########################
# 31.10.2005 MIK
############################
<?
	$result = $baza->sql_query("show tables");
	$size_result = $baza->size_result($result);
	if ($size_result){
		for ($i=0; $i<$size_result; $i++){
			$row = mysql_result($result,$i);
			$ile_kolumn=create_table($row);
			insert_data($row,$ile_kolumn);
		}
	}
/*	
	$zapytanie="show tables";
	$wynik_szukania=mysql_query($zapytanie);
	if (!$wynik_szukania){echo "ERROR 1"; }
	$ile=mysql_num_rows($wynik_szukania);	
	for($i=1;$i<=$ile;$i++){
		$wiersz=mysql_result($wynik_szukania,$i-1);
		if($wiersz!="admin_database_backup"){
			$ile_kolumn=create_table($wiersz);
			insert_data($wiersz,$ile_kolumn);
		}
		echo "\n";
	}
*/	
##########################################################################################
function create_table($tabela){
	//zapytanie o wszystkie kolumny w tabeli
	$zapytanie="show columns from $tabela";
	$wynik_szukania=mysql_query($zapytanie);
	if (!$wynik_szukania){echo "ERROR 2"; }
	$ile=mysql_num_rows($wynik_szukania);	
	$ile_kolumn=$ile;

	//tworzenie odpowiedniej instrukcji SQL
	echo "CREATE TABLE `$tabela` ( \n";
	for($i=0;$i<$ile;$i++){
		
		//nazwy kolumn
		echo " `".mysql_result($wynik_szukania,$i,0)."` ";
		
		//typy kolumn
		echo " ".mysql_result($wynik_szukania,$i,1)." ";
		
		//czy jest NOT NULL
		if(mysql_result($wynik_szukania,$i,2)==""){
			echo " NOT NULL ";
		}
		
		//co jest kluczem g��wnym
		if(mysql_result($wynik_szukania,$i,3)=="PRI"){
			$primary=" PRIMARY KEY (`".mysql_result($wynik_szukania,$i,0)."`)\n";
			$pole_primary=mysql_result($wynik_szukania,$i,0);
		}
		else{
			//jaka jest warto�� domy�lna
			echo " default '".mysql_result($wynik_szukania,$i,4)."' ";
		}
		
		//auto_increment
		echo mysql_result($wynik_szukania,$i,5).",";
		echo "\n";
	}
	
	//co jest kluczem g��wnym
	echo $primary;

	//znalezienie warto�ci nast�pnej auto_increment
	$zapytanie="select MAX($pole_primary) from $tabela";
	$wynik_szukania=mysql_query($zapytanie);
	if (!$wynik_szukania){echo "ERROR 3"; }
	$wiersz=mysql_result($wynik_szukania,0)+1;
	
	//typ tabeli i warto�� auto_increment
	echo " ) TYPE=MyISAM AUTO_INCREMENT=$wiersz; \n\n";
	
	return $ile_kolumn;
}
##########################################################################################
function insert_data($tabela,$ile_kolumn){
	//zapytanie o ilo�� rtekord�w danej tabeli
	$zapytanie="select * from $tabela";
	$wynik_szukania=mysql_query($zapytanie);
	if (!$wynik_szukania){echo "ERROR 4"; }
	$ile=mysql_num_rows($wynik_szukania);

	for($i=0;$i<$ile;$i++){
		$wiersz=mysql_fetch_row($wynik_szukania);
		echo "INSERT INTO `$tabela` VALUES (";
			for($j=0;$j<$ile_kolumn;$j++){
				if($j==0){
					echo "'$wiersz[$j]'";
				}
				else{
					echo ",'";
					$wartosc=$wiersz[$j];
					for($k=0;$k<strlen($wartosc);$k++){
						if($wartosc[$k]=="'"){
							echo "''";
						}
						else{
							echo $wartosc[$k];
						}
					}
					echo "'";
//					echo ",'$wiersz[$j]'";
				}
			}
		echo ");\n";
	}	
}
##########################################################################################
?>