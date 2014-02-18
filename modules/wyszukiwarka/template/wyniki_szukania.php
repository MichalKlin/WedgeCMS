<?

	$szukany_tekst = $_POST[text_szukaj];
	if (strlen($szukany_tekst)>=3){
		$r_art=$this->baza->select("*","modarticle,modarticlesingle,cmspage,modarticlegroup","activeModArticle='YES' and 
			(titleModArticle like '%$szukany_tekst%' or modarticle_short like '%$szukany_tekst%' or contentModArticle like '%$szukany_tekst%') 
			and modarticle.idModArticle=modarticlesingle.idArticleModArticleSingle and modarticle_group=modarticlegroup_id and 
			modarticlesingle.idPageModArticleSingle=cmspage.idPage and modarticlegroup.modarticlegroup_newsletter='YES' and 
			cmspage.active='YES'","group by htmlName");
	
		if (($ile = $this->baza->size_result($r_art))>0){
			for ($i=0; $i<$ile; $i++){
				$row_art = $this->baza->row($r_art);
				?>
				<strong><?=$row_art[titleModArticle]?></strong>
				<br>
				<?=strip_tags(substr($row_art[modarticle_short]." ".$row_art[contentModArticle],0,100))?>
				<br>
				<div style="text-align: right;"><a href="<?=$row_art[htmlName]?>.html" style=" color: #777; font-weight: bold; font-size: 9px">przejdź do strony</a></div>
				<br><br>
				<?
			}
		}
		
	} else {
		?>
		<p class="error">Zbyt dużo wyników - prosimy wpisać dłuższą frazę do wyszukania.</p>
		
		<?
	}
?>
<br><br>