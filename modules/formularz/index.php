<br />
W przypadku jakichkolwiek pytań prosimy o wypełnienie poniższego formularza:
<br /><br />
<?
if (!isset($_POST[send])){
?>
<form method="POST" action="">
	<table border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td>
			<strong>Treść wiadomości:</strong>
			<br />
			<textarea name="tresc" cols="50" rows="7"></textarea>
			</td>
		</tr>
		<tr>
			<td>
			<br>
			<strong>Zostaw adres e-mail, jeśli oczekujesz odpowiedzi:</strong>
			<br />
			<input type="text" name="email" size="50" />
			</td>
		</tr>
		<tr>
			<td style="text-align: center;">
				<input type="submit" name="send" value="Wyślij" />
			</td>
		</tr>
	</table>
</form>
<?
}
else{
	?>
	<strong>Dziękujemy za skorzystanie z naszego formularza. Widomość została wysłana poprawnie. Wkrótce możesz spodziewać się odpowiedzi.</strong>
	<?
	$result_m = $this->baza->select("value","cmsconfig","name='ADM_EMAIL'");
	$row_m = $this->baza->row($result_m);
	$do = $row_m[value];
	$od = $_POST['email'];
	$temat = "pytanie ze strony";
//	$temat="=?UTF-8?B?".base64_encode($temat)."?=";
	$wiadomosc = $_POST['tresc']."<br><br>wiadomość od: ".$od;

	$headers .= "Mime-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=ISO-8859-2\r\n";
    $headers .= "Content-Transfer-Encoding: 8bit\r\n";	
	$headers .= "From: <$od>\n";
	$headers .= "X-Mailer: PHP\n"; // program pocztowy
	$headers .= "X-Priority: 1\n"; // ważna wiadomość!
	$headers .= "Return-Path: <$od>\n";	
	
	$temat=iconv("UTF-8","ISO-8859-2", $temat);
    $temat='=?iso-8859-2?B?'.base64_encode($temat).'?=';

    $wiadomosc=iconv("UTF-8","ISO-8859-2", $wiadomosc);

	mail($do,$temat,$wiadomosc,$headers);
}
?>