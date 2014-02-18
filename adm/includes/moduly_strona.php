<h3>Zarządzanie modułami na stronie:</h3>
<?
if (isset($_GET['manage'])){
	$manage = false;
	//co to za moduł
	$r=$baza->select("*","cmsmodule,cmsschemamodule","cmsschemamodule.id=".$_GET['manage']." AND idModule=cmsschemamodule.module","","");
	$row = $baza->row($r);
	$folder_module = $row['folder'];
	if (file_exists("../modules/".$folder_module."/admin.php")){
		include_once("../modules/".$folder_module."/admin.php");
		echo "<p><a href=\"?pages=&p=".$_GET['p']."&modul=\" >Powrót</a></p>";
	}
	else {
		$manage = true;
		echo "<p>Tym modułem nie można zarządzać!</p>";
	}
}
if (!isset($_GET['manage']) or $manage==true){
	include_once("functions/modul_w_sekcji.php");		
	include_once('includes/functions.php');

	$r=$baza->select("cmspage.schema as schemat","cmspage","idPage=".$_GET['p'],"","");
	$row = $baza->row($r);
	$id_schema = $row['schemat'];
	
	$r=$baza->select("*","cmstemplate,cmsschema","cmsschema.template=cmstemplate.id AND cmsschema.id=".$id_schema,"","");
	$row = $baza->row($r);
	//echo $roow[name];
	
	$res_sek = $baza->select("*","cmstemplate","id=".$row['template'],"","");
	$row_sek = $baza->row($res_sek);
	
	$res = $baza->select("*","cmstemplate","id='".$row['template']."'");
	$row = $baza->row($res);
	$file_path = '../template/'.$row['file'];
	$fd = fopen($file_path,'r');
	$cont = fread($fd,filesize($file_path));
	fclose($fd);
	
	$cont = substr($cont, strpos("</head>",$con));
	$regex = array('/<img[^>]*>/i','/<p([^>]*)>([^<]*)<\/p>/i','/<a([^>]*)>([^<]*)<\/a>/i','/<td([^>]*)>([^<]*)<\/td>/i');
	$mixed = array('','','','<td$1></td>');
	$cont = preg_replace($regex,$mixed,$cont);
	
	for ($j=0; $j<23; $j++){
		$cont = str_replace('<div id="sekcja'.$j.'"></div>',
		'<div id="sekcja'.$j.'" style="border: 1px solid blue; padding:5px; margin:1px; height: 100%;" '.check_section_selected($row_sek,$j,'YES').' ><center>
		'.moduly_w_sekcji_page($baza,$row,$j,$id_schema).' 
		</center></div>',$cont);
	}
	?>
	<form method="POST" id="f" action="">
	<div width="98%" height="100%">
		<?=$cont?>
	</div>
	<?
}
?>