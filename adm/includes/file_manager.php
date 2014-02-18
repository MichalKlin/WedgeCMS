<script>
function czy_usun(strona){
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
<br>
<?
//tworzenie katalogu
if (isset($_POST[add_dir]) and strlen($_POST[katalog_nazwa])>0){
	if (!is_dir($_SESSION[fmag]."/".poprawna_nazwa_pliku($_POST[katalog_nazwa])))
		mkdir($_SESSION[fmag]."/".poprawna_nazwa_pliku($_POST[katalog_nazwa]),0777);
	else{
		?><p>Katalog o podanej nazwie już istnieje!!!</p><?
	}
}

//dodawanie pliku
if (isset($_POST[add_file]) and strlen($_FILES['file_name']['name'])>0) {
	if (!file_exists($_SESSION[fmag]."/".poprawna_nazwa_pliku($_FILES['file_name']['name']))){
		if (is_uploaded_file($_FILES['file_name']['tmp_name'])){
			move_uploaded_file($_FILES['file_name']['tmp_name'],$_SESSION[fmag]."/".poprawna_nazwa_pliku($_FILES['file_name']['name']));
		}
		else{
			?><p>Wystąpił nieoczekiwany błąd zapisu pliku. Proszę spróbować ponownie!!!!</p><?
		}
	}
	else{
		?><p>Plik już istnieje!!!</p><?
	}
}

//zmiana nazwy pliku/katalogu
if (isset($_POST[edytuj]) and strlen($_POST[edytuj])>0){
	if (!file_exists($_SESSION[fmag]."/".poprawna_nazwa_pliku($_POST[edytuj])))
		rename($_SESSION[fmag]."/".$_POST[old_name],$_SESSION[fmag]."/".poprawna_nazwa_pliku($_POST[edytuj]));
	else{
		?><p>Plik o podanej nazwie już istnieje!!!</p><?
	}		
}

//usuwanie pliku
if (isset($_GET[usun_p])){
	$plik_do_us = $_SESSION[fmag]."/".$_GET[usun_p];
	if (file_exists($plik_do_us))
		unlink($plik_do_us);
}

//usuwanie katalogu
if (isset($_GET[usun_k])){
	$kat_do_us = $_SESSION[fmag]."/".$_GET[usun_k];
	if (file_exists($kat_do_us)){
		$d_us = dir($kat_do_us);
		$count_us = 0;
		while (false !== ($entry = $d_us->read())) {
			if ($entry!="." and $entry!=".."){
				$count_us++;
			}
		}
		if ($count_us==0)
			rmdir($kat_do_us);
		else{
			?>
			<p>Nie można usunąć katalogu - katalog nie jest pusty!!!</p>
			<?
		}
		$d_us->close();
	}
}

$res = $baza->select("*","cmsconfig","name='DIR_START'","","");
$row = $baza->row($res);
$kat = "../../".$row['value'];

if (!isset($_SESSION[fmag])){
	$katalog = $kat;
} else {
	$katalog = $_SESSION[fmag];
}
//$katalog = $kat;
if (isset($_GET[dir])){
	if ($_GET[dir]!=".."){
		$ok = false;
		$d = dir($katalog);
		while (false !== ($entry = $d->read())) {
			if (is_dir($katalog."/".$entry)){
				if ($entry == $_GET[dir])
					$ok = true;
			}
		}
		if ($ok)
			$katalog .= "/".$_GET[dir];
	} else{
		$katalog = substr($katalog, 0, strrpos($katalog,"/"));
	}
}

//jeśli wyszedł za nisko (nie znaleziona w ścieżce katalogu startowego) to ustaw katalog startowy
if (strpos($katalog,$row[value])==false){
	$katalog = $kat;
}

$_SESSION[fmag] = $katalog;
	
//pobranie listy katalogów
$d = dir($katalog);
$count_kat = 0;
while (false !== ($entry = $d->read())) {
	if (!($entry=="." or $entry[0]=="." or $entry=="adm" or $entry=="class" or $entry=="config" or $entry=="javascript" 
			or $entry=="template" or $entry=="modules" or $entry=="css" or $entry==".htaccess" or $entry=="include" 
			or strpos($entry,".php")!=false or strpos($entry,".sql")!=false or strpos($entry,".xml")!=false
			or ($entry==".." and $katalog==$kat))){
		$lista_katalogów[$count_kat][0] = $entry;
		$lista_katalogów[$count_kat][1] = filesize($katalog."/".$entry);
		$lista_katalogów[$count_kat][2] = is_dir($katalog."/".$entry);
		$count_kat++;
	}
}
$d->close();

//sortowanie katalogów i plików alfabetycznie
for ($i=0; $i<sizeof($lista_katalogów); $i++){		
	for ($j=0; $j<sizeof($lista_katalogów)-$i-1; $j++){
		$zamien = false;
		$entry = $lista_katalogów[$j][0];
		$size = $lista_katalogów[$j][1];
		$is_dir = $lista_katalogów[$j][2];
		$entry2 = $lista_katalogów[$j+1][0];
		$is_dir2 = $lista_katalogów[$j+1][2];
		$size2 = $lista_katalogów[$j+1][1];
		if ($is_dir==false and $is_dir2==true)
			$zamien = true;
		elseif ($is_dir==true and $is_dir2==false){
			
		}	
		else{
			if (strcmp(strtolower($entry),strtolower($entry2))>0)
				$zamien = true;
		}
		if ($zamien){
			$lista_katalogów[$j][0] = $entry2; 
			$lista_katalogów[$j+1][0] = $entry; 
			$lista_katalogów[$j][1] = $size2; 
			$lista_katalogów[$j+1][1] = $size; 
			$lista_katalogów[$j][2] = $is_dir2; 
			$lista_katalogów[$j+1][2] = $is_dir; 
		}
	}
}

?>
<center>
<table border="0" cellpadding="0" cellspacing="0" id="file_manager">
	<tr>
		<th style="text-align: center;">Name</th>
		<th style="text-align: center;">Size</th>
		<th colspan="2">&nbsp;</th>
	</tr>
<?
for ($i=0; $i<sizeof($lista_katalogów); $i++){		
	$entry = $lista_katalogów[$i][0];
	$size = $lista_katalogów[$i][1];
	$is_dir = $lista_katalogów[$i][2];
	$gray = $i%2;
	?>
	<form method="POST" action="?admin=&admin_manager=">
	<tr class="gray<?=$gray?>">
		<td style="text-align: left; width: 250px;">
			<?if (isset($_GET[edytuj]) and $_GET[edytuj]==$entry and !isset($_POST[edytuj])){
				?>
				<input type="text" name="edytuj" value="<?=$entry?>" style="width: 100%;" />
				<input type="hidden" name="old_name" value="<?=$entry?>" />
				<?
			}
			else{
				if ($is_dir){?>
				<a href="?admin=&admin_manager=&dir=<?=$entry?>">
				<?}
				if ($entry!="..")
					echo $entry;
				else{
					?>
					<img src="images/up.gif">
					<?
				}
				if ($is_dir){?>
				</a>
			<?}
			}
			?>
		</td>
		<td style="text-align: right;">
			<?if (!$is_dir){?>
				<?=show_size($size)?>
			<?}else echo "&nbsp;";?>
		</td>
		<td>
			<?if ($entry!=".."){?>
			<?if (isset($_GET[edytuj]) and $_GET[edytuj]==$entry and !isset($_POST[edytuj])){
				?>
				<input type="button" style="text-decoration: blink; width:16px; height: 16px; border: 0; background-color:#fff; cursor: pointer; background-image: url('images/save.gif');" onclick="this.form.submit();" />
				<?
			}
			else{
				?>
				&nbsp;<a href="?admin=&admin_manager=&edytuj=<?=$entry?>"><img src="images/editor.gif"></a>&nbsp;
			<?}
			}?>
		</td>
		<td>
			<?if ($entry!=".."){?>
				<?if ($is_dir){?>
					&nbsp;<a href="#" onclick="czy_usun('?admin=&admin_manager=&usun_k=<?=$entry?>')"><img src="images/folderdelete.gif"></a>&nbsp;
				<?}else{?>
					&nbsp;<a href="#" onclick="czy_usun('?admin=&admin_manager=&usun_p=<?=$entry?>')"><img src="images/filedelete.gif"></a>&nbsp;
				<?}?>
			<?}?>
		</td>
	</tr>
	</form>
	<?
}
?>
<tr>
	<td colspan="4" style="text-align: left; border-top: 2px solid #000; border-left: 2px solid #000; border-right: 2px solid #000;">
		<form method="POST" action="?admin=&admin_manager=">
			<input type="text" name="katalog_nazwa" style="width: 200px;" /><input type="hidden" value="true" name="add_dir" />&nbsp;&nbsp;<input type="button" name="add" style="width:16px; height: 16px; border: 0; cursor: pointer; background-color:#fff; background-image: url('images/folderadd.gif');" onclick="this.form.submit();" />&nbsp; dodaj katalog
		</form>
	</td>
</tr>
<tr>
	<td colspan="4" style="text-align: left; border: 2px solid #000;">
		<form method="POST" action="?admin=&admin_manager=" enctype="multipart/form-data">
			<input type="file" name="file_name" style="width: 200px;" /><input type="hidden" value="true" name="add_file" />&nbsp;&nbsp;<input type="button" name="add" style="width:16px; height: 16px; border: 0; cursor: pointer; background-color:#fff; background-image: url('images/fileadd.gif');" onclick="this.form.submit();" />&nbsp; dodaj plik
		</form>
	</td>
</tr>
</table>
</center><br />
<?
function poprawna_nazwa_pliku($string){
	//$string = strtolower($string);
	return str_replace(" ","_",$string);
}
function show_size($size){
	if ($size<1000)
		return $size." B&nbsp;";
	elseif ($size<1000000)
		return floor($size/1000)." kB&nbsp;";
	else
		return floor($size/1000000)." MB&nbsp;";
}
?>