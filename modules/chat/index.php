<style>
#iframe {
	overflow-x: hidden;
	overflow-y: scroll;
} 
</style>
<table border="0" cellpadding="0" cellspacing="0" width="150">
	<tr>
		<td>
		<?
		if (isset($_SESSION[cms_user_id])){
			$masz_wiadomosc = "";
			$r = $this->baza->select("*","cmsuser", "active='YES' and id!=1 and id!=$_SESSION[cms_user_id]","");
			if (($il = $this->baza->size_result($r))>0){
				for ($i=0; $i<$il; $i++){
					$row = $this->baza->row($r);
					$r2 = $this->baza->select("*","modchat", "(author2ModChat=$row[id] and authorModChat=$_SESSION[cms_user_id]) or 
					(author2ModChat=$_SESSION[cms_user_id] and authorModChat=$row[id]) or 
					(author2ModChat=authorModChat and authorModChat=$_SESSION[cms_user_id])","ORDER BY idModChat DESC LIMIT 1","");
					$row2 = $this->baza->row($r2);
					if($row2[author2ModChat]==$_SESSION[cms_user_id] and $row2[authorModChat]!=$row2[author2ModChat] and $row2[readModChat]=='NO'){
						$masz_wiadomosc .= " <b style=\"color: yellow;\">".$row[login]."</b>,";
						$licz++;
					}
				}
			}
			if ($licz>0){
				echo "<p style=\"font-size: 11px; color: #fff;\">Masz prywatnych wiadomości: <b style=\"color: yellow;\">$licz</b> od: ".$masz_wiadomosc;
			} 
		}

		$path = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		$path = substr($path,0,strrpos($path,'/'));
		?>
		<iframe name="moj_iframe1" src="http://<?=$path?>/modules/chat/iframe1.php" 
			width="150" height="170" id="iframe"
			frameborder="0" scrolling="No" marginwidth="0" marginheight="0" >
		</iframe>
		</td>
	</tr>
</table>