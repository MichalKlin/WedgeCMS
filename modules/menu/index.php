<?
$r_menu=$this->baza->select("*","modmenu,modmenuschema",
"modmenuschema_schema=".$SCHEMA_MODULE." and modmenuschema_menu=modmenu_id","","");
if ($this->baza->size_result($r_menu)>0){
	$row_menu = $this->baza->row($r_menu);
	if ($row_menu['modmenu_active']=='YES'){	
		$r_item=$this->baza->select("*","modmenuitem,cmspage",
			"modmenuitem_menu=$row_menu[modmenu_id] and modmenuitem_page=cmspage.idPage",
			"order by modmenuitem_order");
		if (($ile=$this->baza->size_result($r_item))>0){
			?>
			<ul>
			<?
			for ($im=0; $im<$ile; $im++){
				$row_item = $this->baza->row($r_item);
				$class = "";
				if ($im<$ile-1){
					$class = "menu_linia";
				}
				?>
				<li class="<?=$class?>"
					<?
					if ($row_item['htmlName']==$PAGE_NAME)
						echo "id=\"menu_selected\"";
					?>
				>
					<?
					if (strpos($row_item[url],'ttp://')!=0) {
						?><a href="<?=$row_item['url']?>" target="_blank"><?=$row_item['name']?></a><?
					}
					elseif (strlen($row_item[url])>0){
						?><a href="<?=$row_item['url']?>"><?=$row_item['name']?></a><?
					}
					else{
						?><a href="<?=$row_item['htmlName']?>.html"><?=$row_item['name']?></a><?
					}
					?>					
				</li>
				<?
//				if ($im!=$ile-1)
//					echo "&nbsp;&nbsp;|&nbsp;&nbsp;";
			}
			?></ul><?
		}
	}
}
?>