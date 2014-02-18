<?
//zarządzanie w MODULES

$link = "?modules=&manage=$_GET[manage]";	

$tabela = "modgallerygroup";

//zapis zmian w grupach galerii
if (isset($_POST['zapisz_grupe'])){
	$result = $baza->select("*",$tabela, "", "ORDER BY id");	
	if (($ile = $baza->size_result($result))>0){
		for ($i=0; $i<$ile; $i++){
			$row = $baza->row($result);	
			$values = "name=\"".$_POST['nazwa'.$i]."\",
				active=\"".$_POST['active'.$i]."\"";
			$where = "id=".$row['id'];
			$baza->update($tabela,$values,$where);
		}	
	}
}

//dodanie nowego menu
if (isset($_POST['dodaj_grupe'])){
	$values = "0,\"".$_POST['nazwa']."\",\"".$_POST['active']."\"";
	$baza->insert($tabela,$values,"","");
}

//usunięcie menu
if (isset($_GET['usun_grupe'])){
	$baza->delete($tabela,"id=".$_GET['usun_grupe']);
}


$result = $baza->select("*",$tabela, "", "ORDER BY name");	
?>
	<center>
	<form method="POST" action="">
	<table border="0" cellpadding="0" cellspacing="0" class="tab_edycji">
		<tr>
			<th>Nazwa</th>
			<th>Aktyw.</th>
			<td></td>
		</tr>
		<tr class="gray0">
			<td><input type="text" name="nazwa" value="" /></td>
			<td><select name="active">
			<option  value="YES" selected>YES</option>
			<option  value="NO">NO</option>
			</select></td>
		</tr>
	</table>
	<input type="submit" name="dodaj_grupe" value="Dodaj grupę" />
	</form>
	
	<br />
	
<?
if (($ile = $baza->size_result($result))>0){
	?>
	<form method="POST" action="">
	<table border="0" cellpadding="0" cellspacing="0" class="tab_edycji">
	<tr>
		<th>LP</th>
		<th>Nazwa</th>
		<th>Aktyw.</th>
	</tr>
	<?
	for ($i=0; $i<$ile; $i++){
		$row = $baza->row($result);	
		$licz = $i+1;
		$mod = $i%2;
		?>
		<tr class="gray<?=$mod;?>">
			<td><?=$licz?></td>
			<td><input type="text" name="nazwa<?=$i?>" value="<?=$row['name']?>" /></td>
			<td><select name="active<?=$i?>">
			<option value="YES" <?if ($row['active']=='YES') echo "selected";?> >YES</option>
			<option  value="NO" <?if ($row['active']=='NO') echo "selected";?>>NO</option>
			</select></td>
			<td><a href="<?=$link?>&usun_grupe=<?=$row['id']?>" >Usuń</a></td>
		</tr>
		<?
	}
	?>
	</table>
	<input type="submit" name="zapisz_grupe" value="Zapisz zmiany" />
	</form>

	</center>
	<?
}

?>	