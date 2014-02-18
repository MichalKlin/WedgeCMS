<?
if (isset($_GET[pages]))
	$link = "?pages=&p=$_GET[p]&modul=&manage=$_GET[manage]";
else 
	$link = "?modules=&manage=$_GET[manage]";	

//edytor ankiet
$tabela = "mod_ankieta";

if (isset($_GET['usun_ank'])){
	$res = $baza->delete($tabela,"ma_id=".$_GET['usun_ank']);
}
	
if (isset($_POST['dodaj'])){
	$res = $baza->insert($tabela,"0,'".htmlspecialchars($_POST['pytanie'])."',
	'".htmlspecialchars($_POST['odpowiedz'])."',0,3,1,'".htmlspecialchars($_POST['aktywnosc'])."'");
}

if (isset($_GET['edytuj_ank'])){
	if (!isset($_POST['edytuj_ank'])){
		$res = $baza->select("*",$tabela,"ma_id=".$_GET['edytuj_ank']);
		$row = $baza->row($res);
		?>
		<form method="POST" action="">
		<table class="tab_edycji">
			<tr class="gray0">
				<th>Pytanie</th>
				<td><input type="text" name="pytanie" value="<?=$row['ma_question']?>" style="width: 250px;" /></td>
			</tr>
			<tr class="gray1">
				<th>Odpowiedzi</th>
				<td><input type="text" name="odpowiedz" value="<?=$row['ma_answer']?>" style="width: 250px;" /></td>
			</tr>
			<tr class="gray0">
				<th>Aktywność</th>
				<td>
					<select name="aktywnosc">
					<option value="YES" <?if ($row['ma_active']=="YES") echo "selected";?>>TAK</option>
					<option value="NO" <?if ($row['ma_active']=="NO") echo "selected";?>>NIE</option>
					</select>
				</td>
			</tr>
		</table>
		<input type="submit" name="edytuj_ank" value="Zapisz zmiany" />
		</form>
		<?
	}
	else{
		$res = $baza->update($tabela,"ma_question='".htmlspecialchars($_POST['pytanie'])."',
		ma_answer='".htmlspecialchars($_POST['odpowiedz'])."',ma_active='".htmlspecialchars($_POST['aktywnosc'])."'","ma_id=".$_GET['edytuj_ank']);
		if ($res)
			echo "<p>Dane poprawnie zapisane</p>";
	}
	?>
	<p><a href="<?=$link?>">Powrót do ankiety</a></p>
	<?	
}
else{
?>
<form method="POST" action="">
<table class="tab_edycji">
	<tr>
		<th>Pytanie</th>
		<th>Odpowiedzi</th>
		<th>Aktywność</th>
	</tr>
	<tr class="gray1">
		<td><input type="text" name="pytanie" /></td>
		<td><input type="text" name="odpowiedz" /></td>
		<td><select name="aktywnosc"><option value="YES">TAK</option><option value="NO">NIE</option></select></td>
	</tr>
	<tr class="gray1">
		<td colspan="3">Odpowiedzi oddzielaj znakiem #</td>
	</tr>
</table>
<input type="submit" name="dodaj" value="Dodaj ankietę" />
</form>
<br />
<?

$res = $baza->select("*",$tabela);
if (($ile=$baza->size_result($res))>0){
?>
<table border="0" cellpadding="0" cellspacing="0" class="tab_edycji">
	<tr>
		<th>Lp</th>
		<th>Pytanie</th>
		<th>Odpowiedzi</th>
		<th>Aktywność</th>
		<th>Funkcje</th>
	</tr>
	<?
	for ($i=0; $i<$ile; $i++){
		$row = $baza->row($res);
		$licz++;
		$mod = $i%2;
		?>
	<tr class="gray<?=$mod;?>">
		<td><?=$licz?></td>
		<td><?=$row['ma_question']?></td>
		<td><?=$row['ma_answer']?></td>
		<td><?=$row['ma_active']?></td>
		<td>
			<a href="<?=$link?>&edytuj_ank=<?=$row['ma_id']?>">Edytuj</a>&nbsp;|&nbsp;
			<a href="<?=$link?>&usun_ank=<?=$row['ma_id']?>">Usuń</a>
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