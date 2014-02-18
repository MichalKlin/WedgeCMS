<?
$tabela = "mod_download";
if (isset($_GET[pages]))
	$link = "?pages=&p=$_GET[p]&modul=&manage=$_GET[manage]";
else 
	$link = "?modules=&manage=$_GET[manage]";	
	
if (isset($_POST[zapisz])){
	$file = "file";
	$file_name = str_replace(" ","_",$_FILES[$file]['name']);
	//echo $file_name;
	if (strlen($file_name)>0 and strlen($_POST[opis])>0 and strlen($_POST[size])>0) {
		if (is_uploaded_file($_FILES[$file]['tmp_name'])){
			move_uploaded_file($_FILES[$file]['tmp_name'],"../download/".$file_name);
			$dzis = date("Y-m-d");
			$result = $baza->insert($tabela, "0,'$file_name','$_POST[opis]','$dzis',0,'$_POST[size]'");	
		}
	}
	else{
		echo "<span style=\"color: red\">Wypełnij wszystkie pola!!!</span><br/>";
	}
}

if (isset($_GET[usun])){
	$result = $baza->delete($tabela, "mdow_id=$_GET[usun]");	
}

?>
Dodanie nowego pliku:<br>
<form method="POST" action="" enctype="multipart/form-data">
<table>
	<tr>
		<th>Wskaż plik</th>
		<td><input type="file" name="file" /></td>
	</tr>
	<tr>
		<th>Opis</th>
		<td><input type="text" name="opis" /></td>
	</tr>
	<tr>
		<th>Rozmiar</th>
		<td><input type="text" name="size" /></td>
	</tr>
</table>
<input type="submit" name="zapisz" value="dodaj" />
</form>
<hr>
<br>


<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
<td class="center" style="padding: 10px;">
<h4>Lista plików do ściągnięcia:</h4>
<br />
<?

$result = $baza->select("*",$tabela, "", "ORDER BY mdow_created DESC");	
if (($ile = $baza->size_result($result))>0){
?>
	<center>
	<form method="POST" action="">
	<table border="0" cellpadding="0" cellspacing="0" class="tab_edycji">
		<tr>
			<th>Plik</th>
			<th>Opis</th>
			<th>Rozmiar</th>
			<th>Data utworzenia</th>
			<th>Liczba pobrań</th>
			<td></td>
		</tr>
		<?
		for ($j=0; $j<$ile; $j++){
			$row = $baza->row($result);	
			$licz = $j+1;
			$mod = $j%2;
			?>
			<tr class="gray<?=$mod;?>">
				<td><?=$row['mdow_file']?></td>
				<td><?=$row['mdow_description']?></td>
				<td><?=$row['mdow_size']?></td>
				<td><?=$row['mdow_created']?></td>
				<td><?=$row['mdow_counter']?></td>
				<td><a href="<?=$link?>&usun=<?=$row['mdow_id']?>" >Usuń</a></td>
			</tr>
			<?
		}?>
	</table>
<?}?>