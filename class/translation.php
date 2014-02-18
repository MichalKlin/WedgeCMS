<?
#########################################################################
#	Autor: Michał Klin  												#
#	Data: 09.06.2009        											#
#########################################################################
#	Opis:																#
#	klasa translacji xml												#
#########################################################################

class translation {
	var $xmlDoc;
	var $baza;

	var $xml;
	var $lang;
	var $transl;
	
	function translation($baza) {
		$this->xmlDoc = new DOMDocument();
	}
	
#######################################################
	function getTranslation($code, $lang='PL') {
		$res = $this->baza->select("*","cmstranslate","code='".$code."'");
		if($this->baza->size_result($res) == 1){
			$row = $this->baza->row($res);
			return $this->getTranslationXML($row[xml], $lang);
		} else{
			return $code;
		}
	}

	function getTranslationXML($xml, $lang='PL') {
		$this->xml = $xml;
		$this->lang = $lang;
		$this->translate();
		return $this->transl;
	}
	
	function translate(){
		if(substr($this->xml,0,5) == '<data' || substr($this->xml,0,5) == '<DATA'){
			if($this->xmlDoc->loadXML($this->xml)){
				$x=$this->xmlDoc->getElementsByTagName('transl');
				$is_trans = false;
				for ($i=0; $i<=5; $i++){
					if($x->item($i) != null){
						if($x->item($i)->getAttribute('lang') == $this->lang){
							if(strlen($x->item($i)->nodeValue)>0){
								$this->transl = $x->item($i)->nodeValue;
								$is_trans = true;
								break;
							}
						}
					}
				}
				if($is_trans == false){
					if($x->item(0) != null){
						$this->transl = $x->item(0)->nodeValue;
					}
				}
			} else{
				$this->transl = $this->xml;
			}
		} else{
			$this->transl = $this->xml;
		}
	}
}
?>