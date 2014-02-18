<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
	<?if (check_rules($baza,'grUser','select')){?>
		<td width="50%" style="text-align: center; vertical-align: top;">
		<center>
		<?include_once("grupy.php");?>
		</center>
		</td>
	<?}
	if (check_rules($baza,'user','select')){?>
		<td width="50%" style="text-align: center; vertical-align: top;">
		<center>
		<?include_once("users.php");?>
		</center>
		</td>
	<?}?>
	</tr>
</table>