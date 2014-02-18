<center>
<?
$error = false;
if (isset($_POST[send])){
	$error = true;
	if (strlen($_POST[nadawca])==0){
		?>
		<p class="error">Proszę wypełnić pole "Twój e-mail"</p>
		<br>
		<?
	}
	elseif (strlen($_POST[email])==0){
		?>
		<p class="error">Proszę wypełnić pole "E-mail odbiorcy"</p>
		<br>
		<?
	}
	else{
		$error = false;
	}
}

if (!isset($_POST[send]) or $error==true){
	?>
	<center>
	<form method="POST" action="">
		<table border="0" cellpadding="5" cellspacing="3" width="90%">
			<tr>
				<th nowrap>Twój e-mail:</th>
				<td width="100%"><input type="text" name="nadawca" style="width: 100%" /></td>
			</tr>
			<tr>
				<th nowrap>E-mail odbiorcy:</th>
				<td><input type="text" name="email" style="width: 100%" /></td>
			</tr>
			<tr>
				<th nowrap>Wiadomość:</th>
				<td><textarea name="tresc" cols="20" rows="6" style="width: 100%">Witaj,<br />
Przesyłam link do serwisu firmy Surfland Deweloper System Sp. z o.o., lidera oprogramowania CRM i ERP dedykowanego deweloperom. W serwisie znajdziesz informacje na temat firmy, jej produktów oraz aktualności z rynku IT i branży deweloperskiej.
Zapraszam do wejścia na <a href="http://www.surfdeweloper.pl">http://www.surfdeweloper.pl</a>
				</textarea></td>
			</tr>
		</table>
		<br>
		<input type="submit" name="send" value="Wyślij" />
	</form>
	</center>
	<?
}
if (isset($_POST[send]) and $error==false){
	$do = $_POST[email];
	$od = $_POST[nadawca];
	$tresc = $_POST[tresc];
	
	$temat="Polecam stronę firmy Surfland Deweloper System";
	$wiadomosc="<html><head><title>Polecam stronę firmy Surfland Deweloper System</title></head>
	<body>
	<br>
	$tresc
	</body> 
	</html>";
	$headers .= "Mime-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=ISO-8859-2\r\n";
	$headers .= "From: $od\r\n";
	$headers .= "X-Priority: 1\r\n"; // ważna wiadomość!
	$wiadomosc=iconv("UTF-8","ISO-8859-2", $wiadomosc);
	$temat=iconv("UTF-8","ISO-8859-2", $temat);
	mail($do, $temat, $wiadomosc, $headers); 
	
	?>
	<p style="font-size: 14px; font-weight: bold;">Wiadomość została wysłana na podane przez Ciebie adres e-mail.</p>
	<?
}
?>
<br>
</center>