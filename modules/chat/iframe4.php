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
<link rel="StyleSheet" href="../../css/styl.css" type="text/css" />
</head>
<body style="margin:0; padding:0;">
<?
$dzis = date("Y-m-d");
$godz = date("H:i");
$path = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$path = substr($path,0,strrpos($path,'/'));
$strona = "http://".$path."/iframe4.php?user2=$_GET[user2]";

if (isset($_POST[send_chat]) and strlen($_POST[chat_content])>0){
	$r = $baza->insert("modchat", "0,$_SESSION[cms_user_id],'$dzis','$godz','".htmlspecialchars($_POST[chat_content])."',$_GET[user2]","","");
}
if (isset($_POST[remember]))
	$r = $baza->insert("modchat", "0,$_SESSION[cms_user_id],'$dzis','$godz','".htmlspecialchars($_POST[chat_content])."',$_SESSION[cms_user_id]","","");
?>

<table border="0" cellpadding="0" cellspacing="0" width="500" class="iframe">
	<tr>
		<td width="500"> 
		<iframe name="moj_iframe5" src="iframe5.php?refresh=yes&user2=<?=$_GET[user2]?>" width="500" height="300" frameborder="0">
		</iframe>
		</td>

	</tr>
	<?
	if (isset($_SESSION[cms_user_id])){
	?>
	<tr>
		<td>
		<form action="<?=$strona?>" method="POST">
		<input type="text" name="chat_content" size="40" />
		<input type="submit" name="send_chat" value="Wyślij" />
		<input type="submit" name="remember" value="Nie przypominaj" />
		</form>
		</td>
	</tr>
	<?}?>
</table>

<a href="#" onclick="javascript:window.close()">Zamknij okno</a>

</body>
</html>