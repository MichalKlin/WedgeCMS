<h3>Zarządzanie obiektami</h3>
<?
if (isset($_GET['dodaj'])){
	if (!isset($_POST['dodaj'])){
		?>
			<form method="POST" id="f" action="">
			<table border="0" cellpadding="0" cellspacing="0" class="tab_edycji">
				<tr>
					<th>Nazwa obiektu:</th>
					<td><input type="text" name="name" size="30" /></td>
				</tr>
				<tr>
					<th>Opis:</th>
					<td><input type="text" name="opis" size="30" /></td>
				</tr>
			</table>
			<p><input type="submit" name="dodaj" value="Dodaj" /></p>
			</form>
		<?
	}
	else{
		if (strlen(trim($_POST['name']))>0){
			$res = $baza->select("1","cmsadmobject","nameAdmObject='".$_POST['name']."'");
			if ($baza->size_result($res)==0){
				$kolumny = "(idAdmObject,nameAdmObject,opisAdmObject)";
				$values = "0, '".htmlspecialchars($_POST['name'])."', '".htmlspecialchars($_POST['opis'])."'";
				$res = $baza->insert("cmsadmobject",$values,$kolumny,"");
				if ($res)
					echo "<p>Nowy obiekt został zapisany w bazie.</p>";
			}
			else{
				echo "<p>Istnieje już obiekt o podanej nazwie!</p>";	
			}
		}
		else{
			echo "<p>Nie podano nazwy obiektu!</p>";
		}
	}
	echo "<p><a href=\"?admin=&obiekty=\">Powrót</a></p>";
}

if (isset($_GET['usun'])){
	if (!isset($_POST[usun])) {			
		echo "<p>Czy na pewno usunąć ten obiekt?</p>";
		echo "<form name=\"usun_form\" method=\"post\">";
		echo "<input type=\"submit\" name=\"usun\" value=\"Usuń\" />";
		echo "</form>";					
	}
	else{			
	   	$res=$baza->delete("cmsadmobject","idAdmObject=".$_GET['usun']);
   		if ($res) 
	   		echo "<p>obiekt został usunięty</p>"; 		
   		else 
   			echo "<p class=\"error\">Błąd zapisu w bazie</p>"; 			
	}
	echo "<p><a href=\"?admin=&obiekty=\">Powrót</a></p>";	
}

if (isset($_GET['edytuj'])){
	if (!isset($_POST['edytuj'])){
		$res = $baza->select("*","cmsadmobject","idAdmObject=".$_GET['edytuj'],"");
		$row = $baza->row($res);

		?>
		<form method="POST" id="f" action="">
		<table border="0" cellpadding="0" cellspacing="0" class="tab_edycji">
			<tr>
				<th>Nazwa obiektu:</th>
				<td><input type="text" name="name" size="30" value="<?=$row[nameAdmObject]?>" /></td>
			</tr>
			<tr>
				<th>Opis:</th>
				<td><input type="text" name="opis" size="50" value="<?=$row[opisAdmObject]?>" /></td>
			</tr>
		</table>
		<p><input type="submit" name="edytuj" value="Zachowaj zmiany" /></p>
		</form>
		<?
	}
	else{
		if (strlen(trim($_POST['name']))>0){
			$values = "	nameAdmObject='".htmlspecialchars($_POST['name'])."', opisAdmObject='".htmlspecialchars($_POST['opis'])."'";
			$where = "idAdmObject=".$_GET['edytuj'];
			$res = $baza->update("cmsadmobject",$values,$where);
			if ($res)
				echo "<p>Zmiany zostały zapisane w bazie.</p>";
		}
		else
			echo "<p>Nie podano nazwy obiektu!</p>";
	}
	echo "<p><a href=\"?admin=&obiekty=\">Powrót</a></p>";	
}

//wyświetlenie rekordów
if (!isset($_GET['dodaj']) and !isset($_GET['usun']) and !isset($_GET['edytuj'])){
	?><p><a href="?admin=&obiekty=&dodaj=" >Dodaj</a> </p><?
	
	$res = $baza->select("*","cmsadmobject","","ORDER BY opisAdmObject");
	if (($ile = $baza->size_result($res))>0){
		?>
		<table border="0" cellpadding="2" cellspacing="0" class="tab_edycji">
			<tr>
				<th>LP</th>
				<th>Nazwa</th>
				<th>Opis</th>
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
				<td><?=$row[nameAdmObject];?></td>
				<td><?=$row[opisAdmObject];?></td>
				<td>
					<a href="?admin=&obiekty=&edytuj=<?=$row[idAdmObject];?>">Edytuj</a>
					<a href="?admin=&obiekty=&usun=<?=$row[idAdmObject];?>">Usuń</a>
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