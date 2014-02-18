<h3>Parametry strony</h3>
<style>
#param_page th{
	text-align: right;
}
#param_page td{
	text-align: left;
}
</style>
<form method="POST" action="">
<table id="param_page">

<tr><th>Nazwa html:</th><td><input type="text" name="page_html" size="50" class="input" style="width: 250px;" value="<?=$row[htmlName];?>" /></td></tr>
<tr><th>Nazwa:</th><td><input type="text" name="page_name" size="50" class="input" style="width: 250px;" value="<?=$row[name];?>" /></td></tr>
<tr><th>Strona zew.:</th><td><input type="text" name="page_url" size="50" class="input" style="width: 250px;" value="<?=$row[url];?>" /></td></tr>
<tr><th>Schemat: </th><td><select name="schema" class="select" style="width: 250px;">
<?
$r = $baza->select("id,name","cmsschema","","order by name");
if (($ile=$baza->size_result($r))>0){
	for ($i=0; $i<$ile; $i++){
		$roow = $baza->row($r);
		if ($roow[id]==$row[schema]) $selected="selected"; else $selected="";
		?>
		<option value="<?=$roow[id];?>" <?=$selected;?>><?=$roow[name];?></option>
		<?
	}
}
?>
</select> </td></tr>
<tr><th>Kolejność: </th><td><input type="text" name="page_order" class="input" style="width: 250px;" size="10" value="<?=$row[orderPage];?>" /></td></tr>
<tr><th>Domyślny: </th><td>
	<select name="default" class="select" style="width: 250px;">
		<option value="YES" <?if ($row[defaultPage]=='YES') echo "selected";?>>Tak</option>
		<option value="NO" <?if ($row[defaultPage]=='NO') echo "selected";?>>Nie</option>
	</select>
</td></tr>
<tr><th>Nadrzędna strona: </th><td><select name="over" class="select" style="width: 250px;">
<option value="0"> -- brak -- </option>
<?
$r = $baza->select("idPage,name","cmspage","idPage!=".$_GET['p'],"order by name","");
if (($ile=$baza->size_result($r))>0){
	for ($i=0; $i<$ile; $i++){
		$roow = $baza->row($r);
		if ($roow['idPage']==$row['over']) $selected="selected"; else $selected="";
		?>
		<option value="<?=$roow['idPage'];?>" <?=$selected;?>><?=$roow['name'];?></option>
		<?
	}
}
?>			
</select></td></tr>
<tr><th>Słowa kluczowe: </th><td><textarea name="keywords" cols="40" rows="3" style="width: 250px;"><?=$row[keywords];?></textarea></td></tr>
<tr><th>Tytuł: </th><td><input type="text" name="title" class="input" style="width: 250px;" size="50" value="<?=$row[title];?>" /></td></tr>
<tr><th>Opis: </th><td><textarea name="description" cols="40" rows="3" style="width: 250px;"><?=$row[description];?></textarea></td></tr>
<tr><th>Aktywny: </th><td>
	<select name="active" class="select" style="width: 250px;">
		<option value="YES" <?if ($row[active]=='YES') echo "selected";?>>Tak</option>
		<option value="NO" <?if ($row[active]=='NO') echo "selected";?>>Nie</option>
	</select>
	</td></tr>
</table>			
<?if (check_rules($baza,'site_parametry','select')){?>
<p><input type="submit" name="zapisz" value="Zapisz zmiany" class="submit" /></p>
<?}?>
</form>