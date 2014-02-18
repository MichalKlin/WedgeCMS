<?
$r_art=$this->baza->select("activeModArticle,titleModArticle,contentModArticle,printModArticle",
"modarticle,modarticlesingle",
"modarticlesingle.idArticleModArticleSingle=modarticle.idModArticle and 
modarticlesingle.idSchemaModuleModArticleSingle=".$SCHEMA_MODULE." 
AND idPageModArticleSingle=".$PAGE,"","");
//." AND pageModArticle=".$PAGE

if ($this->baza->size_result($r_art)>0){
	$row_art = $this->baza->row($r_art);
	if ($row_art['activeModArticle']=='YES'){	
		$title = $row_art['titleModArticle'];
		$content_art = $row_art['contentModArticle'];
		$cont = str_replace(array("\n","\r","\""), array("","","'"),$content_art);
		$short_art = $row_art['modarticle_short'];
		$short = str_replace(array("\n","\r","\""), array("","","'"),$short_art);
		?>
<script language="javascript">
	function print_article(title,content){
		var thePopup = window.open('','Drukowanie','width = 600, height = 600');
		thePopup.document.write("<html><body onLoad='window.print();window.close();'><strong><?=$title?></strong><br><br><?=$short?><br><br><?=$cont?></body></html>");
		thePopup.document.close();
	}
</script>
		<table border="0" cellpadding="0" cellspacing="0" width="100%" class="art_single">
		<tr>
			<td class="art_single_title" style="color: #5f040d;">
				<h1 class="art_arrow"><?=$title?></h1>
			</td>
		</tr>
		<tr>
			<td class="art_single_content"><?=$content_art?></td>
		</tr>
		<?
		if ($row_art['printModArticle']=="YES"){
		?>
		<tr>
			<td class="do_druku">
				<a href="javascript:print_article('<?=$title?>','')"><img src="images/print.gif" style="border: 0;" /></a>
			</td>
		</tr>
		<?}?>
		<tr>
			<td class="art_single_bottom">&nbsp;</td>
		</tr>
		</table>
		<?
	}
}
?>