<?
session_start();

header("Content-Type: text/html; charset=utf-8");

include_once("../functions/functions.php");
include_once("../../class/base.php");
include_once("../../class/file.php");
$baza = new baza("../../config/config_db.inc");
$baza->connect();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">

<head>
<title>Panel administracyjny KLIN-CMS</title>
</head>

<body>
<?
if (isset($_GET['wyloguj'])){
	unset($_SESSION['panel_admin_user']);
	unset($_SESSION['panel_admin_user_id']);
	unset($_SESSION['panel_admin_name']);
	unset($_SESSION['panel_admin_user_grupa']);
	unset($_SESSION['fmag']);
	echo "<h4>Zostałeś wylogowany - zapraszamy ponownie</h4>";
	if (isset($_SERVER['HTTPS']))
		$http = "https";
	else 
		$http = "http";		
	$url = $http."://".$_REQUEST[''].$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
	//echo $url;
	reload_page($url,1);
}

if (!isset($_SESSION['panel_admin_user']) and isset($_POST['loguj'])){
	$res = $baza->select("*", "cmsadmuser","loginAdmUser='".$_POST['login']."' and 
	passwordAdmUser='".md5($_POST['haslo'])."'","","");
	if ($baza->size_result($res)>0){
		$row = $baza->row($res);
		$_SESSION['panel_admin_user'] = $_POST['login'];
		$_SESSION['panel_admin_name'] = $row['forenameAdmUser']." ".$row['nameAdmUser'];
		$_SESSION['panel_admin_user_id'] = $row['idAdmUser'];
		$_SESSION['panel_admin_user_grupa'] = $row['admGroupUser'];
	}
}
?>


<center style="vertical-align: top;">
	<?
	include_once('../includes/functions.php');
	if (isset($_POST[zapisz_sekcje])){
		$sek0 = ($_POST['sekcja0']!="") ? $_POST['sekcja0'] : 'NO';
		$sek1 = ($_POST['sekcja1']!="") ? $_POST['sekcja1'] : 'NO';
		$sek2 = ($_POST['sekcja2']!="") ? $_POST['sekcja2'] : 'NO';
		$sek3 = ($_POST['sekcja3']!="") ? $_POST['sekcja3'] : 'NO';
		$sek4 = ($_POST['sekcja4']!="") ? $_POST['sekcja4'] : 'NO';
		$sek5 = ($_POST['sekcja5']!="") ? $_POST['sekcja5'] : 'NO';
		$sek6 = ($_POST['sekcja6']!="") ? $_POST['sekcja6'] : 'NO';
		$sek7 = ($_POST['sekcja7']!="") ? $_POST['sekcja7'] : 'NO';
		$sek8 = ($_POST['sekcja8']!="") ? $_POST['sekcja8'] : 'NO';
		$sek9 = ($_POST['sekcja9']!="") ? $_POST['sekcja9'] : 'NO';
		$sek10 = ($_POST['sekcja10']!="") ? $_POST['sekcja10'] : 'NO';
		$sek11 = ($_POST['sekcja11']!="") ? $_POST['sekcja11'] : 'NO';
		$sek12 = ($_POST['sekcja12']!="") ? $_POST['sekcja12'] : 'NO';
		$sek13 = ($_POST['sekcja13']!="") ? $_POST['sekcja13'] : 'NO';
		$sek14 = ($_POST['sekcja14']!="") ? $_POST['sekcja14'] : 'NO';
		$sek15 = ($_POST['sekcja15']!="") ? $_POST['sekcja15'] : 'NO';
		$sek16 = ($_POST['sekcja16']!="") ? $_POST['sekcja16'] : 'NO';
		$sek17 = ($_POST['sekcja17']!="") ? $_POST['sekcja17'] : 'NO';
		$sek18 = ($_POST['sekcja18']!="") ? $_POST['sekcja18'] : 'NO';
		$sek19 = ($_POST['sekcja19']!="") ? $_POST['sekcja19'] : 'NO';
		$sek20 = ($_POST['sekcja20']!="") ? $_POST['sekcja20'] : 'NO';
		$sek21 = ($_POST['sekcja21']!="") ? $_POST['sekcja21'] : 'NO';
		$sek22 = ($_POST['sekcja22']!="") ? $_POST['sekcja22'] : 'NO';
		$values = "	sekcja0='".$sek0."',
					sekcja1='".$sek1."',
					sekcja2='".$sek2."',
					sekcja3='".$sek3."',
					sekcja4='".$sek4."',
					sekcja5='".$sek5."',
					sekcja6='".$sek6."',
					sekcja7='".$sek7."',
					sekcja8='".$sek8."',
					sekcja9='".$sek9."',
					sekcja10='".$sek10."',
					sekcja11='".$sek11."',
					sekcja12='".$sek12."',
					sekcja13='".$sek13."',
					sekcja14='".$sek14."',
					sekcja15='".$sek15."',
					sekcja16='".$sek16."',
					sekcja17='".$sek17."',
					sekcja18='".$sek18."',
					sekcja19='".$sek19."',
					sekcja20='".$sek20."',
					sekcja21='".$sek21."',
					sekcja22='".$sek22."'";
		$where = "id=".$_GET['podglad'];
		$res = $baza->update("cmstemplate",$values,$where,"");
		if ($res)
			echo "<p>Zmiany zostały zapisane w bazie.</p>";
	}

	$res_sek = $baza->select("*","cmstemplate","id=".$_GET['podglad'],"","");
	$row_sek = $baza->row($res_sek);
	
	$res = $baza->select("*","cmstemplate","id='".$_GET['podglad']."'");
	$row = $baza->row($res);
	$file_path = '../../template/'.$row['file'];
	$fd = fopen($file_path,'r');
	$cont = fread($fd,filesize($file_path));
	fclose($fd);
	
	//podmiana ściażki pliku css
	//$cont = str_replace("css/", "../../css/", $cont);
	/*
	$css = substr($cont, strpos($cont,"css/"));
	$f = explode('"',$css);
	$css = $f[0];
	$path_css = "../../".$css;
	$fd = fopen($path_css,'r');
	$cont_css = fread($fd,filesize($path_css));
	fclose($fd);
		
	$e = strpos($cont, "<link");
	$head = substr($cont,0,$e);
	$foot = substr($cont,$e);
	$cont = $head."<style>".$cont_css."</style>".$foot;
	*/
	
	$cont = substr($cont, strpos($cont,"</head>"));
	$regex = array('/<img[^>]*>/i','/<p([^>]*)>([^<]*)<\/p>/i','/<a([^>]*)>([^<]*)<\/a>/i','/<td([^>]*)>([^<]*)<\/td>/i');
	$mixed = array('','','','<td$1></td>');
	$cont = preg_replace($regex,$mixed,$cont);
	
	for ($j=0; $j<23; $j++){
		$cont = str_replace('<div id="sekcja'.$j.'"></div>',
		'<div id="sekcja'.$j.'" style="width: 100%; height: 100%; border: 1px solid blue; padding:5px; margin:1px;" '.check_section_selected($row_sek,$j,'YES').' ><center>sekcja '.$j.': 
		<input type="radio" name="sekcja'.$j.'" value="YES" '.check_section($row_sek,$j,'YES').'/> TAK 
		<input type="radio" name="sekcja'.$j.'" value="NO" '.check_section($row_sek,$j,'NO').' /> NIE 
		</center></div>',$cont);
	}
	?>
	<form method="POST" id="f" action="">
	<div width="98%" height="95%" >
		<?=$cont?>
	</div>
	<?if (check_rules($baza,'template_section','update')){?>
	<div style="clear: both;">
	<p><input type="submit" name="zapisz_sekcje" value="Zachowaj zmiany" /></p>
	</div>
	<?}?>
	</form>
</center>	

</body>
</html>