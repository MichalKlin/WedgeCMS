<?
$menu = new structure($this->baza,$this->getUser());

$podmen = null;
$r1 = $this->baza->select("*", "cmspage", "htmlName='$PAGE_NAME'");
$row1 = $this->baza->row($r1);

$over = $row1[over];
$top_menu_item = $row1[idPage];
//echo $menu->getLevel($over);

// dla drzewka w poziomie 1
while ($over != 0){
	$r1 = $this->baza->select("*", "cmspage", "idPage=$over");
	$row1 = $this->baza->row($r1);	
	$over = $row1[over];
	if ($over == 0){
		$top_menu_item = $row1[idPage];
		break;
	}
}

for ($i=0; $i<sizeof($menu->list); $i++){
	if($menu->list[$i]->getId()==$top_menu_item){
		$podmen = $menu->list[$i]->getPodmenu();
		$break;
	}
}
struktura_tree($podmen);

// dla drzewka w poziomie 2
//if($menu->getLevel($over)>0){
//	while ($menu->getLevel($over)>1){
//		//echo $over;
//		$r1 = $this->baza->select("*", "cmspage", "idPage=$over");
//		$row1 = $this->baza->row($r1);	
//		$over = $row1[over];
//		if ($menu->getLevel($over) == 1){
//			$top_menu_item = $row1[over];
//			break;
//		}
//	}
//	 
//	$podmen = new structure($this->baza,$this->getUser(),$top_menu_item);
//	
//	struktura_tree($podmen);
//}

function struktura_tree($menu,$poziom=0){
	$php_self = substr($_SERVER['PHP_SELF'],0,strrpos($_SERVER['PHP_SELF'], "/"))."/";
	if (sizeof($menu->list)>0){
	?><ul><?
	for ($i=0; $i<sizeof($menu->list); $i++){
		$plus = 0;
		for ($j=0; $j<$poziom; $j++)
			$plus+=1;
		$header = 2+$plus;	
		
		$selected = "";
		if ($menu->list[$i]->getHtmlName()==$_GET['page'])
			$selected = "class=\"menu_selected\"";
			
		if (strpos($menu->list[$i]->getUrl(),'ttp://')!=0)
			echo "<li><a $selected href=\"".$menu->list[$i]->getUrl()."\" target=\"_blank\">".$menu->list[$i]->getName()."</a></li>";
		else	
			echo "<li><a $selected href=\"http://".$_SERVER['HTTP_HOST'].$php_self.$menu->list[$i]->getHtmlName().".html\">".$menu->list[$i]->getName()."</a></li>";
		if (($podmen = $menu->list[$i]->getPodmenu())!=null){
			if($_GET['page']==$menu->list[$i]->getHtmlName() or isPodmenu($_GET['page'],$podmen)==true){
				$poziom++; 
				$poziom = struktura_tree($podmen,$poziom);
			}
		}
	}
	?></ul><?
	}
	return --$poziom;
}

function isPodmenu($htmlNamePage,$podmen){
	for ($i=0; $i<sizeof($podmen->list); $i++){
		if ($podmen->list[$i]->getHtmlName()==$htmlNamePage)
			return true;
		if (($podmenu = $podmen->list[$i]->getPodmenu())!=null)
			if (isPodmenu($htmlNamePage,$podmenu))
				return true;		
	}
	return false;
}
	
?>