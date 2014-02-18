<?
$tabela = "mod_licznik";
if (isset($_POST['zapisz_licznik'])){
	$licznik = $_POST['nowa_wartosc'];
	$result = $baza->update($tabela,"mli_wartosc=$licznik","");
}

$result = $baza->select("*",$tabela, "", "");	
$row = $baza->row($result);	
$wart_licznika = $row['mli_wartosc'];
?>
<form method="POST" action="">
<input type="text" name="nowa_wartosc" value="<?=$wart_licznika?>" />
<input type="submit" name="zapisz_licznik" value="Zapisz" />
</form>