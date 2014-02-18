<?
if (check_rules($baza,'sitemap','select')){
?>
<center style="padding: 5px;">
<form method="POST" action="">
<input type="submit" name="generuj_sitemap" value="Generuj sitemap" />
</form>

<br>
<hr>
<h3>Podgląd Sitemap</h3>
<textarea name="sitemap" cols="100" rows="20">
<?
$file_name = "../sitemap.xml";
$f = fopen($file_name,"r");
echo fread ($f, filesize ($file_name));
fclose($f);
?>
</textarea>
</center>
<?
}
else{
	echo "Brak dostępu!";
}
?>
<?
if (isset($_POST['generuj_sitemap'])){
	include_once("../class/structure.php");
	
	$menu = new structure($baza);

	$content = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n
<urlset xmlns=\"http://www.google.com/schemas/sitemap/0.84\">\n";
	$content .= struktura($menu);	
	$content .= "</urlset>";
	
//	echo $content;
	
	$file_name = "../sitemap.xml";
	$f = fopen($file_name,"w+");
	fwrite($f,$content);
	fclose($f);	
}


function struktura($menu,$poziom=0){
	for ($i=0; $i<sizeof($menu->list); $i++){
		//tylko aktywne
		if ($menu->list[$i]->getActive()!='NO'){
			$content.="<url>\n";
	    	$content.="<loc>http://".$_SERVER['HTTP_HOST']."/".$menu->list[$i]->getHtmlName().".html</loc>\n";
	    	$content.="<lastmod>".date("Y-m-d")."</lastmod>\n";
	    	$content.="<changefreq>weekly</changefreq>\n";
	    	$content.="<priority>0.5</priority>\n";
			$content.="</url>\n";
					
			if (($podmen = $menu->list[$i]->getPodmenu())!=null){
				$poziom++;
				$content .= struktura($podmen,$poziom);
			}
		}
	}
	return $content;
}
?>