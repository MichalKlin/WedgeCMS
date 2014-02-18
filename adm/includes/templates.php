<table width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td id="page_menu">	
			<?include_once("template_menu.php");?>
		</td>
	</tr>
	
	<tr>
		<td class="tab_elementow">
		<center>
		<?
		if (isset($_GET['templ_html']))
			include_once("templ_html.php"); 
		elseif (isset($_GET['templ_css']))
			include_once("templ_css.php"); 
		else{ 
			?><h3>Zarządzanie templates</h3><?
		}
		?>
		</center>
		</td>
	</tr>
</table>
