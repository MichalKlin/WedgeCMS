<script>
function translate(obj,langs){
	//parsowanie XML
	var xml = document.getElementById(obj).value;
	var tab = langs.split("|");
	//czyszczenie pól
	for(var j=0; j<tab.length-1; j++){
		var el = tab[j];
		document.getElementById(el).value = "";
	}
	try {
		var xmlDoc = new DOMParser().parseFromString(xml, "text/xml");
		var node = xmlDoc.getElementsByTagName('data')[0];

		// Wyznaczanie poszczegolnych tlumaczen:
		var nodes = node.getElementsByTagName('transl');
		for(var n = 0; n < nodes.length; n++) {
			node = nodes[n];
			for(var i=0; i<tab.length-1; i++){
				var el = tab[i];
				if(node.getAttribute('lang')==el && node.firstChild != null){
					document.getElementById(el).value = node.firstChild.nodeValue;
				}
			}
		}
	} catch(err) {
		//alert(err);
		document.getElementById('PL').value = xml;
	}

	document.getElementById('form_trans').style.display = "";
	document.getElementById('pole_trans').value = obj;
	document.getElementById('lang_trans').value = langs;
}
function translateXML(){
	document.getElementById('form_trans').style.display = "none";
	var pole = document.getElementById('pole_trans').value;
	var langs = document.getElementById('lang_trans').value;
	var tab = langs.split("|");
	var xml = "<data>";

	for(var i=0; i<tab.length-1; i++){
		var el = tab[i];
		var item = document.getElementById(el).value;
		xml = xml + "<transl lang='" + el + "'>" + item + "</transl>";
	}
	xml = xml + "</data>";
	document.getElementById(pole).value = xml;
}
function closeXML(){
	document.getElementById('form_trans').style.display = "none";
}
</script>
<center>

<div id="form_trans" style="display: none; position:absolut;top:100px; width: 200px; height: 200px; background-color: #efefef; border: 1px solid #888;">
<input type="hidden" name="pole_trans" id="pole_trans" value="" />
<input type="hidden" name="lang_trans" id="lang_trans" value="" />
<?

include_once('../class/translation.php');
$trans = new Translation($baza);

$res = $baza->select("*","cmstranslatelang","","order by id");
$ile = $baza->size_result($res);
$lista_jezykow = "";
if ($ile>0){
	?>
	<table>
	<?
	for ($i=0; $i<$ile; $i++){
		$row = $baza->row($res);
		$lista_jezykow .= $row[code]."|";
		?>
		<tr>
			<td>
		<?=$row[name]?></td><td> <input type="text" name="<?=$row[code]?>" id="<?=$row[code]?>" />
			</td>
		</tr>
		<?
	}
	?>
	</table>
	<br>
	<input type="submit" name="createxml" value="Utwórz tłumaczenie" onclick="translateXML()" />
	<input type="submit" name="cancel" value="Anuluj" onclick="closeXML()" />
	<?
}
?>
</div>


<?		
if (!isset($_GET[groups]) and !isset($_GET[langs])){
	if (isset($_POST[groupTrans])){
		$_SESSION[groupTrans] = $_POST[groupTrans];
	}
	if (isset($_POST[langTrans])){
		$_SESSION[langTrans] = $_POST[langTrans];
	}
	
	//dodanie tłumaczenia
	if(isset($_POST[save])){
		if(strlen($_POST[code])>0 and strlen($_POST[name])>0){
			$r = $baza->insert("cmstranslate","0,0,'$_POST[code]','$_POST[name]',$_POST[group]");
		}
	}
	
	//zmiana tłumaczeń
	if(isset($_POST[update])){
		$where = "";
		if (isset($_SESSION[groupTrans]) and $_SESSION[groupTrans] != 0){
			$where = "trans_group=".$_SESSION[groupTrans];
		}
		$result = $baza->select("*","cmstranslate",$where,"","");
		$ile = $baza->size_result($result);
		if ($ile>0){
			for ($i=0; $i<$ile; $i++){
				$row = $baza->row($result);
				$code = "code_".$row[id];
				$name = "name_".$row[id];
				if(strlen($_POST[$code])>0 and strlen($_POST[$name])>0){
					$r = $baza->update("cmstranslate","code='$_POST[$code]', xml='$_POST[$name]'","id=$row[id]");
				}
			}
		}
	}
	?>
	
	<br>
	
	<form method="POST" action="">
	<table border="1">
		<tr>
			<th>Kod</th>
			<th>Wartość</th>
			<th>Grupa</th>
		</tr>
		<tr>
			<td><input type="text"  name="code" style="width:100%" /></td>
			<td><input type="text"  name="name" id="name" style="width:100%" readonly 
					onclick="translate('name','<?=$lista_jezykow?>')" /></td>
			<td>
			<select name="group">
			<?
			$res = $baza->select("*","cmstranslategroup","","order by code");
			$ile = $baza->size_result($res);
			for ($i=0; $i<$ile; $i++){
				$row = $baza->row($res);
				?>
				<option <?if($row[id]==$_SESSION[groupTrans]) echo "selected";?>  value="<?=$row[id]?>"><?=$row[code]?> | <?=$row[name]?></option>
				<?
			}
			?>
		</select>
			</td>
		</tr>
	</table>
	<br>
	<input type="submit" name="save" value="Dodaj tłumaczenie" />
	</form>

	<br />
	<form method="POST" action="">
		wybierz język: <select name="langTrans" onchange="submit(this)">
			<?
			$res = $baza->select("*","cmstranslatelang","","order by id");
			$ile = $baza->size_result($res);
			for ($i=0; $i<$ile; $i++){
				$row = $baza->row($res);
				?>
				<option <?if($row[code]==$_SESSION[langTrans]) echo "selected";?>  value="<?=$row[code]?>"><?=$row[name]?></option>
				<?
			}
			?>
		</select>
		
		wybierz grupę: <select name="groupTrans" onchange="submit(this)">
			<option value="0"> --- </option>
			<?
			$res = $baza->select("*","cmstranslategroup","","order by code");
			$ile = $baza->size_result($res);
			for ($i=0; $i<$ile; $i++){
				$row = $baza->row($res);
				?>
				<option <?if($row[id]==$_SESSION[groupTrans]) echo "selected";?>  value="<?=$row[id]?>"><?=$row[code]?> | <?=$row[name]?></option>
				<?
			}
			?>
		</select>
		&nbsp;&nbsp;&nbsp;<a href="?admin=&admin_trans=&groups=">edytuj grupy</a>
		&nbsp;&nbsp;&nbsp;<a href="?admin=&admin_trans=&langs=">edytuj języki</a>
	</form>

	<br />
	<?
	$where = "";
	if (isset($_SESSION[groupTrans]) and $_SESSION[groupTrans] != 0){
		$where = "trans_group=".$_SESSION[groupTrans];
	}
	$result = $baza->select("*","cmstranslate",$where,"","");
	$ile = $baza->size_result($result);
	if ($ile>0){
		?>
		<br>
		<form method="POST" action="">
		<table border="1">
			<tr>
				<th>Kod</th>
				<th>Wartość</th>
			</tr>
		<?
		for ($i=0; $i<$ile; $i++){
			$row = $baza->row($result);
			?>
			<tr>
				<td><input type="text" name="code_<?=$row[id]?>" id="code_<?=$row[id]?>" value="<?=$row[code]?>" style="width: 300px;" /></td>
				<td><input type="text" name="trans_<?=$row[id]?>" id="trans_<?=$row[id]?>" value="<?=trans($trans,$row[xml],$_SESSION[langTrans])?>" readonly 
					onclick="translate('name_<?=$row[id]?>','<?=$lista_jezykow?>')" style="width: 300px;" />
					<input type="hidden" name="name_<?=$row[id]?>" id="name_<?=$row[id]?>" value="<?=$row[xml]?>" />
				</td>
			</tr>
			<?
		}
		?>
		</table>
		<input type="submit" name="update" value="Zapisz zmiany"/>
		</form>
		<?
	}
} 
elseif (isset($_GET[langs])){
	// edycja języków

	//zapis zamin w grupach
	if(isset($_POST[update_lang])){
		$res = $baza->select("*","cmstranslatelang","","order by id");
		$ile = $baza->size_result($res);
		if ($ile>0){
			for ($i=0; $i<$ile; $i++){
				$row = $baza->row($res);
				$id = $row[id];
				$code_inp = "code_".$id;
				$name_inp = "name_".$id;
				if(strlen($_POST[$code_inp])>0 and strlen($_POST[$name_inp])>0){
					$r = $baza->update("cmstranslatelang","code='$_POST[$code_inp]', name='$_POST[$name_inp]'","id=".$id);
				}
			}	
		}
	}
	
	//dodanie nowej grupy
	if(isset($_POST[save_lang])){
		if(strlen($_POST[code])>0 and strlen($_POST[name])>0){
			$r = $baza->insert("cmstranslatelang","0,'$_POST[code]','$_POST[name]'");
		}
	}
	
	$res = $baza->select("*","cmstranslatelang","","order by id");
	$ile = $baza->size_result($res);
	if ($ile>0){
		?>
		<br>
		<form method="POST" action="">
		<table border="1">
			<tr>
				<th>Kod</th>
				<th>Nazwa</th>
			</tr>
			<tr>
				<td><input type="text"  name="code" style="width:100%" /></td>
				<td><input type="text"  name="name" style="width:100%" /></td>
			</tr>
		</table>
		<br />
		<input type="submit" name="save_lang" value="Dodaj język" />
		</form>
		
		<br>
		<form method="POST" action="">
		<table border="1">
			<tr>
				<th>Kod</th>
				<th>Nazwa</th>
			</tr>
		<?
		for ($i=0; $i<$ile; $i++){
			$row = $baza->row($res);
			?>
			<tr>
				<td><input type="text" value="<?=$row[code]?>" name="code_<?=$row[id]?>" style="width:100%" /></td>
				<td><input type="text" value="<?=$row[name]?>" name="name_<?=$row[id]?>" style="width:100%" /></td>
			</tr>
			<?
		}
		?>
		</table>
		<br />
		<input type="submit" name="update_lang" value="Zapisz zmiany" />
		</form>
		<?
	}
	?>
	<p>
		<a href="?admin=&admin_trans=">powrót</a>
	</p>
	<?	
}
elseif (isset($_GET[groups])){
	// grupy tłumaczeń

	//zapis zamin w grupach
	if(isset($_POST[update_group])){
		$res = $baza->select("*","cmstranslategroup","","order by code");
		$ile = $baza->size_result($res);
		if ($ile>0){
			for ($i=0; $i<$ile; $i++){
				$row = $baza->row($res);
				$id = $row[id];
				$code_inp = "code_".$id;
				$name_inp = "name_".$id;
				if(strlen($_POST[$code_inp])>0 and strlen($_POST[$name_inp])>0){
					$r = $baza->update("cmstranslategroup","code='$_POST[$code_inp]', name='$_POST[$name_inp]'","id=".$id);
				}
			}	
		}
	}
	
	//dodanie nowej grupy
	if(isset($_POST[save_group])){
		if(strlen($_POST[code])>0 and strlen($_POST[name])>0){
			$r = $baza->insert("cmstranslategroup","0,'$_POST[code]','$_POST[name]'");
		}
	}
	
	$res = $baza->select("*","cmstranslategroup","","order by code");
	$ile = $baza->size_result($res);
	if ($ile>0){
		?>
		<br>
		<form method="POST" action="">
		<table border="1">
			<tr>
				<th>Kod</th>
				<th>Wartość</th>
			</tr>
			<tr>
				<td><input type="text"  name="code" style="width:100%" /></td>
				<td><input type="text"  name="name" style="width:100%" /></td>
			</tr>
		</table>
		<br />
		<input type="submit" name="save_group" value="Dodaj grupę" />
		</form>
		
		<br>
		<form method="POST" action="">
		<table border="1">
			<tr>
				<th>Kod</th>
				<th>Wartość</th>
			</tr>
		<?
		for ($i=0; $i<$ile; $i++){
			$row = $baza->row($res);
			?>
			<tr>
				<td><input type="text" value="<?=$row[code]?>" name="code_<?=$row[id]?>" style="width:100%" /></td>
				<td><input type="text" value="<?=$row[name]?>" name="name_<?=$row[id]?>" style="width:100%" /></td>
			</tr>
			<?
		}
		?>
		</table>
		<br />
		<input type="submit" name="update_group" value="Zapisz zmiany" />
		</form>
		<?
	}
	?>
	<p>
		<a href="?admin=&admin_trans=">powrót</a>
	</p>
	<?
}
?>
</center>

<?
function trans($trans,$xml,$lang){ 
	return $trans->getTranslationXML($xml, $lang);
}
?>