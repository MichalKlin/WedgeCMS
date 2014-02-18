<center>
<table border="0" cellpadding="0" cellspacing="0">
	<tr>
	<?if (check_rules($baza,'template_html','select')){?>
		<td 
		<?if (isset($_GET[templ_html])) echo "class=\"page_menu_item2\""; 
		else echo "class=\"page_menu_item\"";?>
		><a href="?template=&templ_html=">Szablony HTML</a></td>
		<td style="font-size: 1px; width: 1px;">&nbsp;</td>
	<?}?>
	<?if (check_rules($baza,'template_css','select')){?>
		<td 
		<?if (isset($_GET[templ_css])) echo "class=\"page_menu_item2\""; 
		else echo "class=\"page_menu_item\"";?>
		><a href="?template=&templ_css=">Style CSS</a></td>
	<?}?>
	</tr>
</table>
</center>
