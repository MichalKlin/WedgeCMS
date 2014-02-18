<h3>Tworzenie nowej strony</h3>
<form method="POST" action="">
<table>
<tr>
	<td>Schemat:</td>
	<td>
	<select name="schema" class="select" style="width: 250px;">
	<?
	$res = $baza->select("id,name","cmsschema","active='YES'","ORDER BY defaultSchema DESC");
	if (($ile=$baza->size_result($res))>0){
		for ($i=0; $i<$ile; $i++){
			$row = $baza->row($res);
			?>
			<option value="<?=$row[id];?>"><?=$row[name];?></option>
			<?
		}
	}
	?>
	</select>
	</td>
</tr>
<tr>
	<td>Nazwa html:</td>
	<td><input type="text" name="page_html" size="20" value="" class="input" style="width: 250px;" />(bez .html)</td>
</tr>
<tr>
	<td>Nazwa:</td>
	<td><input type="text" name="page_name" size="30" class="input" style="width: 250px;" /></td>
</tr>
<tr>
	<td>Kolejność:</td>
	<td><input type="text" name="page_order" size="10" class="input" style="width: 250px;" /></td>
</tr>
</table>
<p><input type="submit" name="dodaj" value="Dodaj" class="submit" /></p>
</form>			
