<br />
<center>
<?
if (!isset($_POST[send])){
	?>
	<form method="POST" action="">
		<table border="0" cellpadding="5" cellspacing="0">
			<tr>
				<th>Przedstaw się:</th>
				<td><input type="text" name="nazwa" /></td>
			</tr>
			<tr>
				<th>adres e-mail:</th>
				<td><input type="text" name="email" /></td>
			</tr>
			<tr>
				<th>Wiadomość:</th>
				<td><textarea name="tresc" cols="50" rows="6"></textarea></td>
			</tr>
		</table>
		<input type="submit" name="send" value="Wyślij" />
	</form>
	<?
}
else{
	$result_m = $this->baza->select("value","cmsconfig","name='ADM_EMAIL'");
	$row_m = $this->baza->row($result_m);
	$do = $row_m[value];
	$temat="Kontakt w sprawie strony Kryniczna";
	$wiadomosc="<html><head><title>Kontakt w sprawie strony Kryniczna</title></head>
	<body>
	<center><b>Powiadomienie o kontakcie:</b></center>
	<br>
	
	<b>Od:</b> $_POST[nazwa]<br>
	<b>e-mail:</b> $_POST[email]<br>
	<b>Treść:</b> $_POST[tresc]<br>
	
	<br>
	<p align=\"right\">Wiadomość automatyczna serwisu ktyniczno.pl</p>
	</body> 
	</html>";
	$naglowki  = "MIME-Version: 1.0\r\n";
	$naglowki .= "Content-type: text/html; charset=utf-8\r\n";
	$naglowki .= "From: Kryniczno<".$row_m[value].">\r\n";
	mail($do, $temat, $wiadomosc, $naglowki); 
	
	?>
	<p style="font-size: 14px; font-weight: bold;">Wiadomość pomyślnie wysłana. Dziękujemy za zainteresowanie naszym serwisem. Jeśli w treści padło pytanie postaramy się jak najszybciej odpowiedzieć na nie.</p>
	<?
}
?>
</center>