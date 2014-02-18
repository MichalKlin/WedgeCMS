<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td width="150" id="menu_left" style="text-align: left; vertical-align: top; padding: 10px; border-right: 1px solid red;" nowrap>
		<?
		if (isset($_SESSION['panel_admin_user'])){	
			if (check_rules($baza,'admFileMng','select')){
			?>
			<a <?if (isset($_GET[admin_manager])) echo "class=\"gray_menu\""?> 
				href="?admin=&admin_manager=">Manager plików</a><br /> 
			<?
			}
			if (check_rules($baza,'admMod','select')){
			?>
			<a <?if (isset($_GET[admin_module])) echo "class=\"gray_menu\""?> 
				href="?admin=&admin_module=">Konfiguracja modułów</a><br /> 
			<?
			}
			if (check_rules($baza,'admTrans','select')){
			?>
			<a <?if (isset($_GET[admin_trans])) echo "class=\"gray_menu\""?> 
				href="?admin=&admin_trans=">Tłumaczenia</a><br /> 
			<?
			}
			if (check_rules($baza,'admUser','select')){
			?>
			<a <?if (isset($_GET[admin_users])) echo "class=\"gray_menu\""?> 
				href="?admin=&admin_users=">Użytkownicy</a><br /> 
			<?
			}
			if (check_rules($baza,'admGrUser','select')){
			?>
			<a <?if (isset($_GET[admin_grupy])) echo "class=\"gray_menu\""?> 
				href="?admin=&admin_grupy=">Grupy użytkowników</a><br />
			<?
			}
			if (check_rules($baza,'sitemap','select')){
			?>
			<a <?if (isset($_GET[sitemap])) echo "class=\"gray_menu\""?> 
				href="?admin=&sitemap=">Sitemap</a><br />
			<?
			}
			if (check_rules($baza,'object','select')){
			?>
			<a <?if (isset($_GET[obiekty])) echo "class=\"gray_menu\""?> 
				href="?admin=&obiekty=">Obiekty</a><br />
			<?
			}
			if (check_rules($baza,'config','select')){
			?>
			<a <?if (isset($_GET[admin_config])) echo "class=\"gray_menu\""?> 
				href="?admin=&admin_config=">Konfiguracja serwisu</a><br />
			<?
			}
			if (check_rules($baza,'stats','select')){
			?>
			<a href="?admin=&statystyki=">Statystyki</a><br />
			<?
			}
		}		
		?>		
		</td>
		<td style="text-align: center; vertical-align: top;" width="100%">
		<?
		if (isset($_GET['admin_manager']) and check_rules($baza,'admFileMng','select')){
			include("includes/file_manager.php");
		}		
		if (isset($_GET['admin_users']) and check_rules($baza,'admUser','select')){
			include("includes/admin_users.php");
		}		
		if (isset($_GET['admin_trans']) and check_rules($baza,'admTrans','select')){
			include("includes/admin_translate.php");
		}		
		if (isset($_GET['admin_grupy']) and check_rules($baza,'admGrUser','select')){
			include("includes/admin_grupy.php");
		}		
		if (isset($_GET['sitemap']) and check_rules($baza,'sitemap','select')){
			include("includes/sitemap.php");
		}		
		if (isset($_GET['obiekty']) and check_rules($baza,'object','select')){
			include("includes/obiekty.php");
		}		
		if (isset($_GET['admin_module']) and check_rules($baza,'admMod','select')){
			include("includes/admin_module.php");
		}		
		if (isset($_GET['admin_config']) and check_rules($baza,'config','select')){
			include("includes/admin_config.php");
		}		
		if (isset($_GET['statystyki']) and check_rules($baza,'stats','select')){
			include("includes/admin_stats.php");
		}		
		?>
		</td>
	</tr>
</table>