<table width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td id="page_menu">		
		<?
		if (isset($_GET['p'])){
			?>
			<center>
			<table border="0" cellpadding="0" cellspacing="0">
				<tr>
				<?if (check_rules($baza,'site_parametry','select')){?>
					<td 
					<?if (isset($_GET[param]) or (
					!isset($_GET[podstrona]) and !isset($_GET[modul]) and !isset($_GET[dostep]) and !isset($_GET[usun_site])
					)) echo "class=\"page_menu_item2\""; 
					else echo "class=\"page_menu_item\"";?>
					><a href="?pages=&p=<?=$_GET['p'];?>&param=">Parametry</a></td>
					<td style="font-size: 1px; width: 1px;">&nbsp;</td>
				<?}?>
				<?if (check_rules($baza,'site_podstrona','select')){?>
					<td 
					<?if (isset($_GET[podstrona])) echo "class=\"page_menu_item2\""; 
					else echo "class=\"page_menu_item\"";?>
					><a href="?pages=&p=<?=$_GET['p'];?>&podstrona=">Podstrona</a></td>
					<td style="font-size: 1px; width: 1px;">&nbsp;</td>
				<?}?>
				<?if (check_rules($baza,'site_module','select')){?>
					<td 
					<?if (isset($_GET[modul])) echo "class=\"page_menu_item2\""; 
					else echo "class=\"page_menu_item\"";?>
					><a href="?pages=&p=<?=$_GET['p'];?>&modul=">Moduły</a></td>
					<td style="font-size: 1px; width: 1px;">&nbsp;</td>
				<?}?>
				<?if (check_rules($baza,'site_dostep','select')){?>
					<td 
					<?if (isset($_GET[dostep])) echo "class=\"page_menu_item2\""; 
					else echo "class=\"page_menu_item\"";?>
					><a href="?pages=&p=<?=$_GET['p'];?>&dostep=">Dostęp</a></td>
					<td style="font-size: 1px; width: 1px;">&nbsp;</td>
				<?}?>
				<?if (check_rules($baza,'site','delete')){?>
					<td 
					<?if (isset($_GET[usun_site])) echo "class=\"page_menu_item2\""; 
					else echo "class=\"page_menu_item\"";?>
					><a href="?pages=&p=<?=$_GET['p'];?>&usun_site=">Usuń</a></td>
				<?}?>
				</tr>
			</table>
			</center>
			 <!--a href="?pages=&p=<?=$_GET['p'];?>&podstrona=">Podstrona</a>
			 | <a href="?pages=&p=<?=$_GET['p'];?>&param=">Parametry</a>
			 | <a href="?pages=&p=<?=$_GET['p'];?>&dostep=">Dostęp</a>
			 | <a href="?pages=&p=<?=$_GET['p'];?>&usun_site=">Usuń</a>
			 | <a href="?pages=&p=<?=$_GET['p'];?>&modul=">Moduły</a-->
			<?
		}
		else 
			echo "(Aby zarządzać stroną wskaż ją w strukturze serwisu.)";
		?>
		</td>
	</tr>
	
	<tr>
		<td class="tab_elementow">
		<center>
		<?
		if (check_rules($baza,'site','insert'))
			if (!isset($_GET['p']) or isset($_GET['katalog']))
				include_once("new_site.php"); 
		if (isset($_GET['podstrona']))
			include_once("new_podstrona.php"); 
		if (isset($_GET['param']))
			include_once("param.php"); 
		if (isset($_GET['dostep']))
			include_once("dostep.php"); 
		if (isset($_GET['usun_site']))
			include_once("usun_strone.php"); 
		if (isset($_GET['modul']))
			include_once("moduly_strona.php"); 
		if (isset($_GET['p']) and !isset($_GET['podstrona']) and 
		!isset($_GET['param']) and !isset($_GET['dostep']) and 
		!isset($_GET['dostep']) and !isset($_GET['usun_site']) 
		and !isset($_GET['modul'])){
			include_once("param.php"); 
		}
		?>
		</center>
		</td>
	</tr>
</table>