<?
$menu = new structure($this->baza,$this->getUser());

	$r1 = $this->baza->select("*", "cmspage", "htmlName='$PAGE_NAME'");
	$row1 = $this->baza->row($r1);

	$page_top = $row1[idPage];
	$over = $row1[idPage];
	while ($over != 0){
		$r1 = $this->baza->select("*", "cmspage", "idPage=$over");
		$row1 = $this->baza->row($r1);	
		$over = $row1[over];
		if ($over == 0){
			$page_top = $row1[idPage];
			break;
		}
	}

	$php_self = substr($_SERVER['PHP_SELF'],0,strrpos($_SERVER['PHP_SELF'], "/"))."/";
	?><div id="level_1"><center><table border="0" cellpadding="0" cellspacing="0"><tr><td> <ul><?
	for ($i=0; $i<sizeof($menu->list); $i++){	
		?>
		<li><a 
		<?
		if ($menu->list[$i]->getId() == $page_top) {echo "style='color: #17CC42'";}
		?>
		 href="http://<?=$_SERVER['HTTP_HOST'].$php_self.$menu->list[$i]->getHtmlName()?>.html">
			<?=$menu->list[$i]->getName()?>
		</a></li>
		<?
	}
	?>
	</ul></td></tr></table></center>
	</div>