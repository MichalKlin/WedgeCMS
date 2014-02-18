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
<!--style>
body{
scrollbar-3dlight-color:tan;
scrollbar-arrow-color:yellow;
scrollbar-base-color:black;
scrollbar-dark-shadow-color:#000000;
scrollbar-face-color:#a70301;
scrollbar-highlight-color:snow;
scrollbar-shadow-color:lightred
}
</style-->
<link rel="StyleSheet" href="../../css/styl.css" type="text/css" />
<script>
function send(obj){
	document.getElementById('zapisz').click();
}
</script>
</head>
<body style="margin:0; padding:0; background: #240000;">
<?

if(isset($_POST[zapisz])){
	if($_SESSION['refresh'] == 'no'){
		$_SESSION['refresh'] = 'yes';
	}
	else{
		$_SESSION['refresh'] = 'no';
	}
}
 

$dzis = date("Y-m-d");
$path = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$path = substr($path,0,strrpos($path,'/'));
$strona = "http://".$path."/iframe2.php?refresh=$refresh";

if ($_SESSION['refresh']=='yes'){
?>
<script language='JavaScript' type='text/JavaScript'>
	window.setTimeout('window.location="<?=$strona?>"',10000);
</script>
<?
}
$r = $baza->select("*","modchat", "author2ModChat=0","ORDER BY idModChat DESC LIMIT 15");
if (($il = $baza->size_result($r))>0){
	?>
	<form method="POST" action="" style="color: #fff;" class="iframe" onclick="send(this.form);">
	<input class="checkbox" type="checkbox" name="refresh" <?if($_SESSION['refresh']=='yes') echo "checked";?> /> auto odświeżanie
	<input type="submit" id="zapisz" name="zapisz" value="zapisz" style="display: none;" />
	</form>
	<table cellpadding="0" cellspacing="0" width="" border="0" class="iframe">
	<?
	for ($i=0; $i<$il; $i++){
		$row = $baza->row($r);
		$r2 = $baza->select("*","cmsuser", "id=$row[authorModChat]");
		$row2 = $baza->row($r2);
		$l = $i%2;
		$dat_mies = substr($row[dateModChat],5,5);
		?>
		<tr class="szare<?=$l?>">
			<td>
			<?
			if($row2[id]==$_SESSION[cms_user_id]){
	         ?>
	         <span style="font-size: 11px; color: orange; font-weight: bold;"><strong><?=$row2[login]?></strong>
	         <?if ($row[dateModChat]==date('Y-m-d')) echo "[".$row[timeModChat]."]";?></span>
	         <?
			}
			else{
	         ?>
	         <span class="autor"><strong><?=$row2[login]?></strong>
	         <?if ($row[dateModChat]==date('Y-m-d')) echo "[".$row[timeModChat]."]";?></span>
	         <?
			}
			?>
			<br>
			<span class="content"><?=$row[contentModChat]?></span>
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