<?
//$r=$this->baza->select("*","cmspage","idPage=$PAGE");
//$row = $this->baza->row($r);
//
//$page_name = $row[htmlName];
//if (substr($page_name,0,3)=="en_" || substr($page_name,0,3)=="de_"){
//	$page_name = substr($page_name,3);
//}
//
//$link_pl = $page_name.".html";
//$link_en = "en_".$page_name.".html";
//$link_de = "de_".$page_name.".html";
$res = $this->baza->select("*","cmstranslatelang","","order by id");
$ile = $this->baza->size_result($res);
for ($i=0; $i<$ile; $i++){
	$row = $this->baza->row($res);
	$link = "?lang=".$row[code];
	?>
	<?
	if($row[code]!=$_SESSION['site_lang']){
		?>
		<a href="<?=$link?>">
			<?=$row[name]?> <img src="images/<?=$row[code]?>.gif" />
		</a>
		<?
	} else{
		?>
			<?=$row[name]?> <img src="images/<?=$row[code]?>.gif" />
		<?
	}
}