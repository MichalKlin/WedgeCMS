<h3>Dostęp grup użytkowników</h3>
<form method="POST" action="">
<table>
<?
$r = $baza->select("*","cmsgroupuser","active='YES'");
if (($ile=$baza->size_result($r))>0){
	for ($i=0; $i<$ile; $i++){
		$roow = $baza->row($r);
		echo "<tr><td style=\"text-align: left;\">";
		$rr = $baza->select("1","cmspageusergroup","page=".$_GET['p']." AND groupUser=".$roow[idGroupUser]);
		if ($baza->size_result($rr)>0){
			?><input type="checkbox" name="group<?=$i;?>" checked /> <?=$roow[nameGroupUser]?><br /> <?
		}
		else{
			?><input type="checkbox" name="group<?=$i;?>" /> <?=$roow[nameGroupUser]?><br /> <?
		}
		echo "</td></tr>";
	}
}
?>
</table>
<?if (check_rules($baza,'site_dostep','update')){?>
<p><input type="submit" name="grupy_user" value="Zapisz zmiany" class="submit" /></p>
<?}?>
</form>