<?
$r_art=$this->baza->select("activeModArticle,titleModArticle,contentModArticle","modarticle","schemamoduleModArticle=".$SCHEMA_MODULE." AND pageModArticle=".$PAGE,"","");

if ($this->baza->size_result($r_art)>0){
	$row_art = $this->baza->row($r_art);
	if ($row_art['activeModArticle']=='YES'){	
		
		echo "<center><div class=\"title\">".$row_art['titleModArticle']."</div></center>";
		
		echo "<div>".$row_art['contentModArticle']."</div>";
	}
}

?>