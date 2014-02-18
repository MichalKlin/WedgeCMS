<?php
if (isset($_GET[modules])){
	$link = "?modules=&manage=$_GET[manage]";
	$tabela = "modarticlegroup";
	
	//zapis zmian w grupach
	if (isset($_POST['zapisz_grupy'])){
		$result = $baza->select("*",$tabela, "", "ORDER BY $tabela"."_id");	
		if (($ile = $baza->size_result($result))>0){
			for ($i=0; $i<$ile; $i++){
				$row = $baza->row($result);	
				$values = $tabela."_name=\"".$_POST['nazwa'.$row[$tabela.'_id']]."\",
					".$tabela."_newsletter=\"".$_POST['newsletter'.$row[$tabela.'_id']]."\",
					".$tabela."_active=\"".$_POST['active'.$row[$tabela.'_id']]."\"";
				$where = $tabela."_id=".$row[$tabela.'_id'];
				$baza->update($tabela,$values,$where);
			}	
		}
	}
	
	//dodanie nowej grupy 
	if (isset($_POST['dodaj_grupe'])){
		$values = "0,\"".$_POST['nazwa']."\",\"".$_POST['active']."\",\"".$_POST['newsletter']."\"";
		$baza->insert($tabela,$values,"","");
	}
	
	//usunięcie grupy 
	if (isset($_GET['usun_grupe'])){
		$baza->delete($tabela,$tabela."_id=".$_GET['usun_grupe']);
	}
	
	?>
	<h4>Grupy artykułów:</h4>
	<br />
	<?
	
	$result = $baza->select("*",$tabela, "", "ORDER BY modarticlegroup_name");	
	?>
		<center>
		<form method="POST" action="">
		<table border="0" cellpadding="0" cellspacing="0" class="tab_edycji">
			<tr>
				<th>Nazwa</th>
				<th>Aktyw.</th>
				<th>Newsletter</th>
				<td></td>
			</tr>
			<tr class="gray0">
				<td><input type="text" name="nazwa" value="" /></td>
				<td><select name="active">
				<option  value="YES" selected>YES</option>
				<option  value="NO">NO</option>
				</select></td>
				<td><select name="newsletter">
				<option  value="YES" selected>YES</option>
				<option  value="NO">NO</option>
				</select></td>
			</tr>
		</table>
		<input type="submit" name="dodaj_grupe" value="Dodaj grupę artykułów" />
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
			<th>Newsletter</th>
		</tr>
		<?
		for ($i=0; $i<$ile; $i++){
			$row = $baza->row($result);	
			$licz = $i+1;
			$mod = $i%2;
			?>
			<tr class="gray<?=$mod;?>">
				<td><?=$licz?></td>
				<td><input type="text" name="nazwa<?=$row[$tabela.'_id']?>" value="<?=$row[$tabela.'_name']?>" /></td>
				<td><select name="active<?=$row[$tabela.'_id']?>">
				<option <?if ($row[$tabela.'_active']=='YES') echo "selected";?> value="YES">YES</option>
				<option <?if ($row[$tabela.'_active']=='NO') echo "selected";?> value="NO">NO</option>
				</select></td>
				<td><select name="newsletter<?=$row[$tabela.'_id']?>">
				<option <?if ($row[$tabela.'_newsletter']=='YES') echo "selected";?> value="YES">YES</option>
				<option <?if ($row[$tabela.'_newsletter']=='NO') echo "selected";?> value="NO">NO</option>
				</select></td>
				<td><a href="<?=$link?>&usun_grupe=<?=$row[$tabela.'_id']?>" >Usuń</a></td>
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
}

//zarządzanie na Stronach
if (isset($_GET[pages])){
	//zapisanie wybranej grupy artykułów
	if (isset($_POST['save'])){
		$r_art=$baza->select("*","modarticlegroup","modarticlegroup_active='YES'","","");
		if (($ile=$baza->size_result($r_art))>0){
			for ($i=0; $i<$ile; $i++){
				$row_art = $baza->row($r_art);
				$id = $row_art['modarticlegroup_id'];
				$w = "grupa_".$id;
				if (isset($_POST[$w]) and $_POST[$w]=="on"){
					$r_as = $baza->select("*","modarticlegrouppage","modarticlegrouppage_schema=$_GET[manage] 
						and modarticlegrouppage_page=$_GET[p] and modarticlegrouppage_group=$id","","");
					$ile_s = $baza->size_result($r_as);
					if ($ile_s>0){	
						$wartsci = "modarticlegrouppage_onpage=$_POST[onpage],modarticlegrouppage_paged=\"$_POST[paged]\"";
						$where = "modarticlegrouppage_page=$_GET[p] and modarticlegrouppage_schema=$_GET[manage]
						and modarticlegrouppage_group=".$id;
						$r = $baza->update("modarticlegrouppage",$wartsci,$where,"");
					}
					else{
						$wartsci2 = "0,".$row_art['modarticlegroup_id'].",".$_GET['p'].",".$_GET['manage'].",$_POST[onpage],\"$_POST[paged]\"";	
						$r = $baza->insert("modarticlegrouppage",$wartsci2,"","");	
					}
				}
				else{
					$where = "modarticlegrouppage_page=$_GET[p] 
						and modarticlegrouppage_schema=$_GET[manage] 
						and modarticlegrouppage_group=$id";
					$r = $baza->delete("modarticlegrouppage",$where);
				}
			}
		}
	}
	
	$r_as = $baza->select("*","modarticlegrouppage","modarticlegrouppage_schema=$_GET[manage] and modarticlegrouppage_page=$_GET[p]","","");
	$row_as = $baza->row($r_as);
	$ile_as=$baza->size_result($r_as);

	//wybór grupy
	$r_art=$baza->select("*","modarticlegroup","modarticlegroup_active='YES'","","");
	if (($ile=$baza->size_result($r_art))>0){
		?>
		<form method="POST" action="">
		<table>
			<tr>
				<th>Wybierz grupy artykułów:</th>
				<td>
					<!--select name="grupa">
						<option></option-->
					<?
					for ($i=0; $i<$ile; $i++){
						$row_art = $baza->row($r_art);						
						$r_test = $baza->select("*","modarticlegrouppage","modarticlegrouppage_schema=$_GET[manage] 
						and modarticlegrouppage_page=$_GET[p] and modarticlegrouppage_group=$row_art[modarticlegroup_id]","","");
						?>
						<input type="checkbox" name="grupa_<?=$row_art['modarticlegroup_id']?>" 
							<?
							if ($baza->size_result($r_test)>0) echo "checked";
							?>
						/><?=$row_art['modarticlegroup_name']?><br />
						<?
					}
					?>
					</select>
				</td>
			</tr>
			<tr>
				<th>Czy jest stronicowanie:</th>
				<td>
					<select name="paged">
						<option <?if ($row_as[modarticlegrouppage_paged]=="YES") echo "selected";?>>YES</option>
						<option <?if ($row_as[modarticlegrouppage_paged]=="NO") echo "selected";?>>NO</option>
					</select>
				</td>
			</tr>
			<tr>
				<th>Ile artykułów na stronie:</th>
				<td>
					<input type="text" name="onpage" value="<?if($row_as['modarticlegrouppage_onpage']>0) echo $row_as['modarticlegrouppage_onpage']; else echo "5";?>" size="10" />
				</td>
			</tr>
		</table>
		<br>
		<input type="submit" name="save" value="Zapisz" />
		</form>
		<br><br>
		<?
	}
	
	//lista artykułów w grupie
	$r_art=$baza->select("*",
	"modarticle,modarticlegrouppage",
	"modarticlegrouppage_group=modarticle_group and 
	modarticlegrouppage_schema=".$_GET[manage]." 
	and modarticlegrouppage_page=".$_GET[p]."
	and activeModArticle='YES'","
	order by modarticle_order,dateModArticle","");
	
	if (($ile=$baza->size_result($r_art))>0){
		?>
		<h3>Lista artykułów w grupie:</h3>
		<table class="ramka_contant" border="0" cellpadding="0" cellspacing="2">
		<?
		$r_art=$baza->select("*",
		"modarticlegrouppage",
		"modarticlegrouppage_schema=".$_GET[manage]." 
		AND modarticlegrouppage_page=".$_GET[p]."","","");
		$row_art = $baza->row($r_art);
		$on_page = $row_art[modarticlegrouppage_onpage];
		$pagowanie = $row_art[modarticlegrouppage_paged];
		//echo $on_page;
		
		$r_art=$baza->select("*",
		"modarticle,modarticlegrouppage",
		"modarticlegrouppage_group=modarticle_group and 
		modarticlegrouppage_schema=".$_GET[manage]." 
		and modarticlegrouppage_page=".$_GET[p]." 
		and activeModArticle='YES'","
		order by modarticle_order desc","");//,dateModArticle
		
		if (($ile=$baza->size_result($r_art))>0){
			for ($i=0; $i<$ile; $i++){
				$row_art = $baza->row($r_art);
				$lp++;
				?>
				<tr>
					<td><?=$lp?></td>
					<td>
						<a href="?modules=&manage=9&article=<?=$row_art['idModArticle']?>">
						<?=$row_art['titleModArticle']?>
						</a>
					</td>
				</tr>
				<?
			}
		}
		?>		
		</table>
		<?
	}	
}
?>