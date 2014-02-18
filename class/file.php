<?
#########################################################################
#	Autor: Micha� Klin  												#
#	Data: 30.06.2005        											#
#########################################################################
#	Opis:																#
#	Klasa ta s�u�y do wszelkich operacji na plikach, czyli tworzenie	#
#	kasowanie, czytanie i pisanie do pliku.								#
#########################################################################

class plik {
	var $path;		//�cie�ka do pliku, ��cznie z nazw� pliku
	var $atribut;	//atrybut otwarcia pliku
	var $size;		//wielko�� przy odczycie
	var $content;	//tre�� pliku
	
	//konstruktor domy�lnie ustawia wielko�� pliku na 20MB
	function plik($path, $atribut, $size="20000000") {
		$this->path = $path;
		$this->atribut = $atribut;
		$this->size = $size;
		$this->content = "";
	}
	
	//funkcja czytaj�ca z pliku i zwracaj�ca odczytan� warto�� b�d� komunikat b��du jak plik nie istnieje
	function czytaj() {		
		//sprawdzenie czy plik istnieje
		if (file_exists($this->path)){
			
			//otwarcie pliku
			$fp=fopen($this->path,$this->atribut);
			
			//zczytanie warto�ci z pliku
			$this->content=fread($fp,$this->size);
			
			//zamkni�cie pliku
			fclose($fp);

			return $this->content;
		}
		else {
			echo "ERROR: File not found!";
			return false;
		}
	}
	
	//zapisuje podan� warto�� do pliku zgodnie z atrybutem
	function zapisz($content){
	}
	
	//tworzy nowy plik
	function utworz(){
	}
	
	//kasuje istniej�cy plik
	function kasuj(){
	}
}
?>