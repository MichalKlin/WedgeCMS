<?
include_once("../include/mail.php");
include("./javascript/edytor_www/fckeditor.php") ;

$dzis = date("Y-m-d");
$godz = date("H:i");


if (!isset($_GET[add_art])){
	$r_as = $baza->select("*","modarticlesingle","idSchemaModuleModArticleSingle=$_GET[manage] and idPageModArticleSingle=$_GET[p]","","");
	$row_as = $baza->row($r_as);
	
	//zapisanie wybranego artukułu
	if (isset($_POST['save'])){
		if ($baza->size_result($r_as)==0){	
			$wartsci = "0,".$_GET['manage'].",".$_POST['artykul'].",$_GET[p]";	
			$r = $baza->insert("modarticlesingle",$wartsci);
		}
		else{
			$wartsci = "idArticleModArticleSingle=".$_POST['artykul']."";
			$where = "idSchemaModuleModArticleSingle=$_GET[manage] and idPageModArticleSingle=$_GET[p]";
			$r = $baza->update("modarticlesingle",$wartsci,$where,"");
		}
	}
	
	//odczyt ktory artykuł jest w wybranej sekcji
	$r_as = $baza->select("*","modarticlesingle","idSchemaModuleModArticleSingle=$_GET[manage] 
	and idPageModArticleSingle=$_GET[p]","","");
	$row_as = $baza->row($r_as);
	$ile_as=$baza->size_result($r_as);
	
	
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
		";	//archivesModArticle='".$_POST['archives']."',
		$where = "idModArticle=".$row_as[idArticleModArticleSingle];
		$r = $baza->update("modarticle",$wartsci,$where,"","");
	}
		
	//wybór artykułu, który ma być wyświetlany jako pojedynczy
	$r_art=$baza->select("activeModArticle,titleModArticle,idModArticle,contentModArticle ","modarticle","","order by titleModArticle","");
	if (($ile=$baza->size_result($r_art))>0){
		?>
		<form method="POST" action="">
		<h4>Wybierz artykuł z listy:
		<select name="artykul">
			<option value="0"></option>
		<?
		if ($ile_as==0){
			?>
			<?
		}
		for ($i=0; $i<$ile; $i++){
			$row_art = $baza->row($r_art);
			if ($row_art['activeModArticle']=='YES'){				
				?>
				<option <?if ($row_as['idArticleModArticleSingle']==$row_art['idModArticle']) echo "selected";?> 
					value="<?=$row_art['idModArticle']?>"><?=$row_art['titleModArticle']?></option>
				<?
			}
		}
		?>
		</select>
		<input type="submit" name="save" value="Zapisz" />
		</h4>
		</form>
		<?
	}
	?>
		lub <a href="?pages=&p=<?=$_GET[p]?>&modul=&manage=<?=$_GET[manage]?>&add_art=">dodaj nowy artykuł</a>
		<br><br>
	<?
	
	if (strlen($row_as[idArticleModArticleSingle])>0){
		$r=$baza->select("*","modarticle","idModArticle=$row_as[idArticleModArticleSingle]","order by titleModArticle","");
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
			<!--Archiwalny: 
			<input type="radio" name="archives" value="NO" <?if ($row['archivesModArticle']=='NO') echo "checked";?> /> NO 
			<input type="radio" name="archives" value="YES" <?if ($row['archivesModArticle']=='YES') echo "checked";?> /> YES <br />
			-->
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

//dodanie nowego artykułu
if (isset($_GET[add_art])){
	if (!isset($_POST[add_art])){
	?>
		<h4>Dodawanie nowego artykułu:</h4>
		<form method="POST" action="" class="form_article">
		Grupa artykułów
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
		<input type="text" name="kolejnosc" value="1" size="20" /><br/>
		<!--Archiwalny: 
		<input type="radio" name="archives" value="NO" checked /> NO 
		<input type="radio" name="archives" value="YES" /> YES <br />
		-->
		Do druku: 
		<input type="radio" name="print" value="NO" checked /> NIE 
		<input type="radio" name="print" value="YES"  /> TAK <br />
		Aktywny: 
		<input type="radio" name="active" value="NO" /> NIE 
		<input type="radio" name="active" value="YES" checked /> TAK <br />
		<input type="submit" name="add_art" value="Zapisz" />
		</form>	
	<?		
	}
	else{
		if (strlen(trim($_POST['title']))>0){			
			$wartsci="0,'".htmlspecialchars($_POST['title'])."',
			".$_POST['group'].",'".$_POST['short']."','".$_POST['content']."',
			$_SESSION[panel_admin_user_id],'$_SESSION[panel_admin_user]','".$dzis."','".$godz."',
			0,'".$_POST['print']."',
			'NO','".$_POST['active']."'
			";
			$where = "idModArticle=".$row_as[idArticleModArticleSingle];
			$r = $baza->insert("modarticle",$wartsci,"","");
			
			//newsletter
			$r = $baza->select("*","mod_newsletter,modarticlegroup",
				"mnews_grupa=".$_POST['group']," and mnews_status>1 and mnews_grupa=modarticlegroup_id");
			if (($ile=$baza->size_result($r))>0){
				for ($i=0; $i<$ile; $i++){
					$row = $baza->row($r);
					$do = $row[mnews_email];
					$title = $_POST[title];
					$grupa = $row[modarticlegroup_name];
					$short = $_POST[short];
					$content = $_POST[content];
					mail_newsletter($do,$title,$grupa,$short,$content);
				}
			}	
						
			?>
			<p>Nowy artykuł zapisany</p>
			<?
		}
		else{
			?>
			<p>Pole tytuł artykułu musi być wypełnione</p>
			<?
		}		
	}
	?>
	<a href="?pages=&p=<?=$_GET[p]?>&modul=&manage=<?=$_GET[manage]?>">powrót do wyboru artykułu</a>
	<?
}
?>