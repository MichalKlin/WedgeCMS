<?
$menu_all = new structure($this->baza,$this->getUser());

$r1 = $this->baza->select("*", "cmspage", "htmlName='$PAGE_NAME'");
$row1 = $this->baza->row($r1);

$over = $row1[idPage];
$top_menu_item = $row1[idPage];
// dla drzewka w poziomie 2
if($menu_all->getLevel($over)>0){
	while ($menu_all->getLevel($over)>1){
		//echo $over;
		$r1 = $this->baza->select("*", "cmspage", "idPage=$over");
		$row1 = $this->baza->row($r1);	
		$over = $row1[over];
		if ($menu_all->getLevel($over) == 1){
			$top_menu_item = $row1[over];
			break;
		}
	}
	 
	$menu = new structure($this->baza,$this->getUser(),$top_menu_item);
	
	if (sizeof($menu->list)>0){
		$r1 = $this->baza->select("*", "cmspage", "htmlName='$PAGE_NAME'");
		$row1 = $this->baza->row($r1);

		$page_top = $row1[idPage];
//		echo $page_top0;

		$over = $row1[idPage];
		while ($over != $top_menu_item){
			$r1 = $this->baza->select("*", "cmspage", "idPage=$over");
			$row1 = $this->baza->row($r1);	
			$over = $row1[over];
			if ($over == $page_top0){
				$page_top = $row1[idPage];
				break;
			}
		}

		$php_self = substr($_SERVER['PHP_SELF'],0,strrpos($_SERVER['PHP_SELF'], "/"))."/";
		?><div id="level_3"><ul><?
		for ($i=0; $i<sizeof($menu->list); $i++){	
			?>
			<li>&nbsp;<a 
			<?
			if ($menu->list[$i]->getId() == $page_top) {echo "style='color: #17CC42'";}
			?>
			 href="http://<?=$_SERVER['HTTP_HOST'].$php_self.$menu->list[$i]->getHtmlName()?>.html">
				<?=$menu->list[$i]->getName()?>
			</a>&nbsp;
			<?if ($i!=sizeof($menu->list)-1){echo "|";}?>
			</li>
			<?
		}
		?>
		</ul></div>
	<?
	}
}
?>