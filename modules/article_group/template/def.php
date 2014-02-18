<center>
<?
$r_art=$this->baza->select("*",
"modarticle,modarticlegrouppage",
"modarticlegrouppage_group=modarticle_group and 
modarticlegrouppage_schema=".$SCHEMA_MODULE." 
and modarticlegrouppage_page=".$PAGE."
and activeModArticle='YES'","
order by modarticle_order,dateModArticle","");

if (($ile=$this->baza->size_result($r_art))>0){
	?>
	<table class="ramka_contant" border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
	<td class="ramka_text">
	<?
	if (!isset($_GET[a]) and !isset($_GET[k])){
		$r_act=$this->baza->select("*","cmsmodule","idModule=$MODULE");
		$row_act = $this->baza->row($r_act);
		$site_action = $row_act['pathAction'];
	
		$r_art=$this->baza->select("*",
		"modarticlegrouppage",
		"modarticlegrouppage_schema=".$SCHEMA_MODULE." 
		AND modarticlegrouppage_page=".$PAGE."","","");
		$row_art = $this->baza->row($r_art);
		$on_page = $row_art[modarticlegrouppage_onpage];
		$pagowanie = $row_art[modarticlegrouppage_paged];
		//echo $on_page;
		
		$r_art=$this->baza->select("*",
		"modarticle,modarticlegrouppage",
		"modarticlegrouppage_group=modarticle_group and 
		modarticlegrouppage_schema=".$SCHEMA_MODULE." 
		and modarticlegrouppage_page=".$PAGE." 
		and activeModArticle='YES'","
		order by modarticle_order desc","");//,dateModArticle
		
		if (($ile=$this->baza->size_result($r_art))>0){
			$ile_podstron = ceil($ile/$on_page);
			$od = $_GET[p]*$on_page;
			for ($i=0; $i<$od; $i++){
				$row_art = $this->baza->row($r_art);
			}
			if ($_GET[p]!=$ile_podstron-1)
				$wysw = $on_page;
			else 
				$wysw = $ile - ($_GET[p])*$on_page;	
	
			for ($i=0; $i<$wysw; $i++){
				$row_art = $this->baza->row($r_art);
				?>
				<table border="0" cellpadding="0" cellspacing="0" width="100%" class="art_group">
				<tr>
					<td colspan="2" class="art_group_date" nowrap><?=$row_art['dateModArticle']?></td>
				</tr>
				<tr>
					<td colspan="2" class="art_group_title" style="font-size: 16px;"><?=$row_art['titleModArticle']?></td>
				</tr>
				
				<tr>
					<td colspan="2" class="art_group_content">
					<?
					if (strlen($row_art['modarticle_short'])>0){
						?><?=$row_art['modarticle_short']?><?
						?><br /><?
					}
					else{
						?><?=$row_art['contentModArticle']?><br><?
					}
					?>
					</td>
				</tr>
				
				<tr>
					<td class="art_group_author" colspan="2">
					<table border="0" cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<!--td class="art_author">
							Autor: <strong><?=$row_art['authorName']?></strong>
							</td-->
							<td class="art_komentarz">
							<?
							if (strlen($row_art['contentModArticle'])>0){
								?><a href="<?=$site_action?>?a=<?=$row_art[idModArticle]?>&p=<?=$PAGE?>"><label>mod.article.group.readMore</label></a><?
							}
							?>
							<!--
							&nbsp;
							<a href="<?=$site_action?>?k=<?=$row_art[idModArticle]?>&p=<?=$PAGE?>">komentarze</a>
							<?
							$r_k = $this->baza->select("*","modarticlecomment","modarticlecomment_article=$row_art[idModArticle] and modarticlecomment_active='YES'");
							$ile_k=$this->baza->size_result($r_k);
							?>
							[<?=$ile_k?>]
							-->
							</td>
						</tr>
					</table>
					</td>
				</tr>
				<tr>
					<td class="ramka_dol" colspan="2"></td>
				</tr>
				</table>
				<br>
				<?
				if ($i<$wysw-1){
					echo "<center><hr width='90%'  color=\"green\" height: 1px; style=\"color: green; background-color: green; border-color: green;\"></center>";
					//echo "* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *";
				}
				?>
				<br>
				<?
			}
		}
		
		//pagowanie
		if ($on_page<$ile and $pagowanie=='YES'){
			?>
			<center>
			<table border="0" class="pagowanie">
				<tr>
					<td>
						<?
						if (isset($_GET[p]) and $_GET[p]>0){
							$prev = $_GET[p]-1;
							?><a href="?p=<?=$prev?>"> << </a><?
						}
						else {
							?> << <?
						}
						?> 
					</td>
					<td>
					<?
					for ($ii=0; $ii<$ile_podstron;$ii++){
						$jj = $ii+1;
						if ((isset($_GET[p]) and $_GET[p]!=$ii)
							or !isset($_GET[p]) and $ile_podstron>1 and $ii!=0){
							?>
							[<a href="?p=<?=$ii?>"><?=$jj?></a>] 
							<?
						}
						else {
							?>[<?=$jj?>]<?
						}
					}
					?>
					</td>
					<td> 
						<?
						if ((isset($_GET[p]) and $_GET[p]<$ile_podstron-1) or 
							(!isset($_GET[p]) and $ile_podstron>1)){
							$next = $_GET[p]+1;
							?><a href="?p=<?=$next?>"> >> </a><?
						}
						else {
							?> >> <?
						}
						?> 
					</td>
				</tr>
			</table>
			</center>
			<?
		}
	}
	
	//pełny artykuł
	elseif (isset($_GET[a])){
		$r=$this->baza->select("*","modarticle,cmsadmuser","idModArticle=$_GET[a] and authorModArticle=idadmuser");
		$row_art = $this->baza->row($r);
		
		?>
		<table border="0" cellpadding="0" cellspacing="0" width="100%" class="art_group">
		<tr>
			<td class="art_group_title"><?=$row_art['titleModArticle']?></td>
			<td class="art_group_date" nowrap><?=$row_art['dateModArticle']?></td>
		</tr>
		<tr><td colspan="2">&nbsp;</td> </tr>
		<tr>
			<td colspan="2" class="art_group_content">
			<?
			if (strlen($row_art['modarticle_short'])>0 and strlen($row_art['contentModArticle'])==0){
				?><span class="zajawka"><?=$row_art['modarticle_short']?></span><?
				?><br /><?
			}
			elseif (strlen($row_art['contentModArticle'])>0){
				
				$tekst = $row_art['contentModArticle'];
				$e = explode("###",$tekst);

				//pagowanie artykułu
				if (sizeof($e)>1) {
					?><center><?
					for ($i=0;$i<sizeof($e);$i++){
						$j = $i+1;
						if (!isset($_GET[s]) and $i==0){
							?>[<span style="color: red; font-weight: bold;"><?=$j?></span>] <?
						}
						else {
							if ($_GET[s]==$i) {
								?>[<span style="color: red; font-weight: bold;"><?=$j?></span>] <?
							}
							else{
								?>[<a href="?a=<?=$_GET[a]?>&p=<?=$_GET[p]?>&s=<?=$i?>"><?=$j?></a>] <?
							}
						}
					}
					?></center><?
				}				
				
				if (!isset($_GET[s]) or $_GET[s]==0){
					?><br /><span class="zajawka"><?=$row_art['modarticle_short']?></span><?
					?><br /><?	
					?><br /><?=$e[0]?><br><?
				}
				else{
					?><br /><?=$e[$_GET[s]]?><br><?					
				}

				//pagowanie artykułu
				if (sizeof($e)>1) {
					?><br/><center><?
					for ($i=0;$i<sizeof($e);$i++){
						$j = $i+1;
						if (!isset($_GET[s]) and $i==0){
							?>[<span style="color: red; font-weight: bold;"><?=$j?></span>] <?
						}
						else {
							if ($_GET[s]==$i) {
								?>[<span style="color: red; font-weight: bold;"><?=$j?></span>] <?
							}
							else{
								?>[<a href="?a=<?=$_GET[a]?>&p=<?=$_GET[p]?>&s=<?=$i?>"><?=$j?></a>] <?
							}
						}
					}
					?></center><?
				}				
			}
			?>
			</td>
		</tr>
		
		<tr>
			<td class="art_group_author" colspan="2">
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td class="art_author">Autor: <strong><?=$row_art['authorName']?></strong></td>
				</tr>
			</table>
			</td>
		</tr>
		</table>
		
		<?
		if (isset($_POST[zapisz])){
			if (strlen($_POST[kom_autor])>0 and strlen($_POST[kom_tresc])>0){
				$dzis = date("Y-m-d");
				$godz = date("H:i");
				$ip = $_SERVER['REMOTE_ADDR'];
				$autor = htmlspecialchars($_POST[kom_autor]);
				$tresc = htmlspecialchars($_POST[kom_tresc]);
				$val = "0, $_GET[a], '$autor', '$tresc',
				'$dzis','$godz','$ip','NO'";
				$this->baza->insert("modarticlecomment",$val);
				$result_m = $this->baza->select("value","cmsconfig","name='ADM_EMAIL'");
				$row_m = $this->baza->row($result_m);
				mail($row_m[value],"nowy komentarz w kryniczno.pl","$autor: $tresc");
				?>
				<p>Dziękujemy za komentarz - po zatwierdzeniu przez moderatora strony komentarz będzie się wyświetlał w tym artykule.</p>
				<?
			}
			else{
				?>
				<p>Należy uzupełnić oba pola: autor i komentarz!!!</p>
				<?
			}
		}
		
		if (isset($_SESSION[cms_user_id])){
		?>
		<br>
		<form method="POST" action="">
		<table class="form">
			<tr>
				<th>Autor: </th>
				<td><input type="text" name="kom_autor" /></td>
			</tr>
			<tr>
				<th>Komentarz: </th>
				<td><textarea name="kom_tresc" cols="40" rows="3"></textarea></td>
			</tr>
			<tr>
				<td colspan="2"><center><input type="submit" name="zapisz" value="Dodaj komentarz" /></center></td>
			</tr>
		</table>
		</form>		
		<?
		}
		
		$r=$this->baza->select("*","cmspage","idPage=$PAGE");
		$row = $this->baza->row($r);
		?>
		<br><center><span class="powrot"><a href="<?=$row[htmlName]?>.html"><label>mod.article.group.title</label></a>
		</span></center><?
		
		//komentarze
		$r = $this->baza->select("*","modarticlecomment","modarticlecomment_article=$_GET[a]
			and modarticlecomment_active='YES'",
			"order by modarticlecomment_id desc");
		if (($ile=$this->baza->size_result($r))>0){
			?>
			<center>
			<br />
			<table width="90%" id="comments" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<th colspan="2" class="com_head">Komentarze:<br><br></th>
				</tr>
			
			<?
			for ($i=0; $i<$ile; $i++){
				$row = $this->baza->row($r);
				?>
				<tr>
					<td width="100%" class="com_author">Autor: <strong><?=$row[modarticlecomment_author]?></strong></td>
					<td nowrap class="com_data" nowrap><?=$row[modarticlecomment_date]?> (<?=$row[modarticlecomment_time]?>)</td>
				</tr>
				<tr>
					<td colspan="2" class="com_content"><?=$row[modarticlecomment_content]?></td>
				</tr>
				<tr>
					<td colspan="2" style="text-align: center; vertical-align: middle; padding: 5px;">***</td>
				</tr>
				<?
			}
			?>
			</table>
			</center>
			<?
		}
	}
	elseif (isset($_GET[k])){
		$r=$this->baza->select("*","modarticle","idModArticle=$_GET[k]");
		$row_art = $this->baza->row($r);
		
		?>
		<table border="0" cellpadding="0" cellspacing="0" width="100%" class="art_group">
		<tr>
			<td class="art_group_title"><?=$row_art['titleModArticle']?></td>
			<td class="art_group_date" nowrap><?=$row_art['dateModArticle']?></td>
		</tr>
		
		<tr>
			<td colspan="2" class="art_group_content">
			<?
			if (strlen($row_art['modarticle_short'])>0 and strlen($row_art['contentModArticle'])==0){
				?><span class="zajawka"><?=$row_art['modarticle_short']?></span><?
				?><br /><?
			}
			elseif (strlen($row_art['contentModArticle'])>0){
				
				$tekst = $row_art['contentModArticle'];
				$e = explode("###",$tekst);

				//pagowanie artykułu
				if (sizeof($e)>1) {
					?><br/><center><?
					for ($i=0;$i<sizeof($e);$i++){
						$j = $i+1;
						if (!isset($_GET[s]) and $i==0){
							?>[<span style="color: red; font-weight: bold;"><?=$j?></span>] <?
						}
						else {
							if ($_GET[s]==$i) {
								?>[<span style="color: red; font-weight: bold;"><?=$j?></span>] <?
							}
							else{
								?>[<a href="?k=<?=$_GET[k]?>&p=<?=$_GET[p]?>&s=<?=$i?>"><?=$j?></a>] <?
							}
						}
					}
					?></center><?
				}				
				
				if (!isset($_GET[s]) or $_GET[s]==0){
					?><br /><span class="zajawka"><?=$row_art['modarticle_short']?></span><?
					?><br /><?	
					?><br /><?=$e[0]?><br><?
				}
				else{
					?><br /><?=$e[$_GET[s]]?><br><?					
				}

				//pagowanie artykułu
				if (sizeof($e)>1) {
					?><br/><center><?
					for ($i=0;$i<sizeof($e);$i++){
						$j = $i+1;
						if (!isset($_GET[s]) and $i==0){
							?>[<span style="color: red; font-weight: bold;"><?=$j?></span>] <?
						}
						else {
							if ($_GET[s]==$i) {
								?>[<span style="color: red; font-weight: bold;"><?=$j?></span>] <?
							}
							else{
								?>[<a href="?k=<?=$_GET[k]?>&p=<?=$_GET[p]?>&s=<?=$i?>"><?=$j?></a>] <?
							}
						}
					}
					?></center><?
				}				
			}
			?>
			</td>
		</tr>
		
		<tr>
			<td class="art_group_author" colspan="2">
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td class="art_author">Autor: <strong><?=$row_art['authorName']?></strong></td>
					<td class="art_komentarz">
					</td>
				</tr>
			</table>
			</td>
		</tr>
		</table>
	
		<br><br>
		<?
		if (isset($_POST[zapisz])){
			if (strlen($_POST[kom_autor])>0 and strlen($_POST[kom_tresc])>0){
				$dzis = date("Y-m-d");
				$godz = date("H:i");
				$ip = $_SERVER['REMOTE_ADDR'];
				$autor = htmlspecialchars($_POST[kom_autor]);
				$tresc = htmlspecialchars($_POST[kom_tresc]);
				$val = "0, $_GET[k], '$autor', '$tresc',
				'$dzis','$godz','$ip','NO'";
				$this->baza->insert("modarticlecomment",$val);
				$result_m = $this->baza->select("value","cmsconfig","name='ADM_EMAIL'");
				$row_m = $this->baza->row($result_m);
				mail($row_m[value],"nowy komentarz w kryniczno.pl","$autor: $tresc");
				?>
				<p>Dziękujemy za komentarz - po zatwierdzeniu przez moderatora strony komentarz będzie się wyświetlał w tym artykule.</p>
				<?
			}
			else{
				?>
				<p>Należy uzupełnić oba pola: autor i komentarz!!!</p>
				<?
			}
		}
		if (isset($_SESSION[cms_user_id])){
		?>
		<form method="POST" action="">
		<table class="form">
			<tr>
				<th>Autor: </th>
				<td><input type="text" name="kom_autor" /></td>
			</tr>
			<tr>
				<th>Komentarz: </th>
				<td><textarea name="kom_tresc" cols="40" rows="3"></textarea></td>
			</tr>
			<tr>
				<td colspan="2"><center><input type="submit" name="zapisz" value="Dodaj komentarz" /></center></td>
			</tr>
		</table>
		</form>
		<?
		}
		$r=$this->baza->select("*","cmspage","idPage=$PAGE");
		$row = $this->baza->row($r);
		?>
		<br><center><span class="powrot"><a href="<?=$row[htmlName]?>.html">Powrót</a></span></center><?
		
		$r = $this->baza->select("*","modarticlecomment","modarticlecomment_article=$_GET[k]
			and modarticlecomment_active='YES'",
			"order by modarticlecomment_id desc");
		if (($ile=$this->baza->size_result($r))>0){
			?>
			<center>
			<br />
			<table width="90%" id="comments" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<th colspan="2" class="com_head">Komentarze:<br><br></th>
				</tr>
			
			<?
			for ($i=0; $i<$ile; $i++){
				$row = $this->baza->row($r);
				?>
				<tr>
					<td width="100%" class="com_author">Autor: <strong><?=$row[modarticlecomment_author]?></strong></td>
					<td nowrap class="com_data" nowrap><?=$row[modarticlecomment_date]?> (<?=$row[modarticlecomment_time]?>)</td>
				</tr>
				<tr>
					<td colspan="2" class="com_content"><?=$row[modarticlecomment_content]?></td>
				</tr>
				<tr>
					<td colspan="2" style="text-align: center; vertical-align: middle; padding: 5px;">***</td>
				</tr>
				<?
			}
			?>
			</table>
			</center>
			<?
		}
	}
	?>
	</td>
	</tr>
	</table>
<?
}
?>
</center>