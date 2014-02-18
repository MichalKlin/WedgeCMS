<?php
$tabela = "mod_rss";

$result = $this->baza->select("*",$tabela, "", "");	
$row = $this->baza->row($result);	
$wart = $row['mrss_wartosc'];

if($wart != null and strlen($wart)>0){

//$xml="http://rss.polska.pl/news.xml";
//$xml = "http://kanaly.rss.interia.pl/sport.xml";
//$xml = "http://www.jak-budowac.pl/rss/porady";
$xml = $wart;
$xmlDoc = new DOMDocument();
$xmlDoc->load($xml);

$channel=$xmlDoc->getElementsByTagName('channel')->item(0);
$channel_title = $channel->getElementsByTagName('title')->item(0)->childNodes->item(0)->nodeValue;
$channel_link = $channel->getElementsByTagName('link')->item(0)->childNodes->item(0)->nodeValue;
$channel_desc = $channel->getElementsByTagName('description')->item(0)->childNodes->item(0)->nodeValue;

$x=$xmlDoc->getElementsByTagName('item');
for ($i=0; $i<=9; $i++)
  {
  $item_title=$x->item($i)->getElementsByTagName('title')->item(0)->childNodes->item(0)->nodeValue;
  $item_link=$x->item($i)->getElementsByTagName('link')->item(0)->childNodes->item(0)->nodeValue;
  $item_desc=$x->item($i)->getElementsByTagName('description')->item(0)->childNodes->item(0)->nodeValue;

  echo ("<p class='rss_title'><a href='" . $item_link  . "' target='_blank'>" . $item_title . "</a>");
  echo ("</p><p>");
  echo ($item_desc . "</p><center><hr width=\"80%\"></center><br>");
  }
}
?> 