<?
include_once("../include/mail.php");
include("./javascript/edytor_www/fckeditor.php") ;

$dzis = date("Y-m-d");
$godz = date("H:i");
$r_as = $baza->select("*","modarticlestatic","idSchemaModulemodarticlestatic=$_GET[manage]","","");
$row_as = $baza->row($r_as);

//zapisanie wybranego artukułu
if (isset($_POST['save'])){
	if ($baza->size_result($r_as)==0){	
		$wartsci = "0,".$_GET['manage'].",".$_POST['artykul'];	
		$r = $baza->insert("modarticlestatic",$wartsci);	
	}
	else{
		$wartsci = "idArticlemodarticlestatic=".$_POST['artykul']."";
		$where = "idSchemaModulemodarticlestatic=$_GET[manage]";
		$r = $baza->update("modarticlestatic",$wartsci,$where,"");
	}
}
	
//zapisanie zmian w artykule
if (isset($_POST['save_article']) and strlen(trim($_POST['title']))>0){			
	$wartsci="
		titleModArticle='".htmlspecialchars($_POST['title'])."',
		modarticle_group=".$_POST['group'].",
		modarticle_short='".$_POST['short']."',
		contentModArticle='".$_POST['content']."',
		dateModArticle='".$dzis."',
		hourModArticle='".$godz."',
		modarticle_order=".$_POST['kolejnosc'].",
		printModArticle='".$_POST['print']."',
		activeModArticle='".$_POST['active']."'
	";	
	$where = "idModArticle=".$row_as['idArticleModArticleStatic'];
	$r = $baza->update("modarticle",$wartsci,$where,"");
}

$r_as = $baza->select("*","modarticlestatic","idSchemaModulemodarticlestatic=$_GET[manage]","","");
$row_as = $baza->row($r_as);
$ile_as=$baza->size_result($r_as);

//wybór artykułu, który ma być wyświetlany jako pojedynczy
$r_art=$baza->select("activeModArticle,titleModArticle,idModArticle,contentModArticle ","modarticle","","order by titleModArticle","");
if (($ile=$baza->size_result($r_art))>0){
	?>
	<form method="POST" action="">
	<h4>Wybierz artykuł:</h4>
	<select name="artykul">
	<?
	if ($ile_as==0){
		?>
		<option></option>
		<?
	}
	for ($i=0; $i<$ile; $i++){
		$row_art = $baza->row($r_art);
		if ($row_art['activeModArticle']=='YES'){				
			?>
			<option <?if ($row_as['idArticleModArticleStatic']==$row_art['idModArticle']) echo "selected";?> 
				value="<?=$row_art['idModArticle']?>"><?=$row_art['titleModArticle']?></option>
			<?
		}
	}
	?>
	</select>
	<br>
	<input type="submit" name="save" value="Zapisz" />
	</form>
	<br><br>
	<?
	//treść wybranego artykułu:
	if (strlen($row_as[idArticleModArticleStatic])>0){
		$r=$baza->select("*","modarticle","idModArticle=$row_as[idArticleModArticleStatic]","order by titleModArticle","");
		if ($baza->size_result($r)>0){
			$row = $baza->row($r);
			?>
			<hr>
			<h4>Edycja wybranego artykułu:</h4>
			<form method="POST" action="" class="form_article">
			Grupa artykułów:
			<select name="group">
				<?
				$r2=$baza->select("*","modarticlegroup","modarticlegroup_active='YES'","ORDER BY modarticlegroup_name","");
				$il2 = $baza->size_result($r2);
				if ($il2>0){
					for ($i=0; $i<$il2; $i++){
						$row2 = $baza->row($r2);
						?>
						<option value="<?=$row2[modarticlegroup_id]?>" 
						<?if ($row2[modarticlegroup_id]==$row[modarticle_group]) echo "selected";?>
						><?=$row2[modarticlegroup_name]?></option>
						<?
					}
				}
				?>
			</select>
			<br />
			Tytuł:
			<input type="text" name="title" value="<?=$row['titleModArticle']?>" size="56" /><br/>
			Streszczenie: 
			<?
			$oFCKeditor = new FCKeditor("short") ;
			$oFCKeditor->BasePath	= "javascript/edytor_www/";
			$oFCKeditor->Value		= $row['modarticle_short'] ;
			$oFCKeditor->Create() ;
			?>			
			Treść:
			<?
			//echo $row['contentModArticle'];
			$oFCKeditor = new FCKeditor("content") ;
			$oFCKeditor->BasePath	= "javascript/edytor_www/";
			$oFCKeditor->Value		= $row['contentModArticle'] ;
			$oFCKeditor->Create() ;
			?>			
			Kolejność w grupie:
			<input type="text" name="kolejnosc" value="<?=$row['modarticle_order']?>" size="20" /><br/>
			Do druku:
			<input type="radio" name="print" value="NO" <?if ($row['printModArticle']=='NO') echo "checked";?> /> NIE 
			<input type="radio" name="print" value="YES" <?if ($row['printModArticle']=='YES') echo "checked";?> /> TAK <br />
			Aktywny: 
			<input type="radio" name="active" value="NO" <?if ($row['activeModArticle']=='NO') echo "checked";?> /> NIE 
			<input type="radio" name="active" value="YES" <?if ($row['activeModArticle']=='YES') echo "checked";?> /> TAK <br />
			<center><input type="submit" name="save_article" value="Zapisz" /></center>
			</form>	
		<?
		}
	}
}
?>	