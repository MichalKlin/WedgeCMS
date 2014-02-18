<br>
<table class="ramka_contant" border="0" cellpadding="0" cellspacing="0" width="98%">
<tr>
<td class="ramka_title" style="color: #660A00; font-size: 16px;background-image: url('../images/art_title_tlo.jpg');"><?=$NAME?></td>
</tr>
<tr>
<td class="ramka_text">
<br>
<table border="0" cellpadding="0" cellspacing="0" width="450">
	<tr>
		<td>
		<?
		$path = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		$path = substr($path,0,strrpos($path,'/'));
		?>
		<iframe name="moj_iframe1" src="http://<?=$path?>/modules/chat_full/iframe1.php" 
			width="100%" height="300" 
			frameborder="0" scrolling="No" marginwidth="0" marginheight="0" >
		</iframe>
		</td>
	</tr>
</table>
<br>
</td>
</tr>
</table>
<br />