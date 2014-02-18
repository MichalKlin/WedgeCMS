<?
function struktura($menu,$poziom=0){
	for ($i=0; $i<sizeof($menu->list); $i++){
//		echo "-";
		for ($j=0; $j<$poziom*2; $j++)
			echo "-";
		
		if (isset($_GET['podstrona']))	
			$and = "&podstrona=".$_GET['podstrona'];
		if (isset($_GET['param']))	
			$and = "&param=".$_GET['param'];
		if (isset($_GET['dostep']))	
			$and = "&dostep=".$_GET['dostep'];
		if (isset($_GET['usun_site']))	
			$and = "&usun_site=".$_GET['usun_site'];
		if (isset($_GET['modul']))	
			$and = "&modul=".$_GET['modul'];
		if ($menu->list[$i]->getId()==$_GET['p']){
			echo "<span style=\"color: gray; font-weight: bolder;\">".$menu->list[$i]->getName();
			if ($menu->list[$i]->getActive()=="NO")
				echo " [N]";
			echo "</span><br />";
		}
		else{
			echo "<a href=\"?pages=&p=".$menu->list[$i]->getId().$and."\">".$menu->list[$i]->getName();
			if ($menu->list[$i]->getActive()=="NO")
				echo " [N]";
			echo "</a><br />";
		}
		if (($podmen = $menu->list[$i]->getPodmenu())!=null){
			$poziom++;
			$poziom = struktura($podmen,$poziom);
		}
	}
	return --$poziom;
}

function mape_site($menu,$poziom=0){
	for ($i=0; $i<sizeof($menu->list); $i++){
		for ($j=0; $j<$poziom*2; $j++)
			if($menu->list[$i]->getActive()=="YES"){
				echo "-";
			}
		
		if (isset($_GET['podstrona']))	
			$and = "&podstrona=".$_GET['podstrona'];
		if (isset($_GET['param']))	
			$and = "&param=".$_GET['param'];
		if (isset($_GET['dostep']))	
			$and = "&dostep=".$_GET['dostep'];
		if (isset($_GET['usun_site']))	
			$and = "&usun_site=".$_GET['usun_site'];
		if (isset($_GET['modul']))	
			$and = "&modul=".$_GET['modul'];
		if ($menu->list[$i]->getId()==$_GET['p']){
			echo "<span style=\"color: gray; font-weight: bolder;\">".$menu->list[$i]->getHtmlName();
			//if ($menu->list[$i]->getActive()=="NO")
			//	echo " [N]";
			echo "</span><br />";
		}
		else{
			if ($menu->list[$i]->getActive()=="YES"){
				echo "<a href=\"".$menu->list[$i]->getHtmlName().".html\">".$menu->list[$i]->getName();
				echo "</a><br />";
			}
		}
		if (($podmen = $menu->list[$i]->getPodmenu())!=null){
			$poziom++;
			$poziom = mape_site($podmen,$poziom);
		}
	}
	return --$poziom;
}

?>