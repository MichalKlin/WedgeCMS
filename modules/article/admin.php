<script>
function czy_usunac(strona){
	var sprawdz = confirm('Czy na pewno usunąć?');
	if (sprawdz == true) {
		var url = 'http://<?=$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']?>';
		document.location.href = url+strona;
		return true;
	}
	else if (sprawdz == false) {
		return false;
	}
}
</script>
<span style="text-align: left;">
<?
include("./javascript/edytor_www/fckeditor.php") ;
include_once("../include/mail.php");

if (isset($_GET[pages]))
	$link = "?pages=&p=$_GET[p]&modul=&manage=$_GET[manage]";
else 
	$link = "?modules=&manage=$_GET[manage]";	

$dzis = date("Y-m-d");
$godz = date("H:i");	

	//dodanie nowego
	if (isset($_POST['save_new']) and strlen(trim($_POST['title']))>0){	
		if ($_POST[data]!="") $dzis = $_POST[data];
		$wartsci = "0,'".htmlspecialchars($_POST['title'])."',
			".$_POST['group'].",'".$_POST['short']."',
			'".$_POST['content']."', $_SESSION[panel_admin_user_id], '".htmlspecialchars($_POST['autor'])."',
			'$dzis','$godz',".$_POST['kolejnosc'].",'".$_POST['print']."',
			'NO','YES'
		"; //".$_POST['archives']."
		//".$_POST['active']."
		//echo $wartsci;
		$r = $baza->insert("modarticle",$wartsci,"");	
			
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
					
	}
	
	//zapisanie zmian
	if (isset($_POST['save']) and strlen(trim($_POST['title']))>0){		
		if ($_POST[data]!="")
			$dzis = $_POST[data];
		$wartsci="
			titleModArticle='".htmlspecialchars($_POST['title'])."',
			modarticle_group=".$_POST['group'].",
			modarticle_short='".$_POST['short']."',
			contentModArticle='".$_POST['content']."',
			authorName='".$_POST['autor']."',
			dateModArticle='".$dzis."',
			hourModArticle='".$godz."',
			modarticle_order=".$_POST['kolejnosc'].",
			printModArticle='".$_POST['print']."'";	
			//archivesModArticle='".$_POST['archives']."',
			//activeModArticle='".$_POST['active']."'

		$where = "idModArticle=".$_GET['article'];
		$r = $baza->update("modarticle",$wartsci,$where,"","");
	}
	
	//usuwanie artykułu
	if (isset($_GET[usun_art])){
		$where = "idModArticle=".$_GET['usun_art'];
		//$r = $baza->delete("modarticle",$where,"","");
		$r = $baza->update("modarticle","activeModArticle='NO'",$where);
	}
	
	if (!isset($_GET[article]) and !isset($_GET[nowy]) and !isset($_GET[comment])){
	?>
	<center>
	<br>
	<?if ($add_flag){?>
	
	<form method="POST" action="<?=$link?>&nowy=&art_template=">
		<select name="template" style="width: 100px">
			<?
			$r_t = $baza->select("*","modarticle","modarticle_group=2","","");
			$il_t = $baza->size_result($r_t);
			for ($j_t=0; $j_t<$il_t; $j_t++){
				$row_t = $baza->row($r_t);
				?>
				<option value="<?=$row_t[idModArticle]?>"><?=$row_t[titleModArticle]?></option>
				<?
			}
			?>
		</select>
		<input type="submit" name="add_template" value="dodaj z szablonu" />
	</form>
	<?}?>
	<br><br>
	
	<table border="0" cellspacing="1" cellpadding="0">
		<tr>
			<td>Wybierz literę początkową tytułu:&nbsp;</td>
			<td style="background: orange; width: 10px; text-align: center;">
				<a href="<?=$link?>&spel=">wszystkie</a>
			</td>
			<?
			$tablica_liter = "ABCDEFGHIJKLMNOPRSTUWYZ";
			for ($l=0; $l<strlen($tablica_liter); $l++){
				?>
				<td style="background: orange; width: 10px; text-align: center;">
					<a href="<?=$link?>&spel=<?=$tablica_liter[$l]?>"><?=$tablica_liter[$l]?></a>
				</td>
				<?
			}
			?>
		</tr>
	</table>
	<?
	$where = "";
	if (isset($_GET[spel]) and strlen($_GET[spel])>0){
		$where = " and titleModArticle LIKE '$_GET[spel]%'";
	}
	?>
	
	<br><br>
	<h4>Lista artykułów z podziałem na grupy:</h4>
	
	<table cellpadding="1" cellspacing="2" border="0">
		<tr class="gray0">
			<th>Tytuł</th>
			<th>Autor</th>
			<th>Autor wyśw.</th>
			<th>Data utworzenia</th>
			<th>Do druku</th>
			<th>Kolojność w grupie</th>
			<th></th>
		</tr>
		<?
		$r_g = $baza->select("*","modarticlegroup","modarticlegroup_active='YES'","order by modarticlegroup_name");
		$il_g = $baza->size_result($r_g);
		for ($j_g=0; $j_g<$il_g; $j_g++){
			$row_g = $baza->row($r_g);
			
			$r_count = $baza->select("MAX(modarticle_order) as max","modarticle","modarticle_group=$row_g[modarticlegroup_id]");
			$row_count = $baza->row($r_count);
			$max_order = $row_count[max];
			if (!$max_order>0)
				$max_order = 0;

			?>
			<tr>
				<td colspan="1" style="text-align: left; font-weight: bold;">
					<?=$row_g[modarticlegroup_name]." (".$max_order."):"?>
				</td>
				<td colspan="6">	
					<?if ($add_flag){?>
						<a href="<?=$link?>&nowy=&order=<?=$max_order?>&group=<?=$row_g[modarticlegroup_id]?>">dodaj artykuł w grupie</a>
					<?}?>
				</td>
			</tr>	
			
			<?	
			$r=$baza->select("*","modarticle","modarticle_group=$row_g[modarticlegroup_id] and activeModArticle='YES' $where","ORDER BY modarticle_order desc","");
			$il = $baza->size_result($r);
			if ($il>0){
				for ($j=0; $j<$il; $j++){
					$row = $baza->row($r);
					$link_usuwania = $link."&usun_art=".$row['idModArticle'];
					$mod = $j%2; 
					?>
					<tr class="gray<?=$mod?>">
						<td>
							<?if ($edt_flag){?>
							<a href="<?=$link?>&article=<?=$row['idModArticle']?>"><?=$row['titleModArticle']?></a>
							<?}else{?>
								<?=$row['titleModArticle']?>
							<?}?>
						</td>
						<td>
						<?
						$r_a=$baza->select("*", "cmsadmuser", "idAdmUser=$row[authorModArticle]","","");
						$row_a = $baza->row($r_a);
						echo $row_a[loginAdmUser];
						?>
						</td>
						<td><?=$row[authorName]?></td>
						<td><?=$row[dateModArticle]?></td>
						<td><? if($row[printModArticle] == "YES") echo "TAK"; else echo "NIE";?></td>
						<td><?=$row[modarticle_order]?></td>
						<td>
							<?if ($del_flag){?>
							<a href="#" onclick="czy_usunac('<?=$link_usuwania?>')">usuń</a>
							<?}?>
							<?
							$res_kom = $baza->select("*","modarticlecomment","modarticlecomment_article=".$row['idModArticle'],"order by modarticlecomment_id desc","");
							if ($baza->size_result($res_kom)>0){
								?>
								&nbsp;|&nbsp;<a href="<?=$link?>&comment=<?=$row['idModArticle']?>">komentarze</a>
								<?
							}
							?>
						</td>
					</tr>
					<?
				}			
			}
			else{
				?>
				<tr>
					<td colspan="6">brak artykułów w grupie</td>
				</tr>
				<?
			}

			?>
			<tr>
				<td colspan="7">&nbsp;</td>
			</tr>	
			<?	
		}
		?>
	</table>	
	</center>	
	<br>
	<?
	}
	
	//komentarze do artykułu
	if (isset($_GET[comment])){
		if (isset($_GET[active])){
			$r = $baza->update("modarticlecomment","modarticlecomment_active='YES'","modarticlecomment_id=".$_GET[active]);
		}
		
		$r = $baza->select("*","modarticlecomment","modarticlecomment_article=$_GET[comment]",
			"order by modarticlecomment_id desc");
		if (($ile=$baza->size_result($r))>0){
			?>
			<center>
			<br />
			<table width="90%" id="comments" border="0" cellpadding="1" cellspacing="1">
				<tr>
					<th colspan="7" style="text-align: center;">Komentarze:<br><br></th>
				</tr>
				<tr class="gray0">
					<th>Lp</th>
					<th>Autor</th>
					<th>Treść</th>
					<th>Data</th>
					<th>IP</th>
					<th>Aktyw.</th>
					<th></th>
				</tr>
			
			<?
			for ($i=0; $i<$ile; $i++){
				$row = $baza->row($r);
				$licz = $i+1;
				$mod = $i%2;
				?>
				<tr class="gray<?=$mod?>">
					<td><?=$licz?></td>
					<td><?=$row[modarticlecomment_author]?></td>
					<td><?=$row[modarticlecomment_content]?></td>
					<td><?=$row[modarticlecomment_date]?> (<?=$row[modarticlecomment_time]?>)</td>
					<td><?=$row[modarticlecomment_ip]?></td>
					<td><?=$row[modarticlecomment_active]?></td>
					<td><?if ($row[modarticlecomment_active] == 'NO'){?>
						<a href="?modules=&manage=<?=$_GET[manage]?>&comment=<?=$_GET[comment]?>&active=<?=$row[modarticlecomment_id]?>" >Aktywuj</a>
						<?}?>
					</td>
				</tr>
				<?
			}
			?>
			</table>
			</center>
			<?
		}
		
		?>
		<br>
		<center>
		<a href="?modules=&manage=<?=$_GET[manage]?>" >Powrót do listy artykułów.</a>
		</center>
		<?	
	}
	
	//formularz do edycji artykułu
	if (isset($_GET[article])){
		$r=$baza->select("*","modarticle,cmsadmuser","idModArticle=".$_GET['article'],"","");
		$il = $baza->size_result($r);
		if ($il>0){
			$row = $baza->row($r);
			?>
			<form method="POST" action="">
			<table cellpadding="1" cellspacing="1" width="100%">
				<tr class="gray0">
					<td width="150">
						Grupa artykułów:
					</td>
					<td style="text-align: left;">
						<select name="group" style="width: 50%;">
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
					</td>
				</tr>
				<tr class="gray1">
					<td>
						Tytuł: 
					</td>
					<td style="text-align: left;">
						<input style="width: 50%;" type="text" name="title" value="<?=$row['titleModArticle']?>" size="56" /><br/>
					</td>
				</tr>
				<tr class="gray0">
					<td>
						Streszczenie: 
					</td>
					<td style="text-align: left;">
						<?
						$oFCKeditor = new FCKeditor("short") ;
						$oFCKeditor->BasePath	= "javascript/edytor_www/";
						$oFCKeditor->Value		= $row['modarticle_short'] ;
						$oFCKeditor->Create() ;
						?>			
					</td>
				</tr>
				<tr class="gray1">
					<td>
						Treść właściwa:
					</td>
					<td style="text-align: left;">
						<?
						//echo $row['contentModArticle'];
						$oFCKeditor = new FCKeditor("content") ;
						$oFCKeditor->BasePath	= "javascript/edytor_www/";
						$oFCKeditor->Value		= $row['contentModArticle'] ;
						$oFCKeditor->Create() ;
						?>			
					</td>
				</tr>
				<tr class="gray0">
					<td>
						Kolejność w grupie: 
					</td>
					<td style="text-align: left;">
						<input style="width: 50%;" type="text" name="kolejnosc" value="<?=$row['modarticle_order']?>" size="20" /><br/>
						<!--Archiwalny: 
						<input type="radio" name="archives" value="NO" <?if ($row['archivesModArticle']=='NO') echo "checked";?> /> NO 
						<input type="radio" name="archives" value="YES" <?if ($row['archivesModArticle']=='YES') echo "checked";?> /> YES <br />
						-->
					</td>
				</tr>
				<tr class="gray1">
					<td>
						Autor artykułu: 
					</td>
					<td style="text-align: left;">
						<input style="width: 50%;" type="text" name="autor" value="<?=$row['authorName']?>" size="30" /><br/>
					</td>
				</tr>
				<tr class="gray0">
					<td>
						Data utworzenia: 
					</td>
					<td style="text-align: left;">
						<input style="width: 50%;" type="text" name="data" value="<?=$row['dateModArticle']?>" onclick="showKal(this)" size="20" /><br/>
					</td>
				</tr>
				<tr class="gray1">
					<td>
						Do druku: 
					</td>
					<td style="text-align: left;">
						<input type="radio" name="print" value="NO" <?if ($row['printModArticle']=='NO') echo "checked";?> /> NIE 
						<input type="radio" name="print" value="YES" <?if ($row['printModArticle']=='YES') echo "checked";?> /> TAK <br />
						<!--Aktywny: 
						<input type="radio" name="active" value="NO" <?if ($row['activeModArticle']=='NO') echo "checked";?> /> NIE 
						<input type="radio" name="active" value="YES" <?if ($row['activeModArticle']=='YES') echo "checked";?> /> TAK <br /-->
					</td>
				</tr>
			</table>
			<center><input type="submit" name="save" value="Zapisz" /></center>
			</form>
			<?
		}
		?>
		<br>
		<center>
		<a href="?modules=&manage=<?=$_GET[manage]?>" >Powrót do listy artykułów.</a>
		</center>
		<?	
	}		
	
	//formularz do dodania nowego artykułu
	if (isset($_GET[nowy])){
		if(isset($_GET[art_template]) and isset($_POST[add_template]) and $_POST[template]!=""){
			$r2=$baza->select("*","modarticle","idmodarticle=$_POST[template]","");
			$row2 = $baza->row($r2);
			$streszczenie = $row2[modarticle_short];
			$pelen = $row2[contentModArticle];
		}
		?>
		<center><h4>Dodawanie nowego artykułu</h4></center>
		<br>
		<form method="POST" action="?modules=&manage=<?=$_GET[manage]?>">
			<form method="POST" action="">
			<table cellpadding="1" cellspacing="1" width="100%">
				<tr class="gray0">
					<td width="150">
						Grupa artykułów:
					</td>
					<td style="text-align: left;">
						<select name="group" style="width: 50%;">
						<?
						$r2=$baza->select("*","modarticlegroup","modarticlegroup_active='YES'","ORDER BY modarticlegroup_name","");
						$il2 = $baza->size_result($r2);
						if ($il2>0){
							for ($i=0; $i<$il2; $i++){
								$row2 = $baza->row($r2);
								?>
								<option value="<?=$row2[modarticlegroup_id]?>" 
									<?if ($row2[modarticlegroup_id]==$_GET[group]) echo "selected";?>
								><?=$row2[modarticlegroup_name]?></option>
								<?
							}
						}
						?>
						</select>
					</td>
				</tr>
				<tr class="gray1">
					<td>
						Tytuł: 
					</td>
					<td style="text-align: left;">
					<input style="width: 50%;" type="text" name="title" value="" size="56" /><br/>
					</td>
				</tr>
				<tr class="gray0">
					<td>
						Streszczenie: 
					</td>
					<td style="text-align: left;">
					<?
					$oFCKeditor = new FCKeditor("short") ;
					$oFCKeditor->BasePath	= "javascript/edytor_www/";
					$oFCKeditor->Value		= "$streszczenie" ;
					$oFCKeditor->Create() ;
					?>			
					</td>
				</tr>
				<tr class="gray1">
					<td>
						Treść właściwa:
					</td>
					<td style="text-align: left;">
					<?
					$oFCKeditor = new FCKeditor("content") ;
					$oFCKeditor->BasePath	= "javascript/edytor_www/";
					$oFCKeditor->Value		= "$pelen" ;
					$oFCKeditor->Create() ;
					?>			
					</td>
				</tr>
				<tr class="gray0">
					<td>
						Kolejność w grupie: 
					</td>
					<td style="text-align: left;">
					<input style="width: 50%;" type="text" name="kolejnosc" value="<?=$_GET[order]+1?>" size="20" /><br/>
					<!--Archiwalny: 
					<input type="radio" name="archives" value="NO" checked /> NO 
					<input type="radio" name="archives" value="YES" /> YES <br />
					-->
					</td>
				</tr>
				<tr class="gray1">
					<td>
						Autor artykułu: 
					</td>
					<td style="text-align: left;">
					<input style="width: 50%;" type="text" name="autor" value="<?=$_SESSION[panel_admin_user]?>" size="20" /><br/>
					</td>
				</tr>
				<tr class="gray0">
					<td>
						Data utworzenia: 
					</td>
					<td style="text-align: left;">
					<input style="width: 50%;" type="text" name="data" value="<?=date("Y-m-d")?>" onclick="showKal(this)" size="20" /><br/>
					</td>
				</tr>
				<tr class="gray1">
					<td>
						Do druku: 
					</td>
					<td style="text-align: left;">
					<input type="radio" name="print" value="NO" checked /> NIE
					<input type="radio" name="print" value="YES" /> TAK <br />
					<!--Aktywny: 
					<input type="radio" name="active" value="NO" /> NIE 
					<input type="radio" name="active" value="YES" checked /> TAK <br /-->
					</td>
				</tr>
			</table>
			<center><input type="submit" name="save_new" value="Dodaj artykuł" /></center>
		</form>
		
		<br>
		<center>
		<a href="?modules=&manage=<?=$_GET[manage]?>" >Powrót do listy artykułów.</a>
		</center>
		<?	
	}
?>
</span>