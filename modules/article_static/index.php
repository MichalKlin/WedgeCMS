<?
$r_art=$this->baza->select("activeModArticle,titleModArticle,contentModArticle",
"modarticle,modarticlestatic",
"modarticlestatic.idArticleModArticleStatic=modarticle.idModArticle and 
modarticlestatic.idSchemaModuleModArticleStatic=".$SCHEMA_MODULE,"","");

if ($this->baza->size_result($r_art)>0){
	$row_art = $this->baza->row($r_art);
	if ($row_art['activeModArticle']=='YES'){	
		?>
		<div><?=$row_art['contentModArticle']?></div>
		<?
	}
}

?>