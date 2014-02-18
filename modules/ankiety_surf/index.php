<script language='JavaScript'>
var popCM = false;
function popup(url,w,h){
	var l = (screen.width-w)/2;
	var t = (screen.height-h)/2;
	var name = 'popup';
	var parm = 'width='+w+',height='+h+',left='+l+',top='+t+',menubar=0,toolbar=0,location=0,status=0,scrollbars=1,resizable=1';
	if (typeof(popCM.document)=="object") popCM.close();
	self.focus();
	popCM = window.open(url,name,parm);
}
</script>
							
<span class="wyroznione">Badanie zadowolenie klienta:</span>
<br />
Celem ankiety jest sprawdzenie poziomu zadowolenia klientów StartUp/Surfland Deweloper System z dotychczasowej obsługi oraz produktów firmy.
Opracowanie poniższej ankiety pozwoli nam na usprawnienia obsługi oraz udoskonalenie produktów, a także jeszcze lepsze przystosowanie ich do potrzeb klientów.
<br />
<center><span class="wyroznione">
<?$url = "http://".$_SERVER['HTTP_HOST']."/ankieter/ankietuj.php"."?mod=badania&ankietuj=67";?>
<a href="#" onclick="popup('<?=$url?>',600,400)" class="wyroznione" style="font-size: 22px;">ankietuj</a>
</span></center>
<br><br>