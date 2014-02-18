<?
include_once('includes/functions.php');

if (isset($_GET['dodaj'])){
	if (!isset($_POST['dodaj'])){
		?>
			<form method="POST" id="f" action="">
			<table border="0" cellpadding="0" cellspacing="0" class="tab_edycji">
				<tr>
					<th>Nazwa wzorca:</th>
					<td><input type="text" name="name" size="30" class="input_add" /></td>
					<td class="input_opis">
						(unikalna nazwa wzorca)
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
							$res = $baza->select("*","cmstemplate","","");
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
						(puste pole - tworzenie nowego wzorca strony)
					</td>
				</tr>				
				<tr>
					<th>Domyślny:</th>
					<td>
						<select name="default" class="input_add">
							<option value="YES">TAK</option>
							<option selected value="NO">NIE</option>
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
							<option selected value="YES">TAK</option>
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
			$file_path = '../template/'.$_POST['file'];
			if (!file_exists($file_path)){
				$res = $baza->select("1","cmstemplate","name='".$_POST['name']."'");
				if ($baza->size_result($res)==0){
					if($_POST['default']=='YES'){
						// default na NO dal wszystkich
						$rr = $baza->update("cmstemplate","cmstemplate.default='NO'","","");
					}
					$kolumny = "(id,name,file,cmstemplate.default, active,
						sekcja0,sekcja1,sekcja2,sekcja3,
						sekcja4,sekcja5,sekcja6,sekcja7,
						sekcja8,sekcja9,sekcja10,sekcja11,
						sekcja12,sekcja13,sekcja14,sekcja15,
						sekcja16,sekcja17,sekcja18,sekcja19,
						sekcja20,sekcja21,sekcja22)";
					$values = "0, '".htmlspecialchars($_POST['name'])."',
					 		'".htmlspecialchars($_POST['file'])."',
					 		'".htmlspecialchars($_POST['default'])."',
					  		'".htmlspecialchars($_POST['active'])."',
					  		'NO','NO','NO','NO','NO','NO','NO','NO','NO','NO','NO','NO','NO',
					  		'NO','NO','NO','NO','NO','NO','NO','NO','NO','NO'";
					$res = $baza->insert("cmstemplate",$values,$kolumny, "");
					
					//tworzenie pliku template
					if ($_POST[kopia]==0){
						$fp = fopen($file_path,'w');
						fclose($fp);
					} 
					else {
						//kopiowanie z istniejącego
						$res = $baza->select("*","cmstemplate","id='".$_POST['kopia']."'");
						$row = $baza->row($res);
						$file_org = "../template/".$row['file'];
						$fd = fopen($file_org,'r');
						$cont = fread($fd,filesize($file_org));
						fclose($fd);

						$fp = fopen($file_path,'w');
						fwrite($fp, $cont);
						fclose($fp);
					}
					
					if ($res)
						echo "<p>Nowy wzorzec został zapisany w bazie.</p>";
				}
				else{
					echo "<p>Istnieje już wzorzec o podanej nazwie!</p>";	
				}
			}
			else{
				echo "<p>Istnieje już plik o podanej nazwie!</p>";					
			}
		}
		else{
			echo "<p>Nie podano nazwy wzorca!</p>";
		}
	}
	echo "<p><a href=\"?template=&templ_html=\">Powrót</a></p>";
}

if (isset($_GET['usun'])){
	if (!isset($_POST[usun])) {			
		echo "<p>Czy na pewno usunąć ten wzorzec?<br>Czynność ta powoduje usunięcie z bazy danych, plik szablonu pozostanie na serwerze (aby go usunąć skontaktuj się z administratorem lub usuń go ręcznie z serwera).</p>";
		echo "<form name=\"usun_form\" method=\"post\">";
		echo "<input type=\"submit\" name=\"usun\" value=\"Usuń\" />";
		echo "</form>";					
	}
	else{			
		$rr = $baza->select("*","cmstemplate","id=".$_GET['usun']);
		$roww = $baza->row($rr);
		
	   	$res=$baza->delete("cmstemplate","id=".$_GET['usun']);
 			
	   	// ustawienie domyślnego
   		if($roww['default']=='YES'){
			// default na YES dla pierwzego lepszego
			$rr = $baza->update("cmstemplate","cmstemplate.default='YES' LIMIT 1","","");
		}

		if ($res) {
	   		echo "<p>wzorzec został usunięty</p>"; 		
	   		$res2 = $baza->delete("cmsschema","template=".$_GET['usun']);
   		}
   		else 
   			echo "<p class=\"error\">Błąd zapisu w bazie</p>"; 			
	}
	echo "<p><a href=\"?template=&templ_html=\">Powrót</a></p>";	
}

if (isset($_GET['edytuj'])){
	if (!isset($_POST['edytuj'])){
		$res = $baza->select("*","cmstemplate","id=".$_GET['edytuj'],"","");
		$row = $baza->row($res);

		?>
		<form method="POST" id="f" action="">
		<table border="0" cellpadding="0" cellspacing="0" class="tab_edycji">
			<tr>
				<th>Nazwa wzorca:</th>
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
			$file_path = '../template/'.$_POST['file'];
			if (file_exists($file_path)){
				if($_POST['default']=='YES'){
					// default na NO dal wszystkich
					$rr = $baza->update("cmstemplate","cmstemplate.default='NO'","","");
				}
				$values = "	name='".htmlspecialchars($_POST['name'])."',
				 			file='".$_POST['file']."',
				  			cmstemplate.default='".$_POST['default']."',
				  			active='".$_POST['active']."'";
				$where = "id=".$_GET['edytuj'];
				$res = $baza->update("cmstemplate",$values,$where);
				if ($res)
					echo "<p>Zmiany zostały zapisane w bazie.</p>";
			}
			else{
				echo "<p>Plik nie istnieje.</p>";				
			}
		}
		else{
			echo "<p>Nie podano nazwy wzorca!</p>";
		}
	}
	echo "<p><a href=\"?template=&templ_html=\">Powrót</a></p>";	
}

// dostępne sekcje w temlacie
if (isset($_GET['sekcje'])){
	if (isset($_POST['sekcje'])) {			
		$values = "	sekcja0='".$_POST['sekcja0']."',
					sekcja1='".$_POST['sekcja1']."',
					sekcja2='".$_POST['sekcja2']."',
					sekcja3='".$_POST['sekcja3']."',
					sekcja4='".$_POST['sekcja4']."',
					sekcja5='".$_POST['sekcja5']."',
					sekcja6='".$_POST['sekcja6']."',
					sekcja7='".$_POST['sekcja7']."',
					sekcja8='".$_POST['sekcja8']."',
					sekcja9='".$_POST['sekcja9']."',
					sekcja10='".$_POST['sekcja10']."',
					sekcja11='".$_POST['sekcja11']."',
					sekcja12='".$_POST['sekcja12']."',
					sekcja13='".$_POST['sekcja13']."',
					sekcja14='".$_POST['sekcja14']."',
					sekcja15='".$_POST['sekcja15']."',
					sekcja16='".$_POST['sekcja16']."',
					sekcja17='".$_POST['sekcja17']."',
					sekcja18='".$_POST['sekcja18']."',
					sekcja19='".$_POST['sekcja19']."',
					sekcja20='".$_POST['sekcja20']."',
					sekcja21='".$_POST['sekcja21']."',
					sekcja22='".$_POST['sekcja22']."'";
		$where = "id=".$_GET['sekcje'];
		$res = $baza->update("cmstemplate",$values,$where);
		if ($res)
			echo "<p>Zmiany zostały zapisane w bazie.</p>";
	}
		$res = $baza->select("*","cmstemplate","id=".$_GET['sekcje'],"","");
		$row = $baza->row($res);
		
		?>
				<form method="POST" id="f" action="">
				<table border="1" id="tabela_obraz_sekcji" width="100%">
					<tr>
						<td colspan="12" <?if ($row[sekcja0]=='YES') echo "class=\"wybrana_sekcja\"";?>>
						sekcja 0: 
						<input type="radio" name="sekcja0" value="YES" <?if ($row[sekcja0]=='YES') echo "checked";?> /> TAK 
						<input type="radio" name="sekcja0" value="NO" <?if ($row[sekcja0]=='NO') echo "checked";?> /> NIE 
						</td>
					</tr>
					<tr>
						<td colspan="6" <?if ($row[sekcja1]=='YES') echo "class=\"wybrana_sekcja\"";?>>
						sekcja 1:
						<input type="radio" name="sekcja1" value="YES" <?if ($row[sekcja1]=='YES') echo "checked";?> /> TAK 
						<input type="radio" name="sekcja1" value="NO" <?if ($row[sekcja1]=='NO') echo "checked";?> /> NIE
						</td>
						<td colspan="6" <?if ($row[sekcja2]=='YES') echo "class=\"wybrana_sekcja\"";?>>
						sekcja 2: 
						<input type="radio" name="sekcja2" value="YES" <?if ($row[sekcja2]=='YES') echo "checked";?> /> TAK 
						<input type="radio" name="sekcja2" value="NO" <?if ($row[sekcja2]=='NO') echo "checked";?> /> NIE
						</td>
					</tr>
					<tr>
						<td colspan="4" <?if ($row[sekcja3]=='YES') echo "class=\"wybrana_sekcja\"";?>>
						sekcja 3: 
						<input type="radio" name="sekcja3" value="YES" <?if ($row[sekcja3]=='YES') echo "checked";?> /> TAK 
						<input type="radio" name="sekcja3" value="NO" <?if ($row[sekcja3]=='NO') echo "checked";?> /> NIE
						</td>
						<td colspan="4" <?if ($row[sekcja4]=='YES') echo "class=\"wybrana_sekcja\"";?>>
						sekcja 4:
						<input type="radio" name="sekcja4" value="YES" <?if ($row[sekcja4]=='YES') echo "checked";?> /> TAK 
						<input type="radio" name="sekcja4" value="NO" <?if ($row[sekcja4]=='NO') echo "checked";?> /> NIE
						</td>
						<td colspan="4" <?if ($row[sekcja5]=='YES') echo "class=\"wybrana_sekcja\"";?>>
						sekcja 5:
						<input type="radio" name="sekcja5" value="YES" <?if ($row[sekcja5]=='YES') echo "checked";?> /> TAK 
						<input type="radio" name="sekcja5" value="NO" <?if ($row[sekcja5]=='NO') echo "checked";?> /> NIE
						</td>
					</tr>
					<tr>
						<td colspan="3" <?if ($row[sekcja6]=='YES') echo "class=\"wybrana_sekcja\"";?>>
						sekcja 6:
						<input type="radio" name="sekcja6" value="YES" <?if ($row[sekcja6]=='YES') echo "checked";?> /> TAK 
						<input type="radio" name="sekcja6" value="NO" <?if ($row[sekcja6]=='NO') echo "checked";?> /> NIE
						</td>
						<td colspan="3" <?if ($row[sekcja7]=='YES') echo "class=\"wybrana_sekcja\"";?>>
						sekcja 7:
						<input type="radio" name="sekcja7" value="YES" <?if ($row[sekcja7]=='YES') echo "checked";?> /> TAK 
						<input type="radio" name="sekcja7" value="NO" <?if ($row[sekcja7]=='NO') echo "checked";?> /> NIE
						</td>
						<td colspan="3" <?if ($row[sekcja8]=='YES') echo "class=\"wybrana_sekcja\"";?>>
						sekcja 8:
						<input type="radio" name="sekcja8" value="YES" <?if ($row[sekcja8]=='YES') echo "checked";?> /> TAK 
						<input type="radio" name="sekcja8" value="NO" <?if ($row[sekcja8]=='NO') echo "checked";?> /> NIE
						</td>
						<td colspan="3" <?if ($row[sekcja9]=='YES') echo "class=\"wybrana_sekcja\"";?>>
						sekcja 9:
						<input type="radio" name="sekcja9" value="YES" <?if ($row[sekcja9]=='YES') echo "checked";?> /> TAK 
						<input type="radio" name="sekcja9" value="NO" <?if ($row[sekcja9]=='NO') echo "checked";?> /> NIE
						</td>
					</tr>
					<tr>
						<td colspan="4" <?if ($row[sekcja10]=='YES') echo "class=\"wybrana_sekcja\"";?>>
						sekcja 10:
						<input type="radio" name="sekcja10" value="YES" <?if ($row[sekcja10]=='YES') echo "checked";?> /> TAK 
						<input type="radio" name="sekcja10" value="NO" <?if ($row[sekcja10]=='NO') echo "checked";?> /> NIE
						</td>
						<td colspan="4" <?if ($row[sekcja11]=='YES') echo "class=\"wybrana_sekcja\"";?>>
						sekcja 11:
						<input type="radio" name="sekcja11" value="YES" <?if ($row[sekcja11]=='YES') echo "checked";?> /> TAK 
						<input type="radio" name="sekcja11" value="NO" <?if ($row[sekcja11]=='NO') echo "checked";?> /> NIE
						</td>
						<td colspan="4" <?if ($row[sekcja12]=='YES') echo "class=\"wybrana_sekcja\"";?>>
						sekcja 12:
						<input type="radio" name="sekcja12" value="YES" <?if ($row[sekcja12]=='YES') echo "checked";?> /> TAK 
						<input type="radio" name="sekcja12" value="NO" <?if ($row[sekcja12]=='NO') echo "checked";?> /> NIE
						</td>
					</tr>
					<tr>
						<td colspan="3" <?if ($row[sekcja13]=='YES') echo "class=\"wybrana_sekcja\"";?>>
						sekcja 13:
						<input type="radio" name="sekcja13" value="YES" <?if ($row[sekcja13]=='YES') echo "checked";?> /> TAK 
						<input type="radio" name="sekcja13" value="NO" <?if ($row[sekcja13]=='NO') echo "checked";?> /> NIE
						</td>
						<td colspan="3" <?if ($row[sekcja14]=='YES') echo "class=\"wybrana_sekcja\"";?>>
						sekcja 14:
						<input type="radio" name="sekcja14" value="YES" <?if ($row[sekcja14]=='YES') echo "checked";?> /> TAK 
						<input type="radio" name="sekcja14" value="NO" <?if ($row[sekcja14]=='NO') echo "checked";?> /> NIE
						</td>
						<td colspan="3" <?if ($row[sekcja15]=='YES') echo "class=\"wybrana_sekcja\"";?>>
						sekcja 15:
						<input type="radio" name="sekcja15" value="YES" <?if ($row[sekcja15]=='YES') echo "checked";?> /> TAK 
						<input type="radio" name="sekcja15" value="NO" <?if ($row[sekcja15]=='NO') echo "checked";?> /> NIE
						</td>
						<td colspan="3" <?if ($row[sekcja16]=='YES') echo "class=\"wybrana_sekcja\"";?>>
						sekcja 16:
						<input type="radio" name="sekcja16" value="YES" <?if ($row[sekcja16]=='YES') echo "checked";?> /> TAK 
						<input type="radio" name="sekcja16" value="NO" <?if ($row[sekcja16]=='NO') echo "checked";?> /> NIE
						</td>
					</tr>
					<tr>
						<td colspan="4" <?if ($row[sekcja17]=='YES') echo "class=\"wybrana_sekcja\"";?>>
						sekcja 17:
						<input type="radio" name="sekcja17" value="YES" <?if ($row[sekcja17]=='YES') echo "checked";?> /> TAK 
						<input type="radio" name="sekcja17" value="NO" <?if ($row[sekcja17]=='NO') echo "checked";?> /> NIE
						</td>
						<td colspan="4" <?if ($row[sekcja18]=='YES') echo "class=\"wybrana_sekcja\"";?>>
						sekcja 18:
						<input type="radio" name="sekcja18" value="YES" <?if ($row[sekcja18]=='YES') echo "checked";?> /> TAK 
						<input type="radio" name="sekcja18" value="NO" <?if ($row[sekcja18]=='NO') echo "checked";?> /> NIE
						</td>
						<td colspan="4" <?if ($row[sekcja19]=='YES') echo "class=\"wybrana_sekcja\"";?>>
						sekcja 19:
						<input type="radio" name="sekcja19" value="YES" <?if ($row[sekcja19]=='YES') echo "checked";?> /> TAK 
						<input type="radio" name="sekcja19" value="NO" <?if ($row[sekcja19]=='NO') echo "checked";?> /> NIE
						</td>
					</tr>
					<tr>
						<td colspan="6" <?if ($row[sekcja20]=='YES') echo "class=\"wybrana_sekcja\"";?>>
						sekcja 20:
						<input type="radio" name="sekcja20" value="YES" <?if ($row[sekcja20]=='YES') echo "checked";?> /> TAK 
						<input type="radio" name="sekcja20" value="NO" <?if ($row[sekcja20]=='NO') echo "checked";?> /> NIE
						</td>
						<td colspan="6" <?if ($row[sekcja21]=='YES') echo "class=\"wybrana_sekcja\"";?>>
						sekcja 21:
						<input type="radio" name="sekcja21" value="YES" <?if ($row[sekcja21]=='YES') echo "checked";?> /> TAK 
						<input type="radio" name="sekcja21" value="NO" <?if ($row[sekcja21]=='NO') echo "checked";?> /> NIE
						</td>
					</tr>
					<tr>
						<td colspan="12" <?if ($row[sekcja22]=='YES') echo "class=\"wybrana_sekcja\"";?>>
						sekcja 22:
						<input type="radio" name="sekcja22" value="YES" <?if ($row[sekcja22]=='YES') echo "checked";?> /> TAK 
						<input type="radio" name="sekcja22" value="NO" <?if ($row[sekcja22]=='NO') echo "checked";?> /> NIE
						</td>
					</tr>
				</table>
				<?if (check_rules($baza,'template_section','update')){?>
				<p><input type="submit" name="sekcje" value="Zachowaj zmiany" /></p>
				<?}?>
				</form>
		<?
	echo "<p><a href=\"?template=&templ_html=\">Powrót</a></p>";	
}

//edycja HTML template
if (isset($_GET['html'])){
	$res = $baza->select("*","cmstemplate","id='".$_GET['html']."'");
	$row = $baza->row($res);
	$file_path = '../template/'.$row['file'];

	if (isset($_POST[zapisz_html])){
		$fd = fopen("includes/backslash.txt",r);
		$backslash = fread($fd,filesize("includes/backslash.txt"));
		fclose($fd);		

		$html = str_replace('\"','"',$_POST[html]);
		$html = str_replace("\'","'",$html);
		$html = str_replace("\\\\",$backslash,$html);
		$html = str_replace("©","&nbsp;",$html);
		
		$fd = fopen($file_path,'w');
		fwrite($fd,$html);
		fclose($fd);		
		
		?>Dane zapisane<?
	}
	else{
		$fd = fopen($file_path,'r');
		$cont = fread($fd,filesize($file_path));
		fclose($fd);
		
		?>
		<form method="POST" action="">
			<textarea name="html" style="width: 90%; height: 400px; background: #ddd;"><?=$cont?></textarea>
			<br />
			<input type="submit" name="zapisz_html" value="Zapisz HTML" />
		</form>
		<?
	}
	echo "<p><a href=\"?template=&templ_html=\">Powrót</a></p>";	
}

// podgląd template
if (isset($_GET['podglad'])){
		?>
		<!--iframe src="includes/template_section.php?podglad=<?=$_GET['podglad']?>" width="100%" height="500">
		</iframe-->
		<?
	if (isset($_POST[zapisz_sekcje])){
		$sek0 = ($_POST['sekcja0']!="") ? $_POST['sekcja0'] : 'NO';
		$sek1 = ($_POST['sekcja1']!="") ? $_POST['sekcja1'] : 'NO';
		$sek2 = ($_POST['sekcja2']!="") ? $_POST['sekcja2'] : 'NO';
		$sek3 = ($_POST['sekcja3']!="") ? $_POST['sekcja3'] : 'NO';
		$sek4 = ($_POST['sekcja4']!="") ? $_POST['sekcja4'] : 'NO';
		$sek5 = ($_POST['sekcja5']!="") ? $_POST['sekcja5'] : 'NO';
		$sek6 = ($_POST['sekcja6']!="") ? $_POST['sekcja6'] : 'NO';
		$sek7 = ($_POST['sekcja7']!="") ? $_POST['sekcja7'] : 'NO';
		$sek8 = ($_POST['sekcja8']!="") ? $_POST['sekcja8'] : 'NO';
		$sek9 = ($_POST['sekcja9']!="") ? $_POST['sekcja9'] : 'NO';
		$sek10 = ($_POST['sekcja10']!="") ? $_POST['sekcja10'] : 'NO';
		$sek11 = ($_POST['sekcja11']!="") ? $_POST['sekcja11'] : 'NO';
		$sek12 = ($_POST['sekcja12']!="") ? $_POST['sekcja12'] : 'NO';
		$sek13 = ($_POST['sekcja13']!="") ? $_POST['sekcja13'] : 'NO';
		$sek14 = ($_POST['sekcja14']!="") ? $_POST['sekcja14'] : 'NO';
		$sek15 = ($_POST['sekcja15']!="") ? $_POST['sekcja15'] : 'NO';
		$sek16 = ($_POST['sekcja16']!="") ? $_POST['sekcja16'] : 'NO';
		$sek17 = ($_POST['sekcja17']!="") ? $_POST['sekcja17'] : 'NO';
		$sek18 = ($_POST['sekcja18']!="") ? $_POST['sekcja18'] : 'NO';
		$sek19 = ($_POST['sekcja19']!="") ? $_POST['sekcja19'] : 'NO';
		$sek20 = ($_POST['sekcja20']!="") ? $_POST['sekcja20'] : 'NO';
		$sek21 = ($_POST['sekcja21']!="") ? $_POST['sekcja21'] : 'NO';
		$sek22 = ($_POST['sekcja22']!="") ? $_POST['sekcja22'] : 'NO';
		$values = "	sekcja0='".$sek0."',
					sekcja1='".$sek1."',
					sekcja2='".$sek2."',
					sekcja3='".$sek3."',
					sekcja4='".$sek4."',
					sekcja5='".$sek5."',
					sekcja6='".$sek6."',
					sekcja7='".$sek7."',
					sekcja8='".$sek8."',
					sekcja9='".$sek9."',
					sekcja10='".$sek10."',
					sekcja11='".$sek11."',
					sekcja12='".$sek12."',
					sekcja13='".$sek13."',
					sekcja14='".$sek14."',
					sekcja15='".$sek15."',
					sekcja16='".$sek16."',
					sekcja17='".$sek17."',
					sekcja18='".$sek18."',
					sekcja19='".$sek19."',
					sekcja20='".$sek20."',
					sekcja21='".$sek21."',
					sekcja22='".$sek22."'";
		$where = "id=".$_GET['podglad'];
		$res = $baza->update("cmstemplate",$values,$where,"");
		if ($res)
			echo "<p>Zmiany zostały zapisane w bazie.</p>";
	}

	$res_sek = $baza->select("*","cmstemplate","id=".$_GET['podglad'],"","");
	$row_sek = $baza->row($res_sek);
	
	$res = $baza->select("*","cmstemplate","id='".$_GET['podglad']."'");
	$row = $baza->row($res);
	$file_path = '../template/'.$row['file'];
	$fd = fopen($file_path,'r');
	$cont = fread($fd,filesize($file_path));
	fclose($fd);
	
//	$cont = substr($cont, strpos("</head>",$con));
//	$regex = array('/<td([^>]*)>([^<]*)<\/td>/i','/<img[^>]*>/i');
//	$mixed = array('<td$1></td>','');
//	$cont = preg_replace($regex,$mixed,$cont);
	$cont = substr($cont, strpos($cont,"</head>"));
	$regex = array('/<img[^>]*>/i','/<p([^>]*)>([^<]*)<\/p>/i','/<a([^>]*)>([^<]*)<\/a>/i','/<td([^>]*)>([^<]*)<\/td>/i');
	$mixed = array('','','','<td$1></td>');
	$cont = preg_replace($regex,$mixed,$cont);
	
	for ($j=0; $j<23; $j++){
		$cont = str_replace('<div id="sekcja'.$j.'"></div>',
		'<div id="sekcja'.$j.'" style="border: 1px solid blue; padding:5px; margin:1px;" '.check_section_selected($row_sek,$j,'YES').' ><center>sekcja '.$j.': 
		<input type="radio" name="sekcja'.$j.'" value="YES" '.check_section($row_sek,$j,'YES').'/> TAK 
		<input type="radio" name="sekcja'.$j.'" value="NO" '.check_section($row_sek,$j,'NO').' /> NIE 
		</center></div>',$cont);
	}
	?>
	<form method="POST" id="f" action="">
	<div width="98%" height="100%">
		<?=$cont?>
	</div>
	<?if (check_rules($baza,'template_section','update')){?>
	<p><input type="submit" name="zapisz_sekcje" value="Zachowaj zmiany" /></p>
	<?}?>
	</form>
	<?
/*		
	*/
	echo "<p><a href=\"?template=&templ_html=\">Powrót</a></p>";		
}

//dodawania template z pliku
if (isset($_GET['dodaj_plik'])){
	if (!isset($_POST['dodaj'])){
		$template=null;
		
		//katalogi templateów
		$katalog = "../template/";
		if ($dir = @opendir($katalog)) {
			$ile_templatow = 0;
			while (($file = readdir($dir)) !== false) {
				if ($file!="" and $file!="." and $file!=".." and is_file($katalog.$file)){
					$r = $baza->select("1","cmstemplate","file='".$file."'");
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
					<th>Nazwa wzorca:</th>
					<td><input type="text" name="name" size="30" class="input_add" /></td>
					<td class="input_opis">
						(unikalna nazwa wzorca)
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
							<option selected value="NO">NIE</option>
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
							<option selected value="YES">TAK</option>
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
			$res = $baza->select("1","cmstemplate","name='".$_POST['name']."'");
			if ($baza->size_result($res)==0){
				$kolumny = "(id,name,file,cmstemplate.default, active,
					sekcja0,sekcja1,sekcja2,sekcja3,
					sekcja4,sekcja5,sekcja6,sekcja7,
					sekcja8,sekcja9,sekcja10,sekcja11,
					sekcja12,sekcja13,sekcja14,sekcja15,
					sekcja16,sekcja17,sekcja18,sekcja19,
					sekcja20,sekcja21,sekcja22)";
				$values = "0, '".htmlspecialchars($_POST['name'])."',
				 		'".htmlspecialchars($_POST['file'])."',
				 		'".htmlspecialchars($_POST['default'])."',
				  		'".htmlspecialchars($_POST['active'])."',
				  		'NO','NO','NO','NO','NO','NO','NO','NO','NO','NO','NO','NO','NO',
				  		'NO','NO','NO','NO','NO','NO','NO','NO','NO','NO'";
				$res = $baza->insert("cmstemplate",$values,$kolumny);
				if ($res)
					echo "<p>Nowy wzorzec został zapisany w bazie.</p>";
			}
			else{
				echo "<p>Istnieje już wzorzec o podanej nazwie!</p>";	
			}
		}
		else{
			echo "<p>Nie podano nazwy wzorca!</p>";
		}
	}

	?><p><a href="?template=&templ_html=">Powrót</a></p><?
}

//wyświetlenie rekordów
if (!isset($_GET['dodaj_plik']) and !isset($_GET['podglad']) and !isset($_GET['html']) and !isset($_GET['dodaj']) and !isset($_GET['usun']) and !isset($_GET['edytuj']) and !isset($_GET['sekcje'])){
	if (check_rules($baza,'template','insert')){
		?><p><a href="?template=&templ_html=&dodaj=" >Dodaj nowy wzorzec</a> | <a href="?template=&templ_html=&dodaj_plik=" >Dodaj z pliku</a></p><?
	}
	
	$res = $baza->select("*","cmstemplate","","");
	if (($ile = $baza->size_result($res))>0){
		?>
		<table border="0" cellpadding="2" cellspacing="0" class="tab_edycji">
			<tr>
				<th>LP</th>
				<th>Nazwa</th>
				<th>Plik</th>
				<th>Domyślny</th>
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
				<td><?=$row['file'];?></td>
				<td><?if($row['default']=='YES') echo "TAK"; else echo 'NIE';?></td>
				<td><?if($row[active]=='YES') echo "TAK"; else echo 'NIE';?></td>
				<td>
					<?if (check_rules($baza,'template','update')){?>
					<a href="?template=&templ_html=&html=<?=$row[id];?>">HTML</a>
					<?}
					if (check_rules($baza,'template','update')){?>
					<a href="?template=&templ_html=&edytuj=<?=$row[id];?>">Edytuj</a>
					<?}
					if (check_rules($baza,'template','delete')){?>
					<a href="?template=&templ_html=&usun=<?=$row[id];?>">Usuń</a>
					<?}
					if (check_rules($baza,'template_section','select')){?>
					<a href="?template=&templ_html=&podglad=<?=$row[id];?>">Sekcje</a>
					<?}?>
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