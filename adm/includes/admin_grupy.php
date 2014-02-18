<h3>Zarządzanie grupami użytkowników</h3>
<?
if (isset($_GET['dodaj'])){
	if (!isset($_POST['dodaj'])){
		?>
			<form method="POST" id="f" action="">
			<table border="0" cellpadding="0" cellspacing="0" class="tab_edycji">
				<tr>
					<th>Nazwa grupy:</th>
					<td><input type="text" name="name" size="30" /></td>
				</tr>
				<tr>
					<th>Aktywność:</th>
					<td><input type="radio" name="active" value="YES" checked /> TAK 
						<input type="radio" name="active" value="NO" /> NIE 
					</td>
				</tr>
				<tr>
					<th>Domyślny:</th>
					<td><input type="radio" name="default" value="YES" /> TAK 
						<input type="radio" name="default" value="NO" checked /> NIE 
					</td>
				</tr>
			</table>
			<p><input type="submit" name="dodaj" value="Dodaj" /></p>
			</form>
		<?
	}
	else{
		if (strlen(trim($_POST['name']))>0){
			$res = $baza->select("1","cmsadmgroupuser","nameadmgroupuser='".$_POST['name']."'");
			if ($baza->size_result($res)==0){
				$kolumny = "(idadmgroupuser,nameadmgroupuser,activeadmgroupuser,defaultadmgroupuser)";
				$values = "0, '".htmlspecialchars($_POST['name'])."',
				  		'".htmlspecialchars($_POST['active'])."',
				  		'".htmlspecialchars($_POST['default'])."'";
				$res = $baza->insert("cmsadmgroupuser",$values,$kolumny,"");
				if ($res)
					echo "<p>Nowy grupa został zapisany w bazie.</p>";
			}
			else{
				echo "<p>Istnieje już grupa o podanej nazwie!</p>";	
			}
		}
		else{
			echo "<p>Nie podano nazwy grupa!</p>";
		}
	}
	echo "<p><a href=\"?admin=&admin_grupy=\">Powrót</a></p>";
}

if (isset($_GET['usun'])){
	if (!isset($_POST[usun])) {			
		echo "<p>Czy na pewno usunąć tą grupę?</p>";
		echo "<form name=\"usun_form\" method=\"post\">";
		echo "<input type=\"submit\" name=\"usun\" value=\"Usuń\" />";
		echo "</form>";					
	}
	else{			
	   	$res=$baza->delete("cmsadmgroupuser","idadmgroupuser=".$_GET['usun']);
   		if ($res) 
	   		echo "<p>grupa został usunięty</p>"; 		
   		else 
   			echo "<p class=\"error\">Błąd zapisu w bazie</p>"; 			
	}
	echo "<p><a href=\"?admin=&admin_grupy=\">Powrót</a></p>";	
}

if (isset($_GET['edytuj'])){
	if (!isset($_POST['edytuj'])){
		$res = $baza->select("*","cmsadmgroupuser","idadmgroupuser=".$_GET['edytuj'],"");
		$row = $baza->row($res);

		?>
		<form method="POST" id="f" action="">
		<table border="0" cellpadding="0" cellspacing="0" class="tab_edycji">
			<tr>
				<th>Nazwa grupy:</th>
				<td><input type="text" name="name" size="30" value="<?=$row[nameadmgroupuser]?>" /></td>
			</tr>
			<tr>
				<th>Aktywność:</th>
				<td><input type="radio" name="active" value="YES" <?if ($row[activeadmgroupuser]=='YES') echo "checked";?> /> TAK 
					<input type="radio" name="active" value="NO" <?if ($row[activeadmgroupuser]=='NO') echo "checked";?> /> NIE 
				</td>
			</tr>
			<tr>
				<th>Domyślna:</th>
				<td><input type="radio" name="default" value="YES" <?if ($row[defaultadmgroupuser]=='YES') echo "checked";?> /> TAK 
					<input type="radio" name="default" value="NO" <?if ($row[defaultadmgroupuser]=='NO') echo "checked";?> /> NIE 
				</td>
			</tr>
		</table>
		<p><input type="submit" name="edytuj" value="Zachowaj zmiany" /></p>
		</form>
		<?
	}
	else{
		if (strlen(trim($_POST['name']))>0){
			$values = "	nameadmgroupuser='".htmlspecialchars($_POST['name'])."',
			  			activeadmgroupuser='".$_POST['active']."',
			  			defaultadmgroupuser='".$_POST['default']."'";
			$where = "idadmgroupuser=".$_GET['edytuj'];
			$res = $baza->update("cmsadmgroupuser",$values,$where);
			if ($res)
				echo "<p>Zmiany zostały zapisane w bazie.</p>";
		}
		else
			echo "<p>Nie podano nazwy grupy!</p>";
	}
	echo "<p><a href=\"?admin=&admin_grupy=\">Powrót</a></p>";	
}

//prawa
if (isset($_GET['prawa'])){
	if (isset($_POST[zapisz])){
		//czyszczenie wszystkich praw dla danej grupy
		$res = $baza->delete("cmsadmrules","admGroupUser=$_GET[prawa]","");
		
		$res = $baza->select("*","cmsadmobject","","");
		if (($ile = $baza->size_result($res))>0){
			for ($i=0; $i<$ile; $i++){
				$row = $baza->row($res);
				
				$nazwa_s = $row[idAdmObject]."_select";
				$select = $_POST[$nazwa_s];
				$nazwa_i = $row[idAdmObject]."_insert";
				$insert = $_POST[$nazwa_i];
				$nazwa_u = $row[idAdmObject]."_update";
				$update = $_POST[$nazwa_u];
				$nazwa_d = $row[idAdmObject]."_delete";
				$delete = $_POST[$nazwa_d];

				$values = "0,$_GET[prawa],$row[idAdmObject],'$select','$insert','$update','$delete'";
				$r = $baza->insert("cmsadmrules",$values,"");
			}
		}
	}
	
	$res = $baza->select("*","cmsadmobject","","order by opisAdmObject");
	if (($ile = $baza->size_result($res))>0){
		?>
		<form method="POST" action="">
		<table border="0" cellpadding="2" cellspacing="0" class="tab_edycji">
			<tr>
				<th>Obiekt</th>
				<th style="width: 70px">Dostęp</th>
				<th style="width: 70px">Dodawanie</th>
				<th style="width: 70px">Usuwanie</th>
				<th style="width: 70px">Modyfikacja</th>
			</tr>
		<?
		for ($i=0; $i<$ile; $i++){
			$row = $baza->row($res);
			$mod = $i%2;
			$res2 = $baza->select("*","cmsadmrules","object=$row[idAdmObject] and admGroupUser=$_GET[prawa]","");
			$row2 = $baza->row($res2);
			?>
			<tr class="gray<?=$mod;?>">
				<td><?=$row[opisAdmObject]?></td>
				<td style="text-align: center;">
					<input <?if ($row2[selectAdmRules]=='on') echo "checked";?> type="checkbox" name="<?=$row[idAdmObject]?>_select" />
				</td>
				<td style="text-align: center;">
					<input <?if ($row2[insertAdmRules]=='on') echo "checked";?> type="checkbox" name="<?=$row[idAdmObject]?>_insert" />
				</td>
				<td style="text-align: center;">
					<input <?if ($row2[deleteAdmRules]=='on') echo "checked";?> type="checkbox" name="<?=$row[idAdmObject]?>_delete" />
				</td>
				<td style="text-align: center;">
					<input <?if ($row2[updateAdmRules]=='on') echo "checked";?> type="checkbox" name="<?=$row[idAdmObject]?>_update" />
				</td>
			</tr>
			<?
		}
		?>
		</table>
		<input type="submit" name="zapisz" value="Zapisz zmiany" />
		</form>
		<?
	}	
	echo "<p><a href=\"?admin=&admin_grupy=\">Powrót</a></p>";	
}

//wyświetlenie rekordów
if (!isset($_GET['dodaj']) and !isset($_GET['usun']) and !isset($_GET['edytuj']) and !isset($_GET['prawa'])){
	?><p><a href="?admin=&admin_grupy=&dodaj=" >Dodaj</a> </p><?
	
	$res = $baza->select("*","cmsadmgroupuser","","");
	if (($ile = $baza->size_result($res))>0){
		?>
		<table border="0" cellpadding="2" cellspacing="0" class="tab_edycji">
			<tr>
				<th>LP</th>
				<th>Nazwa</th>
				<th>Aktyw.</th>
				<th>Domyślna.</th>
				<th>Operacje</th>
			</tr>
		<?
		for ($i=0; $i<$ile; $i++){
			$row = $baza->row($res);
			$licznik++;
			$mod = $i%2;
			?>
			<tr class="gray<?=$mod;?>">
				<td><?=$licznik;?></td>
				<td><?=$row[nameadmgroupuser];?></td>
				<td><?if($row[activeadmgroupuser]=='YES') echo "TAK"; else echo 'NIE';?></td>
				<td><?if($row[defaultadmgroupuser]=='YES') echo "TAK"; else echo 'NIE';?></td>
				<td>
					<a href="?admin=&admin_grupy=&edytuj=<?=$row[idadmgroupuser];?>">Edytuj</a>
					<a href="?admin=&admin_grupy=&usun=<?=$row[idadmgroupuser];?>">Usuń</a>
					<a href="?admin=&admin_grupy=&prawa=<?=$row[idadmgroupuser];?>">Prawa</a>
				</td>
			</tr>
			<?
		}
		?>
		</table>
		<?
	}
}
?>