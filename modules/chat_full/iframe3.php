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
<style type="text/css">
a{
	color: yellow;
}
</style>
<meta http-equiv="Content-type" content="text/html; charset=iso-8859-2" />
<link rel="StyleSheet" href="../../css/styl2.css" type="text/css" />

<script language='JavaScript'>
var popCM = false;
function chat_private_start(url,w,h){
	var l = (screen.width-w)/2;
	var t = (screen.height-h)/2;
	var name = 'popup';
	var parm = 'width='+w+',height='+h+',left='+l+',top='+t+',menubar=0,toolbar=0,location=0,status=0,scrollbars=1,resizable=1';
	if (typeof(popCM.document)=="object") popCM.close();
	self.focus();
	popCM = window.open(url,name,parm);
}
</script>
</head>
<body style="margin:0; padding:0; background-color: #240000;">
<?
$dzis = date("Y-m-d");
$path = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$path = substr($path,0,strrpos($path,'/'));
$strona = "http://".$path."/iframe3.php";
$strona2 = "http://".$path."/iframe4.php";
?>
<script language='JavaScript' type='text/JavaScript'>
	window.setTimeout('window.location="<?=$strona?>"',10000);
</script>

<?
$r = $baza->select("*","cmsuser", "active='YES' and id!=1 and id!=$_SESSION[cms_user_id]","ORDER BY forename,name");
if (($il = $baza->size_result($r))>0){
	?>

	<table cellpadding="0" cellspacing="0" width="100%" border="0"  style="margin:0; padding:0; background-color: #600000;" >
	<tr>
		<td>&nbsp;</td>
	</tr>
	<?
	for ($i=0; $i<$il; $i++){
		$row = $baza->row($r);
		?>
		<tr>
			<td style="font-size: 11px; color: #fff;">
			+ <span class="autor">
			<a href="#" onclick="chat_private_start('<?=$strona2?>?user2=<?=$row[id]?>',500,400)">
				<?=$row[login]?>
			</a>
			<?
			//sprawdzenie czy masz prywatną wiadomość
			$r2 = $baza->select("*","modchat", "(author2ModChat=$row[id] and authorModChat=$_SESSION[cms_user_id]) or 
				(author2ModChat=$_SESSION[cms_user_id] and authorModChat=$row[id]) or 
				(author2ModChat=authorModChat and authorModChat=$_SESSION[cms_user_id])","ORDER BY idModChat DESC LIMIT 1","");
			$row2 = $baza->row($r2);
			if($row2[author2ModChat]==$_SESSION[cms_user_id] and $row2[authorModChat]!=$row2[author2ModChat] and $row2[readModChat]=='NO'){
				echo " <span style=\"text-decoration: blink;\">(N $row2[dateModChat])</span>";
			}
			?>
			</span><br>
			</td>
		</tr>
		<?
	}
	?>
	<tr>
		<td>&nbsp;</td>
	</tr>
	</table>
	
	<p style="font-size: 11px; color: #fff; text-align: center;"><b>N - nowa prywatna wiadomość</b></p>
	<?
}
?>

</body>
</html>