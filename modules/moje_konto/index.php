<br>
<table class="ramka_contant" border="0" cellpadding="0" cellspacing="0" width="98%">
<tr>
<td class="ramka_title" style="color: #660A00; font-size: 16px;"><?=$NAME?></td>
</tr>
<tr>
<td class="ramka_text">
<br>
<?php
if (isset($_SESSION[cms_user_id])){
	$file_name = "galeria_forum/$_SESSION[cms_user_id].jpg";
	$podkat = "galeria_forum";
	
	//zapisanie danych osobowych
	if (isset($_POST['dane'])){
		if (strlen($_POST['email'])>0){
			$r = $this->baza->update('cmsuser',
				"email='".$_POST['email']."',
				name='".$_POST['nazwisko']."',
				forename='".$_POST['imie']."'",
				'id='.$_SESSION[cms_user_id]);			
		}
		else 
			echo "<p style=\"font-size: 16px; color: yellow;\">Błędny adres e-mail.</p><br>";
	}
	
	//zapisanie nowego hasła
	if (isset($_POST['haslo'])){
		if (strlen($_POST['haslo_old'])>0 and strlen($_POST['haslo1'])>0 and strlen($_POST['haslo2'])>0 and $_POST['haslo2']==$_POST['haslo1']){
			$r = $this->baza->select('*','cmsuser','id='.$_SESSION[cms_user_id]);
			$row = $this->baza->row($r);
			if ($row[password]==md5($_POST['haslo_old'])){
				$r = $this->baza->update('cmsuser',"password='".md5($_POST['haslo1'])."'",'id='.$_SESSION[cms_user_id]);
				echo "<p style=\"font-size: 16px; color: yellow;\">Hasło zostało zmienione poprawnie</p><br>";
			}
			else
				echo "<p style=\"font-size: 16px; color: yellow;\">Nieudana zmiana hasła</p><br>";
		}
		else 
			echo "<p style=\"font-size: 16px; color: yellow;\">Nieudana zmiana hasła.</p><br>";
	}
/*	
	//zapisanie ustawień do furum
	if (isset($_POST['forum'])){
		$r = $this->baza->update('modforumuser',
			"footerModForumUser='".$_POST['stopka']."',
			emailShowModForumUser='".$_POST['emial_wid']."',
			emailAwardModForumUser='".$_POST['emial_award']."',
			emailNewItemModForumUser='".$_POST['emial_item']."'",
			"idModForumUser=".$_SESSION[cms_user_id]);
	}
	
	//ikona
	if (isset($_POST['ikona'])){
		if (isset($_POST['ch_zd'])){
			if (file_exists($file_name)){
				unlink($file_name);
			}
		}
		dodaj_zdj("zdjecie",$_SESSION[cms_user_id],$file_name,$podkat);
	}
*/	
	
	$res = $this->baza->select("*","cmsuser","id=$_SESSION[cms_user_id]","");
	$row = $this->baza->row($res);
	
//	$res_f = $this->baza->select("*","modforumuser","idModForumUser=$_SESSION[cms_user_id]","");
//	$row_f = $this->baza->row($res_f);
	?>
	
	<p style="font-size: 18px;">Zmiana danych osobowych:</p>
	<form action="" method="POST">
	<table border="0" cellpadding="0" cellspacing="0" >
		<tr>
			<th>Imię:</th>
			<td><input type="text" name="imie" value="<?=$row[forename]?>" /></td>
		</tr>
		<tr>
			<th>Nazwisko:</th>
			<td><input type="text" name="nazwisko" value="<?=$row[name]?>" /></td>
		</tr>
		<tr>
			<th>E-mail:</th>
			<td><input type="text" name="email" value="<?=$row[email]?>" /></td>
		</tr>
	</table>
	<p><input type="submit" name="dane" value="Zapisz" /></p>
	</form>
	
	<br><br>
	
	<p style="font-size: 18px;">Zmiana hasła:</p>
	<form action="" method="POST">
	<table border="0" cellpadding="0" cellspacing="0" >
		<tr>
			<th>Stare hasło:</th>
			<td><input type="password" name="haslo_old" /></td>
		</tr>
		<tr>
			<th>Nowe hasło:</th>
			<td><input type="password" name="haslo1" /></td>
		</tr>
		<tr>
			<th>Powtórz nowe hasło:</th>
			<td><input type="password" name="haslo2" /></td>
		</tr>
	</table>
	<p><input type="submit" name="haslo" value="Zapisz" /></p>
	</form>
<?
}

//rejestracja
else{
	if (!isset($_POST[save_rejesrtacja])){
	?>
	<form action="" method="POST">
	<table border="0" cellpadding="0" cellspacing="0" >
		<tr>
			<th>Imię:*</th>
			<td><input type="text" name="imie" /></td>
		</tr>
		<tr>
			<th>Nazwisko:*</th>
			<td><input type="text" name="nazwisko" /></td>
		</tr>
		<tr>
			<th>Login:*</th>
			<td><input type="text" name="login" /> (minimum 6 znaków)</td>
		</tr>
		<tr>
			<th>Hasło:*</th>
			<td><input type="password" name="haslo" /></td>
		</tr>
		<tr>
			<th>E-mail:*</th>
			<td><input type="text" name="email" /> (bez poprawnego adresu e-mail aktywacja nie będzie możliwa)</td>
		</tr>
		<tr>
			<th nowrap>Miejsce zamieszkania:</th>
			<td><input type="text" name="miejscowosc" /></td>
		</tr>
		<tr>
			<th>Strona WWW:</th>
			<td><input type="text" name="www" /></td>
		</tr>
		<tr>
			<th>Gadu-Gadu:</th>
			<td><input type="text" name="gg" /></td>
		</tr>
	</table>
	<p>pola oznaczone symbolem * są wymagane</p>
	<p><input type="submit" name="save_rejesrtacja" value="Zapisz" /></p>
	</form>	
	<?
	}
	else{
		if (strlen($_POST['imie'])>0 and strlen($_POST['nazwisko'])>0 and strlen($_POST['login'])>0 and 
		strlen($_POST['haslo'])>0 and strlen($_POST['email'])>0){
			if (strlen($_POST['login'])>5){
				$r = $this->baza->select("*","cmsuser","login='".$_POST['login']."'");
				if ($this->baza->size_result($r)==0){
					$kolumny = "(id,groupuser,name,forename,login,password,email,active)";
					$values = "0,0, '".htmlspecialchars($_POST['imie'])."', 
							'".htmlspecialchars($_POST['nazwisko'])."',
							 '".htmlspecialchars($_POST['login'])."', 
							 '".md5(htmlspecialchars($_POST['haslo']))."',
							  '".htmlspecialchars($_POST['email'])."',
					  		'NO'";
					$res = $this->baza->insert("cmsuser",$values,$kolumny,"");
					if ($res){
						$dzis = date("Y-m-d");
						$r = $this->baza->select("*","cmsuser","login='".$_POST['login']."'");
						$row = $this->baza->row($r);
						$id_usera = $row[id];
						
//						$kolumny = "(idModForumUser,idCmsUserModForumUser,groupModForumUser,cityModForumUser,wwwModForumUser,ggModForumUser,dateJoinModForumUser,footerModForumUser,emailShowModForumUser,emailNewItemModForumUser,emailAwardModForumUser,activeModForumUser,deleteModForumUser)";
//						$values = "0,$id_usera,0,'".htmlspecialchars($_POST['miejscowosc'])."',
//						'".htmlspecialchars($_POST['www'])."','".htmlspecialchars($_POST['gg'])."',
//						'$dzis','','NO','NO','NO','NO','NO'";
//						$res2 = $this->baza->insert("modforumuser",$values,$kolumny,"");
						
						echo "<p>Dziękujemy za rejestrację. Po weryfikacji danych otrzymasz wiadomość o uaktywnieniu konta.</p>";
					}
				}
				else{
					?><p>Wybierz inny login - ten jest niedozwolony.</p>
					<a href="">Powrót</a><?				
				}
			}
			else{
				?><p>Login musi mieć przynajmniej 6 znaków</p>
				<a href="">Powrót</a><?				
			}
		}
		else {
			?><p>Pola oznaczone symbolem * muszą zostać wypełnione</p>
			<a href="">Powrót</a><?
		}		
	}
}	


########## funkcje dodatkowe ####################
function dodaj_zdj($file_name,$nr_model,$filename,$podkat){
	$sciezka_zdjec = $podkat;
	$x_big = 70;
	$y_big = 70;

	if ($_FILES[$file_name]['type']=="image/jpg" or $_FILES[$file_name]['type']=="image/jpeg"){
		if (file_exists($filename)){
			unlink($filename);
		}
	
		if ($_FILES[$file_name]['tmp_name']!=""){
			if (is_uploaded_file($_FILES[$file_name]['tmp_name'])){
				$nazwa_zdjecia = $nr_model.".jpg";
				
				//Tu wstawiamy normalne zdjecie
				move_uploaded_file($_FILES[$file_name]['tmp_name'],$sciezka_zdjec.$_FILES[$file_name]['name']);
				zmniejszaj($sciezka_zdjec.$_FILES[$file_name]['name'],$x_big,$y_big,$sciezka_zdjec.$nazwa_zdjecia);
				$zd = $nazwa_zdjecia;
			}
		}	
	}
	else {echo "<p>Proszę podać obraz w formacie jpg!</p>";}
}

function zmniejszaj($IMAGE_SOURCE,$THUMB_X,$THUMB_Y,$OUTPUT_FILE)
//Funkcja odpowiedzialna za zmiane wymiarow pliku graficznego JPG 
{
//	echo "zmniejszaj";
  $BACKUP_FILE = $OUTPUT_FILE . "_backup.jpg";
  copy($IMAGE_SOURCE,$BACKUP_FILE);
  $IMAGE_PROPERTIES = GetImageSize($BACKUP_FILE);
  if (!$IMAGE_PROPERTIES[2] == 2) {
   return(0);
  } else {
   $SRC_IMAGE = ImageCreateFromJPEG($BACKUP_FILE);
   $SRC_X = ImageSX($SRC_IMAGE);
   $SRC_Y = ImageSY($SRC_IMAGE);
      
   if ($SRC_X<$SRC_Y){
   		$THUMB_X = $SRC_X*$THUMB_Y/$SRC_Y;
   }
   else{
   		$THUMB_Y = $SRC_Y*$THUMB_X/$SRC_X;
   }
      
   if (($THUMB_Y == "0") && ($THUMB_X == "0")) {
     return(0);
   } 
   elseif ($THUMB_Y == "0") {
     $SCALEX = $THUMB_X/($SRC_X-1);
     $THUMB_Y = $SRC_Y*$SCALEX;
   } 
   elseif ($THUMB_X == "0") {
     $SCALEY = $THUMB_Y/($SRC_Y-1);
     $THUMB_X = $SRC_X*$SCALEY;
   }
   $THUMB_X = (int)($THUMB_X);
   $THUMB_Y = (int)($THUMB_Y);
   
   $DEST_IMAGE = imagecreatetruecolor($THUMB_X, $THUMB_Y);
   #$DEST_IMAGE = imagecreate($THUMB_X, $THUMB_Y);
   
   unlink($BACKUP_FILE);
//    if (!imagecopyresized($DEST_IMAGE, $SRC_IMAGE, 0, 0, 0, 0, $THUMB_X, $THUMB_Y, $SRC_X, $SRC_Y))
    if (!imagecopyresampled($DEST_IMAGE, $SRC_IMAGE, 0, 0, 0, 0, $THUMB_X, $THUMB_Y, $SRC_X, $SRC_Y))
   {
     imagedestroy($SRC_IMAGE);
     imagedestroy($DEST_IMAGE);
     return(0);
   } else {
     imagedestroy($SRC_IMAGE);
     if (ImageJPEG($DEST_IMAGE,$OUTPUT_FILE)) {
       imagedestroy($DEST_IMAGE);
       return(1);
     }
     imagedestroy($DEST_IMAGE);
   }
   return(0);
  }
}
?>
<br>
</td>
</tr>
</table>
<br />