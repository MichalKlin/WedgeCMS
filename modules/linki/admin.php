<?php
if (isset($_GET[pages]))
	$link = "?pages=&p=$_GET[p]&modul=&manage=$_GET[manage]";
else 
	$link = "?modules=&manage=$_GET[manage]";	

$tabela = "dictionary_linki_grupy";
$tabela_link = "mod_linki";

//zapis zmian w grupach linków
if (isset($_POST['zapisz_grupy'])){
	$result = $baza->select("*",$tabela, "", "ORDER BY dlg_id");	
	if (($ile = $baza->size_result($result))>0){
		for ($i=0; $i<$ile; $i++){
			$row = $baza->row($result);	
			$values = "dlg_nazwa=\"".$_POST['nazwa'.$i]."\",
				dlg_active=\"".$_POST['active'.$i]."\",
				dlg_order=".$_POST['order'.$i];
			$where = "dlg_id=".$row['dlg_id'];
			$baza->update($tabela,$values,$where);
		}	
	}
}

//dodanie nowej grupy linków
if (isset($_POST['dodaj_grupe'])){
	$values = "0,\"".$_POST['nazwa']."\",\"".$_POST['active']."\",".$_POST['order'];
	$baza->insert($tabela,$values,"","");
}

//usunięcie grupy linków
if (isset($_GET['usun_grupe'])){
	$baza->delete($tabela,"dlg_id=".$_GET['usun_grupe']);
}

######################
# Linki#
######################
//zapis zmian w linkach
if (isset($_POST['zapisz_linki'])){
	$result = $baza->select("*",$tabela_link, "", "ORDER BY mlin_id");	
	if (($ile = $baza->size_result($result))>0){
		for ($i=0; $i<$ile; $i++){
			$row = $baza->row($result);	
			$values = "mlin_grupa=\"".$_POST['grupa'.$i]."\",
				mlin_active=\"".$_POST['active'.$i]."\",
				mlin_link=\"".$_POST['link'.$i]."\",
				mlin_opis=\"".$_POST['opis'.$i]."\"";
			$where = "mlin_id=".$row['mlin_id'];
			$baza->update($tabela_link,$values,$where);
		}	
	}
}

//dodanie nowego linku
if (isset($_POST['dodaj_link']) and strlen($_POST[grupa])>0){
	$values = "0,\"".$_POST['grupa']."\",\"".$_POST['link']."\",\"".$_POST['opis']."\",\"".$_POST['active']."\"";
	$baza->insert($tabela_link,$values,"","");
}

//usunięcie linku
if (isset($_GET['usun_link'])){
	$baza->delete($tabela_link,"mlin_id=".$_GET['usun_link']);
}


?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
<td class="center" style="padding: 10px;">
<h4>Grupy linków:</h4>
<br />
<?

$result = $baza->select("*",$tabela, "", "ORDER BY dlg_id");	
?>
	<center>
	<form method="POST" action="">
	<table border="0" cellpadding="0" cellspacing="0" class="tab_edycji">
		<tr>
			<th>Nazwa</th>
			<th>Aktyw.</th>
			<th>Kolejność</th>
			<td></td>
		</tr>
		<tr class="gray0">
			<td><input type="text" name="nazwa" value="" /></td>
			<td><select name="active">
			<option  value="YES" selected>TAK</option>
			<option  value="NO">NIE</option>
			</select></td>
			<td><input type="text" name="order" value="1" style="width: 20px;" /></td>
		</tr>
	</table>
	<input type="submit" name="dodaj_grupe" value="Dodaj grupę linków" />
	</form>
	
	<br />
	
<?
if (($ile = $baza->size_result($result))>0){
	?>
	<form method="POST" action="">
	<table border="0" cellpadding="0" cellspacing="0" class="tab_edycji">
	<tr>
		<th>LP</th>
		<th>Nazwa</th>
		<th>Aktyw.</th>
		<th>Kolejność</th>
	</tr>
	<?
	for ($i=0; $i<$ile; $i++){
		$row = $baza->row($result);	
		$licz = $i+1;
		$mod = $i%2;
		?>
		<tr class="gray<?=$mod;?>">
			<td><?=$licz?></td>
			<td><input type="text" name="nazwa<?=$i?>" value="<?=$row['dlg_nazwa']?>" /></td>
			<td><select name="active<?=$i?>">
			<option  value="YES" <?if ($row['dlg_active']=='YES') echo "selected";?>>TAK</option>
			<option  value="NO" <?if ($row['dlg_active']=='NO') echo "selected";?>>NIE</option>
			</select></td>
			<td><input type="text" name="order<?=$i?>" value="<?=$row['dlg_order']?>" style="width: 20px;" /></td>
			<td><a href="<?=$link?>&usun_grupe=<?=$row['dlg_id']?>" >Usuń</a></td>
		</tr>
		<?
	}
	?>
	</table>
	<input type="submit" name="zapisz_grupy" value="Zapisz grupy" />
	</form>

	</center>
	<?
}
?>
</td>
<td class="center" style="padding: 10px;">


<h4>Linki:</h4>
<br />

	<center>
	<form method="POST" action="">
		<table border="0" cellpadding="0" cellspacing="0" class="tab_edycji">
		<tr>
			<th>Grupa</th>
			<th width="150">Link</th>
			<th width="200">Opis</th>
			<th>Aktyw.</th>
		</tr>
		<tr class="gray0">
			<td>
			<select name="grupa">
			<?
			$res = $baza->select("*",$tabela, "", "ORDER BY dlg_order","");	
			if (($ile_l = $baza->size_result($res))>0){
				for ($i_i=0; $i_i<$ile_l; $i_i++){
					$row_i = $baza->row($res);	
					?>
					<option value="<?=$row_i['dlg_id']?>"><?=$row_i['dlg_nazwa']?></option>
					<?
				}
			}
			?>
			</select>
			</td>
			<td><input type="text" name="link" value="http://" style="width: 150px" /></td>
			<td><input type="text" name="opis" value="" style="width: 200px" /></td>
			<td><select name="active">
			<option  value="YES">TAK</option>
			<option  value="NO">NIE</option>
			</select></td>
		</tr>
	</table>
	<input type="submit" name="dodaj_link" value="Dodaj link" />
	</form>
	
	<br />

<?
$result = $baza->select("*",$tabela_link, "", "ORDER BY mlin_id","");	
if (($ile = $baza->size_result($result))>0){
	?>
	<center>
	<form method="POST" action="">
	<table border="0" cellpadding="0" cellspacing="0" class="tab_edycji">
	<tr>
		<th>LP</th>
		<th>Grupa</th>
		<th width="150">Link</th>
		<th width="200">Opis</th>
		<th>Aktyw.</th>
		<td></td>
	</tr>
	<?
	for ($i=0; $i<$ile; $i++){
		$row = $baza->row($result);	
		$licz = $i+1;
		$mod = $i%2;
		?>
		<tr class="gray<?=$mod;?>">
			<td><?=$licz?></td>
			<td>
			<select name="grupa<?=$i?>">
			<?
			$res = $baza->select("*",$tabela, "", "ORDER BY dlg_order","");	
			if (($ile_l = $baza->size_result($res))>0){
				for ($i_i=0; $i_i<$ile_l; $i_i++){
					$row_i = $baza->row($res);	
					?>
					<option value="<?=$row_i['dlg_id']?>" <?if ($row_i['dlg_id']==$row[mlin_grupa]) echo "selected";?>><?=$row_i['dlg_nazwa']?></option>
					<?
				}
			}
			?>
			</select>
			</td>
			<td><input type="text" name="link<?=$i?>" value="<?=$row['mlin_link']?>" style="width: 150px" /></td>
			<td><input type="text" name="opis<?=$i?>" value="<?=$row['mlin_opis']?>" style="width: 200px" /></td>
			<td><select name="active<?=$i?>">
			<option  value="YES" <?if ($row['mlin_active']=='YES') echo "selected";?>>TAK</option>
			<option  value="NO" <?if ($row['mlin_active']=='NO') echo "selected";?>>NIE</option>
			</select></td>
			<td><a href="<?=$link?>&usun_link=<?=$row['mlin_id']?>" >Usuń</a></td>
		</tr>
		<?
	}
	?>
	</table>
	<input type="submit" name="zapisz_linki" value="Zapisz linki" />
	</form>

<?
}
?>
</td>
</tr>
</table>
