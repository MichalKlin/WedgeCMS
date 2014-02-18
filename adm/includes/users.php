<h3>Zarządzanie użytkownikami</h3>
<?
if (isset($_GET['dodaj_user'])){
	if (!isset($_POST['dodaj'])){
		?>
			<form method="POST" id="f" action="">
			<table border="0" cellpadding="0" cellspacing="0" class="tab_edycji">
				<tr>
					<th>Grupa:</th>
					<td>
						<select name="grupa">
			<?
				$res_g = $baza->select("*","cmsgroupuser","","");
				if (($ile_g = $baza->size_result($res_g))>0){
					for ($i=0; $i<$ile_g; $i++){
						$row_g = $baza->row($res_g);
						?><option value="<?=$row_g['idGroupUser'];?>"><?=$row_g['nameGroupUser'];?></option><?
					}
				}
			?>
						</select>
					</td>				
				</tr>
				<tr>
					<th>Imię:</th>
					<td><input type="text" name="forename" size="30" /></td>
				</tr>
				<tr>
					<th>Nazwisko:</th>
					<td><input type="text" name="name" size="30" /></td>
				</tr>
				<tr>
					<th>Login:</th>
					<td><input type="text" name="login" size="30" /></td>
				</tr>
				<tr>
					<th>Hasło:</th>
					<td><input type="text" name="haslo" size="30" /></td>
				</tr>
				<tr>
					<th>E-mail:</th>
					<td><input type="text" name="email" size="30" /></td>
				</tr>
				<tr>
					<th>Aktywność:</th>
					<td><input type="radio" name="active" value="YES" checked /> TAK 
						<input type="radio" name="active" value="NO" /> NIE 
					</td>
				</tr>
			</table>
			<p><input type="submit" name="dodaj" value="Dodaj" /></p>
			</form>
		<?
	}
	else{
		if (strlen(trim($_POST['name']))>0){
//			$res = $baza->select("1","cmsgroupuser","namegroupuser='".$_POST['name']."'");
//			if ($baza->size_result($res)==0){
				$kolumny = "(id,groupuser,name,forename,login,password,email,active)";
				$values = "0,".$_POST['grupa'].", '".htmlspecialchars($_POST['name'])."', '".htmlspecialchars($_POST['forename'])."',
						 '".htmlspecialchars($_POST['login'])."', '".md5(htmlspecialchars($_POST['haslo']))."',
						  '".htmlspecialchars($_POST['email'])."',
				  		'".htmlspecialchars($_POST['active'])."'";
				$res = $baza->insert("cmsuser",$values,$kolumny,"");
				if ($res)
					echo "<p>Nowy uzytkownik został zapisany w bazie.</p>";
//			}
//			else{
//				echo "<p>Istnieje już grupa o podanej nazwie!</p>";	
//			}
		}
		else{
			echo "<p>Nie podano nazwiska użytkownika!</p>";
		}
	}
	echo "<p><a href=\"?user=\">Powrót</a></p>";
}

if (isset($_GET['usun'])){
	if (!isset($_POST[usun])) {			
		echo "<p>Czy na pewno usunąć tego użytkownika?</p>";
		echo "<form name=\"usun_form\" method=\"post\">";
		echo "<input type=\"submit\" name=\"usun\" value=\"Usuń\" />";
		echo "</form>";					
	}
	else{			
	   	$res=$baza->delete("cmsuser","id=".$_GET['usun']);
   		if ($res) 
	   		echo "<p>Użytkownik został usunięty</p>"; 		
   		else 
   			echo "<p class=\"error\">Błąd zapisu w bazie</p>"; 			
	}
	echo "<p><a href=\"?user=\">Powrót</a></p>";	
}

if (isset($_GET['edytuj'])){
	if (!isset($_POST['edytuj'])){
		$res = $baza->select("*","cmsuser","id=".$_GET['edytuj'],"");
		$row = $baza->row($res);

		?>
		<form method="POST" id="f" action="">
		<table border="0" cellpadding="0" cellspacing="0" class="tab_edycji">
			<tr>
				<th>Grupa:</th>
				<td>
					<select name="grupa">
					<?
					$r = $baza->select("*","cmsgroupuser");
					$ile = $baza->size_result($r);					
					for ($i=0; $i<$ile; $i++){	
						$roow = $baza->row($r);
						if ($roow[idGroupUser]==$row[groupUser]) $selected="selected"; else $selected="";
					?>	
						<option <?=$selected;?> value="<?=$roow[idGroupUser];?>"><?=$roow[nameGroupUser];?></option>
					<?
					}
					?>
					</select>
				</td>
			</tr>
			<tr>
				<th>Imię:</th>
				<td><input type="text" name="forename" size="30" value="<?=$row[forename]?>" /></td>
			</tr>
			<tr>
				<th>Nazwisko:</th>
				<td><input type="text" name="name" size="30" value="<?=$row[name]?>" /></td>
			</tr>
			<tr>
				<th>Login:</th>
				<td><input type="text" name="login" size="30" value="<?=$row[login]?>" /></td>
			</tr>
			<tr>
				<th>E-mail:</th>
				<td><input type="text" name="email" size="30" value="<?=$row[email]?>" /></td>
			</tr>
			<tr>
				<th>Aktywność:</th>
				<td>
				<select name="aktywnosc">
					<option <?if ($row[active]=='YES') echo "selected"?> value="YES">TAK</option>
					<option <?if ($row[active]=='NO') echo "selected"?> value="NO">NIE</option>
				</select>
				</td>
			</tr>
		</table>
		<input type="submit" name="edytuj" value="Zachowaj zmiany" />
		</form>
		<?
	}
	else{
		if (strlen(trim($_POST['forename']))>0 and strlen(trim($_POST['name']))>0 and strlen(trim($_POST['login']))>0 and strlen(trim($_POST['email']))>0){
			$values = "	groupUser='".htmlspecialchars($_POST['grupa'])."',
						forename='".htmlspecialchars($_POST['forename'])."',
						name='".htmlspecialchars($_POST['name'])."',
						login='".htmlspecialchars($_POST['login'])."',
						email='".htmlspecialchars($_POST['email'])."',
						active='".htmlspecialchars($_POST['aktywnosc'])."'
			  			";
			$where = "id=".$_GET['edytuj'];
			$res = $baza->update("cmsuser",$values,$where);
			if ($res)
				echo "<p>Zmiany zostały zapisane w bazie.</p>";
		}
		else
			echo "<p>Proszę wypełnić wszystkie pola!</p>";
	}
	echo "<p><a href=\"?user=\">Powrót</a></p>";	
}

//wyświetlenie rekordów
if (!isset($_GET['dodaj_user']) and !isset($_GET['usun']) and !isset($_GET['edytuj'])){
	if (check_rules($baza,'user','insert')){
		?><p><a href="?user=&dodaj_user=" >Dodaj</a> </p><?
	}
	
	$licznik=0;
	$res = $baza->select("*","cmsuser","id!=1","");
	if (($ile = $baza->size_result($res))>0){
		?>
		<table border="0" cellpadding="0" cellspacing="0" class="tab_edycji">
			<tr>
				<th>LP</th>
				<th>Grupa</th>
				<th>Imię i nazwisko</th>
				<th>Login</th>
				<th>E-mail</th>
				<th>Aktyw.</th>
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
				<td>
					<?
					if ($row[groupUser] != null){
						$r=$baza->select("nameGroupUser","cmsgroupuser","idGroupUser=".$row[groupUser],"","");
						if ($baza->size_result($r)>0){
							$roow = $baza->row($r);
							echo $roow[nameGroupUser];
						}
					}
					?>
				</td>
				<td><?=$row[forename];?> <?=$row[name];?></td>
				<td><?=$row[login];?></td>
				<td><?=$row[email];?></td>
				<td><?if($row[active]=='YES') echo "TAK"; else echo 'NIE';?></td>
				<td>
					<?if (check_rules($baza,'user','update')){?>
					<a href="?user=&edytuj=<?=$row[id];?>">Edytuj</a>
					<?}
					if (check_rules($baza,'user','delete')){?>
					<a href="?user=&usun=<?=$row[id];?>">Usuń</a>
					<?}?>
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