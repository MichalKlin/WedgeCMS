<!--h3>Zarządzanie modułami</h3-->
<?
if (isset($_GET['manage'])){
	$manage = false;
	//co to za moduł
	$r=$baza->select("*","cmsmodule","idModule=".$_GET['manage'],"","");
	$row = $baza->row($r);
	$folder_module = $row['folder'];
	
	//ustawienie uprawnień
	$add_flag = false;
	$edt_flag = false;
	$del_flag = false;
	$r_rul=$baza->select("*","cmsmodulerule,cmsadmuser",
		"cmsmodulerule.module=$_GET[manage]
		and cmsmodulerule.usergroup=cmsadmuser.admGroupUser 
		and cmsadmuser.idAdmUser=$_SESSION[panel_admin_user_id]"
		,"","");
	$ile = $baza->size_result($r_rul);
	for ($i=0; $i<$ile; $i++){
		$row_rul = $baza->row($r_rul);
		if ($row_rul[add_row]=='on') $add_flag = true;
		if ($row_rul[edt_row]=='on') $edt_flag = true;
		if ($row_rul[del_row]=='on') $del_flag = true;
	}

	if (file_exists("../modules/".$folder_module."/admin.php")){
		include_once("../modules/".$folder_module."/admin.php");
	//echo "<p><a href=\"?modules=\" >Powrót do wyboru modułu</a></p>";
	}
	else {
		$manage = true;
		echo "<p>Tym modułem nie można zarządzać!</p>";
		echo "<p><a href=\"?modules=\" >Powrót do wyboru modułu</a></p>";
	}
}

//wyświetlenie rekordów
if (!isset($_GET['dodaj']) and !isset($_GET['usun']) and !isset($_GET['edytuj'])
 and !isset($_GET['manage'])){
	
	$res = $baza->select("*","cmsmodule,cmsmodulerule,cmsadmuser",
		"cmsmodule.active='YES' and cmsmodule.idModule=cmsmodulerule.module 
		and cmsmodulerule.usergroup=cmsadmuser.admGroupUser 
		and cmsadmuser.idAdmUser=$_SESSION[panel_admin_user_id]","ORDER BY cmsmodule.name");
	if (($ile = $baza->size_result($res))>0){
		?>
		<table border="0" cellpadding="2" cellspacing="0" class="tab_edycji">
			<tr>
				<th>LP</th>
				<th>Nazwa</th>
				<th>Operacje</th>
			</tr>
		<?
		for ($i=0; $i<$ile; $i++){
			$row = $baza->row($res);
			$folder_module = $row['folder'];
			$adm_mod = $row[admManage];
			if (file_exists("../modules/".$folder_module."/admin.php") and $adm_mod=='YES'){
				$licznik++;
				$mod = $licznik%2;
				?>
				<tr class="gray<?=$mod;?>">
					<td><?=$licznik;?></td>
					<td><?=$row[name];?></td>
					<td>
						<?if (check_rules($baza,'mod','update')){?>
						<a href="?modules=&manage=<?=$row[idModule];?>">Zarządzaj</a>
						<?}?>
					</td>
				</tr>
				<?
			}
		}
		?>
		</table>
		<?
	}
}
?>