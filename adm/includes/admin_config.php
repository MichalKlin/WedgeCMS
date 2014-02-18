<?
$tabela = "cmsconfig";

if (isset($_POST[zapisz])){
	$res = $baza->select("*",$tabela,"","order by name");
	if (($ile = $baza->size_result($res))>0){
		for ($i=0; $i<$ile; $i++){
			$row = $baza->row($res);
			$nazwa_des = "description_".$row[name];
			$nazwa_val = "value_".$row[name];
			$res2 = $baza->update($tabela,"value='$_POST[$nazwa_val]'","name='$row[name]'","");//description='$_POST[$nazwa_des]',
		}	
	}
}

$res = $baza->select("*",$tabela,"","order by name");
if (($ile = $baza->size_result($res))>0){
	?>
	<center>
	<form method="POST" action="">
	<br>
	<table border="0" cellpadding="1" cellspacing="1" class="tab_edycji">
	<tr>
		<th>znacznik</th>
		<th>opis</th>
		<th>wartość</th>
	</tr>
	<?
	for ($i=0; $i<$ile; $i++){
		$row = $baza->row($res);
		$j = $i % 2;
		?>
		<tr class="gray<?=$j?>">
			<td><?=$row[name]?></td>
			<td><!--input type="text" name="description_<?=$row[name]?>" size="50" value="--><?=$row[description]?><!--" /--></td>
			<td><input type="text" name="value_<?=$row[name]?>" style="width: 200px;" value="<?=$row[value]?>" /></td>
		</tr>
		<?
	}
	?>
	</table>
	<br>
	<input type="submit" name="zapisz" value="Zapisz" />
	</form>
	<br><br>
	</center>
	<?
}		
?>