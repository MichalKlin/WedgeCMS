<?php
include_once("class/class_edit_table.php");
$tabela = "mod_ksiega_gosci";
$klucz_glowny = "mkg_id";
$tablica_kolumn = array("mkg_autor","mkg_typ","mkg_email","mkg_data","mkg_tresc","mkg_active");
$tablica_nazw_kolumn = array("Autor","Typ","E-mail","Data","Treść","Aktyw.");
$tabela_relacji = null;
$tab_rel_przedrostek = null;
$kolumna_order = "mkg_id";		
$typ_order = "ASC";

//stworzenie asocjacyjnej tabicy wartości domyslnych, czyli takich ktĂłre nie byĹy w formularzu
$tablica_wart_domyslnych = array("mkg_id" => "0");
$liczba_wyswietlanych = 200;		//liczba wyswietlanych wierszy na stronie


//$tabela_blokow = new edit_table($this->path, $baza, $tabela, $tabela_relacji, $tab_rel_przedrostek, $klucz_glowny, $tablica_kolumn, $tablica_nazw_kolumn, $tablica_wart_domyslnych);
//$tabela_blokow->manage($liczba_wyswietlanych);

$tabela_blokow = new edit_table("?modules=&manage=$_GET[manage]", $baza, $tabela, $tabela_relacji, $tab_rel_przedrostek, $klucz_glowny, $tablica_kolumn, 
								$tablica_nazw_kolumn, $tablica_wart_domyslnych, $tab_war_skroconych, $select, $single,
								$kolumna_order,$typ_order,$liczba_wyswietlanych,$liczba_wyswietlen_edit,$left_checkbox,
								$pola_file_jpg,$path_file_big,$size_file_big,$path_file_small,$size_file_small);
//wyświetlenie edytora								
$tabela_blokow->manage($liczba_wyswietlanych);

?>