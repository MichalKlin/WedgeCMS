<?php
if (!isset($_SESSION['cms_user_id'])){
	if (!isset($_POST['odpowiedz_log'])) { 
		formularz();
		if (isset($_POST['przypomnienie'])){
		}
	}
	if (isset($_POST['odpowiedz_log'])) { 
		$ok = logowanie($this->baza);
		if ($ok==false){
			formularz();
		}
	}
	$result2 = $this->baza->select("*","cmsmodule", "idModule=$MODULE","","");
	$row2 = $this->baza->row($result2);
	formularz_rejestruj('start.html');//$row2[pathActionCmsModule]
}

?>
<center>
<?
if (isset($_SESSION['cms_user_id'])){
	$zalogowany_user = $_SESSION['cms_user_id'];
	$result = $this->baza->select("*","cmsuser", "id=$zalogowany_user");
	$row = $this->baza->row($result);
	
	echo "<p>Zalogowany:<br><strong>".$row['forename']." ".$row['name']."</strong></p>";
	
	// and !isset($_GET['edytuj_konto'])
	if (!isset($_GET['wyloguj'])){
		formularz_wyl();
		
		$result2 = $this->baza->select("*","cmsmodule", "idModule=$MODULE","","");
		$row2 = $this->baza->row($result2);
		formularz_edyt_konto($row2[pathActionCmsModule]);
	}
	
	if (isset($_GET['wyloguj'])){
		wyloguj();
		reload_page_wyl($_GET['page'].".html",0);
	}
}
?>
</center>
<?
######################################################################
function formularz_wyl(){
	echo "&nbsp;<span><a href=\"?wyloguj=\">Wyloguj</a></span>&nbsp;";
}
######################################################################
function formularz_edyt_konto($path){
	echo "&nbsp;<span><a href=\"$path\">Edytuj dane</a></span>&nbsp;";
}
######################################################################
function formularz_rejestruj($path){
	echo "&nbsp;<span><a href=\"$path?rejestracja=\">Rejestracja</a></span>&nbsp;";
}
######################################################################
function wyloguj(){
	unset($_SESSION['cms_user_id']);
}
######################################################################
function reload_page_wyl($url, $time){
	echo "<script language='JavaScript' type='text/JavaScript'>
	window.setTimeout('window.location=\"$url\"',$time);
	</script>";
}
######################################################################
function formularz(){
	//podstawowy formularz logowania
	echo "<h5 style=\"margin:0; vertical-align:middle;\"><center>
	<form name=\"pytanie_log\" id=\"pytanie_log\" method=\"post\" action=\"$_SERVER[REQUEST_URI]\">
		<table>
		<tr><td align=\"right\">login:</td><td align=\"left\"><input type=\"text\" name=\"login\" size=\"15\" /></td></tr>
		<tr><td align=\"right\">hasło:</td><td align=\"left\"><input type=\"password\" name=\"haslo\" size=\"15\" /></td></tr>
		<tr><td colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"odpowiedz_log\" value=\"Loguj\" />&nbsp;
			<!--input type=\"submit\" name=\"przypomnienie\" value=\"Przypomnij\" /--></td></tr>
		</table>
	</form></center></h5>";
}
######################################################################
function logowanie($baza){
	if($_POST['login']!="" and $_POST['haslo']!=""){
		
		$login = $_POST['login'];

		//szyfrowanie hasa funkcj crypt i powizanie go z dwoma znakami z loginu aby na pewno by unikatowy w bazie danych
 		$haslo = md5($_POST['haslo']);         		

  		//sprawdzenie czy jest ktoś w bazie o takich danych
		$result = $baza->select("*","cmsuser", "login='$login' and password='$haslo' and active='YES' ","","");
		$size_result = $baza->size_result($result);
		
		if ($size_result==1){
			$row = $baza->row($result);
			$_SESSION['cms_user_id']=$row['id'];
			$_SESSION['cms_user_name']=$row['name']." ".$row['forename'];
			$_SESSION["cms_user_gr"]=$row['groupUser'];
			reload_page_l($_GET['page'].".html",0);
    	}
    	else{
        	echo "<h5>Niepoprawny login lub hasło.</h5>";
        	return false;
    	}
    }
    else{
        echo "<h5>Nie wypełniono wszystkich pól.</h5>";
        return false;
    }
}
######################################################################
function reload_page_l($url, $time){
	echo "<script language='JavaScript' type='text/JavaScript'>
	window.setTimeout('window.location=\"$url\"',$time);
	</script>";
}
?>
