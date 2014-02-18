<?
#######################################################
#	Autor: Micha Klin  									        	#
#	Data: 30.06.2005        											#
#######################################################
#	Opis:														#
#	Klasa zwizana z obsług sesji									 	#
#######################################################

class session {
	//konstruktor uruchamia sesje
	function session(){	
		session_start();
	}
	
	//konczy sesje
	function session_stop(){
		session_destroy();
	}
	
	//ustawia zmienn sesyjn
	// $name - nazwa zmiennej sesyjnej
	// $value - wartosc tej zmiennej
	function set($name, $value){
		$_SESSION[$name]=$value;
	}
	
	//kasuje zmienne sesyjne
	function unset_session($name){
		unset($_SESSION[$name]);
	}
	
	//sprawdza czy jest zmianna sesyjna
	function is($name){
		if (isset($_SESSION[$name])){
			return true;
		}
		else {
			return false;
		}
	}
	
	//pobierz wartosc zmiennej sesyjnej
	function get($name){
		return $_SESSION[$name];
	}
}
?>