<?php
include_once("class/class_edit_table.php");
$tabela = "modarticlecomment";
$klucz_glowny = "modarticlecomment_id";
$tablica_kolumn = array("modarticlecomment_article","modarticlecomment_author","modarticlecomment_content",
"modarticlecomment_date","modarticlecomment_ip","modarticlecomment_active");
$tablica_nazw_kolumn = array("Artykuł","Autor","Treść","Data","IP","Aktyw.");
$tabela_relacji = null;
$tab_rel_przedrostek = null;
$kolumna_order = "modarticlecomment_id";		
$typ_order = "DESC";

//stworzenie asocjacyjnej tabicy wartości domyslnych, czyli takich ktĂłre nie byĹy w formularzu
$tablica_wart_domyslnych = array("modarticlecomment_id" => "0");
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