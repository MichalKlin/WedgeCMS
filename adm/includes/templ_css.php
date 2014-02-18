<?
$katalog = "../css/";

//dodawanie
if (isset($_GET['dodaj_css'])){
	if (isset($_POST[dodaj_css])){
		$file_path = $katalog.$_POST[file];
		$fd = fopen("includes/backslash.txt",r);
		$backslash = fread($fd,filesize("includes/backslash.txt"));
		fclose($fd);		

		$css = str_replace('\"','"',$_POST[css]);
		$css = str_replace("\'","'",$css);
		$css = str_replace("\\\\",$backslash,$css);
		$html = str_replace("©","&nbsp;",$html);
		
		$fd = fopen($file_path,'w');
		fwrite($fd,$css);
		fclose($fd);		
		
		?>Dane zapisane<?
	}
	else{
		?>
		<form method="POST" action="">
			Nazwa pliku: <input type="text" name="file" /><br>
			Zawartość: <br />
			<textarea name="css" style="width: 90%; height: 200px; background: #ddd;"><?=$cont?></textarea>
			<br />
			<input type="submit" name="dodaj_css" value="Zapisz CSS" />
		</form>
		<?
	}
	echo "<p><a href=\"?template=&templ_css=\">Powrót</a></p>";	
}

//edycja css
if (isset($_GET['edytuj_css'])){
	$file_path = $katalog.$_GET['edytuj_css'];
	if (isset($_POST[zapisz_css])){
		$fd = fopen("includes/backslash.txt",r);
		$backslash = fread($fd,filesize("includes/backslash.txt"));
		fclose($fd);		

		$css = str_replace('\"','"',$_POST[css]);
		$css = str_replace("\'","'",$css);
		$css = str_replace("\\\\",$backslash,$css);
		
		$fd = fopen($file_path,'w');
		fwrite($fd,$css);
		fclose($fd);		
		
		?>Dane zapisane<?
	}
	else{
		$fd = fopen($file_path,'r');
		$cont = fread($fd,filesize($file_path));
		fclose($fd);
		
		?>
		<form method="POST" action="">
			<textarea name="css" style="width: 90%; height: 400px; background: #ddd;"><?=$cont?></textarea>
			<br />
			<input type="submit" name="zapisz_css" value="Zapisz CSS" />
		</form>
		<?
	}
	echo "<p><a href=\"?template=&templ_css=\">Powrót</a></p>";	
}

if (!isset($_GET['edytuj_css']) and !isset($_GET['dodaj_css'])){
	if (check_rules($baza,'template','insert')){
		?><p><a href="?template=&templ_css=&dodaj_css=">Dodaj css</a> </p><?
	}
	
	$css_files = null;
		
	//katalogi templateów
	if ($dir = @opendir($katalog)) {
		$ile_css = 0;
		while (($file = readdir($dir)) !== false) {
			if ($file!="" and $file!="." and $file!=".." and $file!="index.php"){
				$css_files[$ile_css++] = $file;
			}
		}  
		closedir($dir);
	}

	if (($ile=sizeof($css_files))>0){
		?>
		<table border="0" cellpadding="2" cellspacing="0" class="tab_edycji">
			<tr>
				<th>LP</th>
				<th>Plik</th>
				<th>Operacje</th>
			</tr>
		<?
		for ($i=0; $i<$ile; $i++){
			$lp = $i+1;
			?>
			<tr>
				<td><?=$lp?></td>
				<td><?=$css_files[$i]?></td>
				<td>
				<?if (check_rules($baza,'template','update')){
					?><a href="?template=&templ_css=&edytuj_css=<?=$css_files[$i]?>">Edytuj css</a><?
				}?>
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