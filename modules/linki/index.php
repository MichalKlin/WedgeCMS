<br>
<table class="ramka_contant" border="0" cellpadding="0" cellspacing="0" width="98%">
<tr>
<td class="ramka_title" style="color: #660A00; font-size: 16px;"><?=$NAME?></td>
</tr>
<tr>
<td class="ramka_text">
<br>
<?php
echo "<center>";

$result = $this->baza->select("*","dictionary_linki_grupy","dlg_active=\"yes\"","ORDER BY dlg_order");
$size_result = $this->baza->size_result($result);
for ($i=0; $i<$size_result; $i++){
	$row = $this->baza->row($result);

	$result2 = $this->baza->select("*","mod_linki","mlin_grupa=$row[dlg_id] and mlin_active=\"yes\"","");
	$size_result2 = $this->baza->size_result($result2);
	if ($size_result2>0){
		?>
		<br />
		<table border="0" width="95%" class="">
		<?
		echo "<tr><td colspan=\"2\" style=\"font-size: 5px;\">&nbsp;</td></tr>";
		echo "<tr><th colspan=\"2\">$row[dlg_nazwa]</th></tr>";

		for ($j=0; $j<$size_result2; $j++){
			$row2 = $this->baza->row($result2);
			echo "<tr><td class=\"left\">
			<a href=\"$row2[mlin_link]\" target=\"_blank\">$row2[mlin_link]</a></td><td><b><i>$row2[mlin_opis]</i></b></td></tr>";
		}
		?></table><?
	}
	echo "<br>";
}
echo "</center>";
?>
<br>
</td>
</tr>
</table>
<br />