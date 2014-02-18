<?
session_start();

header("Content-Type: text/html; charset=utf-8");

include_once("./functions/functions.php");
include_once("../class/base.php");
include_once("../class/file.php");
$baza = new baza("../config/config_db.inc");
$baza->connect();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">

<head>
<link rel="StyleSheet" href="./css/admin_style.css" type="text/css" />
<script language="javascript" src="../javascript/kalendarz/kalendarz2.js"></script>
<script language="javascript">
function start(){
	document.onmousemove = mysz;
	<?
	if (!isset($_SESSION['panel_admin_user']) and !isset($_POST['loguj'])){
		?>
		document.getElementById('login').focus();
		<?
	}
	?>
}
</script>
<title>Panel administracyjny WeDGe-CMS</title>
</head>

<body onLoad="start()">
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
<table border="0" cellpadding="0" cellspacing="0" width="98%" height="100%">
	<tr>
		<td id="logo_adm" rowspan="2"><img src="images/logo.png" alt="" width="200" /></td>
		<td id="logo_zalogowany">
			<?if (isset($_SESSION['panel_admin_name'])){?>
			zalogowany: <strong><?=$_SESSION['panel_admin_name']?></strong>
			<?}?>
		</td>
	<tr>
		<td id="menu_adm">
		<?
		if (isset($_SESSION['panel_admin_user'])){	
			?>
			<center>
			<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td
					<?if (isset($_GET[wyloguj])) echo "class=\"menu_item2\""; 
					else echo "class=\"menu_item\"";?>
					><a href="?wyloguj=">Wyloguj</a></td>
					<td class="przerwa">&nbsp;</td>
				<?if (check_rules($baza,'site','select')){?>
					<td 
					<?if (isset($_GET[pages])) echo "class=\"menu_item2\""; 
					else echo "class=\"menu_item\"";?>
					><a href="?pages=">Strony</a></td>
				<?}?>
				<?if (check_rules($baza,'mod','select')){?>
					<td class="przerwa">&nbsp;</td>
					<td
					<?if (isset($_GET[modules])) echo "class=\"menu_item2\""; 
					else echo "class=\"menu_item\"";?>
					><a href="?modules=">Moduły</a></td>
				<?}?>
				<?if (check_rules($baza,'schema','select')){?>
					<td class="przerwa">&nbsp;</td>
					<td
					<?if (isset($_GET[schema])) echo "class=\"menu_item2\""; 
					else echo "class=\"menu_item\"";?>
					><a href="?schema=">Schematy</a></td>
					<td class="przerwa">&nbsp;</td>
				<?}?>
				<?if (check_rules($baza,'template','select')){?>
					<td
					<?if (isset($_GET[template])) echo "class=\"menu_item2\""; 
					else echo "class=\"menu_item\"";?>
					><a href="?template=&templ_html=">Wzorce stron</a></td>
					<td class="przerwa">&nbsp;</td>
				<?}?>
				<?if (check_rules($baza,'user','select')){?>
					<td
					<?if (isset($_GET[user])) echo "class=\"menu_item2\""; 
					else echo "class=\"menu_item\"";?>
					><a href="?user=">Użytkownicy</a></td>
					<td class="przerwa">&nbsp;</td>
				<?}?>
				<?if (check_rules($baza,'adm','select')){?>
					<td
					<?if (isset($_GET[admin])) echo "class=\"menu_item2\""; 
					else echo "class=\"menu_item\"";?>
					><a href="?admin=">Administracja</a></td>
				<?}?>
				</tr>
			</table>
			</center>
			<?
		}		
		?>
		</td>
	</tr>
	<tr>
		<td class="pasek_l"></td>
		<td class="pasek_p"></td>
	</tr>
	<tr>
		<td colspan="2" id="content_adm" height="100%">
		<?
		if (!isset($_SESSION['panel_admin_user'])){
			if (!isset($_POST['loguj'])){
				?>
					<form name="zaloguj_form" method="post" action="">
					<center style="margin-top: 100px; padding-bottom:100px" >
					<table border="0" cellpadding="0" cellspacing="10" >
						<tr>
							<td class="menu">Login: </td>
							<td class="left"><input type="text" name="login" id="login" size="40" class="inputLog" /></td>
						</tr>
						<tr>
							<td class="menu">Hasło:</td>
							<td class="left"><input type="password" name="haslo" size="40" class="inputLog" /></td>
						</tr>
						<tr>
							<td colspan="2" class="center"><input type="submit" name="loguj" value="Zaloguj" /></td>
						</tr>
					</table>
					</center>
					</form>
				<?
			}
			else{
				echo "<div style=\"margin-top: 100px; padding-bottom:100px\">";
				if ($_POST['login']=="" or $_POST['haslo']==""){
					echo "<p>Proszę wypełnić oba pola: login i hasło.</p>";
					echo "<p><a href=\"?powrot=yes\">Powrót</a></p>";
				}
				else{
//					if ($_POST['login']!="m" or $_POST['haslo']!="m"){
						echo "<p>Podane login i/lub hasło nie jest poprawne.</p>";
						echo "<p><a href=\"?powrot=yes\">Powrót</a></p>";			
//					}
				}
				echo "</div>";
			}
		}
		if (isset($_SESSION['panel_admin_user'])){
			?>
			<table border="0" cellpadding="5" cellspacing="0" width="100%" height="100%">
				<tr>
					<td>
						<center>
			<?
			
			if (!isset($_GET['user']) and !isset($_GET['grupy']) and !isset($_GET['schema']) and !isset($_GET['admin']) 
				and !isset($_GET['pages']) and !isset($_GET['template']) and !isset($_GET['modules'])){
				?>
				<center style="margin-top: 100px; padding-bottom:100px" >
				<h4>Witamy w centrum zarządzania treścia serwisu internetowego systemu WeDGe-CMS.</h4>
				</center>
				<?
			}
			
			
			if (isset($_GET['modules']) and check_rules($baza,'mod','select')){
				include("includes/modules.php");
			}
			if (isset($_GET['template']) and check_rules($baza,'template','select')){
				include("includes/templates.php");
			}
			if (isset($_GET['user']) and check_rules($baza,'user','select')){
				include("includes/user.php");
			}
			if (isset($_GET['schema']) and check_rules($baza,'schema','select')){
				include("includes/schema.php");
			}
			if (isset($_GET['pages']) and check_rules($baza,'site','select')){
				include("includes/page.php");
			}			
			if (isset($_GET['admin']) and check_rules($baza,'adm','select')){
				include("includes/admin.php");
			}
			?>
			
			</center></td></tr>
			</table>
		<?}?>
		</td>
	</tr>
	<tr>
		<td colspan="2" id="footer_adm"> &copy; Copyright by Michał Klin <a href="http://wedge.org.pl" target="_blank">WeDGe-CMS</a></td>
	</tr>
</table>

</center>	

</body>
</html>