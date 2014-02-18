<?
function check_section($row,$nr,$flag){
	if ($row['sekcja'.$nr]==$flag) 
		return " checked";
	return "";	
}
function check_section_selected($row,$nr,$flag){
	if ($row['sekcja'.$nr]==$flag){ 
		return " class=\"wybrana_sekcja\"";
	}
	return " class=\"niewybrana_sekcja\"";	
}
?>