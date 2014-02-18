<?
include_once("class/class_edit_table.php");
$newsletter = $_GET[newsletter];
if ($newsletter==""){$newsletter='news';}
$link = "?modules=&manage=$_GET[manage]&newsletter=$newsletter";
?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td id="menu_newsletter" width="100">
			<br>
			<a href="?modules=&manage=<?=$_GET[manage]?>&newsletter=news" <?if ($newsletter=='news') echo " class='gray_menu' ";?>>Newsletter</a>
			<br>
			<a href="?modules=&manage=<?=$_GET[manage]?>&newsletter=mail" <?if ($newsletter=='mail') echo " class='gray_menu' ";?>>Baza e-maili</a>
			<br>
			<a href="?modules=&manage=<?=$_GET[manage]?>&newsletter=group" <?if ($newsletter=='group') echo " class='gray_menu' ";?>>Grupy</a>
			<br>
		</td>
		<td id="content_newsletter">
		<?
		if (!isset($_GET[newsletter]) or $_GET[newsletter]=='news'){
			?>
			<center>
			<h3>Newslettery</h3>
			<?
			if (isset($_GET[send_news])){
				$result = $baza->select("*","modnewsletter,modarticle", "n_article=idModArticle and n_id=$_GET[send_news]", "");
				$row = $baza->row($result);	
				$grupa_newslettera = $row[n_group];

				$result2 = $baza->select("*","cmsconfig", "name='DIR_START'");
				$row2 = $baza->row($result2);
				$start_path = $row2[value];
				$result2 = $baza->select("*","cmsconfig", "name='ADM_EMAIL'");
				$row2 = $baza->row($result2);
				$adm_email = $row2[value];
				
				$od = $adm_email;
				$dzis = date('d-m-Y');
				$temat = "Newsletter Surfland Deweloper System";
				$headers .= "Mime-Version: 1.0\r\n";
			    $headers .= "Content-type: text/html; charset=ISO-8859-2\r\n";
			    $headers .= "Content-Transfer-Encoding: 8bit\r\n";	
				$headers .= "From: <$od>\n";
				$headers .= "X-Priority: 1\n"; // ważna wiadomość!
				$headers .= "Return-Path: <$od>\n";	
				$wiadomosc = "
				<html>
				<body>
				<br />
				<center>
				<h3>
				$row[titleModArticle]
				</h3>
				</center>
				<hr /><br />
				$row[modarticle_short]
				<br>
				$row[contentModArticle]
				<p style='text-align: right;'>$dzis</p>
				<br>
				<hr>
				<p>Surfland Deweloper System Sp. z o.o.<br>
				ul. Legnicka 46/14, 53-674 Wrocław<br>
				Tel.: 071 783 32 38, fax: 071 783 32 39<br>
				<a href='mailto:biuro@surfdeweloper.pl'>biuro@surfdeweloper.pl</a>, 
				<a href='http://www.surfdeweloper.pl'>www.surfdeweloper.pl</a>
				</p>
				<p>Spółka wpisana do Krajowego Rejestru Sądowego pod nr 0000286792 prowadzonego w  Sądzie Rejonowym dla Wrocławia Fabrycznej. Kapitał zakładowy 135 000,00 zł, NIP: 897 17 31 779</p>
				<hr>
				<br>
				<p>Jeśli nie chcesz otrzymywać kolejnych wiadomości kliknij poniższy link:<br>
				";
				$temat=iconv("UTF-8","ISO-8859-2", $temat);
			    $temat='=?iso-8859-2?B?'.base64_encode($temat).'?=';
			    $wiadomosc=iconv("UTF-8","ISO-8859-2", $wiadomosc);
			    $wiadomosc = str_replace("href=\"/UserFiles","href=\"http://$start_path/UserFiles",$wiadomosc);
			    $wiadomosc = str_replace("src=\"/UserFiles","src=\"http://$start_path/UserFiles",$wiadomosc);

			    $result = $baza->select("*","modnewsletteruser,modnewsletterusergrupa", "nug_user=nu_id and nug_group=$row[n_group]", "");
				if (($ile = $baza->size_result($result))>0){
					for ($i=0; $i<$ile; $i++){
						$row = $baza->row($result);	
						$do = $row[nu_email];
						$user_id=$row[nu_id];
						$wiadomosc2 = "
						<a href='http://$start_path/newsletter.html?usun=$row[nu_key]&grp=$grupa_newslettera&usr=$user_id'>$start_path - usunięcie z listy subskrypcji</a>
						</p>
						</body>
						</html>
						";
					    $wiadomosc2=iconv("UTF-8","ISO-8859-2", $wiadomosc2);
						mail($do,$temat,$wiadomosc.$wiadomosc2,$headers);
					}
				}
				
				$values = "n_status='W'";
				$where = "n_id=".$_GET[send_news];
				$baza->update("modnewsletter",$values,$where,"");
			}

			if(isset($_GET[usun_news])){
				$baza->delete("modnewsletter","n_id=$_GET[usun_news]");	
			}

			if(isset($_GET[dodaj_news])){
				if (isset($_POST[dodaj_news])){
					$dzis = date("Y-m-d");
					$values = "0,".$_POST['artykul'].",".$_POST['grupa'].",$_SESSION[panel_admin_user_id],'$dzis','','N','YES'";
					$baza->insert("modnewsletter",$values);					
					?>
					<p>Zmiany zapisane</p>
					<?
				} else{
					?>
					<form method="POST" action="">
					<table>
						<tr>
							<th>Artykuł</th>
							<td>
								<?
								$result2 = $baza->select("*","modarticle,modarticlegroup", "modarticlegroup_id=modarticle_group
										and modarticlegroup_newsletter='YES' and modarticlegroup_active='YES' and activeModArticle='YES'", 
										"order by titleModArticle", "");
								?>
								<select name="artykul" style="width: 300px;">
									<?
									if (($ile = $baza->size_result($result2))>0){
										for ($i=0; $i<$ile; $i++){
											$row2 = $baza->row($result2);
											?>
											<option <?if ($row[n_article]==$row2[idModArticle]) echo "selected";?> 
												value="<?=$row2[idModArticle]?>"><?=$row2[titleModArticle]?></option>
											<?
										}
									}
									?>
								</select>
							</td>
						</tr>
						<tr>
							<th>Grupa newsletterów</th>
							<td>
								<?
								$result2 = $baza->select("*","modnewslettergrupa", "ng_active='YES'","","");
								?>
								<select name="grupa">
									<?
									if (($ile = $baza->size_result($result2))>0){
										for ($i=0; $i<$ile; $i++){
											$row2 = $baza->row($result2);
											?>
											<option <?if ($row[n_group]==$row2[ng_id]) echo "selected";?> 
												value="<?=$row2[ng_id]?>"><?=$row2[ng_name]?></option>
											<?
										}
									}
									?>
								</select>
							</td>
						</tr>
					</table>
					<input type="submit" name="dodaj_news" value="Dodaj nowy" />
					</form>
					<?					
				}
				?>
				<p><a href="<?=$link?>">powrót</a> </p>
				<?
			}
			
			if (isset($_GET[edytuj_news])){
				$result = $baza->select("*","modnewsletter", "n_id=$_GET[edytuj_news]", "");
				$row = $baza->row($result);	
				if (isset($_POST[edutyj_news])){
					$values = "n_article=".$_POST['artykul'].",
						n_group=".$_POST['grupa']."";
					$where = "n_id=".$_GET[edytuj_news];
					$baza->update("modnewsletter",$values,$where);					
					?>
					<p>Zmiany zapisane</p>
					<?
				} else{
					?>
					<form method="POST" action="">
					<table>
						<tr>
							<th>Artykuł</th>
							<td>
								<?
								$result2 = $baza->select("*","modarticle,modarticlegroup", "modarticlegroup_id=modarticle_group
										and modarticlegroup_newsletter='YES' and modarticlegroup_active='YES' and activeModArticle='YES'", 
										"order by titleModArticle", "");
								?>
								<select name="artykul" style="width: 300px;">
									<?
									if (($ile = $baza->size_result($result2))>0){
										for ($i=0; $i<$ile; $i++){
											$row2 = $baza->row($result2);
											?>
											<option <?if ($row[n_article]==$row2[idModArticle]) echo "selected";?> 
												value="<?=$row2[idModArticle]?>"><?=$row2[titleModArticle]?></option>
											<?
										}
									}
									?>
								</select>
							</td>
						</tr>
						<tr>
							<th>Grupa newsletterów</th>
							<td>
								<?
								$result2 = $baza->select("*","modnewslettergrupa", "ng_active='YES'","","");
								?>
								<select name="grupa">
									<?
									if (($ile = $baza->size_result($result2))>0){
										for ($i=0; $i<$ile; $i++){
											$row2 = $baza->row($result2);
											?>
											<option <?if ($row[n_group]==$row2[ng_id]) echo "selected";?> 
												value="<?=$row2[ng_id]?>"><?=$row2[ng_name]?></option>
											<?
										}
									}
									?>
								</select>
							</td>
						</tr>
					</table>
					<input type="submit" name="edutyj_news" value="Zapisz zmiany" />
					</form>
					<?					
				}
				?>
				<p><a href="<?=$link?>">powrót</a> </p>
				<?
			}
			
			if (isset($_GET[ready_news])){
				$values = "n_status='G'";
				$where = "n_id=".$_GET[ready_news];
				$baza->update("modnewsletter",$values,$where);
			}
			
			if (!isset($_GET[edytuj_news]) and !isset($_GET[dodaj_news])){
				?>
				<a href="<?=$link?>&dodaj_news">Dodaj nowy newsletter</a>
				<br><br>
				<?
				$result = $baza->select("*","modnewsletter,modarticle,cmsadmuser,modnewslettergrupa", 
					"n_article=idModArticle and n_author=idAdmUser and n_group=ng_id", "");
				if (($ile = $baza->size_result($result))>0){
					?>
					<table border="0" cellpadding="0" cellspacing="0" class="tab_edycji">
						<tr>
							<th>LP</th>
							<th>Artykuł</th>
							<th>Grupa</th>
							<th>Autor</th>
							<th>Data</th>
							<th>Status</th>
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
								<td><?=$row['titleModArticle']?></td>
								<td><?=$row['ng_name']?></td>
								<td><?=$row['loginAdmUser']?></td>
								<td><?=$row['n_date']?></td>
								<td><?switch($row['n_status']){
									case 'N': echo "nieaktywny"; break;
									case 'G': echo "gotowy"; break;
									case 'W': echo "wysłany"; break;
									default: echo "nieaktywny"; break;
								}?></td>
								<td style="text-align: center"><?if($row['n_active']=='YES')echo "TAK"; else echo "NIE";?></td>
								<td>
									<a href="<?=$link?>&usun_news=<?=$row['n_id']?>" >Usuń</a>
									<a href="<?=$link?>&edytuj_news=<?=$row['n_id']?>" >Edytuj</a>
									<?if ($row['n_status']=='N'){?>
										<a href="<?=$link?>&ready_news=<?=$row['n_id']?>" >Gotowy</a>
									<?}?>
									<?if ($row['n_status']=='G'){?>
										<a href="<?=$link?>&send_news=<?=$row['n_id']?>" >Wyślij</a>
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
			</center>
			<?
		} elseif ($_GET[newsletter]=='mail'){
			?>
			<center>
			<h3>Baza e-maili newslettera</h3>
			<?
			if (isset($_POST['edytuj_email'])){
				$values = "nu_email=\"".$_POST['email']."\",
					nu_active=\"".$_POST['active']."\",
					nu_description=\"".$_POST['opis']."\"";
				$where = "nu_id=".$_GET[edytuj_email];
				$baza->update("modnewsletteruser",$values,$where);
				?>
				Zmiany zapisane
				<p><a href="<?=$link?>">Powrót</a></p>
				<?
			}
			
			//dodanie nowego emaila
			if (isset($_POST['dodaj_email']) and strlen($_POST[email])>0){
				$key = md5($_POST[email].$_POST['opis'].date('Y-m-d'));
				$values = "0,\"".$_POST['email']."\",\"".$_POST['opis']."\",'$key',\"".$_POST['active']."\"";
				$baza->insert("modnewsletteruser",$values,"","");

				//dodanie do domyślnej grupy wszyscy
				$id = $baza->last_insert_id();
				$baza->insert("modnewsletterusergrupa","0,$id,0","","");
			}
			
			//usuwanie emaila
			if (isset($_GET['usun_email'])){
				$baza->delete("modnewsletteruser","nu_id=$_GET[usun_email]","");
				?>
				<p>E-mail poprawnie usunięty z bazy!</p>
				<?
			}
			
			if (isset($_GET[edytuj_email])){
				$result = $baza->select("*","modnewsletteruser", "nu_id=$_GET[edytuj_email]", "","");
				$row = $baza->row($result);
			}
			
			if(isset($_GET[grupy_email])){
				$result = $baza->select("*","modnewslettergrupa", "","");
				if (($ile = $baza->size_result($result))>0){
//					zapis grup
					if (isset($_POST[save_grupy])){
						for ($i=0; $i<$ile; $i++){
							$row = $baza->row($result);
							$id = $row[ng_id];
							if (isset($_POST['gr_'.$id])){
								$result2 = $baza->select("*","modnewsletterusergrupa", "nug_user=$_GET[grupy_email] and nug_group=$id","");
								if ($baza->size_result($result2)==0){
									$baza->insert("modnewsletterusergrupa","0,$_GET[grupy_email],$id");
								}
							}
							elseif ($id>0){
								$result2 = $baza->select("*","modnewsletterusergrupa", "nug_user=$_GET[grupy_email] and nug_group=$id","");
								if ($baza->size_result($result2)>0){
									$baza->delete("modnewsletterusergrupa","nug_user=$_GET[grupy_email] and nug_group=$id");
								}
							}
						}
					}
					$result = $baza->select("*","modnewslettergrupa", "","");
					
					?>
					<form method="POST" action="">
					<table>
					<?
					for ($i=0; $i<$ile; $i++){
						$row = $baza->row($result);
						?>
						<tr>
							<td>
								<input type="checkbox" name="gr_<?=$row[ng_id]?>" <?if ($row[ng_id] == 0) echo "disabled";?>
								<?
								$result2 = $baza->select("*","modnewsletterusergrupa", "nug_user=$_GET[grupy_email] and nug_group=$row[ng_id]","","");
								if ($baza->size_result($result2)>0){
									echo " checked";
								}
								?>
						 		/>
							</td>
							<td>
								<?=$row[ng_name]?>
							</td>
						</tr>
						<?
					}
					?>
					</table>
					<input type="submit" name="save_grupy" value="Zapisz zmiany" />
					</form>
					<?
				} else{
					?>
					<p>Brak zdefiniowanych grup newsleterowych!</p>
					<?
				}
				?><p><a href="<?=$link?>">Powrót</a></p><?
			}
			
			if (!isset($_POST[edytuj_email]) and !isset($_GET[grupy_email])){
			?>
			<form method="POST" action="">
			<table border="0" cellpadding="0" cellspacing="0" class="tab_edycji">
				<tr>
					<th>E-mail</th>
					<th>Opis</th>
					<th>Aktywność</th>
				</tr>
				<tr class="gray0">
					<td><input type="text" name="email" value="<?=$row[nu_email]?>" /></td>
					<td><input type="text" name="opis" value="<?=$row[nu_description]?>" /></td>
					<td><select name="active">
					<option value="YES" <?if ($row[nu_active]=='YES') echo "selected";?>>TAK</option>
					<option value="NO" <?if ($row[nu_active]=='NO') echo "selected";?>>NIE</option>
					</select></td>
				</tr>
			</table>
			<?
			if (!isset($_GET[edytuj_email])){
			?>
			<input type="submit" name="dodaj_email" value="Dodaj e-mail" />
			<?
			}else{
			?>
			<input type="submit" name="edytuj_email" value="Zapisz zmiany" />
			<?	
			}
			?>
			</form>
			<br />
			<?
			}
			if (!isset($_GET[edytuj_email]) and !isset($_GET[grupy_email])){
			$result = $baza->select("*","modnewsletteruser", "", "ORDER BY nu_email");
			if (($ile = $baza->size_result($result))>0){
				?>
				<table border="0" cellpadding="0" cellspacing="0" class="tab_edycji">
					<tr>
						<th>LP</th>
						<th>E-mail</th>
						<th>Opis</th>
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
							<td><?=$row['nu_email']?></td>
							<td><?=$row['nu_description']?></td>
							<td style="text-align: center"><?if($row['nu_active']=='YES')echo "TAK"; else echo "NIE";?></td>
							<td>
								<a href="<?=$link?>&usun_email=<?=$row['nu_id']?>" >Usuń</a>
								<a href="<?=$link?>&edytuj_email=<?=$row['nu_id']?>" >Edytuj</a>
								<a href="<?=$link?>&grupy_email=<?=$row['nu_id']?>" >Grupy</a>
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
			</center>
			<?
		} else{
			?>
			<center>
			<h3>Grupy newslettera</h3>
			<?
			if (isset($_GET[emaile])){
				$result = $baza->select("*","modnewsletterusergrupa,modnewsletteruser", "nug_group=$_GET[emaile] and nug_user=nu_id", "ORDER BY nu_email");
				if (($ile = $baza->size_result($result))>0){
					?>
					<table>
					<?
					for ($i=0; $i<$ile; $i++){
						$row = $baza->row($result);	
						$mod = $i%2;
						?>
						<tr class="gray<?=$mod;?>">
							<td>
								<a href="?modules=&manage=<?=$_GET[manage]?>&newsletter=mail&edytuj_email=<?=$row[nu_id]?>">
								<?=$row[nu_email]?>
								</a>
							</td>
						</tr>
						<?
					}
					?>
					</table>
					<?
				}
				?>
				<p><a href="<?=$link?>">powrót</a> </p>
				<?
			}
			else{
				$tabela = "modnewslettergrupa";
				$klucz_glowny = "ng_id";
				$tablica_kolumn = array("ng_name","ng_description","ng_active");
				$tablica_nazw_kolumn = array("Nazwa","Opis","Aktyw.");
				$tabela_relacji = null;
				$tab_rel_przedrostek = null;
				$kolumna_order = "ng_id";		
				$typ_order = "ASC";
				
				$tablica_wart_domyslnych = array("ng_id" => "0");
				$liczba_wyswietlanych = 200;		//liczba wyswietlanych wierszy na stronie
				
				$tabela_blokow = new edit_table("?modules=&manage=$_GET[manage]&newsletter=$_GET[newsletter]", $baza, $tabela, $tabela_relacji, $tab_rel_przedrostek, $klucz_glowny, $tablica_kolumn, 
												$tablica_nazw_kolumn, $tablica_wart_domyslnych, $tab_war_skroconych, $select, $single,
												$kolumna_order,$typ_order,$liczba_wyswietlanych,$liczba_wyswietlen_edit,$left_checkbox,
												$pola_file_jpg,$path_file_big,$size_file_big,$path_file_small,$size_file_small,
												array("emaile"),array("E-maile"));
				$tabela_blokow->manage($liczba_wyswietlanych);
			}
			?>
			<br>
			</center>
			<?
		}
		?>
		</td>
	</tr>
</table>