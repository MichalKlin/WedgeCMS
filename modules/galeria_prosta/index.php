<?
include_once('modules/galeria_prosta/functions.php');
?>
<br>
<center>
<table class="ramka_contant" border="0" cellpadding="0" cellspacing="0" width="98%">
<tr>
<td class="ramka_text">
<br>
<?php
$path = "http://".$_SERVER['HTTP_HOST'].substr($_SERVER['PHP_SELF'],0,strrpos($_SERVER['PHP_SELF'], "/"))."/".$_GET['page'].".html";
$tabela_galeria="modgallery";
$katalog_galerii="galeria";

echo "<center>";
$result = $this->baza->select("*",$tabela_galeria.",modgalleryschema","activeModGallery=\"YES\" 
		and modgallery.group=modgalleryschema.gallery_group and modgalleryschema.schema=$SCHEMA_MODULE",
	"ORDER BY createdModGallery DESC","");
$size_result = $this->baza->size_result($result);
if ($size_result>0){
	$row = $this->baza->row($result);
	$g = $row[idModGallery];
if(!isset($_GET[g])){
	echo "<div id=\"gallery_list\">";
	gallery_list($this->baza, $path, $tabela_galeria, $tabela_zdjec, $session,$katalog_galerii,$SCHEMA_MODULE);
	echo "</div>";
}
else{
	echo "<div id=\"gallery\">";
	if(!isset($_GET[p])){
		gallery($this->baza, $path, $tabela_galeria, $g,$katalog_galerii,$SCHEMA_MODULE);
		?><br><?
		nawigacja_galeria();
	}
	else{
		photo($this->baza, $path, $tabela_galeria, $g, $_GET[p],$katalog_galerii);
		nawigacja_galeria($g);
	}
	echo "</div>";
}
}
echo "</center>";
	
?>
<br>
</td>
</tr>
</table>
</center>
<br />
