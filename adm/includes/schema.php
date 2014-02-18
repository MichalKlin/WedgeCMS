<h3>Zarządzanie wzorami stron</h3>
<?
include_once('includes/functions.php');

//wyświetlenie rekordów
if (!isset($_GET['dodaj']) and !isset($_GET['usun']) and !isset($_GET['edytuj']) 
	and !isset($_GET['modul']) and !isset($_GET['kopiuj'])){
	if (check_rules($baza,'schema','insert')){
		?><p><a href="?schema=&dodaj=" >Dodaj</a> </p><?
	}
	
	$res = $baza->select("*","cmsschema","","ORDER BY name ASC");
	if (($ile = $baza->size_result($res))>0){
		?>
		<table border="0" cellpadding="2" cellspacing="0" class="tab_edycji">
			<tr>
				<th>LP</th>
				<th>Nazwa</th>
				<th>Template</th>
				<th>Domyślna</th>
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
				<td><?=$row[name];?></td>
				<td>
					<?
					$r=$baza->select("name","cmstemplate",
					"id=".$row[template],
					"","");
					$roow = $baza->row($r);
					echo $roow[name];
					?>
				</td>
				<td><?if($row[defaultSchema]=='YES') echo "TAK"; else echo 'NIE';?></td>
				<td><?if($row[active]=='YES') echo "TAK"; else echo 'NIE';?></td>
				<td>
					<?if (check_rules($baza,'schema','update')){?>
					<a href="?schema=&edytuj=<?=$row[id];?>">Edytuj</a>
					<?
					}
					if (check_rules($baza,'schema','delete')){
					?>
					<a href="?schema=&usun=<?=$row[id];?>">Usuń</a>
					<?
					}
					if (check_rules($baza,'schema_modules','select')){
					?>
					<a href="?schema=&modul=<?=$row[id];?>">Moduły</a>
					<?}
					if (check_rules($baza,'schema_copy','select')){
					?>
					<a href="?schema=&kopiuj=<?=$row[id];?>">Kopiuj</a>
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

//kopiowanie schematu
if (isset($_GET['kopiuj'])){
	$res = $baza->select("*","cmsschema","id=".$_GET['kopiuj'],"","");
	$row = $baza->row($res);
	if (!isset($_POST['kopiuj'])){
		?>
		<h3>Kopiowanie schematu z: <span style="color: blue"><?=$row[name]?></span></h3><br />
		<form method="POST" id="f" action="">
		<table border="0" cellpadding="0" cellspacing="0" class="tab_edycji">
			<tr>
				<th>Nazwa nowego schematu:</th>
				<td><input type="text" name="name" size="30" /></td>
			</tr>
			<tr>
				<th>Domyślny:</th>
				<td><input type="radio" name="default" value="YES" /> TAK 
					<input type="radio" name="default" value="NO" checked /> NIE 
				</td>
			</tr>
			<tr>
				<th>Aktywność:</th>
				<td><input type="radio" name="active" value="YES" checked /> TAK 
					<input type="radio" name="active" value="NO" /> NIE
				</td>
			</tr>
		</table>
		<p><input type="submit" name="kopiuj" value="Dodaj" /></p>
		</form>
		<?
	}else{
		$template = $row[template];
		if (strlen($_POST[name])>0){
			if($_POST['default']=='YES'){
				// default na NO dal wszystkich
				$rr = $baza->update("cmsschema","defaultSchema='NO'","","");
			}
			
			//dodanie schematu
			$kolumny = "(id,template,name,defaultSchema, active)";
			$values = "0,".$template.", '".htmlspecialchars($_POST['name'])."',
			 		'".htmlspecialchars($_POST['default'])."',
			  		'".htmlspecialchars($_POST['active'])."'";
			$res = $baza->insert("cmsschema",$values,$kolumny,"");
			if ($res)
				echo "<p>Nowa schema została zapisany w bazie.</p>";
			$schema_id = $baza->last_insert_id();	
			
			//dodanie modułów
			$res2 = $baza->select("*","cmsschemamodule","cmsschemamodule.schema=".$_GET['kopiuj']." and active='YES'","","");
			if (($ile2=$baza->size_result($res2))>0){
				for ($i=0; $i<$ile2; $i++){
					$row2 = $baza->row($res2);
					$kolumny = "(id,cmsschemamodule.schema,module,sekcja,cmsschemamodule.order, active, config,template)";
					$values = "0,$schema_id,$row2[module], $row2[sekcja],
					 		$row2[order],'YES','".$row2[config]."',$row2[template]";
					$res3 = $baza->insert("cmsschemamodule",$values,$kolumny,"");
				}
			}
		}
		else{
			?>
			<p class="error">Należy podać nazwę nowego schematu!</p>
			<p><a href="?schema=&kopiuj=<?=$_GET[kopiuj]?>">Popraw</a></p>
			<?
		}
	}
	echo "<p><a href=\"?schema=\">Powrót do listy schematów</a></p>";
}

//dodanie
if (isset($_GET['dodaj'])){
	if (!isset($_POST['dodaj'])){
		?>
		<form method="POST" id="f" action="">
		<table border="0" cellpadding="0" cellspacing="0" class="tab_edycji">
			<tr>
				<th>Nazwa schematu:</th>
				<td><input type="text" name="name" size="30" /></td>
			</tr>
			<tr>
				<th>Template:</th>
				<td>
					<?
					$r=$baza->select("id,name","cmstemplate","",
					"order by cmstemplate.default ASC, name");
					?>
					<select name="template">
					<?
					$ile_temp = $baza->size_result($r);
					for ($i=0; $i<$ile_temp; $i++){	
						$roow = $baza->row($r);
					?>	
						<option value="<?=$roow[id];?>"><?=$roow[name];?></option>
					<?
					}
					?>
					</select>
				</td>
			</tr>
			<tr>
				<th>Domyślny:</th>
				<td><input type="radio" name="default" value="YES" /> TAK 
					<input type="radio" name="default" value="NO" checked /> NIE 
				</td>
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
			if($_POST['default']=='YES'){
				// default na NO dal wszystkich
				$rr = $baza->update("cmsschema","defaultSchema='NO'","","");
			}
			$res = $baza->select("1","cmsschema","name='".$_POST['name']."'");
			if ($baza->size_result($res)==0){
				$kolumny = "(id,template,name,defaultSchema, active)";
				$values = "0,".$_POST['template'].", '".htmlspecialchars($_POST['name'])."',
				 		'".htmlspecialchars($_POST['default'])."',
				  		'".htmlspecialchars($_POST['active'])."'";
				$res = $baza->insert("cmsschema",$values,$kolumny,"");
				if ($res)
					echo "<p>Nowa schema została zapisany w bazie.</p>";
			}
			else{
				echo "<p>Istnieje już schema o podanej nazwie!</p>";	
			}
		}
		else{
			echo "<p>Nie podano nazwy Schematu!</p>";
		}
	}
	echo "<p><a href=\"?schema=\">Powrót</a></p>";
}

//edycja schemy 
//dodanie
if (isset($_GET['edytuj'])){
	if (!isset($_POST['edytuj'])){
		$res = $baza->select("*","cmsschema","id=".$_GET['edytuj'],"","");
		$row = $baza->row($res);
		?>
		<form method="POST" id="f" action="">
		<table border="0" cellpadding="0" cellspacing="0" class="tab_edycji">
			<tr>
				<th>Nazwa schematu:</th>
				<td><input type="text" name="name" size="30" value="<?=$row[name]?>" /></td>
			</tr>
			<tr>
				<th>Template:</th>
				<td>
					<?
					$r=$baza->select("id,name","cmstemplate");
					?>
					<select name="template">
					<?
					$ile_temp = $baza->size_result($r);
					for ($i=0; $i<$ile_temp; $i++){	
						$roow = $baza->row($r);
						if ($roow[id]==$row[template]) $selected="selected"; else $selected="";
					?>	
						<option value="<?=$roow[id];?>" <?=$selected;?>><?=$roow[name];?></option>
					<?
					}
					?>
					</select>
				</td>
			</tr>
			<tr>
				<th>Domyślny:</th>
				<td><input type="radio" name="default" value="YES" <?if ($row[defaultSchema]=='YES') echo "checked";?> /> TAK 
					<input type="radio" name="default" value="NO" <?if ($row[defaultSchema]=='NO') echo "checked";?> /> NIE 
				</td>
			</tr>
			<tr>
				<th>Aktywność:</th>
				<td><input type="radio" name="active" value="YES" <?if ($row[active]=='YES') echo "checked";?> /> TAK 
					<input type="radio" name="active" value="NO" <?if ($row[active]=='NO') echo "checked";?> /> NIE 
				</td>
			</tr>
		</table>
		<p><input type="submit" name="edytuj" value="Zachowaj zmiany" /></p>
		</form>
		<?
	}
	else{
		if (strlen(trim($_POST['name']))>0){
			if($_POST['default']=='YES'){
				// default na NO dal wszystkich
				$rr = $baza->update("cmsschema","defaultSchema='NO'","","");
			}
			$kolumny = "(id,template,name,defaultSchema, active)";
			$values = "template=".$_POST['template'].",
			 		name='".htmlspecialchars($_POST['name'])."',
			 		defaultSchema='".htmlspecialchars($_POST['default'])."',
			  		active='".htmlspecialchars($_POST['active'])."'";
			$where = "id=".$_GET['edytuj'];
			$res = $baza->update("cmsschema",$values,$where);
			if ($res)
				echo "<p>Zmiany zostały zapisane w bazie.</p>";
		}
		else{
			echo "<p>Nie podano nazwy Schematu!</p>";
		}
	}
	echo "<p><a href=\"?schema=\">Powrót</a></p>";
}


// usuniecie
if (isset($_GET['usun'])){
	if (!isset($_POST['usun'])) {			
		echo "<p>Czy na pewno usunąć tą schemę?</p>";
		echo "<form name=\"usun_form\" method=\"post\">";
		echo "<p><input type=\"submit\" name=\"usun\" value=\"Usuń\" /></p>";
		echo "</form>";					
	}
	else{			
		$rr = $baza->select("*","cmsschema","id=".$_GET['usun']);
		$roww = $baza->row($rr);
		
	   	$res=$baza->delete("cmsschema","id=".$_GET['usun']);
	   	// ustawienie domyślnego
   		if($roww['defaultSchema']=='YES'){
			// default na YES dla pierwzego lepszego
			$rr = $baza->update("cmsschema","defaultSchema='YES' LIMIT 1","","");
		}

		if ($res) 
	   		echo "<p>Schema została usunięta</p>"; 		
   		else 
   			echo "<p class=\"error\">Błąd zapisu w bazie</p>"; 			
	}
	echo "<p><a href=\"?schema=\">Powrót</a></p>";	
}

// moduły
if (isset($_GET['modul'])){
	//usunięcie modułu z schemy
	if (isset($_GET['usun_m'])){
	   	$res=$baza->delete("cmsschemamodule","id=".$_GET['usun_m']);
   		if (!$res) 
   			echo "<p class=\"error\">Błąd zapisu w bazie</p>"; 			
	}

	//dodanie modułu do schemy
	if (isset($_POST['dodaj_m'])){
		$kolumny = "(id,cmsschemamodule.schema,module,sekcja,cmsschemamodule.order, active, config)";
		$values = "0,".$_GET['modul'].",".$_POST['modul'].", '".$_GET['dodaj_m']."',
		 		'".htmlspecialchars($_POST['order'])."','YES',''";
		$res = $baza->insert("cmsschemamodule",$values,$kolumny,"");
		if ($res){
			echo "<p>Moduł dodany do sekcji.</p>";
			$id_last = $baza->last_insert_id();
			$r=$baza->select("cmsschemamodule.order as 'order',cmsschemamodule.module as module,cmsschemamodule.template as template","cmsschemamodule","cmsschemamodule.id=".$id_last,"","");
			$row = $baza->row($r);			
			?>
			<form method="POST" id="f" action="?schema=&modul=<?=$_GET[modul]?>&modyf_m=<?=$id_last?>">
			<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<th>Kolejność w sekcji:&nbsp;</th>
					<td><input type="text" name="order" size="30" class="input" style="width: 250px;" value="<?=$row[order];?>" /></td>
				</tr>
				<tr>
					<th>Szablon modułu:</th>
					<td>
						<select name="template" class="input" style="width: 250px;">
							<option value="0"></option>
						<?
						$res_t = $baza->select("*","cmsmoduletemplate","active='YES' and module=".$row['module'],"order by name","a");
						if (($ile = $baza->size_result($res_t))>0){
							for ($i=0; $i<$ile; $i++){
								$row_t = $baza->row($res_t);
								?>
								<option value="<?=$row_t[id]?>" <?if ($row_t[id]==$row[template] and $row[template]!=0 or $row[template]==0 and $row_t['default']=='YES') echo "selected";?>><?=$row_t[name]?></option>
								<?
							}
						}
						?>
						</select>
					</td>
				</tr>
			<?
			$rr = $baza->select("*","cmsparam","id_modul=$row[module]");
			if(($ile=$baza->size_result($rr))>0){
			?>
			<tr>
				<th colspan="2"><center>parametry dodatkowe</center></th>
			</tr>
			<?
				for ($k=0; $k<$ile; $k++){
					$roww = $baza->row($rr);
					?>
					<tr>
						<th><?=$roww[name]?>:</th>
						<td>
						<?
						$r3 = $baza->select("*","cmsschemamodulparam","schemamodul_id=$id_last and param_id=$roww[id]");
						$ile3 = $baza->size_result($r3);
						// jest już wpis
						if ($ile3>0){
							
						}
						
						//wartość domyślna parametru
						else{
							if ($roww[type]=='LIST'){
								$r4 = $baza->select("*","cmsparamvalue","id_param=$roww[id]","","");
								$ile4 = $baza->size_result($r4);
								if ($ile4>0){
									?>
									<select name="param_<?=$roww[id]?>" style="width:100%">
									<?
									for($l=0; $l<$ile4; $l++){									
										$row4 = $baza->row($r4); 
										?>
										<option <?if($row4['default']=='YES') echo "selected";?>"><?=$row4[value]?></option>
										<?
									}
									?>
									</select>
									<?
								}
							}elseif ($roww[type]=='YES/NO'){
								$r4 = $baza->select("*","cmsparamvalue","id_param=$roww[id]","","");
								$row4 = $baza->row($r4); 
								?>
								<select name="param_<?=$roww[id]?>" style="width:100%">
									<option value="YES" <?if($row4[value]=='YES') echo "selected";?>">YES</option>
									<option value="NO" <?if($row4[value]=='NO') echo "selected";?>">NO</option>
								</select>
								<?
							}else{
								$r4 = $baza->select("*","cmsparamvalue","id_param=$roww[id]");
								$row4 = $baza->row($r4);
								?>
								<input type="text" name="param_<?=$roww[id]?>" style="width:100%" value="<?=$row4[value]?>" />
								<?
							}
						}
						?>
						</td>
					</tr>
					<?
				}
			}
			?>
			</table>
			<p><input type="submit" name="modyf_m" value="Zapisz zmiany" /></p>
			</form>
			<br>
			<a href="?schema=&modul=<?=$_GET[modul]?>">powrót do edycji modułów</a>
			<?
		}
	}
	
	//edycja modułu
	if (isset($_POST['modyf_m'])){
		$where = "cmsschemamodule.id=".$_GET['modyf_m'];
		$values = "cmsschemamodule.order='".htmlspecialchars($_POST['order'])."',cmsschemamodule.template=$_POST[template]";
		$res = $baza->update("cmsschemamodule",$values,$where,"");
		
		$r = $baza->select("module","cmsschemamodule","id=$_GET[modyf_m]");
		$row = $baza->row($r);
		
		//zapis parametrów modułu
		$rr = $baza->select("*","cmsparam","id_modul=$row[module]");
		if(($ile=$baza->size_result($rr))>0){
			for ($k=0; $k<$ile; $k++){
				$roww = $baza->row($rr);
				$par = "param_".$roww[id];
				
				$ra = $baza->select("*","cmsschemamodulparam","schemamodul_id=$_GET[modyf_m] and param_id=$roww[id]");
				if ($baza->size_result($ra)>0){
					$baza->update("cmsschemamodulparam","value='$_POST[$par]'","schemamodul_id=$_GET[modyf_m] and param_id=$roww[id]","");
				}else{
					$baza->insert("cmsschemamodulparam","0,$_GET[modyf_m],$roww[id],'$_POST[$par]'","","");
				}
			}		
		}
		
		if ($res){
			echo "<p>Zmiany zapisane poprawnie.</p>";
		}
	}
	
	if (isset($_GET['dodaj_m'])){
		if (!isset($_POST['dodaj_m'])){
			?>
		<form method="POST" id="f" action="">
		<table border="0" cellpadding="0" cellspacing="0" class="tab_edycji">
			<tr>
				<th>Moduł:</th>
				<td>
					<?
					$r=$baza->select("idModule,name","cmsmodule","active='YES'","ORDER BY name ASC","");
					?>
					<select name="modul">
					<?
					$ile_mod = $baza->size_result($r);
					for ($i=0; $i<$ile_mod; $i++){	
						$row = $baza->row($r);
					?>	
						<option value="<?=$row[idModule];?>"><?=$row[name];?></option>
					<?
					}
					?>
					</select>
				</td>
			</tr>
			<tr>
				<th>Kolejność w sekcji:</th>
				<td><input type="text" name="order" size="30" value="0" /></td>
			</tr>
		</table>
		<p><input type="submit" name="dodaj_m" value="Dodaj" /></p>
		</form>
		<br>
		<a href="?schema=&modul=<?=$_GET[modul]?>">powrót do edycji modułów</a>
			<?
		}
	}
	elseif (isset($_GET['modyf_m'])){
		if (!isset($_POST['modyf_m'])){
			$r=$baza->select("cmsschemamodule.order as 'order',cmsschemamodule.module as module,cmsschemamodule.template as template","cmsschemamodule","cmsschemamodule.id=".$_GET['modyf_m'],"","");
			$row = $baza->row($r);			
		?>
		<form method="POST" id="f" action="">
		<table border="0" cellpadding="0" cellspacing="0">
			<tr>
				<th>Kolejność w sekcji:&nbsp;</th>
				<td><input type="text" name="order" size="30" class="input" style="width: 250px;" value="<?=$row[order];?>" /></td>
			</tr>
			<tr>
				<th>Szablon modułu:</th>
				<td>
					<select name="template" class="input" style="width: 250px;">
						<option value="0"></option>
					<?
					$res_t = $baza->select("*","cmsmoduletemplate","active='YES' and module=".$row['module'],"order by name","a");
					if (($ile = $baza->size_result($res_t))>0){
						for ($i=0; $i<$ile; $i++){
							$row_t = $baza->row($res_t);
							?>
							<option value="<?=$row_t[id]?>" <?if ($row_t[id]==$row[template] and $row[template]!=0 or $row[template]==0 and $row_t['default']=='YES') echo "selected";?>><?=$row_t[name]?></option>
							<?
						}
					}
					?>
					</select>
				</td>
			</tr>
			<?
			$rr = $baza->select("*","cmsparam","id_modul=$row[module]");
			if(($ile=$baza->size_result($rr))>0){
			?>
			<tr>
				<th colspan="2"><center>parametry dodatkowe</center></th>
			</tr>
			<?
				for ($k=0; $k<$ile; $k++){
					$roww = $baza->row($rr);
					?>
					<tr>
						<th><?=$roww[name]?>:</th>
						<td>
						<?
						$r3 = $baza->select("*","cmsschemamodulparam","schemamodul_id=$_GET[modyf_m] and param_id=$roww[id]");
						$ile3 = $baza->size_result($r3);
						// jest już wpis
						if ($ile3>0){
							$row_p = $baza->row($r3);
							if ($roww[type]=='LIST'){
								$r4 = $baza->select("*","cmsparamvalue","id_param=$roww[id]","","");
								$ile4 = $baza->size_result($r4);
								if ($ile4>0){
									?>
									<select name="param_<?=$roww[id]?>" style="width:100%">
									<?
									for($l=0; $l<$ile4; $l++){									
										$row4 = $baza->row($r4); 
										?>
										<option <?if($row_p[value]==$row4[value]) echo "selected";?>"><?=$row4[value]?></option>
										<?
									}
									?>
									</select>
									<?
								}
							}elseif ($roww[type]=='YES/NO'){
								$r4 = $baza->select("*","cmsparamvalue","id_param=$roww[id]","","");
								$row4 = $baza->row($r4); 
								?>
								<select name="param_<?=$roww[id]?>" style="width:100%">
									<option value="YES" <?if($row_p[value]=='YES') echo "selected";?>">YES</option>
									<option value="NO" <?if($row_p[value]=='NO') echo "selected";?>">NO</option>
								</select>
								<?
							}else{
								$r4 = $baza->select("*","cmsparamvalue","id_param=$roww[id]");
								$row4 = $baza->row($r4);
								?>
								<input type="text" name="param_<?=$roww[id]?>" style="width:100%" value="<?=$row_p[value]?>" />
								<?
							}							
						}
						
						//wartość domyślna parametru
						else{
							if ($roww[type]=='LIST'){
								$r4 = $baza->select("*","cmsparamvalue","id_param=$roww[id]","","");
								$ile4 = $baza->size_result($r4);
								if ($ile4>0){
									?>
									<select name="param_<?=$roww[id]?>" style="width:100%">
									<?
									for($l=0; $l<$ile4; $l++){									
										$row4 = $baza->row($r4); 
										?>
										<option <?if($row4['default']=='YES') echo "selected";?>"><?=$row4[value]?></option>
										<?
									}
									?>
									</select>
									<?
								}
							}elseif ($roww[type]=='YES/NO'){
								$r4 = $baza->select("*","cmsparamvalue","id_param=$roww[id]","","");
								$row4 = $baza->row($r4); 
								?>
								<select name="param_<?=$roww[id]?>" style="width:100%">
									<option value="YES" <?if($row4[value]=='YES') echo "selected";?>">YES</option>
									<option value="NO" <?if($row4[value]=='NO') echo "selected";?>">NO</option>
								</select>
								<?
							}else{
								$r4 = $baza->select("*","cmsparamvalue","id_param=$roww[id]");
								$row4 = $baza->row($r4);
								?>
								<input type="text" name="param_<?=$roww[id]?>" style="width:100%" value="<?=$row4[value]?>" />
								<?
							}
						}
						?>
						</td>
					</tr>
					<?
				}
			}
			?>
		</table>
		<p><input type="submit" name="modyf_m" value="Zapisz zmiany" /></p>
		</form>
		<br>
		<a href="?schema=&modul=<?=$_GET[modul]?>">powrót do edycji modułów</a>
		<?
		}
	}
	
	//wyświetlenie struktury schemy z modułami
	if ((!isset($_GET['modyf_m']) or isset($_POST['modyf_m'])) and !isset($_GET['dodaj_m'])) {	
		include_once("functions/modul_w_sekcji.php");		
		$r=$baza->select("*","cmstemplate,cmsschema","cmsschema.template=cmstemplate.id AND cmsschema.id=".$_GET['modul'],"","");
		$row = $baza->row($r);
		
		$res_sek = $baza->select("*","cmstemplate","id=".$row['template'],"","");
		$row_sek = $baza->row($res_sek);
		
		$res = $baza->select("*","cmstemplate","id='".$row['template']."'");
		$row = $baza->row($res);
		$file_path = '../template/'.$row['file'];
		$fd = fopen($file_path,'r');
		$cont = fread($fd,filesize($file_path));
		fclose($fd);
		
		$cont = substr($cont, strpos("</head>",$con));
		$regex = array('/<img[^>]*>/i','/<p([^>]*)>([^<]*)<\/p>/i','/<a([^>]*)>([^<]*)<\/a>/i','/<td([^>]*)>([^<]*)<\/td>/i');
		$mixed = array('','','','<td$1></td>');
		$cont = preg_replace($regex,$mixed,$cont);
		
		for ($j=0; $j<23; $j++){
			$cont = str_replace('<div id="sekcja'.$j.'"></div>',
			'<div id="sekcja'.$j.'" style="border: 1px solid blue; padding:5px; margin:1px; height: 100%;" '.check_section_selected($row_sek,$j,'YES').' ><center>
			'.moduly_w_sekcji_schema($baza,$row,$j,$_GET[modul]).' 
			</center></div>',$cont);
		}
		?>
		<form method="POST" id="f" action="">
		<div width="98%" height="100%">
			<?=$cont?>
		</div>
		<?
	}
	echo "<p><a href=\"?schema=\">Powrót</a></p>";	
}
?>