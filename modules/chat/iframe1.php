<?
session_start();
header("Content-Type: text/html; charset=utf-8");

include_once("../../class/base.php");
include_once("../../class/file.php");
$baza = new baza("../../config/config_db.inc");
$baza->connect();
?>

<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />

<link rel="StyleSheet" href="../../css/styl.css" type="text/css" />
</head>
<body style="margin:0; padding:0; background: #240000;">
<?
$dzis = date("Y-m-d");
$godz = date("H:i");
$path = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$path = substr($path,0,strrpos($path,'/'));
$strona = "http://".$path."/iframe1.php";

if (isset($_POST[send_chat]) and strlen($_POST[chat_content])>0){
	$r = $baza->insert("modchat", "0,$_SESSION[cms_user_id],'$dzis','$godz','".htmlspecialchars($_POST[chat_content])."',0,'NO'","","");
}

//sprawdzenie czy ma dostęp
$dostep = false;
if (isset($_SESSION[cms_user_id])){
	$r = $baza->select("*","cmsuser", "id=$_SESSION[cms_user_id] and active='YES'");
	if ($baza->size_result($r)>0)
		$dostep = true;
}
?>

<table border="0" cellpadding="0" cellspacing="0" width="150" class="iframe">
	<?
	if ($dostep){
	?>
	<tr>
		<td colspan="1">
		<form action="<?=$strona?>" method="POST">
		<input type="text" name="chat_content" size="16" />
		<input type="submit" name="send_chat" value="Wyślij" />
		</form>
		</td>
	</tr>
	<?}?>
	<tr>
		<td width="130">
		<iframe name="moj_iframe2" src="iframe2.php?refresh=yes" width="150" height="230" frameborder="0"
	frameborder="0" marginheight="0" marginwidth="0" scrolling="auto">
		</iframe>
		</td>
		<?
		/*
		if ($dostep){
		?>
		<td width="">
		Chat prywatny z:<br>
		<iframe name="moj_iframe3" src="iframe3.php" width="" height="" frameborder="0">
		</iframe>
		</td>
		<?
		}
		*/
		?>
	</tr>
</table>

</body>
</html>