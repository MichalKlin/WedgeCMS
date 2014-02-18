<?
//mail zapisz do newslettera
function mail_newsletter_zapisz($do,$grupa,$code){
	$serw = "<a href='http://".$_SERVER['HTTP_HOST']."/newsletter.html?add=$code'>http://".$_SERVER['HTTP_HOST']."/newsletter.html?add=$code</a>";
	$mes = "Aby aktywować newsletter: $grupa kliknij w link:
	$serw";

	$temat="Newsletter Kryniczno";
	$wiadomosc="<html><head><title>Zapisanie do newslettera Kryniczno</title></head>
	<body>
	<center><b>Witaj</b></center>
	$mes
	<br>
	<p align=\"right\">Wiadomość automatyczna serwisu kryniczno.pl</p>
	</body> 
	</html>";
	$result = $this->baza->select("value","cmsconfig","name='ADM_EMAIL'");
	$row = $this->baza->row($result);
	$naglowki  = "MIME-Version: 1.0\r\n";
	$naglowki .= "Content-type: text/html; charset=utf-8\r\n";
	$naglowki .= "From: Kryniczno<".$row[value].">\r\n";
	mail($do, $temat, $wiadomosc, $naglowki);
}


function mail_newsletter($do,$title,$grupa,$short,$content){
	$tresc = $short;
	if (strlen($short)<10) {
		$tresc .= substr($content,0,100);
	}
	$tresc .= "...";
	$temat="Newsletter Kryniczno";
	$wiadomosc="<html><head><title>Newsletter Kryniczno</title></head>
	<body>
	<center><b>Witaj</b></center>
	<p>Informujemy, że w serwisie <a href=\"http://kryniczno.pl\">http://kryniczno.pl</a> pojawił się nowy artykuł z grupy: 
	<b>$grupa</b></p>
	<br>
	<b>Tytuł</b>: $title<br>
	<b>Treść</b>: $tresc<br><br>
	więcej przeczytasz w naszym serwisie
	<br>
	<p align=\"right\">Wiadomość automatyczna serwisu ktyniczno.pl</p>
	</body> 
	</html>";
		$result = $this->baza->select("value","cmsconfig","name='ADM_EMAIL'");
		$row = $this->baza->row($result);
	$naglowki  = "MIME-Version: 1.0\r\n";
	$naglowki .= "Content-type: text/html; charset=utf-8\r\n";
	$naglowki .= "From: Kryniczno<".$row[value].">\r\n";
//	$naglowki .= "Bcc: kryniczno@o2.pl\r\n";
	mail($do, $temat, $wiadomosc, $naglowki);
}

function mail_rejestracja($do,$user,$code){
	$temat="Rejestracja Kryniczno";
	$wiadomosc="<html><head><title>Rejestracja Kryniczno</title></head>
	<body>
	<center><b>Witaj</b></center>
	<p>Aby aktywować konto w serwisie <a href=\"http://kryniczno.pl\">http://kryniczno.pl</a>
	należy kliknąc w poniższy link:<br>

	<a href='http://kryniczno.pl/start.html?rejestracja_potw=$code'>
	http://kryniczno.pl/start.html?u=$user&rejestracja_potw=$code
	</a>
	
	<br>
	<p align=\"right\">Wiadomość automatyczna serwisu ktyniczno.pl</p>
	</body> 
	</html>";
		$result = $this->baza->select("value","cmsconfig","name='ADM_EMAIL'");
		$row = $this->baza->row($result);
	$naglowki  = "MIME-Version: 1.0\r\n";
	$naglowki .= "Content-type: text/html; charset=utf-8\r\n";
	$naglowki .= "From:Kryniczno<".$row[value].">\r\n";
//	$naglowki .= "Bcc: kryniczno@o2.pl\r\n";
	mail($do, $temat, $wiadomosc, $naglowki);
}

//mail o nowym uzytkowniku w serwisie
function mail_new_user(){
	$temat="Rejestracja Kryniczno - nowy użytkownik";
	$wiadomosc="<html><head><title>Rejestracja Kryniczno</title></head>
	<body>
	Nowy uzytkownik zarejestrował się i potwierdził aktywację konta.
	<br>
	<p align=\"right\">Wiadomość automatyczna serwisu ktyniczno.pl</p>
	</body> 
	</html>";
		$result = $this->baza->select("value","cmsconfig","name='ADM_EMAIL'");
		$row = $this->baza->row($result);
	$naglowki  = "MIME-Version: 1.0\r\n";
	$naglowki .= "Content-type: text/html; charset=utf-8\r\n";
	$naglowki .= "From: Kryniczno<".$row[value].">\r\n";
//	$naglowki .= "Bcc: kryniczno@o2.pl\r\n";
	mail('kryniczno@o2.pl', $temat, $wiadomosc, $naglowki);
}

//mail do sprawdzenia newsletterów
function mail_newsletter_moje($do,$newslettery){
	$temat="Newslettery Kryniczno - sprawdzenie stanu";
	$wiadomosc="<html><head><title>$temat</title></head>
	<body>
	Zapisany jesteś do następujących newsletterów:
	<br><br>";
	
	for ($i=0; $i<sizeof($newslettery); $i++){
		$wiadomosc .= $newslettery[$i][1]." <a href='http://".$_SERVER['HTTP_HOST']."/newsletter.html?del=".$newslettery[$i][2]."&i=".$newslettery[$i][0]."'>wypisz się z tego newslettera</a><br><br>";
	}
	
	$wiadomosc.="<br>
	<p align=\"right\">Wiadomość automatyczna serwisu ktyniczno.pl</p>
	</body> 
	</html>";
		$result = $this->baza->select("value","cmsconfig","name='ADM_EMAIL'");
		$row = $this->baza->row($result);
	$naglowki  = "MIME-Version: 1.0\r\n";
	$naglowki .= "Content-type: text/html; charset=utf-8\r\n";
	$naglowki .= "From: Kryniczno<".$row[value].">\r\n";
	mail($do, $temat, $wiadomosc, $naglowki);
}
?>