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
if ((!isset($_POST[zapisz]) and $_GET[refresh]=='yes') or 
	(isset($_POST[zapisz]) and $_POST[refresh]=='on'))
		$refresh = 'yes';
	else
		$refresh = 'no';	

$dzis = date("Y-m-d");
$path = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$path = substr($path,0,strrpos($path,'/'));
$strona = "http://".$path."/iframe5.php?refresh=$refresh&user2=$_GET[user2]";

if ($refresh=='yes'){
?>
<script language='JavaScript' type='text/JavaScript'>
	window.setTimeout('window.location="<?=$strona?>"',10000);
</script>
<?
}

$r = $baza->select("*","modchat", "(author2ModChat=$_GET[user2] and authorModChat=$_SESSION[cms_user_id]) or 
	(author2ModChat=$_SESSION[cms_user_id] and authorModChat=$_GET[user2])","ORDER BY idModChat DESC LIMIT 50","");
if (($il = $baza->size_result($r))>0){
	?>
	<form method="POST" action="">
	<input type="checkbox" name="refresh" <?if($refresh=='yes') echo "checked";?> /> automatyczne odświeżanie
	<input type="submit" name="zapisz" value="zapisz" />
	</form>
	<table cellpadding="0" cellspacing="0" width="480" border="0" class="iframe">
	<?
	for ($i=0; $i<$il; $i++){
		$row = $baza->row($r);
		$r2 = $baza->select("*","cmsuser", "id=$row[authorModChat]");
		$row2 = $baza->row($r2);
		$l = $i%2;
		?>
		<tr class="szare<?=$l?>">
			<td>
			<span class="autor"><u>(<?=$row2[name]?> <?=$row2[forename]?>)</u> [<?=$row[dateModChat]?> <?=$row[timeModChat]?>]</span><br><?=$row[contentModChat]?>
			</td>
		</tr>
		<?
	}
	?>
	</table>
	<?
}
?>

</body>
</html>