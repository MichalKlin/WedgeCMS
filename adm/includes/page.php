<?include_once("../class/structure.php");?>
<?
if (isset($_POST['dodaj'])){
	if (strlen(trim($_POST['page_name']))>0){
		$columns = "(idPage,cmspage.schema,htmlName,name,
		defaultPage, over,orderPage,keywords,
		title,description,active,counter,url,icon)";
		$values = "0,".$_POST['schema'].", '".htmlspecialchars($_POST['page_html'])."',
		 '".htmlspecialchars($_POST['page_name'])."',
			'NO',0,'".htmlspecialchars($_POST['page_order'])."',
			'','','','YES',0,'".htmlspecialchars($_POST['page_url'])."',''";
		$res = $baza->insert("cmspage",$values,$columns);
		
		$id_page = $baza->last_insert_id();
		
		$res = $baza->select("idGroupUser","cmsgroupuser","defaultGroupUser='YES'");
		if (($ile=$baza->size_result($res))>0){
			for ($j=0; $j<$ile; $j++){
				$roow = $baza->row($res);
				$id_grupy = $roow['idGroupUser'];
				
				$value = "0,$id_grupy,$id_page";
				$r = $baza->insert("cmspageusergroup",$value);
			}
		}
	}
}
if (isset($_POST['zapisz'])){
	if (strlen(trim($_POST['page_name']))>0 and strlen(trim($_POST['page_html']))>0){
		$columns = "(idPage,cmspage.schema,htmlName,name,
		defaultPage, over,orderPage,keywords,
		title,description,active,counter)";
		$values = "cmspage.schema=".$_POST['schema'].",
		 			htmlName='".htmlspecialchars($_POST['page_html'])."',
		 			name='".htmlspecialchars($_POST['page_name'])."',
		 			defaultPage='".$_POST['default']."',
		 			over=".$_POST['over'].",
					orderPage='".htmlspecialchars($_POST['page_order'])."',
					keywords='".htmlspecialchars($_POST['keywords'])."',
					title='".htmlspecialchars($_POST['title'])."',
					description='".htmlspecialchars($_POST['description'])."',
					url='".htmlspecialchars($_POST['page_url'])."',
					active='".htmlspecialchars($_POST['active'])."'";
		$where = "idPage=".$_GET['p'];
		$res = $baza->update("cmspage",$values,$where,"");
	}
}
if (isset($_POST['dodaj_podmenu'])){
	if (strlen(trim($_POST['page_name']))>0 and strlen(trim($_POST['page_html']))>0){
		$columns = "(idPage,cmspage.schema,htmlName,name,
		defaultPage, over,orderPage,keywords,
		title,description,active,counter,url,icon)";
		$values = "0,".$_POST['schema'].", '".htmlspecialchars($_POST['page_html'])."',
			'".htmlspecialchars($_POST['page_name'])."',
			'NO',".$_GET['p'].",'".htmlspecialchars($_POST['page_order'])."',
			'','','','YES',0, '".htmlspecialchars($_POST['page_url'])."',''";
		$res = $baza->insert("cmspage",$values,$columns);
	}

	$id_page = $baza->last_insert_id();
	
	$res = $baza->select("idGroupUser","cmsgroupuser","defaultGroupUser='YES'");
	if (($ile=$baza->size_result($res))>0){
		for ($j=0; $j<$ile; $j++){
			$roow = $baza->row($res);
			$id_grupy = $roow['idGroupUser'];
			
			$value = "0,$id_grupy,$id_page";
			$r = $baza->insert("cmspageusergroup",$value);
		}
	}
}
// zapisanie powiązań strona - grupa użytkowników
if (isset($_POST['grupy_user'])){
	$r = $baza->select("*","cmsgroupuser","active='YES'");
	if (($ile=$baza->size_result($r))>0){
		//usunięcie wszystkich wpisów
		$rrr = $baza->delete("cmspageusergroup","page=".$_GET['p']);
		
		for ($i=0; $i<$ile; $i++){
			$roow = $baza->row($r);			
			//dodanie tylko zaznaczonych
			if ($_POST['group'.$i]=="on"){
				$columns = "(id,page,groupUser)";
				$values = "0,".$_GET['p'].",".$roow[idGroupUser];
				$rr = $baza->insert("cmspageusergroup",$values,$columns);
			}
		}
	}
}
if (isset($_GET['usun'])){
	$where = "idPage=".$_GET['usun'];
	$res = $baza->delete("cmspage",$where);
	usunWDol($baza,$_GET['usun']);
}

?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
	<td id="page_drzewko" nowrap>
	<h3>Struktura serwisu</h3>
	<?
	//drzewko
	$menu = new structure($baza);

	include_once("functions/struktura.php");
	struktura($menu);	
	?>
	<br />
	
	<?if (check_rules($baza,'site','insert')){?>
	<span>[<a href="?pages=&katalog=">Nowa strona</a>]</span>
	<?}?>
	<center>
	<span>[N] - strona nieaktywna</span>
	</center>
	</td>
	
	<td class="left_border">
	<?
	if (isset($_GET['p'])){
		$res = $baza->select("*","cmspage","idPage=".$_GET['p'],"","");
		if ($baza->size_result($res)>0){
			$row = $baza->row($res);
		}
	}
	include_once("page_menu.php");
	?>
	</td>
</tr>
</table>	
<?
/*	

<tr>
	<td colspan="2">

	</td>
</tr>
</table>
<?
*/
function usunWDol($baza,$over){
	$r = $baza->select("idPage","cmspage","over=".$over,"","");
	if (($ile=$baza->size_result($r))>0){
		for ($i=0; $i<$ile; $i++){
			$row = $baza->row($r);
			
			$where = "idPage=".$row[idPage];
			$res = $baza->delete("cmspage",$where,"");
			usunWDol($baza,$row[idPage]);
		}
	}
}
?>