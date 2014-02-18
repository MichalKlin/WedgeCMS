<?
/*
$r_art=$this->baza->select("activeModArticle,titleModArticle,contentModArticle",
"modarticle,modarticlesingle",
"modarticlesingle.idArticleModArticleSingle=modarticle.idModArticle and 
modarticlesingle.idSchemaModuleModArticleSingle=".$SCHEMA_MODULE." 
AND idPageModArticleSingle=".$PAGE,"","");
//." AND pageModArticle=".$PAGE

if ($this->baza->size_result($r_art)>0){
	$row_art = $this->baza->row($r_art);
	if ($row_art['activeModArticle']=='YES'){	
		?>
		<div><?=$row_art['contentModArticle']?></div>
		<?
	}
}
*/
if (isset($_GET[a])){
	$r_art=$this->baza->select("*","modarticle","idModArticle=$_GET[a]");
	if ($this->baza->size_result($r_art)>0){
		$row_art = $this->baza->row($r_art);
		if ($row_art['activeModArticle']=='YES'){	
			?>
			<center>
			<h3><?=$row_art['titleModArticle']?></h3>
			</center>
			<br>
			<div><?=$row_art['contentModArticle']?></div>
			<?
		}
	}
}

if (isset($_GET[p])){
	$r=$this->baza->select("*","cmspage","idPage=$_GET[p]");
	$row = $this->baza->row($r);
	?><br><center><a href="<?=$row[htmlName]?>.html">Powrót</a></center><?	
}
?>