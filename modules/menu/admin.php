<?
//zarządzanie w MODULES
if (isset($_GET[modules])){
	if (isset($_GET[pages]))
	$link = "?pages=&p=$_GET[p]&modul=&manage=$_GET[manage]";
else 
	$link = "?modules=&manage=$_GET[manage]";	

$tabela = "modmenu";
$tabela_item = "modmenuitem";

//zapis zmian w grupach linków
if (isset($_POST['zapisz_menu'])){
	$result = $baza->select("*",$tabela, "", "ORDER BY modmenu_id");	
	if (($ile = $baza->size_result($result))>0){
		for ($i=0; $i<$ile; $i++){
			$row = $baza->row($result);	
			$id = $row[modmenu_id];
			$values = "modmenu_nazwa=\"".$_POST['nazwa'.$id]."\",
				modmenu_active=\"".$_POST['active'.$id]."\"";
			$where = "modmenu_id=".$row['modmenu_id'];
			$baza->update($tabela,$values,$where);
		}	
	}
}

//dodanie nowego menu
if (isset($_POST['dodaj_menu'])){
	$values = "0,\"".$_POST['nazwa']."\",\"".$_POST['active']."\"";
	$baza->insert($tabela,$values,"","");
}

//usunięcie menu
if (isset($_GET['usun_menu'])){
	$baza->delete($tabela,"modmenu_id=".$_GET['usun_menu']);
}

######################
# Pozycje#
######################
//zapis zmian w menu
if (isset($_POST['zapisz_pozycje'])){
	$result = $baza->select("*",$tabela_item, "", "ORDER BY modmenuitem_id");	
	if (($ile = $baza->size_result($result))>0){
		for ($i=0; $i<$ile; $i++){
			$row = $baza->row($result);	
			$id = $row['modmenuitem_id'];
			if (isset($_POST['menu'.$id])){
				$values = "modmenuitem_menu=".$_POST['menu'.$id].",
					modmenuitem_page=".$_POST['page'.$id].",
					modmenuitem_order=".$_POST['order'.$id];
				$where = "modmenuitem_id=".$id;
				$baza->update($tabela_item,$values,$where);
			}
		}	
	}
}

//dodanie nowej pozycji
if (isset($_POST['dodaj_item'])){
	$values = "0,".$_POST['menu'].",".$_POST['page'].",0,".$_POST['order'];
	$baza->insert($tabela_item,$values,"","");
}

//usunięcie pozycji menu
if (isset($_GET['usun_pozycje'])){
	$baza->delete($tabela_item,"modmenuitem_id=".$_GET['usun_pozycje']);
}


?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
<td class="center" style="padding: 10px;">
<h4>Menu:</h4>
<br />
<?

$result = $baza->select("*",$tabela, "", "ORDER BY modmenu_nazwa");	
?>
	<center>
	<form method="POST" action="">
	<table border="0" cellpadding="0" cellspacing="0" class="tab_edycji">
		<tr>
			<th>Nazwa</th>
			<th>Aktyw.</th>
			<td></td>
		</tr>
		<tr class="gray0">
			<td><input type="text" name="nazwa" value="" /></td>
			<td><select name="active">
			<option  value="YES" selected>TAK</option>
			<option  value="NO">NIE</option>
			</select></td>
		</tr>
	</table>
	<input type="submit" name="dodaj_menu" value="Dodaj menu" />
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
	</tr>
	<?
	for ($i=0; $i<$ile; $i++){
		$row = $baza->row($result);	
		$licz = $i+1;
		$mod = $i%2;
		?>
		<tr class="gray<?=$mod;?>">
			<td><?=$licz?></td>
			<td><input type="text" name="nazwa<?=$row[modmenu_id]?>" value="<?=$row['modmenu_nazwa']?>" /></td>
			<td><select name="active<?=$row[modmenu_id]?>">
			<option  value="YES" <?if ($row['modmenu_active']=='YES') echo "selected";?>>TAK</option>
			<option  value="NO" <?if ($row['modmenu_active']=='NO') echo "selected";?>>NIE</option>
			</select></td>
			<td><a href="<?=$link?>&usun_menu=<?=$row['modmenu_id']?>" >Usuń</a></td>
		</tr>
		<?
	}
	?>
	</table>
	<input type="submit" name="zapisz_menu" value="Zapisz zmiany" />
	</form>

	</center>
	<?
}
?>
</td>
<td class="center" style="padding: 10px;">


<h4>Pozycje menu:</h4>
<br />

	<center>
	<form method="POST" action="">
		<table border="0" cellpadding="0" cellspacing="0" class="tab_edycji">
		<tr>
			<th>Menu</th>
			<th>Strona</th>
			<th>Kolejność</th>
		</tr>
		<tr class="gray0">
			<td>
			<select name="menu">
			<?
			$res = $baza->select("*",$tabela, "modmenu_active='YES'", "ORDER BY modmenu_nazwa","");	
			if (($ile_l = $baza->size_result($res))>0){
				for ($i_i=0; $i_i<$ile_l; $i_i++){
					$row_i = $baza->row($res);	
					?>
					<option value="<?=$row_i['modmenu_id']?>" 
						<?
						if ($_POST['menu']==$row_i['modmenu_id']) echo "selected";
						?>
					><?=$row_i['modmenu_nazwa']?></option>
					<?
				}
			}
			?>
			</select>
			</td>
			<td>
			<select name="page">
			<?
			$res = $baza->select("*","cmspage", "", "ORDER BY name","");	
			if (($ile_l = $baza->size_result($res))>0){
				for ($i_i=0; $i_i<$ile_l; $i_i++){
					$row_i = $baza->row($res);	
					?>
					<option value="<?=$row_i['idPage']?>"><?=$row_i['name']?></option>
					<?
				}
			}
			?>
			</select>
			</td>
			<td><input type="text" name="order" value="" /></td>
		</tr>
	</table>
	<input type="submit" name="dodaj_item" value="Dodaj pozycję menu" />
	</form>
	
	<br />

<?
$result = $baza->select("*",$tabela_item.",cmspage", "modmenuitem_page=idPage", "ORDER BY modmenuitem_menu, modmenuitem_order","");	
if (($ile = $baza->size_result($result))>0){
	?>
	<center>
	<form method="POST" action="">
	<table border="0" cellpadding="0" cellspacing="0" class="tab_edycji">
	<tr>
		<th>LP</th>
		<th>Menu</th>
		<th>Strona</th>
		<th>Kolejność</th>
		<td></td>
	</tr>
	<?
	for ($i=0; $i<$ile; $i++){
		$row = $baza->row($result);	
		$id = $row[modmenuitem_id];
		$licz = $i+1;
		$mod = $i%2;
		?>
		<tr class="gray<?=$mod;?>">
			<td><?=$licz?></td>
			<td>
			<select name="menu<?=$id?>">
			<?
			$res = $baza->select("*",$tabela, "", "ORDER BY modmenu_nazwa","");	
			if (($ile_l = $baza->size_result($res))>0){
				for ($i_i=0; $i_i<$ile_l; $i_i++){
					$row_i = $baza->row($res);	
					?>
					<option value="<?=$row_i['modmenu_id']?>" <?if ($row_i['modmenu_id']==$row[modmenuitem_menu]) echo "selected";?>><?=$row_i['modmenu_nazwa']?></option>
					<?
				}
			}
			?>
			</select>
			</td>
			<td>
			<select name="page<?=$id?>">
			<?
			$res = $baza->select("*","cmspage", "", "ORDER BY name","");	
			if (($ile_l = $baza->size_result($res))>0){
				for ($i_i=0; $i_i<$ile_l; $i_i++){
					$row_i = $baza->row($res);	
					?>
					<option value="<?=$row_i['idPage']?>" 
					<?if ($row_i['idPage']==$row[modmenuitem_page]) echo "selected";?>>
					<?=$row_i['name']?></option>
					<?
				}
			}
			?>
			</select>
			</td>
			<td><input type="text" name="order<?=$id?>" value="<?=$row['modmenuitem_order']?>" /></td>
			<td><a href="<?=$link?>&usun_pozycje=<?=$row['modmenuitem_id']?>" >Usuń</a></td>
		</tr>
		<?
	}
	?>
	</table>
	<input type="submit" name="zapisz_pozycje" value="Zapisz zmiany" />
	</form>

<?
}
?>
</td>
</tr>
</table>
<?
}

//zarządzania w PAGES
if (isset($_GET[pages])){
	$r_as = $baza->select("*","modmenuschema","modmenuschema_schema=$_GET[manage]","","");
	$row_as = $baza->row($r_as);
	
	//zapisanie wybranego artukułu
	if (isset($_POST['save'])){
		if ($baza->size_result($r_as)==0){	
			$wartsci = "0,".$_POST['menu'].",".$_GET['manage'];	
			$r = $baza->insert("modmenuschema",$wartsci);	
		}
		else{
			$wartsci = "modmenuschema_menu=".$_POST['menu']."";
			$where = "modmenuschema_schema=$_GET[manage]";
			$r = $baza->update("modmenuschema",$wartsci,$where,"");
		}
	}
		
	$r_as = $baza->select("*","modmenuschema","modmenuschema_schema=$_GET[manage]","","");
	$row_as = $baza->row($r_as);
	$ile_as=$baza->size_result($r_as);
	
	//wybór menu 
	$r_art=$baza->select("*","modmenu","modmenu_active='YES'","ORDER by modmenu_nazwa","");
	if (($ile=$baza->size_result($r_art))>0){
		?>
		<form method="POST" action="">
		<h4>Wybierz menu:</h4>
		<select name="menu">
		<?
		if ($ile_as==0){
			?>
			<option></option>
			<?
		}
		for ($i=0; $i<$ile; $i++){
			$row_art = $baza->row($r_art);
			if ($row_art['modmenu_active']=='YES'){				
				?>
				<option <?if ($row_as['modmenuschema_menu']==$row_art['modmenu_id']) echo "selected";?> 
					value="<?=$row_art['modmenu_id']?>"><?=$row_art['modmenu_nazwa']?></option>
				<?
			}
		}
		?>
		</select>
		<br>
		<input type="submit" name="save" value="Zapisz" />
		</form>
		<br><br>
		<?
	}
}
?>	