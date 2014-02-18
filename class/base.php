<?
#########################################################################
#	Autor: Michał Klin  												#
#	Data: 30.06.2005        											#
#########################################################################
#	Opis:																#
#	klasa bazy danych, ustawia połączenie z bazą oraz wszystkie metordy #
#	związane z dostępem do bazy i operacjami na danych w bazie			#
#########################################################################

class baza {
	var $host;
	var $name;
	var $login;
	var $password;
	var $port = "3306";
	
	//konstruktor, pobiera dane konfiguracyjne z pliku config_db.inc umieszczonego w głównym katalogu
	//można to zmienić - zmienna $path
	function baza($path="./config/config_db.inc") {
//		$path = "./config/config_db.inc";
		$file = new plik($path, "r");
		if($file_content = $file->czytaj()) {
			
			//pobieranie po linii z pliku konfiguracyjnego
			$lines = explode("\n",$file_content);
			for($i=0;$i<sizeof($lines);$i++){
				
				//nie brał linii z komentarzami i pustych linii
				if(!strstr($lines[$i],"#") and strlen($lines[$i])>1){
					
					//rozbicie na tokeny każdej linii
					$token = explode("\"", $lines[$i]);
					switch ($token[0]) {
						case "host ":  $this->host = $token[1];
						case "name ":  $this->name = $token[1];
						case "login ":  $this->login = $token[1];
						case "password ":  $this->password = $token[1];
						case "port ":  $this->port = $token[1];
					}
				}
			}
		}
		else {
			$this->host = "";
			$this->name = "";
			$this->login = "";
			$this->password = "";
			$this->port = "";
		}
	}
	
#################### otwarcie i zamknięcie połączenia ###################################	 
	//połączenie z bazą danych
	function connect() {
		if (strlen($this->port)>0){
			$this->host = $this->host.":".$this->port;
		}
		@$db=mysql_connect("$this->host", "$this->login", "$this->password");
		echo mysql_error();
		if (!$db) {
			echo "<p>Nie można utworzyć połączenia z bazą danych.</p>";
			exit;
		}
//		mysql_query("set character set utf8");
    	mysql_select_db("$this->name");
	}
	
	//zamknięcie połączenia z bazą danych
	function disconect () {
		mysql_close();
	}
	
#################### podstawowe zapytania ###################################	 
	//zapytanie select
	function select($lista_pol, $nazwa_tabeli, $where="", $another="",$echo="") {
		//utworzenie zapytania
		$query = "SELECT $lista_pol FROM $nazwa_tabeli";
		if (strlen($where)>0) {
			$query .= " WHERE $where";
		}
		$query .= " $another";
		
		if ($echo!=""){
			echo $query."<br />";
		}
		
		return $this->result($query);
	}
	
	//wstawienie wiersza
	function insert($nazwa_tabeli, $wartosci, $kolumny="", $echo="") {
		$query = "INSERT INTO $nazwa_tabeli $kolumny VALUES($wartosci)";
		if ($echo!=""){
			echo $query."<br />";
		}
		return $this->result($query);
	}
	
	//modyfikowanie wiersza
	function update($nazwa_tabeli, $pole_wartosc, $where="",$echo="") {
		$query = "UPDATE $nazwa_tabeli SET $pole_wartosc ";
		if (strlen($where)>0) {
			$query .= " WHERE $where";
		}
		if ($echo!=""){
			echo $query."<br />";
		}
		return $this->result($query);
	}
	
	//usuwanie wiersza
	function delete($nazwa_tabeli, $where="") {
		$query = "DELETE FROM $nazwa_tabeli ";
		if (strlen($where)>0) {
			$query .= " WHERE $where";
		}
		return $this->result($query);
	}
	
	//tworzenie nowej tabeli
	function create($nazwa_tabeli, $kolumny_wlasciwosci) {
		$query = "CREATE TABLE $nazwa_tabeli ($kolumny_wlasciwosci)";
		return $this->result($query);
	}
	
	//usuwanie tabeli
	function drop($nazwa_tabeli) {
		$query = "DROP TABLE $nazwa_tabeli";
		return $this->result($query);
	}
	
	//dowolne zapytanie
	function sql_query($zapytanie) {
		$query = "$zapytanie";
		return $this->result($query);
	}

	//zwraca numer id ostatnio dodanego wiersza
	function last_insert_id() {
		return mysql_insert_id();
	}
	
	//zapytanie o kolumny danej tabeli
	function show_columns($nazwa_tabeli){
		$query = "SHOW COLUMNS FROM $nazwa_tabeli";
		return $this->result($query);
	}
	
	//zwraca numer największego id w zadanej tabeli
	function get_max_id($nazwa_tabeli, $nazwa_klucza) {
		$result = $this->select("MAX($nazwa_klucza)",$nazwa_tabeli,"","");
		$row = $this->row($result);
		return $row[0];
	}	
	
################# funkcje pomocnicze ##################################################		
	//ile wyników wyszukania
	function size_result($result){
   		$size_result = mysql_num_rows($result);
		return $size_result;			
	}
	
	//zwraca wiersz wyniku poszukiwan
	function row($result){
		return mysql_fetch_array($result);
	}
	
	//zwraca wynik zapytania i ewentualnie komunikat o bledzie
	function result($query) {
		$result = mysql_query($query);
		//echo $query;
		if (!$result) {
			echo "ERROR<br />";
			echo mysql_error()."<br />";
			return false;
		}
		else {
    		return $result;
		}
	}		
}		
############################## koniec ######################################	
?>