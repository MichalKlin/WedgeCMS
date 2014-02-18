<?
$result = $this->baza->select("*","modnewsletteruser", "nu_id=$_GET[usr] and nu_key=$_GET[usun]");
if ($this->baza->size_result($result)==1 and $_GET[grp]!=0){
	$result = $this->baza->delete("modnewsletterusergrupa", "nug_user=$_GET[usr] and nug_group=$_GET[grp]");
	?>
	<p>Usunięcie z newslettera przebiegło pomyślnie.</p>
	<?
} else{
	?>
	<p>Wystąpił błąd - niepoprawny link lub usunięcie nie jest możliwe.</p>
	<?
}