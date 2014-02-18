<?
$tabela = "mod_rss";
if (isset($_POST['zapisz_rss'])){
	$rss = $_POST['nowa_wartosc'];
	$result = $baza->select("*",$tabela, "", "");	
	if ($baza->size_result($result)>0){
		$result = $baza->update($tabela,"mrss_wartosc='$rss'","");
	}else{
		$result = $baza->insert($tabela,"0,'$rss'","");
	}
}

$result = $baza->select("*",$tabela, "", "");	
$row = $baza->row($result);	
$wart = $row['mrss_wartosc'];
?>
<form method="POST" action="">
<input type="text" name="nowa_wartosc" value="<?=$wart?>" style="width: 300px;" />
<input type="submit" name="zapisz_rss" value="Zapisz" />
</form>