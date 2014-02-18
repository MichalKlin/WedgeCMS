<br>
<table class="ramka_contant" border="0" cellpadding="0" cellspacing="0" width="98%">
<tr>
<td class="ramka_title"><?=$NAME?></td>
</tr>
<tr>
<td class="ramka_text">
<br>
<?
include_once("./include/mail.php");

//usunięcie z newslettera
if (isset($_GET[del])){
	$r = $this->baza->delete("mod_newsletter","mnews_kod='$_GET[del]' and mnews_id=$_GET[i]");
	?>
	<br>
	<p style="color: yellow; font-size: 18px;">Wypisanie z newslettera przebiegła pomyślnie!</p>
	<?
}

//aktywacja newslettera
if (isset($_GET[add])){
	$r = $this->baza->select("*","mod_newsletter","mnews_kod='$_GET[add]'");
	if ($this->baza->size_result($r)>0){
		$row = $this->baza->row($r);
		$r = $this->baza->update("mod_newsletter","mnews_status=2","mnews_id=$row[mnews_id]");
		
		?>
		<br>
		<p style="color: yellow; font-size: 18px;">Aktywacja newslettera przebiegła pomyślnie!</p>
		<?
	}	
}

if (!isset($_POST[zapisz]) and !isset($_POST[sprawdz])){
	?>
	<br>
	<form method="POST" action="" >
		<table border="0" cellpadding="0" cellspacing="0">
			<tr>
				<th>Adres e-mail: </th>
				<td><input type="text" name="email" /></td>
			</tr>
			<tr>
				<th>Newsletter: </th>
				<td>
				<?
				$r_art=$this->baza->select("*","modarticlegroup","modarticlegroup_active='YES'","","");
				if (($ile=$this->baza->size_result($r_art))>0){
					for ($i=0; $i<$ile; $i++){
						$row_art = $this->baza->row($r_art);	
						if ($row_art['modarticlegroup_id']!=1){
						?>
						<input class="checkbox" type="checkbox" name="grupa_<?=$row_art['modarticlegroup_id']?>" /> <?=$row_art['modarticlegroup_name']?><br />
						<?
						}
					}
				}
				?>
				</td>
			</tr>
			<tr>
				<td colspan="2" style="text-align: center;">
					<br><input type="submit" name="zapisz" value="Zapisz do newslettera" />
					&nbsp;&nbsp;&nbsp;<input type="submit" name="sprawdz" value="Sprawdź moje" />
				</td>
			</tr>
		</table>
	</form>
	<?
}
if (isset($_POST[zapisz])){
	if (isset($_POST[email]) and strlen(trim($_POST[email]))>0 and strchr($_POST[email],"@")){
		$r_art=$this->baza->select("*","modarticlegroup","modarticlegroup_active='YES'","","");
		if (($ile=$this->baza->size_result($r_art))>0){
			$ok = false;
			for ($i=0; $i<$ile; $i++){
				$row_art = $this->baza->row($r_art);	
				if (isset($_POST[grupa_.$row_art['modarticlegroup_id']]) and $_POST[grupa_.$row_art['modarticlegroup_id']]=="on"){
					$ok = true;
					$tab[$i] = $row_art['modarticlegroup_id'];
				}
				else 
					$tab[$i] = "";
			}
		}
		if ($ok){
			for ($i=0; $i<$ile; $i++){
				if (strlen($tab[$i])>0){
					$r = $this->baza->select("*","mod_newsletter,modarticlegroup",
					"mnews_email='$_POST[email]' and mnews_grupa=$tab[$i] 
					and mnews_status>=1 and mnews_grupa=modarticlegroup_id","","");
					if ($this->baza->size_result($r)==0){
						$code = md5($_POST[email].$tab[$i]);
						$r = $this->baza->insert("mod_newsletter","0,\"$_POST[email]\",$tab[$i],1,\"$code\"","");
						
						$r2 = $this->baza->select("*","modarticlegroup",
							"modarticlegroup_id=$tab[$i]");
						$row2 = $this->baza->row($r2);
						?>
						<p style="color: yellow; font-size: 18px;">Zapisanie do newslettera: <b><?=$row2[modarticlegroup_name]?></b> przebiegło pomyślnie. Do pełnej aktywacji naleťy odebrać e-mail i aktywować newsletter klikając w link.</p><br>
						<?
						$do = $_POST[email];
						$grupa = $row2[modarticlegroup_name];
						mail_newsletter_zapisz($do,$grupa,$code);
					}
					else{
						$row = $this->baza->row($r);
						?>
						<p style="color: yellow; font-size: 18px;">Jesteś juť zapisany do newslettera: <?=$row[modarticlegroup_name]?></p><br>
						<?
					}
				}
			}
		}
		else{
			?>
			<p style="color: red; font-size: 18px;">Naleťy wybrać przynajmniej jeden Newsletter!</p>
			<?
		}
	}
	else{
		?>
		<p style="color: red; font-size: 18px;">Naleťy podać poprawny adres e-mail!</p>
		<?
	}
	?>
	<br /><p><a href="?p=">Powrót</a> </p>
	<?
}
if (isset($_POST[sprawdz])) {
	if (isset($_POST[email]) and strlen(trim($_POST[email]))>0 and strchr($_POST[email],"@")){
		$do = $_POST['email'];
		$r = $this->baza->select("*","mod_newsletter,modarticlegroup",
				"mnews_email='$_POST[email]' and mnews_status>=1 and mnews_grupa=modarticlegroup_id","","");
		if (($ile=$this->baza->size_result($r))>0){
			for ($i=0; $i<$ile; $i++){
				$row = $this->baza->row($r);	
				$tab_newsletter[$i][0] = $row[mnews_id];
				$tab_newsletter[$i][1] = $row[modarticlegroup_name];
				$tab_newsletter[$i][2] = $row[mnews_kod];
			}
		}
		mail_newsletter_moje($do,$tab_newsletter);
		?>
		<p style="color: yellow; font-size: 18px;">Na podany adres e-mail została wysłana lista newsletterów, do których jesteś zapisany.</p>
		<?
	}
	else{
		?>
		<p style="color: red; font-size: 18px;">Naleťy podać poprawny adres e-mail!</p>
		<?
	}
	?>
	<br /><p><a href="?p=">Powrót</a> </p>
	<?	
}
?>
<br>
</td>
</tr>
</table>
<br />