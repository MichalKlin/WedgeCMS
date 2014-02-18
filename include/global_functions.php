<?
function reload_page($url, $time){
	if($url == '' || $url == null){
		$url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; 
	}
	echo "<script language='JavaScript' type='text/JavaScript'>
	window.setTimeout('window.location=\"$url\"',$time);
	</script>";
}
?>