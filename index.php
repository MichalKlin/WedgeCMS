<?
session_start();
include_once("class/base.php");
include_once("class/file.php");
include_once("class/structure.php");
include_once("class/translation.php");
include_once("include/global_functions.php");
$baza = new baza();
$baza->connect();
$trans = new Translation($baza);

include_once("class/cms.php");

$cms = new cms($baza, $trans);
if ($cms->getHtml() == -1){ 
	echo "Strona w budowie - nie ma żadnych stron";
}
else {
	echo $cms->getHtml();
}
?>