<?
include_once("../modules/galeria_prosta/functions.php") ;
include("./javascript/edytor_www/fckeditor.php") ;
echo "<script language=JavaScript src=\"../javascript/kalendarz/kalendarz.js\" type=text/javascript></script>";

$tabela = "modgallery";
$katalog_galerii="../galeria";

//ściezka
if (isset($_GET['modules'])){
	$path = "?modules=&manage=$_GET[manage]";
	$ile_zdjec_szerokosc = 3;

//dodanie
if (isset($_POST['dodaj_galerie'])){
	if (strlen($_POST['nazwa'])>0){// and strlen($_POST['katalog'])>0)
		$new_katalog = $_POST['nazwa'];
		$new_katalog = str_replace("ą","a",$new_katalog);
		$new_katalog = str_replace("ę","e",$new_katalog);
		$new_katalog = str_replace("ż","z",$new_katalog);
		$new_katalog = str_replace("ź","z",$new_katalog);
		$new_katalog = str_replace("ś","s",$new_katalog);
		$new_katalog = str_replace("ć","c",$new_katalog);
		$new_katalog = str_replace("ń","n",$new_katalog);
		$new_katalog = str_replace("ł","l",$new_katalog);
		$new_katalog = str_replace("ó","o",$new_katalog);
		$new_katalog = str_replace("Ą","a",$new_katalog);
		$new_katalog = str_replace("Ę","e",$new_katalog);
		$new_katalog = str_replace("Ż","z",$new_katalog);
		$new_katalog = str_replace("Ź","z",$new_katalog);
		$new_katalog = str_replace("Ś","s",$new_katalog);
		$new_katalog = str_replace("Ć","c",$new_katalog);
		$new_katalog = str_replace("Ń","n",$new_katalog);
		$new_katalog = str_replace("Ł","l",$new_katalog);
		$new_katalog = str_replace("Ó","o",$new_katalog);
		$new_katalog = strtolower($new_katalog);
		$new_katalog = date("Y")."_".str_replace(" ","_",$new_katalog);
		//echo $new_katalog;
		if (!is_dir($katalog_galerii."/".$new_katalog)){
			$value = "0,
			'".htmlspecialchars($_POST['nazwa'])."',
			'".htmlspecialchars($_POST['opis'])."',
			'".htmlspecialchars($new_katalog)."',
			'".htmlspecialchars($_POST['data'])."',
			'".htmlspecialchars($_POST['autor'])."',
			'".htmlspecialchars($_POST['licznik'])."',
			$_POST[grupa],
			'".htmlspecialchars($_POST['info'])."',
			'".$_POST['aktyw']."'";
			$baza->insert($tabela,$value);
			
			mkdir($katalog_galerii."/".$new_katalog);
			mkdir($katalog_galerii."/".$new_katalog."/full");
			?><p style="color: green;">Galeria utworzona</p><?
		} else{
			?><p style="color: green;">Katalog o podanej nazwie już istnieje!</p><?
		}
	}
	else{
		?><p style="color: green;">Nazwa galerii i katalog są polami obowiązkowymi - proszę je wypełnić!</p><?
	}
}

//usuwanie
if (isset($_GET['usun_gal'])){
//	$result = $baza->select("*",$tabela,"idModGallery=".$_GET['usun_gal']);
//	$row = $baza->row($result);
//	echo $row[dirModGallery];
//	unlink($katalog_galerii."/".$row[dirModGallery]);
	$baza->delete($tabela,"idModGallery=".$_GET['usun_gal']);
}

//edycja galerii
if (isset($_GET['edytuj_gal'])){
	$where = "";
	if (isset($_GET['edytuj_gal']) and !isset($_POST['edytuj_gal'])){
		$where = "idModGallery=".$_GET['edytuj_gal'];
	}
	
	$result = $baza->select("*",$tabela,$where,"order by createdModGallery DESC","");		
	$size_result = $baza->size_result($result);	//zapis po edycji
	
	if (isset($_POST['zapisz_galerie'])){
		$value = "
		nameModGallery='".htmlspecialchars($_POST['nazwa'])."',
		describeModGallery='".htmlspecialchars($_POST['opis'])."',
		authorModGallery='".htmlspecialchars($_POST['autor'])."',
		createdModGallery='".htmlspecialchars($_POST['data'])."',
		counterModGallery='".htmlspecialchars($_POST['licznik'])."',
		modgallery.group=".$_POST[grupa].",
		activeModGallery='".$_POST['aktyw']."'";
		$baza->update($tabela,$value,"idModGallery=".$_GET['edytuj_gal'],"");
		echo "<p>Dane zostały pomyślnie zapisane</p>";
	}
	else{
		$row = $baza->row($result);
		?>
	<form method="POST" action="" name="pytanie">
	<p>Dodaj nową galerię:</p>
	<table border="0" class="tab_edycji">
	<tr>
		<th>Nazwa: </th><td><input type="text" name="nazwa" value="<?=$row['nameModGallery']?>" style="width: 300px;" size="40" /></td>
	</tr><tr>	
		<th>Opis:</th><td>
		<textarea name="opis" cols="60" rows="1" style="width: 300px;"><?=$row['describeModGallery'];?></textarea>					
		</td>
	</tr>
	<tr>
		<th>Autor: </th><td><input type="text" name="autor" style="width: 300px;" value="<?=$row['authorModGallery'];?>" /></td>
	</tr>	
	<!--tr>
		<th>Katalog:</th><td>	 
		<select name="katalog">
		<?
//		$d = dir("../galeria/");
//		$i=0;
//		while (false !== ($entry = $d->read())) {
//			if ($entry!="." and $entry!=".."){
//				$entr[$i]=$entry;
//				$i++;
//			}
//		}
//		$d->close();
//		sort($entr);
//		foreach ($entr as $katalog){
//		    echo "<option";
//		    if ($katalog==$row['dirModGallery']) echo " selected";
//		    echo ">$katalog</option>";
//		}
		?>		
		</select>		
		<input type="text" name="katalog" value="<?=$row['dirModGallery'];?>" style="width: 300px;" />
		</td>
	</tr-->	
	<tr>
		<th>Data utworzenia: </th><td><input type="text" name="data" value="<?=$row['createdModGallery'];?>" style="width: 300px;" onclick="showKal(this)" />
		</td>
	</tr>	
	<tr>
		<th>Licznik: </th><td><input type="text" name="licznik" value="<?=$row['counterModGallery'];?>" style="width: 300px;" /></td>
	</tr>	
	<tr>
		<th>Grupa: </th><td>
		<select name="grupa" style="width: 300px;" class="select">
			<?
			$result_g = $baza->select("*","modgallerygroup", "", "ORDER BY name");
			if (($ile = $baza->size_result($result_g))>0){
				for ($i=0; $i<$ile; $i++){
					$row_g = $baza->row($result_g);	
					?>
					<option value="<?=$row_g[id]?>" <?if ($row_g[id]==$row[group]) echo "selected";?>><?=$row_g[name]?></option>
					<?
				}
			}
			?>
		</select>
		</td>
	</tr>	
	<tr>
		<th>Aktywność:</th><td> 
		<select name="aktyw" style="width: 300px;" class="select">
			<option <? if($row['activeModGallery']=="YES") echo "selected";?> value="YES">Tak</option>
			<option <? if($row['activeModGallery']=="NO") echo "selected";?> value="NO">Nie</option>
		</select>
		</td>
	</tr>	
	</table>	
	<input type="submit" name="zapisz_galerie" value="Zachowaj zmiany" />
	</form>		<?
	}
	?><p><a href="<?=$path?>">Powrót do galerii</a></p><?	
}

if (isset($_GET['zdjecia_gal'])){
	?>
	<script language="javascript">
		function add(){
			var i = document.getElementById('number_next').value;
			
			var newTDlicz = document.createElement("td");
			newTDlicz.innerHTML = i + ".";
			
			var newTDfile = document.createElement("td");
			newTDfile.innerHTML = "<input type='file' name='file" + i +"' size='100' />";
			
			var newfoto = document.createElement("tr");
			newfoto.appendChild(newTDlicz);
			newfoto.appendChild(newTDfile);

			document.getElementById('fotos').appendChild(newfoto);
			document.getElementById('number_next').value = parseInt(i) + 1;
		}
	</script>
	<form method="POST" action="" enctype="multipart/form-data">
		Wskaż zdjęcia (tylko pliki jpg): <a href="#" onclick="add()">dodaj więcej</a>
		<table border="0" cellpadding="1" cellspacing="0">
			<tbody id="fotos">
			<tr>
				<td>1.</td>
				<td><input type="file" name="file1" size="100" /></td>
			</tr>
			</tbody>
		</table>
		
		<input type="hidden" name="number_next" id="number_next" value="2" />
	<input type="submit" name="zapisz" value="dodaj wszystkie" />
	</form>	
	<?
	
	$result = $baza->select("*",$tabela,"idModGallery=".$_GET['zdjecia_gal'],"");
	if ($size_result = $baza->size_result($result)==1){
		$row = $baza->row($result);
		
		//usuwanie zdjęcia
		if (isset($_GET[usun_zdj])){
			if (file_exists($katalog_galerii."/".$row['dirModGallery']."/".$_GET[usun_zdj]))
				unlink($katalog_galerii."/".$row['dirModGallery']."/".$_GET[usun_zdj]);
			if (file_exists($katalog_galerii."/".$row['dirModGallery']."/full/".$_GET[usun_zdj]))
				unlink($katalog_galerii."/".$row['dirModGallery']."/full/".$_GET[usun_zdj]);
		}
		
		//dodawanie zdjęcia
		if (isset($_POST[zapisz])){
			for ($ii=1; $ii<$_POST[number_next];$ii++){
				$file = "file".$ii;
				$file_name = $_FILES[$file]['name'];
				//echo $file_name;
				if (strlen($file_name)>0) {
					if (is_uploaded_file($_FILES[$file]['tmp_name'])){
						if (!isset($_SESSION['IMAGE_SIZE_X'])){
							$r_rozm = $baza->select("*","cmsconfig","name='IMAGE_SIZE_X'");
							if ($baza->size_result($r_rozm)==1){
								$row_rozm = $baza->row($r_rozm);
								$_SESSION['IMAGE_SIZE_X'] = $row_rozm[value];
							} else {$_SESSION['IMAGE_SIZE_X'] = 800;}
	
							$r_rozm = $baza->select("*","cmsconfig","name='IMAGE_SIZE_Y'");
							if ($baza->size_result($r_rozm)==1){
								$row_rozm = $baza->row($r_rozm);
								$_SESSION['IMAGE_SIZE_Y'] = $row_rozm[value];
							} else {$_SESSION['IMAGE_SIZE_Y'] = 600;}
	
							$r_rozm = $baza->select("*","cmsconfig","name='IMAGE_SIZE_MIN_X'");
							if ($baza->size_result($r_rozm)==1){
								$row_rozm = $baza->row($r_rozm);
								$_SESSION['IMAGE_SIZE_MIN_X'] = $row_rozm[value];
							} else {$_SESSION['IMAGE_SIZE_MIN_X'] = 200;}
	
							$r_rozm = $baza->select("*","cmsconfig","name='IMAGE_SIZE_MIN_Y'");
							if ($baza->size_result($r_rozm)==1){
								$row_rozm = $baza->row($r_rozm);
								$_SESSION['IMAGE_SIZE_MIN_Y'] = $row_rozm[value];
							} else {$_SESSION['IMAGE_SIZE_MIN_Y'] = 150;}
						}
						
						//Ustalamy rozmiar miniaturek
						$x_small=$_SESSION['IMAGE_SIZE_MIN_X'];
						$y_small=$_SESSION['IMAGE_SIZE_MIN_Y'];
						//Ustalamy rozmiar zdjec
						$x_big=$_SESSION['IMAGE_SIZE_X'];
						$y_big=$_SESSION['IMAGE_SIZE_Y'];
						
						move_uploaded_file($_FILES[$file]['tmp_name'],$katalog_galerii."/".$row['dirModGallery']."/".$file_name);
	
						zmniejszaj($katalog_galerii."/".$row['dirModGallery']."/".$file_name,$x_big,$y_big,$katalog_galerii."/".$row['dirModGallery']."/full/".$file_name);		
						unlink($katalog_galerii."/".$row['dirModGallery']."/".$file_name);
						//Tu wstawiamy miniaturke
						zmniejszaj($katalog_galerii."/".$row['dirModGallery']."/full/".$file_name,$x_small,$y_small,$katalog_galerii."/".$row['dirModGallery']."/".$file_name);
						
						//zapis do bazy
						$r = $baza->insert("modgalleryphoto","0,'$file_name',$row[idModGallery],'',0,'','NO'",'');
					}
				}
				else{
					echo "<span style=\"color: red\">Niewypełniono pola zdjecia $ii!!!</span><br/>";
				}
			}
		}		

		//ustawienie domyślnego
		if (isset($_GET[set_def])){
			$entry = $_GET[set_def];
			$d = dir($katalog_galerii."/".$row['dirModGallery']);
			while (false !== ($entry = $d->read())) {
				if ($entry!="." and $entry!=".." and $entry!="full"){
					$r = $baza->update("modgalleryphoto","gp_default='NO'","gp_file='$entry'");
				}
			}
			$r = $baza->select("*","modgalleryphoto","gp_galery=".$row[idModGallery]." and gp_file='$_GET[set_def]'");
			if($baza->size_result($r)>0){
				$r = $baza->update("modgalleryphoto","gp_default='YES'","gp_galery=".$row[idModGallery]." and gp_file='$_GET[set_def]'");
			}else{
				$r = $baza->insert("modgalleryphoto","0,'$_GET[set_def]',$row[idModGallery],'',0,'','YES'",'');
			}
		}
		
		//zapis zmian w opisach i datach
		if (isset($_POST[save_photos])){
			$d = dir($katalog_galerii."/".$row['dirModGallery']);
			while (false !== ($entry = $d->read())) {
				if ($entry!="." and $entry!=".." and $entry!="full"){
					$result2 = $baza->select("*","modgalleryphoto","gp_galery=".$row[idModGallery]." and gp_file='$entry'");
					$size_result2 = $baza->size_result($result2);

					$n = substr($entry,0,strpos($entry,'.'));
					$desc = $_POST['opis_'.$n];
					$data = $_POST['data_'.$n];
					$kolejnosc = $_POST['kolej_'.$n];

					if ($size_result2==0){
						$res = $baza->insert("modgalleryphoto","0,'$entry',$row[idModGallery],'$desc',0,'$data',''");
					} else{
						$row2 = $baza->row($result2);
						$res = $baza->update("modgalleryphoto","
							gp_desc='$desc',
							gp_date='$data',
							gp_order='$kolejnosc'
							","gp_id=$row2[gp_id]","");
					}
				}
			}
		}
		
		# Pobranie i wyswietlenie wszystkich zdjec nalezacych do danej galerii #
		?>
		<br />
		<form method="POST" action="" >
		<table border="0" cellpadding="0" cellspacing="0" class="zdjecia" width="80%">
			<?
			$d = dir($katalog_galerii."/".$row['dirModGallery']);
			$licznik = 0;
			while (false !== ($entry = $d->read())) {
				if ($entry!="." and $entry!=".." and $entry!="full"){
					$tab_entry[$licznik][0] = $entry;
					$opis = "";
					$data = "";
					$default = "";
					$result2 = $baza->select("*","modgalleryphoto","gp_galery=".$row[idModGallery]." and gp_file='$entry'");		
					$size_result2 = $baza->size_result($result2);
					$kolejnosc=0;
					if ($size_result2>0){
						$row2 = $baza->row($result2);
						$default = $row2[gp_default];
						$opis = $row2[gp_desc];
						$data = $row2[gp_date];
						$kolejnosc = $row2[gp_order];
						if ($data=='0000-00-00')
							$data = "";
					}
					$tab_entry[$licznik][1] = $opis;
					$tab_entry[$licznik][2] = $data;
					$tab_entry[$licznik][3] = $kolejnosc;
					$tab_entry[$licznik][4] = $default;

					$licznik++;
				}
			}
			$d->close();
			
			$ile_zdj = sizeof($tab_entry);

			for ($i=0; $i<$ile_zdj-1; $i++){
				for ($j=0; $j<$ile_zdj-$i-1; $j++){
					$k = $j+1;
					if (intval($tab_entry[$j][3]) > intval($tab_entry[$k][3])){
						$tmp[0] = $tab_entry[$j][0];
						$tmp[1] = $tab_entry[$j][1];
						$tmp[2] = $tab_entry[$j][2];
						$tmp[3] = $tab_entry[$j][3];
						$tmp[4] = $tab_entry[$j][4];
						$tab_entry[$j][0] = $tab_entry[$k][0];
						$tab_entry[$j][1] = $tab_entry[$k][1];
						$tab_entry[$j][2] = $tab_entry[$k][2];
						$tab_entry[$j][3] = $tab_entry[$k][3];
						$tab_entry[$j][4] = $tab_entry[$k][4];
						$tab_entry[$k][0] = $tmp[0];
						$tab_entry[$k][1] = $tmp[1];
						$tab_entry[$k][2] = $tmp[2];
						$tab_entry[$k][3] = $tmp[3];
						$tab_entry[$k][4] = $tmp[4];
					}
				}
			}
			
			$licznik=0;
			//$ile_zdj = sizeof($tab_entry);

			for ($i=0; $i<$ile_zdj; $i++){
				//echo sizeof($tab_entry);
				$entry = $tab_entry[$i][0];
				$opis = $tab_entry[$i][1];
				$data = $tab_entry[$i][2];
				$kolejnosc = $tab_entry[$i][3];
				$default = $tab_entry[$i][4];
					
				$k = $licznik%$ile_zdjec_szerokosc;
				if ($k==0) {
					echo "<tr>";
				}
				
				$gal = $row[dirModGallery];

				$n = substr($entry,0,strpos($entry,'.'));
				
				echo "<td class=\"zdjecie_min\" style=\"padding: 2px;\" >
					<center>
					<img src=\"$katalog_galerii/$row[dirModGallery]/$entry\"  alt=\"\" />
					<br>						
					<a href=\"$path&zdjecia_gal=$_GET[zdjecia_gal]&usun_zdj=$entry\">usuń</a>";
				if ($default=='NO' or $default==''){
					echo "|
						<a href=\"$path&zdjecia_gal=$_GET[zdjecia_gal]&set_def=$entry\">ustaw domyślne</a>";
				}
				echo "<br>
					<table border=\"0\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">
						<tr>
							<td style=\"text-align: right;\">opis:</td>
							<td width=\"100%\">
								<textarea style=\"width: 98%;\" name=\"opis_$n\" rows=\"1\">$opis</textarea>
							</td>
						</tr>
						<tr>
							<td style=\"text-align: right;\">data:</td>
							<td>
								<input type=\"text\" name=\"data_$n\" style=\"width: 100%;\" onclick=\"showKal(this)\" value=\"$data\" />
							</td>
						</tr>
						<tr>
							<td style=\"text-align: right;\">kolej.:</td>
							<td>
								<input type=\"text\" name=\"kolej_$n\" style=\"width: 100%;\" value=\"$kolejnosc\" />
							</td>
						</tr>
					</table>		
					</center>
					<br />";
				
				?></td><?
				if ($k==$ile_zdjec_szerokosc-1) {
					echo "</tr>";
				}						
				$licznik++;	
			}
			?>				
		</table>
		<input type="submit" name="save_photos" value="Zapisz zmiany" />
		</form>
		<?
	}
	
	?><p><a href="<?=$path?>">Powrót do galerii</a></p><?	
}

//nie edycja - dodawanie i wyswietlanie
if (!isset($_GET['edytuj_gal']) and !isset($_GET['zdjecia_gal'])){
?>
	<form method="POST" action="" name="pytanie">
	<p>Dodaj nową galerię:</p>
	<table border="0" class="tab_edycji">
	<tr>
		<th>Nazwa: </th><td><input type="text" name="nazwa" value="" size="40" style="width: 300px;" /></td>
	</tr>
	<tr>	
		<th>Opis:</th><td>
		<textarea name="opis" cols="60" rows="1" style="width: 300px;"></textarea>					
		</td>
	</tr>
	<tr>
		<th>Autor: </th><td><input type="text" name="autor" value="" style="width: 300px;" /></td>
	</tr>	
	<!--tr>
		<th>Katalog: </th><td>
		<select name="katalog">
		<?
//		$d = dir("../galeria/");
//		$i=0;
//		while (false !== ($entry = $d->read())) {
//			if ($entry!="." and $entry!=".."){
//				$entr[$i]=$entry;
//				$i++;
//			}
//		}
//		$d->close();
//		sort($entr);
//		foreach ($entr as $katalog)
//		    echo "<option>$katalog</option>";
		?>		
		</select>
		<input type="text" name="katalog" value="" style="width: 300px;" />
		</td>
	</tr-->	
	<tr>
		<th>Data utworzenia:</th><td> <input type="text" name="data" value="<?=date("Y-m-d")?>" style="width: 300px;" onclick="showKal(this)" />
		</td>
	</tr>	
	<tr>
		<th>Licznik: </th><td><input type="text" name="licznik" style="width: 300px;" value="0" /></td>
	</tr>	
	<!--tr class="gray1">
		<th>Grupy użytkowników: </th><td><input type="text" name="grupy" value="" readonly /></td>
	</tr-->	
	<tr>
		<th>Grupa: </th><td>
		<select name="grupa" style="width: 300px;" class="select">
			<?
			$result_g = $baza->select("*","modgallerygroup", "", "ORDER BY name");
			if (($ile = $baza->size_result($result_g))>0){
				for ($i=0; $i<$ile; $i++){
					$row_g = $baza->row($result_g);	
					?>
					<option value="<?=$row_g[id]?>"><?=$row_g[name]?></option>
					<?
				}
			}
			?>
		</select>
		</td>
	</tr>	
	<tr>
		<th>Aktywność: </th><td>
		<select name="aktyw" style="width: 300px;" class="select">
			<option value="YES">TAK</option>
			<option value="NO">NIE</option>
		</select>
		</td>
	</tr>	
	</table>	
	<input type="submit" name="dodaj_galerie" value="Dodaj" />
	</form>
	<br />
<?
	$result = $baza->select("idModGallery,activeModGallery,counterModGallery,authorModGallery,dirModGallery,nameModGallery,describeModGallery,createdModGallery,modgallerygroup.name as group_name",$tabela.",modgallerygroup","modgallery.group=modgallerygroup.id","order by createdModGallery DESC","");		
	$size_result = $baza->size_result($result);
	if ($size_result>0){		
		echo "<table border=0 cellpadding=\"0\" cellspacing=\"0\" style=\"width: 98%;\" class=\"tab_edycji\">";
		echo "<tr>
		<th>Nazwa</th>
		<th>Opis</th>
		<th>Katalog</th>
		<th>Data utworzenia</th>
		<th>Autor</th>
		<th>Licznik</th>
		<th>Grupy użytkowników</th>
		<th>Aktyw.</th>
		<th>Operacje</th>
		</tr>";
		for($i=0;$i<$size_result;$i++){
			$row = $baza->row($result);
			$mod = $i%2;
			
			echo "<tr class=\"gray$mod\">
				
				<td><p>$row[nameModGallery]</p></td>
				
				<td><p>".obetnij_tekst($row[describeModGallery],100)."</p></td>
				
				<td><p>$row[dirModGallery]</p></td>
				
				<td><p>$row[createdModGallery]</p></td>
				
				<td><p>$row[authorModGallery]</p></td>
				
				<td><p>$row[counterModGallery]</p></td>
				
				<td><p>$row[group_name]</p></td>
				
				
				<td><p>$row[activeModGallery]</p></td>
				
				<td class=\"opcje\"><p>
				<a href=\"$path&zdjecia_gal=$row[idModGallery]\">Zdjęcia</a> 
				<a href=\"$path&edytuj_gal=$row[idModGallery]\">Edytuj</a> 
				<a href=\"$path&usun_gal=$row[idModGallery]\">Usuń</a> </p>
				</td>
				
				</tr>";
		}
		echo "</table><br />";
	}	
}
}

//zarządzanie na stronach
if (isset($_GET['pages'])){
	$path = "?pages=&p=$_GET[p]&modul=&manage=$_GET[manage]";
	
	//zapisanie wybranego artukułu
	if (isset($_POST['save'])){
		$r_as = $baza->select("*","modgalleryschema","modgalleryschema.schema=$_GET[manage]","","");
		if ($baza->size_result($r_as)==0){	
			$wartsci = "0,".$_POST['grupa'].",".$_GET['manage'].",$_POST[amount]";	
			$r = $baza->insert("modgalleryschema",$wartsci);	
		}
		else{
			$wartsci = "gallery_group=".$_POST['grupa'].", amountThumb=$_POST[amount]";
			$where = "modgalleryschema.schema=$_GET[manage]";
			$r = $baza->update("modgalleryschema",$wartsci,$where,"");
		}
	}

	$r_as = $baza->select("*","modgalleryschema","modgalleryschema.schema=$_GET[manage]","","");
	$row_as = $baza->row($r_as);
	$ile_zdjec_szerokosc = $row_as[amountThumb];
	if ($ile_zdjec_szerokosc=="" or $ile_zdjec_szerokosc==0){
		$ile_zdjec_szerokosc = 2;
	}
		
	$r_as = $baza->select("*","modgalleryschema","modgalleryschema.schema=$_GET[manage]","","");
	$row_as = $baza->row($r_as);
	$ile_as=$baza->size_result($r_as);
	
	//wybór grupy galerii 
	$r_art=$baza->select("*","modgallerygroup","active='YES'","ORDER by name","");
	if (($ile=$baza->size_result($r_art))>0){
		?>
		<form method="POST" action="">
		<h4>Wybierz grupę galerii:
		<select name="grupa" class="select">
		<?
		if ($ile_as==0){
			?>
			<option></option>
			<?
		}
		for ($i=0; $i<$ile; $i++){
			$row_art = $baza->row($r_art);
			if ($row_art['active']=='YES'){				
				?>
				<option <?if ($row_as['gallery_group']==$row_art['id']) echo "selected";?> 
					value="<?=$row_art['id']?>"><?=$row_art['name']?></option>
				<?
			}
		}
		?>
		</select></h4>
		<h4>Ile miniatur na szerokość:
		<input type="text" name="amount" value="<?=$ile_zdjec_szerokosc?>" /></h4>
		<br>
		<input type="submit" name="save" value="Zapisz" />
		</form>
		<br><br>
		<?
	}
}

?>
<div class="galeria"><br />
<center>
<?
?>
</center>

<?

?>