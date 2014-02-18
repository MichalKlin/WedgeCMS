<?

function moduly_w_sekcji_schema($baza,$row,$sekcja,$id_schema){
	$nazwa_sek = "sekcja".$sekcja."";

	$return = "";
	
	if ($row[$nazwa_sek]=='YES'){
		$return = "<div style=\"border: 0px solid red; width: 100%; height:100%;\">";
		$res = $baza->select("*","cmsschemamodule,cmsmodule","cmsschemamodule.schema=$id_schema AND cmsschemamodule.sekcja=$sekcja AND cmsschemamodule.module=idModule","ORDER BY cmsschemamodule.order","");
		if (($ile=$baza->size_result($res))>0){
			$return .= "
			<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"margin: 1px;\">";
			
				for ($i=0; $i<$ile; $i++){
					$rrow = $baza->row($res);
					$licznik++;
			$return .="
				<tr>
					<td>".$licznik.". </td>
					<td style=\"text-align: left;\">".$rrow[name]."&nbsp;[".$rrow[order]."]&nbsp;</td>
					<td nowrap>"; 
					if (check_rules($baza,'schema_modules','delete')){
						$return .="<a href=\"?schema=&modul=".$_GET['modul']."&usun_m=".$rrow[id]."\">usuń</a> | ";
					}
					if (check_rules($baza,'schema_modules','update')){
						$return .="<a href=\"?schema=&modul=".$_GET['modul']."&modyf_m=".$rrow[id]."\">konfiguruj</a>";
					}
					$return .="</td>
				</tr>";
				}
			$return .="</table>";
		}
		if (check_rules($baza,'schema_modules','insert')){
			$return .="<p><a href=\"?schema=&modul=".$_GET['modul']."&dodaj_m=$sekcja\">dodaj moduł</a></p>";
		}
		$return .="</div>";
	}
	return $return;
}

function moduly_w_sekcji_page($baza,$row,$sekcja,$id_schema){
	$nazwa_sek = "sekcja".$sekcja;

	if ($row[$nazwa_sek]=='YES'){
		$return .="<div style=\"border: 0px solid red; width: 100%; height:100%; margin-top:0px;\">";
		$res = $baza->select("*","cmsschemamodule,cmsmodule","cmsschemamodule.schema=$id_schema AND sekcja=$sekcja AND module=cmsmodule.idModule","ORDER BY cmsschemamodule.order","");
		if (($ile=$baza->size_result($res))>0){
			$return .="
			<table border=\"0\" cellpadding=\"2\" cellspacing=\"0\">";
				for ($i=0; $i<$ile; $i++){
					$rrow = $baza->row($res);
					$licznik++;
			$return .="
				<tr>
					<td>".$licznik.". </td>
					<td>".$rrow[name]."&nbsp;</td>
					<td nowrap>";
					
					$folder_module = $rrow['folder'];
					if (file_exists("../modules/".$folder_module."/admin.php") and $rrow[siteManage]=='YES'){
						$res_mod = $baza->select("*","cmsmodule,cmsmodulerule,cmsadmuser",
							"cmsmodule.active='YES' and cmsmodule.idModule=cmsmodulerule.module 
							and cmsmodulerule.usergroup=cmsadmuser.admGroupUser 
							and cmsadmuser.idadmuser=$_SESSION[panel_admin_user_id] 
							and cmsmodule.idModule=$rrow[idModule]","");
						if ($baza->size_result($res_mod)>0){
							$return .="[ <a href=\"?pages=&p=".$_GET['p']."&modul=&manage=".$rrow[id]."\">zarządzaj</a> ]";
						}
					}
					$return .="</td>
				</tr>";
				}
			$return .="</table>";
		}
		$return .="</div>";
	}
	return $return;
}

function moduly_w_sekcji_manage($baza,$row,$sekcja,$id_schema){
	$nazwa_sek = "sekcja".$sekcja."";

	if ($row[$nazwa_sek]=='YES'){
		echo "<div style=\"border: 1px solid red; width: 99%; height:100%; margin-top:1px;\">";
		$res = $baza->select("*","cmsschemamodule,cmsmodule","cmsschemamodule.schema=$id_schema AND cmsschemamodule.sekcja=$sekcja AND cmsschemamodule.module=cmsmodule.idModule","ORDER BY cmsschemamodule.order","");
		if (($ile=$baza->size_result($res))>0){
			?>
			<table border="0" cellpadding="2" cellspacing="0">
			<?
				for ($i=0; $i<$ile; $i++){
					$rrow = $baza->row($res);
					$licznik++;
			?>
				<tr>
					<td><?=$licznik;?>. </td>
					<td><?=$rrow[name];?>&nbsp;</td>
					<td nowrap>
					<?
					$folder_module = $rrow['folder'];
					if (file_exists("../modules/".$folder_module."/admin.php") and $rrow[siteManage]=='YES'){?> 
						<?if (check_rules($baza,'site_module','update')){?>
							[ <a href="?pages=&p=<?=$_GET['p'];?>&modul=&manage=<?=$rrow[id];?>">zarządzaj!</a> ]
						<?}?>
					<?}?>
					</td>
				</tr>
			<?
				}
			?>
			</table>
			<?
		}
		echo "</div>";
	}
}

function moduly_w_sekcji($baza,$row,$sekcja,$id_schema){
	$nazwa_sek = "sekcja".$sekcja."";

	if ($row[$nazwa_sek]=='YES'){
		echo "<div style=\"border: 1px solid red; width: 99%; height:100%;\">";
		$res = $baza->select("*","cmsschemamodule,cmsmodule","cmsschemamodule.schema=$id_schema AND cmsschemamodule.sekcja=$sekcja AND cmsschemamodule.module=cmsmodule.idModule","ORDER BY cmsschemamodule.order","");
		if (($ile=$baza->size_result($res))>0){
			?>
			<table border="0" cellpadding="0" cellspacing="0" style="margin: 3px;">
			<?
				for ($i=0; $i<$ile; $i++){
					$rrow = $baza->row($res);
					$licznik++;
			?>
				<tr>
					<td><?=$licznik;?>. </td>
					<td><?=$rrow[name];?>&nbsp;[<?=$rrow[order];?>]&nbsp;</td>
					<td nowrap> 
					<?if (check_rules($baza,'schema_modules','delete')){?>
					<a href="?schema=&modul=<?=$_GET['modul'];?>&usun_m=<?=$rrow[id];?>">usuń</a> | 
					<?
					}
					if (check_rules($baza,'schema_modules','update')){
					?>
					<a href="?schema=&modul=<?=$_GET['modul'];?>&modyf_m=<?=$rrow[id];?>">konfiguruj</a>
					<?}?>
					</td>
				</tr>
			<?
				}
			?>
			</table>
			<?
		}
		if (check_rules($baza,'schema_modules','insert')){
			echo "<p><a href=\"?schema=&modul=".$_GET['modul']."&dodaj_m=$sekcja\">dodaj moduł</a></p>";
		}
		echo "</div>";
	}
}
?>