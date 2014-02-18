<?
##############################################################################################################
function gallery_list($baza, $path, $tabela_galeria, $tabela_zdjec, $session,$katalog_galerii,$SCHEMA_MODULE){
	
	# Pobranie informacji na temat dostepnych galerii #
	$result = $baza->select("*",$tabela_galeria.",modgalleryschema","activeModGallery=\"YES\" 
		and modgallery.group=modgalleryschema.gallery_group and modgalleryschema.schema=$SCHEMA_MODULE",
	"ORDER BY createdModGallery DESC","");
	
	$size_result = $baza->size_result($result);
	if ($size_result>0){
		for ($i=0; $i<$size_result; $i++){
			$row = $baza->row($result);

			$licznik = 0;
			if (file_exists($katalog_galerii."/".$row['dirModGallery'])){
				$d = dir($katalog_galerii."/".$row['dirModGallery']);
				while (false !== ($entry = $d->read())) {
					if ($entry!="." and $entry!=".." and $entry!="full"){
						$miniatura = $entry;
						$r = $baza->select("*","modgalleryphoto","gp_galery=".$row[idModGallery]." and gp_file='$entry'");
						if($baza->size_result($r)>0){
							$row2 = $baza->row($r);
							if ($row2[gp_default]=='YES')	
								break;
						}
						$licznik++;
					}
				}
				$d->close();

				if ($licznik>0){
					?><br>
					<table width="70%" cellpadding="0" cellspacing="0" class="zdjecia">	
						<tr>
							<td colspan="2" class="zdjecie_title"><?=$row[nameModGallery]?></td>
						</tr>
						<tr>
							<td rowspan="3">
								<a href="<?=$path?>?g=<?=$row[idModGallery]?>">
									<img src="<?=$katalog_galerii."/".$row[dirModGallery]."/".$miniatura?>" alt="" />
								</a>
							</td>
							<td class="zdjecie_opis">&nbsp;<?=$row[describeModGallery]?></td>
						</tr>
						<tr>
							<td class="zdjecie_data">Autor: <?=$row[authorModGallery]?></td>
						</tr>
						<tr>
							<td class="zdjecie_licznik" width="100%"><strong>wyświetleń:</strong> <?=$row[counterModGallery]?> 
							&nbsp;|&nbsp;<strong>ilość zdjęć:</strong> <?=$licznik?>
							&nbsp;|&nbsp;data: <span style="font-style: italic;"><?=$row[createdModGallery]?></span></td>
						</tr>
					</table><br>
					<?
				}
			}
		}		
	}
	else{
		?><p>Obecnie nie ma żadnych zdjęć.</p> <?
	}
}
##############################################################################################################
function gallery($baza, $path, $tabela_galeria, $nr_galerii,$katalog_galerii,$SCHEMA_MODULE){
	# Sprawdzenie czy wybrana galeria istnieje jezeli nie to wracamy do listy galerii #
	$result = $baza->select("*",$tabela_galeria,"activeModGallery=\"YES\" and idModGallery=$nr_galerii","","");
	if ($size_result = $baza->size_result($result)==1){
		$row = $baza->row($result);

		$result0 = $baza->select("*","modgalleryschema","modgalleryschema.gallery_group=$row[group] 
			and modgalleryschema.schema=$SCHEMA_MODULE",
			"","");
		$row0 = $baza->row($result0);
		$ile_zdjec_szerokosc = $row0[amountThumb];

		//zwiększenie licznika odwiedzin galerii
		add_to_counter_gal($baza,$tabela_galeria, $nr_galerii, $row['counterModGallery']);
		
		# Pobranie i wyswietlenie wszystkich zdjec nalezacych do danej galerii #

		$d = dir($katalog_galerii."/".$row['dirModGallery']);
		
		//tablica zdjęć
		$licznik = 0;
		$tab_zdjec = "";
		while (false !== ($entry = $d->read())) {
//			echo $entry;
			if ($entry!="." and $entry!=".." and $entry!="full"){
				$gal = $row[dirModGallery];
				$result2 = $baza->select("*","modgalleryphoto","gp_galery=".$row[idModGallery]." and gp_file='$entry'","","");		
				$size_result2 = $baza->size_result($result2);
				if ($size_result2>0){
					$row2 = $baza->row($result2);
					$opis = $row2[gp_desc];
					$data = $row2[gp_date];
					$order = $row2[gp_order];
					$default = $row2[gp_default];
					if ($data=='0000-00-00')
						$data = "";
					$tab_zdjec[$licznik][0] = $entry;
					$tab_zdjec[$licznik][1] = $opis;
					$tab_zdjec[$licznik][2] = $data;
					$tab_zdjec[$licznik][3] = $order;
					$tab_zdjec[$licznik][4] = $default;
					$licznik++;
				}
			}
		}
		
		//sortowanie po order
		$max_zdjec = $licznik;
		for ($i=0; $i<$max_zdjec; $i++){
			for ($j=0; $j<$max_zdjec-$i-1; $j++){
				$k = $j+1;
				if ($tab_zdjec[$j][3]>$tab_zdjec[$k][3]){
					$tmp[0] = $tab_zdjec[$j][0];
					$tmp[1] = $tab_zdjec[$j][1];
					$tmp[2] = $tab_zdjec[$j][2];
					$tmp[3] = $tab_zdjec[$j][3];
					$tmp[4] = $tab_zdjec[$j][4];
					$tab_zdjec[$j][0] = $tab_zdjec[$k][0];
					$tab_zdjec[$j][1] = $tab_zdjec[$k][1];
					$tab_zdjec[$j][2] = $tab_zdjec[$k][2];
					$tab_zdjec[$j][3] = $tab_zdjec[$k][3];
					$tab_zdjec[$j][4] = $tab_zdjec[$k][4];
					$tab_zdjec[$k][0] = $tmp[0];
					$tab_zdjec[$k][1] = $tmp[1];
					$tab_zdjec[$k][2] = $tmp[2];
					$tab_zdjec[$k][3] = $tmp[3];
					$tab_zdjec[$k][4] = $tmp[4];
				}
			}
		}

		
		?>
		<table border="0" cellpadding="0" cellspacing="0" class="zdjecia" width="98%">
			<tr>
				<td colspan="<?=$ile_zdjec_szerokosc?>" class="zdjecie_title"><?=$row[nameModGallery]?></td>
			</tr>
			<?
			$licznik = 0;
			for($i=0; $i<$max_zdjec; $i++){
//			while (false !== ($entry = $d->read())) {
//				if ($entry!="." and $entry!=".." and $entry!="full"){
					$k = $licznik%$ile_zdjec_szerokosc;
					if ($k==0) {
						echo "<tr>";
					}
					$gal = $row[dirModGallery];
					
//					$result2 = $baza->select("*","modgalleryphoto","gp_galery=".$row[idModGallery]." and gp_file='$entry'");		
//					$size_result2 = $baza->size_result($result2);
//					if ($size_result2>0){
//						$row2 = $baza->row($result2);
//						$opis = $row2[gp_desc];
//						$data = $row2[gp_date];
//						if ($data=='0000-00-00')
//							$data = "";
//					}
					$entry = $tab_zdjec[$i][0];
					$opis = $tab_zdjec[$i][1];
					$data = $tab_zdjec[$i][2];

					echo "<td class=\"zdjecie_min\" style=\"text-align: center;\">
					<a href=\"$katalog_galerii/$row[dirModGallery]/full/$entry\" rel=\"lightbox[$gal]\" title=\"$opis\">
						<img src=\"$katalog_galerii/$row[dirModGallery]/$entry\" alt=\"\"  />
					</a>";
					//echo "<br>Data: $data<br>"; 
					//echo $opis;
					echo"</td>";
					if ($k==$ile_zdjec_szerokosc-1) {
						echo "</tr>";
					}						
					$licznik++;
//				}
			}
			?>				
			<!--tr>
				<td colspan="<?=$ile_zdjec_szerokosc?>" class="zdjecie_licznik">wyświetleń: <?=$row[counterModGallery]?> 
					&nbsp;|&nbsp;Autor: <?=$row[authorModGallery]?>
					&nbsp;|&nbsp;data: <?=$row[createdModGallery]?></td>
			</tr-->
		</table>
		<?
		$d->close();
	}
	else{
		echo "Wystąpił błąd - niewłaściwy nr galerii!";
//		reload_page("",0);
	}
}
###############################################################################################################
function photo($baza, $path, $tabela_galeria, $nr_galerii, $zdjecie,$katalog_galerii){
	# Sprawdzenie czy wybrane zdjecie istnieje i czy nalezy do danej galerii; jezeli nie to wracamy do listy galerii #
	$result = $baza->select("*",$tabela_galeria,"activeModGallery=\"YES\" and idModGallery=$nr_galerii","");
	if ($size_result = $baza->size_result($result)==1){
		$row = $baza->row($result);
	
		$sciezka = "$katalog_galerii/".$row['dirModGallery']."/full/".$zdjecie;
		if (file_exists($sciezka)){
			echo "<p><img src=\"$sciezka\" alt=\"\" /></p>";
		}
		else{
			echo "Wystąpił błąd - niewłaściwy nr zdjęcia!";
		//	reload_page("",0);
		}	
	}
	else{
		echo "Wystąpił błąd - niewłaściwy nr zdjęcia!";
	//	reload_page("",0);
	}	
}
###############################################################################################################
function add_to_counter_gal($baza, $tabela, $element, $poprzedni_licznik){
	$nowy_licznik=$poprzedni_licznik+1;

	$pole_wartosc = "counterModGallery = $nowy_licznik";
	$result = $baza->update($tabela,$pole_wartosc, "idModGallery=$element");
	if (!$result){
		echo "Błąd zwiększenia licznika";
	}
}
###############################################################################################################
function add_to_counter_pht($baza, $tabela, $element, $poprzedni_licznik){
	$nowy_licznik=$poprzedni_licznik+1;

	$pole_wartosc = "mgp_counter = $nowy_licznik";
	$result = $baza->update($tabela,$pole_wartosc, "mgp_id=$element");
	if (!$result){
		echo "Błąd zwiększenia licznika";
	}
}
###############################################################################################################
function nawigacja_galeria($nr_galerii=NULL){
	if($nr_galerii!=NULL){$powrót_do_galerii=" | <a href=\"?g=$nr_galerii\">Powrót do wyboru zdjęć obecnej galerii</a>";}
//	echo "<p><a href=\"".$_GET['page'].".html\">Powrót do wyboru galerii</a> $powrót_do_galerii</p>";
}
###############################################################################################################

#dla admina
function obetnij_tekst($tekst,$dlugosc){
	return substr($tekst,0,$dlugosc);
	
}
function zmniejszaj($IMAGE_SOURCE,$THUMB_X,$THUMB_Y,$OUTPUT_FILE)
//Funkcja odpowiedzialna za zmiane wymiarow pliku graficznego JPG 
{

  $BACKUP_FILE = $OUTPUT_FILE . "_backup.jpg";
  copy($IMAGE_SOURCE,$BACKUP_FILE);
  $IMAGE_PROPERTIES = GetImageSize($BACKUP_FILE);
  if (!$IMAGE_PROPERTIES[2] == 2) {
   return(0);
  } else {
   $SRC_IMAGE = ImageCreateFromJPEG($BACKUP_FILE);
   $SRC_X = ImageSX($SRC_IMAGE);
   $SRC_Y = ImageSY($SRC_IMAGE);
   
   
   //sprawdzenie czy poprawne proporcje
   $wsp_org = $SRC_X/$SRC_Y;
   $wsp_nowy= $THUMB_X/$THUMB_Y;
   //blokowanie szerokości
   if($wsp_org>$wsp_nowy){
   		$THUMB_Y = round($THUMB_X*$SRC_Y/$SRC_X);
   }
   //blokowanie wysokości
   if($wsp_org<$wsp_nowy){
   		$THUMB_X = round($SRC_X*$THUMB_Y/$SRC_Y);
   }
   
   //Spradzamy czy zdjeci w poziomie czy w pionie ...
   if($SRC_X<$SRC_Y && $THUMB_X>$THUMB_Y){$tmp=$THUMB_Y; $THUMB_Y=$THUMB_X; $THUMB_X=$tmp;}
   if($SRC_X>$SRC_Y && $THUMB_X<$THUMB_Y){$tmp=$THUMB_Y; $THUMB_Y=$THUMB_X; $THUMB_X=$tmp;}
      
   if (($THUMB_Y == "0") && ($THUMB_X == "0")) {
     return(0);
   } elseif ($THUMB_Y == "0") {
     $SCALEX = $THUMB_X/($SRC_X-1);
     $THUMB_Y = $SRC_Y*$SCALEX;
   } elseif ($THUMB_X == "0") {
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