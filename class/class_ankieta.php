<?
#########################################################################
#	Autor: Micha� Klin  												#
#	Data: 05.07.2005        											#
#########################################################################
#	Opis:																#
#	zarz�dzanie ankietami, lista ankiet oraz pojedyncze ankiety			#
#########################################################################
class lista_ankiet {
	var $lista_ankiet;
	var $size;
	var $tabela;
	var $baza;

	//tworzy obiekt w postaci listy (tablicy ankiet)
	function lista_ankiet($baza, $name_ankieta="mod_ankieta") {
		$this->baza = $baza;
		$this->tabela = $name_ankieta;

		$result = $baza->select("*",$name_ankieta,"","ORDER BY ma_active");
		$size_result = $baza->size_result($result);
		$this->size = $size_result;
		
		for($i=0;$i<$size_result;$i++){
			$row = $baza->row($result);
			$ankieta = new ankieta($row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6]);
			$this->lista_ankiet[$i]=$ankieta;
		}		
	}
	
	//dodaj ankiet�
	function add($pytanie,$lista_odpowiedzi,$lista_wynikow,$typ,$mode,$active){
		$result = $this->baza->insert($this->tabela, "0, \"$pytanie\", \"$lista_odpowiedzi\", \"$lista_wynikow\", $typ, $mode, \"$active\"");
		$id = $this->baza->last_insert_id();
		$ankieta = new ankieta($id, $pytanie,$lista_odpowiedzi,$lista_wynikow,$typ,$mode,$active);
		$this->lista_ankiet[$this->size] = $ankieta;
		$this->size++;
	}
	
	//usuniecie ankiety
	function delete($nr_na_liscie, $ankieta){
		//usuwanie z listy i przesuni�cie listy w d�, ostatni element bedzie null
		for ($i=$nr_na_liscie;$i<$this->size;$i++){
			$this->lista_ankiet[$i] = $this->lista_ankiet[$i + 1];
		}
		
		//usuni�cie z bazy
		$this->baza->delete("$this->tabela","ma_id=$ankieta");
	}
	
	//ustawienie aktywno�ci b�d� nieaktywno�ci danej ankiety
	function active($nr_na_liscie, $ankieta, $state){
		$this->lista_ankiet[$nr_na_liscie]->active($state);
		//zmiana w bazie danych
		$this->baza->update("$this->tabela","ma_active=\"$state\"","ma_id=$ankieta");
	}
	
	//zwraca wielko�� listy ankiet - liczba wszystkich ankiet
	function get_size(){
		return $this->size;
	}
	
	//zwraca list� wszystkich ankiet
	function get_lista_ankiet(){
		return $this->lista_ankiet;
	}

	//zwiekszenie licznika g�os�w
	function glosuj($nr_ankiety, $ankieta, $odpowiedz){
		$nowa_wartosc = $this->lista_ankiet[$nr_ankiety]->glosuj($odpowiedz);
		$this->baza->update("$this->tabela","ma_result=\"$nowa_wartosc\"","ma_id=$ankieta");
	}	
}

##################################################################################################
//pojedyncza ankieta
##################################################################################################
class ankieta {
	var $id;
	var $pytanie;
	var $lista_odpowiedzi;
	var $lista_wynikow;
	var $typ;
	var $mode;
	var $active;
	var $size;
	
	//konstruktor
	function ankieta($id, $pytanie, $lista_odpowiedzi, $lista_wynikow="", $typ=1, $mode=1, $active="yes") {
		$this->id = $id;
		$this->pytanie = $pytanie;
		$this->lista_odpowiedzi = $lista_odpowiedzi;
		$this->lista_wynikow = $lista_wynikow;
		$this->typ = $typ;
		$this->mode = $mode;
		$this->active = $active;

		//wyliczenie wielko�ci ankiety - ile jest odpowiedzi
		$tablica_odpowiedzi = explode("#",$this->lista_odpowiedzi);
		$this->size = sizeof($tablica_odpowiedzi);

	}
	
	//sprawdzenie czy dana ankieta jest aktywna
	function is_active(){
		if ($this->active == "yes"){
			return true;
		}
		else {
			return false;
		}
	}
	
	//pobranie danych ankiety i przes�anie w postaci tabeli
	function get(){
		$tab[0] = $this->id;
		$tab[1] = $this->pytanie;
		$tab[2] = $this->lista_odpowiedzi;
		$tab[3] = $this->lista_wynikow;
		$tab[4] = $this->typ;
		$tab[5] = $this->mode;
		$tab[6] = $this->active;
		$tab[7] = $this->size;
		return $tab;
	}
	
	//dodanie g�osu
	function glosuj($odpowiedz){
		//rozbicie wynik�w do tablicy
		$tablica_wynikow = explode("#",$this->lista_wynikow);
		
		//zwi�kszenie odpowiedniego pola tablicy - odpowiedz kt�ra zosta�a wybrana
		$tablica_wynikow[$odpowiedz]++;
		
		//stworzenie nowej warto�ci listy wynik�w
		$this->lista_wynikow="";
		for ($i=0;$i<$this->size;$i++){
			$this->lista_wynikow.="$tablica_wynikow[$i]#";
		}
		return $this->lista_wynikow;
	}
	
	//ustawia aktywno��: true - aktywna, false - nieaktywna
	function active($state){
		$this->active = $state;
	}
}
?>