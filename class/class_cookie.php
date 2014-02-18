<?php
$nazwa_licznika = $_SESSION['session_name']."_licznik";

if(isset($_SESSION[$nazwa_licznika])){
	if ($_SESSION[$nazwa_licznika]<time())
		unset($_SESSION[$nazwa_licznika]);
}
?>