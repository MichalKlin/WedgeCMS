<?php

if (isset($_SESSION['cms_user_id'])){
	$zalogowany_user = $_SESSION['cms_user_id'];
	$result = $this->baza->select("*","cmsuser", "id=$zalogowany_user");
	$row = $this->baza->row($result);
	
	echo "<p>Zalogowany: <u>".$row['forename']." ".$row['name']."</u></p>";
	if (!isset($_GET['wyloguj'])){
		formularz_wyl();
	}
	else{
		wyloguj();
		reload_page_wyl($_GET['page'].".html",0);
	}
}

######################################################################
function formularz_wyl(){
	echo "<p><a href=\"?wyloguj=\">WYLOGUJ</a></p>";
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

?>
