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
				$res_g = $baza->select("*","cmsadmgroupuser","","");
				if (($ile_g = $baza->size_result($res_g))>0){
					for ($i=0; $i<$ile_g; $i++){
						$row_g = $baza->row($res_g);
						?><option value="<?=$row_g[idadmgroupuser];?>"><?=$row_g[nameadmgroupuser];?></option><?
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
//			$res = $baza->select("1","cmsadmgroupuser","nameadmgroupuser='".$_POST['name']."'");
//			if ($baza->size_result($res)==0){
				$kolumny = "(idAdmUser,admGroupUser,nameAdmUser,forenameAdmUser,loginAdmUser,passwordAdmUser,emailAdmUser,activeAdmUser)";
				$values = "0,".$_POST['grupa'].", '".htmlspecialchars($_POST['name'])."', '".htmlspecialchars($_POST['forename'])."',
						 '".htmlspecialchars($_POST['login'])."', '".htmlspecialchars(md5($_POST['haslo']))."',
						  '".htmlspecialchars($_POST['email'])."',
				  		'".htmlspecialchars($_POST['active'])."'";
				$res = $baza->insert("cmsadmuser",$values,$kolumny,"");
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
	echo "<p><a href=\"?admin=&admin_users=\">Powrót</a></p>";
}

if (isset($_GET['usun_user'])){
	if (!isset($_POST[usun])) {			
		echo "<p>Czy na pewno usunąć tego użytkownika?</p>";
		echo "<form name=\"usun_form\" method=\"post\">";
		echo "<input type=\"submit\" name=\"usun\" value=\"Usuń\" />";
		echo "</form>";					
	}
	else{			
	   	$res=$baza->delete("cmsadmuser","idAdmUser=".$_GET['usun_user']);
   		if ($res) 
	   		echo "<p>Użytkownik został usunięty</p>"; 		
   		else 
   			echo "<p class=\"error\">Błąd zapisu w bazie</p>"; 			
	}
	echo "<p><a href=\"?admin=&admin_users=\">Powrót</a></p>";	
}

if (isset($_GET['edytuj_user'])){
	if (!isset($_POST['edytuj'])){
		$res = $baza->select("*","cmsadmuser","idAdmUser=".$_GET['edytuj_user'],"");
		$row = $baza->row($res);

		?>
		<form method="POST" id="f" action="">
		<table border="0" cellpadding="0" cellspacing="0" class="tab_edycji">
			<tr>
				<th>Grupa:</th>
				<td>
					<select name="grupa">
					<?
					$r = $baza->select("*","cmsadmgroupuser");
					$ile = $baza->size_result($r);					
					for ($i=0; $i<$ile; $i++){	
						$roow = $baza->row($r);
					?>	
						<option <?if ($roow[idadmgroupuser]==$row[admGroupUser]) echo "selected";?> 
						value="<?=$roow[idadmgroupuser];?>"><?=$roow[nameadmgroupuser];?></option>
					<?
					}
					?>
					</select>
				</td>
			</tr>
			<tr>
				<th>Imię:</th>
				<td><input type="text" name="forename" size="30" value="<?=$row[forenameAdmUser]?>" /></td>
			</tr>
			<tr>
				<th>Nazwisko:</th>
				<td><input type="text" name="name" size="30" value="<?=$row[nameAdmUser]?>" /></td>
			</tr>
			<tr>
				<th>Login:</th>
				<td><input type="text" name="login" size="30" value="<?=$row[loginAdmUser]?>" /></td>
			</tr>
			<tr>
				<th>E-mail:</th>
				<td><input type="text" name="email" size="30" value="<?=$row[emailAdmUser]?>" /></td>
			</tr>
		</table>
		<input type="submit" name="edytuj" value="Zachowaj zmiany" />
		</form>
		<?
	}
	else{
		if (strlen(trim($_POST['forename']))>0 and strlen(trim($_POST['name']))>0 and strlen(trim($_POST['login']))>0 and strlen(trim($_POST['email']))>0){
			$values = "	admGroupUser='".htmlspecialchars($_POST['grupa'])."',
						forenameAdmUser='".htmlspecialchars($_POST['forename'])."',
						nameAdmUser='".htmlspecialchars($_POST['name'])."',
						loginAdmUser='".htmlspecialchars($_POST['login'])."',
						emailAdmUser='".htmlspecialchars($_POST['email'])."'
			  			";
			$where = "idAdmUser=".$_GET['edytuj_user'];
			$res = $baza->update("cmsadmuser",$values,$where);
			if ($res)
				echo "<p>Zmiany zostały zapisane w bazie.</p>";
		}
		else
			echo "<p>Proszę wypełnić wszystkie pola!</p>";
	}
	echo "<p><a href=\"?admin=&admin_users=\">Powrót</a></p>";	
}

//wyświetlenie rekordów
if (!isset($_GET['dodaj_user']) and !isset($_GET['usun_user']) and !isset($_GET['edytuj_user'])){
	?><p><a href="?admin=&admin_users=&dodaj_user=" >Dodaj</a> </p><?
	
	$licznik = 0;
	$res = $baza->select("*","cmsadmuser","","");
	if (($ile = $baza->size_result($res))>0){
		?>
		<table border="0" cellpadding="0" cellspacing="0" class="tab_edycji">
			<tr>
				<th>LP</th>
				<th>Grupa</th>
				<th>Imię i nazwisko</th>
				<th>Login</th>
				<th>E-mail</th>
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
					if ($row['admGroupUser'] != null){
						$r=$baza->select("nameadmgroupuser","cmsadmgroupuser","idadmgroupuser=".$row['admGroupUser'],"","");
						$roow = $baza->row($r);
						echo $roow[nameadmgroupuser];
					}else{
						?>
						<span style="color: red">Brak określonej grupy!</span>
						<?
					}
					?>
				</td>
				<td><?=$row[forenameAdmUser];?> <?=$row[nameAdmUser];?></td>
				<td><?=$row[loginAdmUser];?></td>
				<td><?=$row[emailAdmUser];?></td>
				<td>
					<a href="?admin=&admin_users=&edytuj_user=<?=$row[idAdmUser];?>">Edytuj</a>
					<?if ($row[idAdmUser]!=$_SESSION['panel_admin_user_id']){?>
						<a href="?admin=&admin_users=&usun_user=<?=$row[idAdmUser];?>">Usuń</a>
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