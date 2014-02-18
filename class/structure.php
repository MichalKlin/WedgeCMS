<?
class structure{
	var $baza;
	var $list;
	
	function structure($baza,$user=0,$id=0){
		if ($user==0)
		$res = $baza->select("*","cmspage",
			"over=".$id,"ORDER BY orderPage");
		else
		$res = $baza->select("*","cmspage,cmsuser,cmsgroupuser,cmspageusergroup",
			"cmsuser.id=$user 
			AND cmspage.active=\"YES\"
			AND cmsuser.groupUser=cmsgroupuser.idGroupUser 
			AND cmspageusergroup.groupUser=cmsgroupuser.idGroupUser 
			AND cmspageusergroup.page=cmspage.idPage 
			AND	cmspage.over=".$id,"ORDER BY cmspage.orderPage");
		if (($ile = $baza->size_result($res))>0){
			for ($i=0; $i<$ile; $i++){
				$row = $baza->row($res);
				$this->list[$i] = new itemStructure($baza,$user,$row['idPage']);
			}
		}
		return $list;		
	}
}

class itemStructure{
	var $podmenu;
	var $nazwa;
	var $html;
	var $baza;
	var $id;
	var $active;
	var $url;

	function itemStructure($baza,$user,$id){
		$this->baza = $baza;
		$res = $baza->select("*","cmspage","idPage=".$id);
		if (($ile = $baza->size_result($res))==1){
			$row = $baza->row($res);
			$this->nazwa = $row['name'];
			$this->html = $row['htmlName'];
			$this->id = $row['idPage'];
			$this->active = $row['active'];
			$this->url = $row['url'];

			if ($this->ilePodmenu($id)>0)
				$this->podmenu = new structure($baza,$user,$row['idPage']);
			else	
				$this->podmenu = null;
		}
		return $this;		
	}

	function ilePodmenu($id){
		$res = $this->baza->select("*","cmspage","over=".$id,"","");
		return $this->baza->size_result($res);
	}	
	
	function getName(){
		return $this->nazwa;
	}
	
	function getHtmlName(){
		return $this->html;
	}
	
	function getId(){
		return $this->id;
	}
	
	function getActive(){
		return $this->active;
	}
	
	function getPodmenu(){
		return $this->podmenu;
	}

	function getUrl(){
		return $this->url;
	}
}
?>