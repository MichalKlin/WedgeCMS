<?
if (!isset($_POST[wyszukaj])){
	$result2 = $this->baza->select("*","cmsmodule", "idModule=$MODULE","","");
	$row2 = $this->baza->row($result2);
	$act = $row2[pathAction];
	?>
	<center>
	<form method="POST" action="<?=$act?>">
		<table border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td>
					<input type="text" id="text_szukaj" name="text_szukaj" />
				</td>
				<td>
					<input type="submit" id="submit_szukaj" name="wyszukaj" value="" />
				</td>
			</tr>
		</table>
	</form>
	</center>	
	<?
}
if (isset($_POST[wyszukaj])){
	$szukany_tekst = $_POST[text_szukaj];
	if (strlen($szukany_tekst)>3){
		
	} else {
		?>
		<p class="error">Zbyt dużo wyników - prosimy wpisać dłuższą frazę do wyszukania.</p>
		
		<?
	}
	?>
	
	<?
}
?>