<script language="javascript">
	function selectAll(id){
		var name = 'group_'+id;
		var ch = document.getElementById(name);
		if(ch.checked){
			document.getElementById(name + '_add').checked = true;
			document.getElementById(name + '_edt').checked = true;
			document.getElementById(name + '_del').checked = true;
		} else{
			document.getElementById(name + '_add').checked = false;
			document.getElementById(name + '_edt').checked = false;
			document.getElementById(name + '_del').checked = false;
		}
	}
</script>

<h3>Zarządzanie modułami</h3>
<?
//dodanie modułu
if (isset($_GET['dodaj'])){
	if (!isset($_POST['dodaj'])){
		$modules=null;
		
		//katalogi modułów
		if ($dir = @opendir("../modules/")) {
			$ile_modulow = 0;
			while (($file = readdir($dir)) !== false) {
				if ($file!="" and $file!="." and $file!=".."){
					if (file_exists("../modules/".$file."/index.php")){
						$r = $baza->select("1","cmsmodule","folder='".$file."'");
						if ($baza->size_result($r)==0)
							$modules[$ile_modulow++] = $file;
					}
				}
			}  
			closedir($dir);
		}

		if (sizeof($modules)>0){
		?>
			<form method="POST" id="f" action="">
			<table border="0" cellpadding="0" cellspacing="0" class="tab_edycji">
				<tr>
					<th>Nazwa modułu:</th>
					<td><input type="text" name="name" size="30" /></td>
				</tr>
				<tr>
					<th>Katalog:</th>
					<td>
						<select name="dir">
						<?
						for ($i=0; $i<sizeof($modules); $i++){	
						?>	
							<option><?=$modules[$i];?></option>
						<?
						}
						?>
						</select>
					</td>
				</tr>
				<!--tr>
					<th>Grafika:</th>
					<td><input type="radio" name="graphic" value="YES" checked /> YES 
						<input type="radio" name="graphic" value="NO" /> NO 
					</td>
				</tr>
				<tr>
					<th>Czy wyswietlić nazwę:</th>
					<td><input type="radio" name="title" value="YES" checked /> YES 
						<input type="radio" name="title" value="NO" /> NO 
					</td>
				</tr-->
				<tr>
					<th>Aktywność:</th>
					<td><input type="radio" name="active" value="YES" checked /> TAK 
						<input type="radio" name="active" value="NO" /> NIE 
					</td>
				</tr>
				<!--tr>
					<th>Stronicowanie:</th>
					<td><input type="radio" name="paging" value="YES"  /> TAK 
						<input type="radio" name="paging" value="NO" checked /> NIE 
					</td>
				</tr>
				<!--tr>
					<th>Strona akcji:</th>
					<td>
					<select name="path_action">
						<option></option>
						<?
						$e = explode(".",$row[pathAction]);
						$wybrana = $e[0];
						$res2 = $baza->select("*","cmspage","active='YES'","ORDER BY htmlName");
						if(($ile2 = $baza->size_result($res2))>0){
							for ($j=0;$j<$ile2; $j++){
								$row2 = $baza->row($res2);
								?>
								<option <?if ($row2[htmlName]==$wybrana) echo "selected";?>><?=$row2[htmlName]?>.html</option>
								<?
							}
						}
						?>
					</select>
					</td>
				</tr-->
				<tr>
					<th>Zarządzanie w modułach:</th>
					<td><input type="radio" name="adm_man" value="YES" checked /> TAK 
						<input type="radio" name="adm_man" value="NO" /> NIE 
					</td>
				</tr>
				<tr>
					<th>Zarządzanie w stronach:</th>
					<td><input type="radio" name="site_man" value="YES" checked /> TAK 
						<input type="radio" name="site_man" value="NO" /> NIE 
					</td>
				</tr>
			</table>
			<p><input type="submit" name="dodaj" value="Dodaj" /></p>
			</form>
		<?
		}
		else{
			echo "<p>Obecnie nie ma żadnych nowych modułów - wszystkie moduły zapisane są w bazie danych.</p>";
		}
	}
	else{
		if (strlen(trim($_POST['name']))>0){
			$res = $baza->select("1","cmsmodule","name='".$_POST['name']."'");
			if ($baza->size_result($res)==0){
				$kolumny = "(idModule,name,folder,graphic,title,active,siteManage,admManage,pathAction)";
				$values = "0, '".htmlspecialchars($_POST['name'])."',
				 		'".htmlspecialchars($_POST['dir'])."',
				  		'".htmlspecialchars($_POST['graphic'])."',
				  		'".htmlspecialchars($_POST['title'])."',
				  		'".htmlspecialchars($_POST['active'])."',
				  		'".htmlspecialchars($_POST['site_man'])."',
				  		'".htmlspecialchars($_POST['adm_man'])."',
				  		''";//'".htmlspecialchars($_POST['path_action'])."',
				$res = $baza->insert("cmsmodule",$values,$kolumny);
				if ($res)
					echo "<p>Nowy moduł został zapisany w bazie.</p>";
			}
			else{
				echo "<p>Istnieje już moduł o podanej nazwie!</p>";	
			}
		}
		else{
			echo "<p>Nie podano nazwy moduły!</p>";
		}
	}
	echo "<p><a href=\"?admin=&admin_module=\">Powrót</a></p>";
}

//usuwanie modułu
if (isset($_GET['usun'])){
	if (!isset($_POST[usun])) {			
		echo "<p>Czy na pewno usunąć ten moduł?</p>";
		echo "<form name=\"usun_form\" method=\"post\">";
		echo "<input type=\"submit\" name=\"usun\" value=\"Usuń\" />";
		echo "</form>";					
	}
	else{			
	   	$res=$baza->delete("cmsmodule","idModule=".$_GET['usun']);
   		if ($res) {
	   		echo "<p>Moduł został usunięty</p>"; 		
	   		$res2 = $baza->delete("cmsschemamodule","module=".$_GET['usun']);
   		}
   		else 
   			echo "<p class=\"error\">Błąd zapisu w bazie</p>"; 			
	}
	echo "<p><a href=\"?admin=&admin_module=\">Powrót</a></p>";	
}

//prawa do modułu
if (isset($_GET['prawa'])){
	if (!isset($_POST['prawa'])){
		$res = $baza->select("*","cmsadmgroupuser","activeadmgroupuser='YES'","","");
		if(($ile_grup = $baza->size_result($res))>0){
		?>
		<p>Określ grupy użytkowników mających dostęp do modułu:</p>
		<center>
		<form method="POST" action="">
		<table border="0" cellpadding="1" cellspacing="1">
			<tr>
				<td></td>
				<th></th>
				<th>dodawanie</th>
				<th>&nbsp;&nbsp;edycja&nbsp;&nbsp;</th>
				<th>usuwanie</th>
			</tr>
			<?
			for ($j=0;$j<$ile_grup; $j++){
				$row = $baza->row($res);
				$res_mod = $baza->select("*","cmsmodulerule","module=$_GET[prawa] and usergroup=$row[idadmgroupuser]","","");
				$row_mod = $baza->row($res_mod);
			?>
			<tr>
				<td style="text-align: center;">
					<input type="checkbox" name="group_<?=$row[idadmgroupuser]?>"  id="group_<?=$row[idadmgroupuser]?>" 
						onclick="selectAll('<?=$row[idadmgroupuser]?>')"
					<?
					if($baza->size_result($res_mod)>0){
						echo " checked";
					}
					?> />
				</td><td class="left">
					<?=$row[nameadmgroupuser]?>
				</td>
				<td style="text-align: center;">
					<input type="checkbox" name="group_<?=$row[idadmgroupuser]?>_add"  id="group_<?=$row[idadmgroupuser]?>_add" 
					<?
					if($baza->size_result($res_mod)>0 and $row_mod[add_row]=="on"){
						echo " checked";
					}
					?> />
				</td>
				<td style="text-align: center;">
					<input type="checkbox" name="group_<?=$row[idadmgroupuser]?>_edt"  id="group_<?=$row[idadmgroupuser]?>_edt" 
					<?
					if($baza->size_result($res_mod)>0 and $row_mod[edt_row]=="on"){
						echo " checked";
					}
					?> />
				</td>
				<td style="text-align: center;">
					<input type="checkbox" name="group_<?=$row[idadmgroupuser]?>_del"  id="group_<?=$row[idadmgroupuser]?>_del" 
					<?
					if($baza->size_result($res_mod)>0 and $row_mod[del_row]=="on"){
						echo " checked";
					}
					?> />
				</td>
			</tr>
			<?}?>
		</table>
		<br>
		<input type="submit" name="prawa" value="Zapisz zmiany" />
		</form>
		</center>
		<?
		}
		else{
			?>
			<p>Nie ma zdefiniowanych grup użytkowników!</p>
			<?
		}
	}
	else{
		//usunięcie spisów z danego modułu i grupy
		$res_mod = $baza->delete("cmsmodulerule","module=$_GET[prawa]","");
		
		$res = $baza->select("*","cmsadmgroupuser","activeadmgroupuser='YES'","");
		if(($ile_grup = $baza->size_result($res))>0){
			for ($j=0;$j<$ile_grup; $j++){
				$row = $baza->row($res);
				if (isset($_POST["group_".$row[idadmgroupuser]])){
					$add = $_POST["group_".$row[idadmgroupuser]."_add"];
					$edt = $_POST["group_".$row[idadmgroupuser]."_edt"];
					$del = $_POST["group_".$row[idadmgroupuser]."_del"];
					$res_mod = $baza->insert("cmsmodulerule","0,$_GET[prawa],$row[idadmgroupuser],'$add','$edt','$del'","");
				}
			}
		}
		?>
		<p>Zmiany poprawnie zapisane!</p>
		<?
	}
	echo "<p><a href=\"?admin=&admin_module=\">Powrót</a></p>";	
}

//templaty modułu
if (isset($_GET['template_mod'])){
	$res = $baza->select("*","cmsmodule","idModule=".$_GET['template_mod'],"");
	$row = $baza->row($res);
	
	//html template modułu
	if (isset($_GET['html_temp'])){
		$res = $baza->select("*","cmsmoduletemplate","id=".$_GET['html_temp'],"","");
		$row_f = $baza->row($res);
		$file_path = "../modules/".$row[folder]."/template/".$row_f['file'];
		if (isset($_POST[zapisz_html])){
			$html = str_replace('\"','"',$_POST[html]);
			$html = str_replace("\'","'",$html);
			
			$fd = fopen($file_path,'w');
			fwrite($fd,$html);
			fclose($fd);		
			
			?>Dane zapisane<?
		}
		else{
			//echo $file_path;
			if (filesize($file_path)>0){
				$fd = fopen($file_path,'r');
				$cont = fread($fd,filesize($file_path));
				fclose($fd);
			}
			
			?>
			<form method="POST" action="">
				<textarea name="html" style="width: 90%; height: 400px; background: #ddd;"><?=$cont?></textarea>
				<br />
				<input type="submit" name="zapisz_html" value="Zapisz szablon" />
			</form>
			<?
		}
		?><p><a href="?admin=&admin_module=&template_mod=<?=$_GET['template_mod']?>">Powrót do szablonów modułu</a></p><?	
	}
	
	//dodawania template z pliku
	if (isset($_GET['dodaj_plik'])){
		if (!isset($_POST['dodaj'])){
			$template=null;
			
			//katalogi templateów
			$katalog = "../modules/".$row[folder]."/template/";
			if ($dir = @opendir($katalog)) {
				$ile_templatow = 0;
				while (($file = readdir($dir)) !== false) {
					if ($file!="" and $file!="." and $file!=".." and $file!="index.php" and is_file($katalog.$file)){
						$r = $baza->select("1","cmsmoduletemplate","file='".$file."' and module=$_GET[template_mod]");
						if ($baza->size_result($r)==0)
							$template[$ile_templatow++] = $file;
					}
				}  
				closedir($dir);
			}
			if (sizeof($template)>0){
			?>
				<form method="POST" id="f" action="">
				<table border="0" cellpadding="0" cellspacing="0" class="tab_edycji">
					<tr>
						<th>Nazwa template:</th>
						<td><input type="text" name="name" size="30" class="input_add" /></td>
						<td class="input_opis">
							(unikalna nazwa template)
						</td>
					</tr>
					<tr>
						<th>Plik:</th>
						<td>
							<select name="file">
							<?
							for ($i=0; $i<sizeof($template); $i++){	
							?>	
								<option><?=$template[$i];?></option>
							<?
							}
							?>
							</select>
						</td>
						<td class="input_opis">
							tylko niedodane do bazy
						</td>
					</tr>
					<tr>
						<th>Domyślny:</th>
						<td>
							<select name="default" class="input_add">
								<option value="YES">TAK</option>
								<option value="NO" selected>NIE</option>
							</select>
						</td>
						<td class="input_opis">
							(TAK - domyślny)
						</td>
					</tr>
					<tr>
						<th>Aktywność:</th>
						<td>
							<select name="active" class="input_add">
								<option value="YES" selected>TAK</option>
								<option value="NO">NIE</option>
							</select>
						</td>
						<td class="input_opis">
							(TAK - aktywny)
						</td>
					</tr>
				</table>
				<p><input type="submit" name="dodaj" value="Dodaj" /></p>
				</form>
			<?
			}
			else{
				?>
				<p>W katalogu template nie ma niedodanych plików do bazy!</p>
				<?
			}
		}
		else{
			if (strlen(trim($_POST['name']))>0){
				$res = $baza->select("1","cmsmoduletemplate","name='".$_POST['name']."' and module=$_GET[template_mod]");
				if ($baza->size_result($res)==0){
//					$kolumny = "(id,module,name,file,description,default, active)";
					$values = "0, $_GET[template_mod],'".htmlspecialchars($_POST['name'])."',
					 		'".htmlspecialchars($_POST['file'])."',
					 		'".htmlspecialchars($_POST['description'])."',
					 		'".htmlspecialchars($_POST['default'])."',
					  		'".htmlspecialchars($_POST['active'])."'";
					$res = $baza->insert("cmsmoduletemplate",$values,'','');
					if ($res)
						echo "<p>Nowy szablon został zapisany w bazie.</p>";
				}
				else{
					echo "<p>Istnieje już szablon o podanej nazwie!</p>";	
				}
			}
			else{
				echo "<p>Nie podano nazwy szablonu!</p>";
			}
		}
	
		?><p><a href="?admin=&admin_module=&template_mod=<?=$_GET['template_mod']?>">Powrót do szablonów modułu</a></p><?	
	}

	//dodawanie nowego pliku
	if (isset($_GET['dodaj_new'])){
		if (!isset($_POST['dodaj'])){
			?>
				<form method="POST" id="f" action="">
				<table border="0" cellpadding="0" cellspacing="0" class="tab_edycji">
					<tr>
						<th>Nazwa template:</th>
						<td><input type="text" name="name" size="30" class="input_add" /></td>
						<td class="input_opis">
							(unikalna nazwa template)
						</td>
					</tr>
					<tr>
						<th>Plik:</th>
						<td><input type="text" name="file" size="30" class="input_add" /></td>
						<td class="input_opis">
							(bez spacji i znaków specjalnych)
						</td>
					</tr>
					<tr>
						<th>Kopiuj z:</th>
						<td>
							<select name="kopia" class="input_add">
								<option value="0"></option>
								<?
								$res = $baza->select("*","cmsmoduletemplate","module=$_GET[template_mod]","");
								if (($ile = $baza->size_result($res))>0){
									for ($i=0; $i<$ile; $i++){
										$row = $baza->row($res);
										?>
										<option value="<?=$row['id']?>"><?=$row['name']?></option>
										<?
									}
								}
								?>							
							</select>
						</td>
						<td class="input_opis">
							(puste pole - tworzenie nowego template)
						</td>
					</tr>				
					<tr>
						<th>Domyślny:</th>
						<td>
							<select name="default" class="input_add">
								<option value="YES">TAK</option>
								<option value="NO" selected>NIE</option>
							</select>
						</td>
						<td class="input_opis">
							(TAK - domyślny)
						</td>
					</tr>
					<tr>
						<th>Aktywność:</th>
						<td>
							<select name="active" class="input_add">
								<option value="YES" selected>TAK</option>
								<option value="NO">NIE</option>
							</select>
						</td>
						<td class="input_opis">
							(TAK - aktywny)
						</td>
					</tr>
				</table>
				<p><input type="submit" name="dodaj" value="Dodaj" /></p>
				</form>
			<?
		}
		else{
			if (strlen(trim($_POST['name']))>0 and strlen(trim($_POST['file']))>0){
				$file_path = "../modules/".$row[folder]."/template/".$_POST['file'];
				if (!file_exists($file_path)){
					$res = $baza->select("1","cmsmoduletemplate","name='".$_POST['name']."'");
					if ($baza->size_result($res)==0){
						$values = "0, $_GET[template_mod],'".htmlspecialchars($_POST['name'])."',
						 		'".htmlspecialchars($_POST['file'])."',
						 		'".htmlspecialchars($_POST['description'])."',
						 		'".htmlspecialchars($_POST['default'])."',
						  		'".htmlspecialchars($_POST['active'])."'";
						$res = $baza->insert("cmsmoduletemplate",$values,"");
						
						//tworzenie pliku template
						if ($_POST[kopia]==0){
							$fp = fopen($file_path,'w');
							fclose($fp);
						} 
						else {
							//kopiowanie z istniejącego
							$res = $baza->select("*","cmsmoduletemplate","id='".$_POST['kopia']."'");
							$row2 = $baza->row($res);
							$file_org = "../modules/".$row[folder]."/template/".$row2['file'];
							$fd = fopen($file_org,'r');
							$cont = fread($fd,filesize($file_org));
							fclose($fd);
	
							$fp = fopen($file_path,'w');
							fwrite($fp, $cont);
							fclose($fp);
						}
						
						if ($res)
							echo "<p>Nowy szablon został zapisany w bazie.</p>";
					}
					else{
						echo "<p>Istnieje już szablon o podanej nazwie!</p>";	
					}
				}
				else{
					echo "<p>Istnieje już plik o podanej nazwie!</p>";					
				}
			}
			else{
				echo "<p>Nie podano nazwy szablonu!</p>";
			}
		}
		?><p><a href="?admin=&admin_module=&template_mod=<?=$_GET['template_mod']?>">Powrót do szablonów modułu</a></p><?	
	}

	//edycja szablonu
	if (isset($_GET['edytuj_temp'])){
		if (!isset($_POST['edytuj'])){
			$res = $baza->select("*","cmsmoduletemplate","id=".$_GET['edytuj_temp'],"","");
			$row = $baza->row($res);
	
			?>
			<form method="POST" id="f" action="">
			<table border="0" cellpadding="0" cellspacing="0" class="tab_edycji">
				<tr>
					<th>Nazwa template:</th>
					<td><input type="text" name="name" size="30" value="<?=$row[name]?>" /></td>
				</tr>
				<tr>
					<th>Plik:</th>
					<td><?=$row[file]?><input type="hidden" name="file" size="30" value="<?=$row[file]?>" /></td>
				</tr>
				<tr>
					<th>Domyślny:</th>
					<td>
						<select name="default" class="input_add">
							<option <?if ($row['default']=='YES') echo "selected";?> value="YES">TAK</option>
							<option <?if ($row['default']=='NO') echo "selected";?> value="NO">NIE</option>
						</select>
					</td>
				</tr>
				<tr>
					<th>Aktywność:</th>
					<td>
						<select name="active" class="input_add">
							<option <?if ($row[active]=='YES') echo "selected";?> value="YES">TAK</option>
							<option <?if ($row[active]=='NO') echo "selected";?> value="NO">NIE</option>
						</select>
					</td>
				</tr>
			</table>
			<p><input type="submit" name="edytuj" value="Zachowaj zmiany" /></p>
			</form>
			<?
		}
		else{
			if (strlen(trim($_POST['name']))>0 and strlen(trim($_POST['file']))>0){
				$file_path = "../modules/".$row[folder]."/template/".$_POST['file'];
				if (file_exists($file_path)){
					$values = "	name='".htmlspecialchars($_POST['name'])."',
					  			cmsmoduletemplate.default='".$_POST['default']."',
					  			active='".$_POST['active']."'";
					$where = "id=".$_GET['edytuj_temp'];
					$res = $baza->update("cmsmoduletemplate",$values,$where,"");
					if ($res)
						echo "<p>Zmiany zostały zapisane w bazie.</p>";
				}
				else{
					echo "<p>Plik nie istnieje.</p>";				
				}
			}
			else{
				echo "<p>Nie podano nazwy szablonu!</p>";
			}
		}
		?><p><a href="?admin=&admin_module=&template_mod=<?=$_GET['template_mod']?>">Powrót do szablonów modułu</a></p><?	
	}
	
	//usuwanie szablonu modułu
	if (isset($_GET['usun_temp'])){
		if (!isset($_POST[usun])) {			
			echo "<p>Czy na pewno usunąć ten template?<br>Czynność ta powoduje usunięcie z bazy danych, plik szablonu pozostanie na serwerze (aby go usunąć skontaktuj się z administratorem lub usuń go ręcznie z serwera).</p>";
			echo "<form name=\"usun_form\" method=\"post\">";
			echo "<input type=\"submit\" name=\"usun\" value=\"Usuń\" />";
			echo "</form>";					
		}
		else{			
		   	$res=$baza->delete("cmsmoduletemplate","id=".$_GET['usun_temp']);
	   		if ($res) {
		   		echo "<p>template został usunięty</p>"; 		
	   		}
	   		else 
	   			echo "<p class=\"error\">Błąd zapisu w bazie</p>"; 			
		}
		?><p><a href="?admin=&admin_module=&template_mod=<?=$_GET['template_mod']?>">Powrót do szablonów modułu</a></p><?	
	}

	
	//przeglądanie rekordów
	if (!isset($_GET['edytuj_temp']) and !isset($_GET['dodaj_plik']) and !isset($_GET['dodaj_new']) and !isset($_GET['html_temp']) and !isset($_GET['usun_temp'])){
		if (check_rules($baza,'admMod','insert')){
			?><p><a href="?admin=&admin_module=&template_mod=<?=$_GET['template_mod']?>&dodaj_new=" >Dodaj nowy template</a> | 
			<a href="?admin=&admin_module=&template_mod=<?=$_GET['template_mod']?>&dodaj_plik=" >Dodaj z pliku</a></p><?
		}
		
		$res = $baza->select("*","cmsmoduletemplate","module=$_GET[template_mod]","");
		if (($ile = $baza->size_result($res))>0){
			?>
			<center>
			<table border="0" cellpadding="1" cellspacing="1" class="tab_edycji">
				<tr>
					<th>LP</th>
					<th>Nazwa szablonu</th>
					<th>Nazwa pliku</th>
					<th>Domyślny</th>
					<th>Aktywność</th>
					<th>Opcje</th>
				</tr>
			<?
			for ($i=0; $i<$ile; $i++){
				$row = $baza->row($res);
				$licznik++;
				$mod = $i%2;
				?>
				<tr class="gray<?=$mod;?>">
					<td><?=$licznik?></td>
					<td><?=$row[name]?></td>
					<td><?=$row[file]?></td>
					<td><?if($row['default']=='YES')echo "TAK"; else echo "NIE";?></td>
					<td><?if($row[active]=='YES')echo "TAK"; else echo "NIE";?></td>
					<td>
						<a href="?admin=&admin_module=&template_mod=<?=$_GET['template_mod']?>&edytuj_temp=<?=$row[id]?>">Edytuj</a>
						<a href="?admin=&admin_module=&template_mod=<?=$_GET['template_mod']?>&html_temp=<?=$row[id]?>">HTML</a>
						<a href="?admin=&admin_module=&template_mod=<?=$_GET['template_mod']?>&usun_temp=<?=$row[id]?>">Usuń</a>
					</td>
				</tr>
				<?
			}
			?>
			</table>
			</center>
			<?		
		}
		else {
			?>
			<p>Moduł nie posiada szablonów!</p>
			<?
		}
	}
	?><p><a href="?admin=&admin_module=">Powrót</a></p><?	
}

//edytowanie modułu
if (isset($_GET['edytuj'])){
	if (!isset($_POST['edytuj'])){
		$res = $baza->select("*","cmsmodule","idModule=".$_GET['edytuj'],"");
		$row = $baza->row($res);
		
		$modules=null;
		
		//katalogi modułów
		if ($dir = @opendir("../modules/")) {
			$ile_modulow = 0;
			while (($file = readdir($dir)) !== false) {
				if ($file!="" and $file!="." and $file!=".."){
					if (file_exists("../modules/".$file."/index.php")){
						$modules[$ile_modulow++] = $file;
					}
				}
			}  
			closedir($dir);
		}

		?>
		<form method="POST" id="f" action="">
		<table border="0" cellpadding="0" cellspacing="0" class="tab_edycji">
			<tr>
				<th>Nazwa modułu:</th>
				<td><input type="text" name="name" size="30" value="<?=$row[name]?>" /></td>
			</tr>
			<tr>
				<th>Katalog:</th>
				<td>
					<select name="dir">
					<?
					for ($i=0; $i<sizeof($modules); $i++){	
						if ($modules[$i]==$row[folder]) $selected="selected"; else $selected="";
					?>	
						<option <?=$selected;?>><?=$modules[$i];?></option>
					<?
					}
					?>
					</select>
				</td>
			</tr>
			<!--tr>
				<th>Grafika:</th>
				<td><input type="radio" name="graphic" value="YES" <?if ($row[graphic]=='YES') echo "checked";?> /> YES 
					<input type="radio" name="graphic" value="NO" <?if ($row[graphic]=='NO') echo "checked";?> /> NO 
				</td>
			</tr>
			<tr>
				<th>Czy wyswietlić nazwę:</th>
				<td><input type="radio" name="title" value="YES" <?if ($row[title]=='YES') echo "checked";?> /> YES 
					<input type="radio" name="title" value="NO" <?if ($row[title]=='NO') echo "checked";?> /> NO 
				</td>
			</tr-->
			<tr>
				<th>Aktywność:</th>
				<td><input type="radio" name="active" value="YES" <?if ($row[active]=='YES') echo "checked";?> /> TAK 
					<input type="radio" name="active" value="NO" <?if ($row[active]=='NO') echo "checked";?> /> NIE 
				</td>
			</tr>
			<!--tr>
				<th>Stronicowanie:</th>
				<td><input type="radio" name="paging" value="YES" <?if ($row[paging]=='YES') echo "checked";?> /> TAK 
					<input type="radio" name="paging" value="NO" <?if ($row[paging]=='NO') echo "checked";?> /> NIE 
				</td>
			</tr>
			<tr>
				<th>Strona akcji:</th>
				<td>
				<select name="path_action">
					<option></option>
					<?
					$e = explode(".",$row[pathAction]);
					$wybrana = $e[0];
					$res2 = $baza->select("*","cmspage","active='YES'","ORDER BY htmlName");
					if(($ile2 = $baza->size_result($res2))>0){
						for ($j=0;$j<$ile2; $j++){
							$row2 = $baza->row($res2);
							?>
							<option <?if ($row2[htmlName]==$wybrana) echo "selected";?>><?=$row2[htmlName]?>.html</option>
							<?
						}
					}
					?>
				</select>
				</td>
			</tr-->
			<tr>
				<th>Zarządzanie w modułach:</th>
				<td><input type="radio" name="adm_man" value="YES" <?if ($row[admManage]=='YES') echo "checked";?> /> TAK 
					<input type="radio" name="adm_man" value="NO" <?if ($row[admManage]=='NO') echo "checked";?> /> NIE 
				</td>
			</tr>
			<tr>
				<th>Zarządzanie w stronach:</th>
				<td><input type="radio" name="site_man" value="YES" <?if ($row[siteManage]=='YES') echo "checked";?> /> TAK 
					<input type="radio" name="site_man" value="NO" <?if ($row[siteManage]=='NO') echo "checked";?> /> NIE 
				</td>
			</tr>
		</table>
		<p><input type="submit" name="edytuj" value="Zachowaj zmiany" /></p>
		</form>
		<?
	}
	else{
		if (strlen(trim($_POST['name']))>0){
			$values = "	name='".htmlspecialchars($_POST['name'])."',
			 			folder='".$_POST['dir']."',
			  			graphic='".$_POST['graphic']."',
			  			title='".$_POST['title']."',		  			
			  			admManage='".$_POST['adm_man']."',
			  			siteManage='".$_POST['site_man']."',
			  			active='".$_POST['active']."'";//pathAction='".$_POST['path_action']."',
			$where = "idModule=".$_GET['edytuj'];
			$res = $baza->update("cmsmodule",$values,$where);
			if ($res)
				echo "<p>Zmiany zostały zapisane w bazie.</p>";
		}
		else{
			echo "<p>Nie podano nazwy moduły!</p>";
		}
	}
	echo "<p><a href=\"?admin=&admin_module=\">Powrót</a></p>";	
}


//edytowanie modułu
if (isset($_GET['param'])){
	if (isset($_POST['param_add'])){
		if (strlen($_POST[param_name])>0 and strlen($_POST[param_code])>0){
			$baza->insert("cmsparam","0,$_GET[param],'$_POST[param_code]','$_POST[param_name]','$_POST[param_desc]','$_POST[param_type]'");
		}else{
			?>
			<p>Pola Kod i Nazwa są wymagane!</p>
			<?
		}
	}
	
	if (isset($_POST['param_edt'])){
		if (strlen($_POST[param_name])>0 and strlen($_POST[param_code])>0){
			$baza->update("cmsparam","code='$_POST[param_code]',name='$_POST[param_name]',description='$_POST[param_desc]',type='$_POST[param_type]'","id=$_GET[edit_param]");
		}else{
			?>
			<p>Pola Kod i Nazwa są wymagane!</p>
			<?
		}
		?><p><a href="?admin=&admin_module=&param=<?=$_GET[param]?>">Powrót do listy parametrów</a></p><?
	}

	//formularz dodawania parametru
	if (!isset($_GET[edit_param]) and !isset($_GET[values]) and !isset($_GET[value]) and !isset($_GET[value_yesno])){
		?>
		<center>
		<form method="POST" action="">
		<table border="0" cellpadding="0" cellspacing="0">
			<tr>
				<th>Kod parametru</th>
				<th>Nazwa parametru</th>
				<th>Opis</th>
				<th>Typ</th>
			</tr>
			<tr>
				<td><input type="text" name="param_code" value="" /></td>
				<td><input type="text" name="param_name" value="" /></td>
				<td><input type="text" name="param_desc" value="" style="width: 300px" /></td>
				<td>
					<select name="param_type">
						<option value="SINGLE">SINGLE</option>
						<option value="LIST">LIST</option>
						<option value="YES/NO">YES/NO</option>
					</select>
				</td>
			</tr>
		</table>
		<input type="submit" name="param_add" value="Dodaj parametr" />
		</form>
		</center>
		
		<br>
		<h3>Lista parametrów modułu:</h3>
		<?
		$res = $baza->select("*","cmsparam","id_modul=$_GET[param]","ORDER BY name");
		if (($ile = $baza->size_result($res))>0){
			?>
			<center>
			<table border="0" cellpadding="1" cellspacing="1">
				<tr>
					<th>Kod</th>
					<th>Nazwa</th>
					<th>Opis</th>
					<th>Typ</th>
					<th></th>
				</tr>
				<?
				for ($i=0; $i<$ile; $i++){
					$row = $baza->row($res);
					$mod = $i%2;
					?>
					<tr class="gray<?=$mod;?>">
						<td><?=$row[code]?></td>
						<td><?=$row[name]?></td>
						<td><?=$row[description]?></td>
						<td><?=$row[type]?></td>
						<td>
							<a href="?admin=&admin_module=&param=<?=$_GET[param]?>&edit_param=<?=$row[id]?>">edytuj</a>
						<?
						if ($row[type]=='LIST'){
							?>
							<a href="?admin=&admin_module=&param=<?=$_GET[param]?>&values=<?=$row[id]?>">lista wartości</a>
							<?
						} elseif ($row[type]=='SINGLE'){
							?>
							<a href="?admin=&admin_module=&param=<?=$_GET[param]?>&value=<?=$row[id]?>">wartość domyślna</a>
							<?
						} else{
							?>
							<a href="?admin=&admin_module=&param=<?=$_GET[param]?>&value_yesno=<?=$row[id]?>">wartość domyślna</a>
							<?
						}
						?>
						</td>
					</tr>
					<?
				}
				?>
			</table>
			</center>
			<?
		}else{
			?>
			<p>Brak zdefiniowanych parametrów!</p>
			<?
		}
	} 
	
	
	// usuwanie parametru
	if (isset($_GET['param_del'])){
		
	}
	
	//definiowanie listy wartości
	if (isset($_GET[values])){
		if (isset($_POST[add_value])){
			$baza->insert("cmsparamvalue","0,$_GET[values],'$_POST[val]','$_POST[def]'");
		}
		
		//dodawnie wartości
		?>
		<form method="POST" action="">
			Wartość: <input type="text" name="val" /> 
			Domyślny: <select name="def">
			<option>NO</option>
			<option>YES</option>
			</select>
			<br>
			<input type="submit" name="add_value" value="Dodaj wartość" />
		</form>
		<?
		
		$r = $baza->select("*","cmsparamvalue","id_param=$_GET[values]");
		$ile = $baza->size_result($r);
		if ($ile>0){
			?>
			<center>
			<h3>Lista wartości:</h3>
			<table border="0" cellpadding="2" cellspacing="2">
				<tr>
					<th>wartość</th>
					<th>domyślna</th>
				</tr>
			<?
			for ($i=0; $i<$ile; $i++){	
				$row = $baza->row($r);
				$mod = $i%2;
				?>
				<tr class="gray<?=$mod?>">
					<td><?=$row[value]?></td>
					<td><?=$row['default']?></td>
				</tr>
				<?
			}
			?>
			</table>
			</center>
			<?
		}
		?><p><a href="?admin=&admin_module=&param=<?=$_GET[param]?>">Powrót do listy parametrów</a></p><?
	}
	
	//wartość domyślna
	if (isset($_GET[value])){
		if (isset($_POST[save_param])){
			$r = $baza->select("*","cmsparamvalue","id_param=$_GET[value_yesno]");
			$ile = $baza->size_result($r);
			
			if ($ile==0){
				$baza->insert("cmsparamvalue","0,$_GET[value],'$_POST[val]','YES'");
			} else{
				$row = $baza->row($r);
				$baza->update("cmsparamvalue","value='$_POST[val]'","id=$row[id]");
			}
		}
		
		$r = $baza->select("*","cmsparamvalue","id_param=$_GET[value_yesno]");
		$ile = $baza->size_result($r);
		$row = $baza->row($r);
		?>
		<form method="POST" action="">
			Wartość domyślna parametru: 
			<input type="text" name="val" value="<?=$row['value']?>" />
			<br>
			<input type="submit" name="save_param" value="Zapisz" />
		</form>
		<?
		?><p><a href="?admin=&admin_module=&param=<?=$_GET[param]?>">Powrót do listy parametrów</a></p><?
	}
	
	//wartośc domyślna dla pola YesNo
	if (isset($_GET[value_yesno])){
		if (isset($_POST[save_param])){
			$r = $baza->select("*","cmsparamvalue","id_param=$_GET[value_yesno]");
			$ile = $baza->size_result($r);
			
			if ($ile==0){
				$baza->insert("cmsparamvalue","0,$_GET[value_yesno],'$_POST[yesno]','YES'");
			} else{
				$row = $baza->row($r);
				$baza->update("cmsparamvalue","value='$_POST[yesno]'","id=$row[id]");
			}
		}
		
		$r = $baza->select("*","cmsparamvalue","id_param=$_GET[value_yesno]");
		$ile = $baza->size_result($r);
		$row = $baza->row($r);
		?>
		<form method="POST" action="">
			Wartość domyślna parametru: 
			<select name="yesno">
				<option value="YES" <?if ($row['value']=='YES') echo "selected";?>>YES</option>
				<option value="NO" <?if ($row['value']=='NO') echo "selected";?>>NO</option>
			</select>
			<br>
			<input type="submit" name="save_param" value="Zapisz" />
		</form>
		<?
		?><p><a href="?admin=&admin_module=&param=<?=$_GET[param]?>">Powrót do listy parametrów</a></p><?
	}

	//edycja parametru
	if (isset($_GET[edit_param])){
		$res = $baza->select("*","cmsparam","id=$_GET[edit_param]","");
		$row = $baza->row($res);
		?>
				
		<center>
		<form method="POST" action="">
		<table border="0" cellpadding="0" cellspacing="0">
			<tr>
				<th>Kod parametru</th>
				<th>Nazwa parametru</th>
				<th>Opis</th>
				<th>Typ</th>
			</tr>
			<tr>
				<td><input type="text" name="param_code" value="<?=$row[code]?>" /></td>
				<td><input type="text" name="param_name" value="<?=$row[name]?>" /></td>
				<td><input type="text" name="param_desc" value="<?=$row[description]?>" style="width: 300px" /></td>
				<td>
					<select name="param_type">
						<option value="SINGLE" <?if ($row[type]=='SINGLE') echo "selected";?>>SINGLE</option>
						<option value="LIST" <?if ($row[type]=='LIST') echo "selected";?>>LIST</option>
						<option value="YES/NO" <?if ($row[type]=='YES/NO') echo "selected";?>>YES/NO</option>
					</select>
				</td>
			</tr>
		</table>
		<input type="submit" name="param_edt" value="Zapisz parametr" />
		</form>
		</center>
		<?
		?><p><a href="?admin=&admin_module=&param=<?=$_GET[param]?>">Powrót do listy parametrów</a></p><?
	}

	echo "<p><a href=\"?admin=&admin_module=\">Powrót</a></p>";	
}


//wyświetlenie rekordów
if (!isset($_GET['dodaj']) and !isset($_GET['usun']) and !isset($_GET['edytuj'])
 and !isset($_GET['manage']) and !isset($_GET['prawa']) and !isset($_GET['template_mod'])
  and !isset($_GET['param'])){
	?><p><a href="?admin=&admin_module=&dodaj=" >Dodaj</a> </p><?
	
	$res = $baza->select("*","cmsmodule","","ORDER BY name");
	if (($ile = $baza->size_result($res))>0){
		?>
		<table border="0" cellpadding="2" cellspacing="0" class="tab_edycji">
			<tr>
				<th>LP</th>
				<th>Nazwa</th>
				<th>Katalog</th>
				<!--th>Grafika</th>
				<th>Tytuł</th-->
				<th>Aktyw.</th>
				<th>Operacje</th>
			</tr>
		<?
		for ($i=0; $i<$ile; $i++){
			$row = $baza->row($res);
			$licznik++;
			$mod = $i%2;
			?>
			<tr class="gray<?=$mod;?>">
				<td><?=$licznik;?></td>
				<td><?=$row[name];?></td>
				<td><?=$row[folder];?></td>
				<!--td><?=$row[graphic];?></td>
				<td><?=$row[title];?></td-->
				<td><?if ($row[active]=='YES')echo "TAK"; else echo "NIE";?></td>
				<td>
					<a href="?admin=&admin_module=&edytuj=<?=$row[idModule];?>">Edytuj</a>
					<a href="?admin=&admin_module=&prawa=<?=$row[idModule];?>">Uprawnienia</a>
					<a href="?admin=&admin_module=&template_mod=<?=$row[idModule];?>">Szablony</a>
					<a href="?admin=&admin_module=&param=<?=$row[idModule];?>">Parametry</a>
					<a href="?admin=&admin_module=&usun=<?=$row[idModule];?>">Usuń</a>
					<?
					$folder_module = $row['folder'];
					$adm_mod = $row[admManage];
					//echo $folder_module;
					/*$r=$baza->select("*","cmsmodule,cmsschemamodule",
						"idModule=".$row[idModule]." 
						and module=idModule","","");
					$row = $baza->row($r);
					*/
					/*
					if (file_exists("../modules/".$folder_module."/admin.php") and $adm_mod=='YES'){
						?>
						<a href="?admin=&admin_module=&manage=<?=$row[idModule];?>">Zarządzaj</a>
						<?
					}
					*/				
					?>
				</td>
			</tr>
			<?
		}
		?>
		</table>
		<?
	}
}
?>