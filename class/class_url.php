<?
#########################################################################
#	Autor: Micha� Klin  												#
#	Data: 06.07.2005        											#
#########################################################################
#	Opis:																#
#	Ustawia i zarz�dza parametrem url 									#
#########################################################################


class url {
	var $path;
	var $path_main;
	
	function url($session){
		if ($session->is("path")){
			$session->unset_session("path");
		}
		$session->set("path",$_SERVER[REQUEST_URI]);
		$sciezka = $session->get("path");
		$nowa_sciezka=explode("&",$sciezka); 
		$session->set("path",$nowa_sciezka[0]);
		$this->path = "http://".$_SERVER['HTTP_HOST'].$session->get("path");
		
		$nowa_sciezka=explode("?",$sciezka); 
		$session->set("path_main",$nowa_sciezka[0]);
		$this->path_main = "http://".$_SERVER['HTTP_HOST'].$session->get("path");
	}
	
	//zwraca wartość path
	function get_path(){
		return $this->path;
	}
	function get_main_path(){
		return $this->path_main;
	}
}
?>