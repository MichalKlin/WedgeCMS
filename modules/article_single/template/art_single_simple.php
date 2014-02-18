<?
$r_art=$this->baza->select("activeModArticle,titleModArticle,contentModArticle,printModArticle",
"modarticle,modarticlesingle",
"modarticlesingle.idArticleModArticleSingle=modarticle.idModArticle and 
modarticlesingle.idSchemaModuleModArticleSingle=".$SCHEMA_MODULE." 
AND idPageModArticleSingle=".$PAGE,"","");

if ($this->baza->size_result($r_art)>0){
	$row_art = $this->baza->row($r_art);
	if ($row_art['activeModArticle']=='YES'){	
		$title = $row_art['titleModArticle'];
		$content_art = $row_art['contentModArticle'];
		?>
		<table border="0" cellpadding="0" cellspacing="0" width="100%" class="art_single">
		<tr>
			<td class="art_single_title" style="color: #5f040d;">
				<h1 class="art_arrow"><?=$title?></h1>
			</td>
		</tr>
		<tr>
			<td class="art_single_content"><?=$content_art?></td>
		</tr>
		</table>
		<?
	}
}
?>