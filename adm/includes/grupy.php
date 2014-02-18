<h3>Zarządzanie grupami użytkowników</h3>
<?
if (isset($_GET['dodaj_g'])){
	if (!isset($_POST['dodaj_g'])){
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
			<p><input type="submit" name="dodaj_g" value="Dodaj" /></p>
			</form>
		<?
	}
	else{
		if (strlen(trim($_POST['name']))>0){
			$res = $baza->select("1","cmsgroupuser","nameGroupUser='".$_POST['name']."'");
			if ($baza->size_result($res)==0){
				$kolumny = "(idGroupUser,nameGroupUser,active,defaultGroupUser)";
				$values = "0, '".htmlspecialchars($_POST['name'])."',
				  		'".htmlspecialchars($_POST['active'])."',
				  		'".htmlspecialchars($_POST['default'])."'";
				$res = $baza->insert("cmsgroupuser",$values,$kolumny,"");
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
	echo "<p><a href=\"?user=\">Powrót</a></p>";
}

if (isset($_GET['usun_g'])){
	if (!isset($_POST['usun_g'])) {			
		echo "<p>Czy na pewno usunąć tą grupę?</p>";
		echo "<form name=\"usun_form\" method=\"post\">";
		echo "<input type=\"submit\" name=\"usun_g\" value=\"Usuń\" />";
		echo "</form>";					
	}
	else{			
	   	$res=$baza->delete("cmsgroupuser","idGroupUser=".$_GET['usun_g']);
   		if ($res) 
	   		echo "<p>grupa został usunięty</p>"; 		
   		else 
   			echo "<p class=\"error\">Błąd zapisu w bazie</p>"; 			
	}
	echo "<p><a href=\"?user=\">Powrót</a></p>";	
}

if (isset($_GET['edytuj_g'])){
	if (!isset($_POST['edytuj_g'])){
		$res = $baza->select("*","cmsgroupuser","idGroupUser=".$_GET['edytuj_g'],"");
		$row = $baza->row($res);

		?>
		<form method="POST" id="f" action="">
		<table border="0" cellpadding="0" cellspacing="0" class="tab_edycji">
			<tr>
				<th>Nazwa grupy:</th>
				<td><input type="text" name="name" size="30" value="<?=$row[nameGroupUser]?>" /></td>
			</tr>
			<tr>
				<th>Aktywność:</th>
				<td><input type="radio" name="active" value="YES" <?if ($row[active]=='YES') echo "checked";?> /> TAK 
					<input type="radio" name="active" value="NO" <?if ($row[active]=='NO') echo "checked";?> /> NIE 
				</td>
			</tr>
			<tr>
				<th>Domyślna:</th>
				<td><input type="radio" name="default" value="YES" <?if ($row[defaultGroupUser]=='YES') echo "checked";?> /> TAK 
					<input type="radio" name="default" value="NO" <?if ($row[defaultGroupUser]=='NO') echo "checked";?> /> NIE 
				</td>
			</tr>
		</table>
		<p><input type="submit" name="edytuj_g" value="Zachowaj zmiany" /></p>
		</form>
		<?
	}
	else{
		if (strlen(trim($_POST['name']))>0){
			$values = "	nameGroupUser='".htmlspecialchars($_POST['name'])."',
			  			active='".$_POST['active']."',
			  			defaultGroupUser='".$_POST['default']."'";
			$where = "idGroupUser=".$_GET['edytuj_g'];
			$res = $baza->update("cmsgroupuser",$values,$where);
			if ($res)
				echo "<p>Zmiany zostały zapisane w bazie.</p>";
		}
		else
			echo "<p>Nie podano nazwy grupy!</p>";
	}
	echo "<p><a href=\"?user=\">Powrót</a></p>";	
}

//wyświetlenie rekordów
if (!isset($_GET['dodaj_g']) and !isset($_GET['usun_g']) and !isset($_GET['edytuj_g'])){
	if (check_rules($baza,'grUser','insert')){
		?><p><a href="?user=&dodaj_g=" >Dodaj</a> </p><?
	}
	
	$res = $baza->select("*","cmsgroupuser","","");
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
				<td><?=$row[nameGroupUser];?></td>
				<td><?if($row[active]=='YES') echo "TAK"; else echo 'NIE';?></td>
				<td><?if($row[defaultGroupUser]=='YES') echo "TAK"; else echo 'NIE';?></td>
				<td>
					<?if (check_rules($baza,'grUser','update')){?>
					<a href="?user=&edytuj_g=<?=$row[idGroupUser];?>">Edytuj</a>
					<?}
					if (check_rules($baza,'grUser','delete')){
						if ($row[idGroupUser]!=1){?>
						<a href="?user=&usun_g=<?=$row[idGroupUser];?>">Usuń</a>
						<?}
					}?>
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