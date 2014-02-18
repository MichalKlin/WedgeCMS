<?
class cms{
	var $html;
	var $baza;
	var $trans;
	
	function cms($baza, $trans = null){
		$this->baza = $baza;
		$this->trans = $trans;
		
		$page = $this->getPage();

		// bez przekierowania gdy nie ma strony
		//if ($page == -1 and $_GET['page']==""){
		//	$page = $this->getDefaultPage();
		//}

		if ($page>=0){				
			//ustawienie zmiennej sesyjnej
			if (!isset($_SESSION['session_name'])){
				$r = $this->baza->select("value","cmsconfig","name='SESSION_NAME'");
				$row = $this->baza->row($r);
				$_SESSION['session_name'] = $row[value];
			}

			//ustawienie zmiennej sesyjnej dla języku interfejsu
			if (!isset($_SESSION['site_lang'])){
				$_SESSION['site_lang'] = 'PL';
			}
			if (isset($_GET[lang])){
				$_SESSION['site_lang'] = $_GET[lang];
			}

			$user = $this->getUser();
			if ($this->checkAccess($page,$user)){
				$this->createHtml($page);
			}
			else{
				$this->reloadDefaultPage();
			}
		}
		else{
			$this->reloadDefaultPage();	
		}
	}
	
	function getHtml(){
		return $this->html;
	}
	
	function getPage(){
		if (isset($_GET['page'])){
			$r = $this->baza->select("idPage","cmspage","htmlName='".$_GET['page']."' AND active='YES'","","");
			if($this->baza->size_result($r)==1){
				$row_cms = $this->baza->row($r);
				return $row_cms['idPage'];
			}
		}
		return -1;
	}
	
	function getUser(){
		if (isset($_SESSION['cms_user_id']))
			return $_SESSION['cms_user_id'];
		return 1;
	}
	
	function checkAccess($page,$user){
		$r = $this->baza->select("1","cmsuser,cmsgroupuser,cmspageusergroup",
		"cmsuser.id=$user AND cmsuser.groupUser=cmsgroupuser.idGroupUser 
		AND cmspageusergroup.groupUser=cmsgroupuser.idGroupUser 
		AND cmspageusergroup.page=$page","","");
		if($this->baza->size_result($r)==1)
			return 1;
		return 0;
	}
	
	function reloadDefaultPage(){
		$r = $this->baza->select("htmlName,name","cmspage","defaultPage='YES' AND active='YES'","","");
		if($this->baza->size_result($r)>=1){
			$row_cms = $this->baza->row($r);
			$strona = $row_cms['htmlName'];
		}
		$php_self = str_replace("index.php","", $_SERVER['PHP_SELF']);
		header('Location: http://'.$_SERVER['HTTP_HOST'].$php_self.$strona.".html");
		exit();
	}
	
	function getDefaultPage(){
		// get default page
		$r = $this->baza->select("idPage","cmspage","defaultPage='YES' AND active='YES'","","");
		if($this->baza->size_result($r)>=1){
			$row_cms = $this->baza->row($r);
			return $row_cms['idPage'];
		}
		return -1;
	}

	function createHtml($page){
		$r = $this->baza->select("file,template","cmstemplate,cmsschema,cmspage",
		"idPage=$page 
		AND cmspage.schema=cmsschema.id 
		AND cmsschema.template=cmstemplate.id","","");
		if($this->baza->size_result($r)==1){
			$row_cms = $this->baza->row($r);
			$path_file = $row_cms['file'];
			$TEMPLATE = $row_cms['template'];

			ob_start();
			include_once("template/".$path_file);
			$content = ob_get_contents();
			ob_end_clean();   
			
			$r = $this->baza->select("cmsschema.id as id_sch,
				cmstemplate.sekcja0 as sekcja0,cmstemplate.sekcja1 as sekcja1,cmstemplate.sekcja2 as sekcja2,
				cmstemplate.sekcja3 as sekcja3,cmstemplate.sekcja4 as sekcja4,cmstemplate.sekcja5 as sekcja5,
				cmstemplate.sekcja6 as sekcja6,cmstemplate.sekcja7 as sekcja7,cmstemplate.sekcja8 as sekcja8,
				cmstemplate.sekcja9 as sekcja9,cmstemplate.sekcja10 as sekcja10,cmstemplate.sekcja11 as sekcja11,
				cmstemplate.sekcja12 as sekcja12,cmstemplate.sekcja13 as sekcja13,cmstemplate.sekcja14 as sekcja14,
				cmstemplate.sekcja15 as sekcja15,cmstemplate.sekcja16 as sekcja16,cmstemplate.sekcja17 as sekcja17,
				cmstemplate.sekcja18 as sekcja18,cmstemplate.sekcja19 as sekcja19,cmstemplate.sekcja20 as sekcja20,
				cmstemplate.sekcja21 as sekcja21,cmstemplate.sekcja22 as sekcja22,
				cmspage.htmlName,cmspage.name",
				"cmsschema,cmspage,cmstemplate",
					"cmspage.idPage=$page AND cmspage.schema=cmsschema.id 
					AND cmsschema.template=cmstemplate.id");
			if($this->baza->size_result($r)==1){
				$row_cms = $this->baza->row($r);
				$schema = $row_cms['id_sch'];
				for ($i_cms=0; $i_cms<23; $i_cms++){
					if ($row_cms['sekcja'.$i_cms]=='YES'){
						//echo $i_cms;
						$rr = $this->baza->select("cmsmodule.*,cmsschemamodule.*,cmsschemamodule.id as id_sch_mod",
						"cmsmodule,cmsschemamodule",
						"cmsschemamodule.module=cmsmodule.idModule 
						AND cmsschemamodule.schema=$schema
						AND cmsschemamodule.active='YES' 						
						AND cmsschemamodule.sekcja=$i_cms",
						"ORDER BY cmsschemamodule.order","");
						if(($ile = $this->baza->size_result($rr))>0){
							$moduly = "";
							for ($j_cms=0;$j_cms<$ile; $j_cms++){
								$row_cmsr = $this->baza->row($rr);

								ob_start();
								$SCHEMA_MODULE = $row_cmsr['id_sch_mod'];

								$SCHEMA = $row_cmsr['schema'];
								$SEKCJA = $row_cmsr['sekcja'];
								$MODULE = $row_cmsr['module'];
								$NAZWA = $row_cmsr['name'];
								$PAGE_NAME = $row_cms['htmlName'];
								$NAME = $row_cms['name'];
								$PAGE = $page;
								$PAGE_FULL_NAME = $row_cms['name'];
								echo "<div class=\"".$row_cmsr['folder']."\">";
								
								//template modułu
								$brak_template = false;
								if ($row_cmsr[template]!='0' and strlen($row_cmsr[template])>0){
									$rmt = $this->baza->select("*","cmsmoduletemplate","id=$row_cmsr[template] and active='YES'");
									$row_cmt = $this->baza->row($rmt);
									$file_include = "modules/".$row_cmsr['folder']."/template/".$row_cmt[file];
								}
								elseif($row_cmsr[template]=='0'){
									$rmta = $this->baza->select("*","cmsmoduletemplate","module=".$row_cmsr[module]." and active='YES'","","");
									if ($this->baza->size_result($rmta)>0){
										$rmtb = $this->baza->select("*","cmsmoduletemplate","module=$row_cmsr[module] and active='YES' and cmsmoduletemplate.default='YES'");
										if ($this->baza->size_result($rmtb)>0){
											$row_cmt = $this->baza->row($rmtb);
											$file_include = "modules/".$row_cmsr['folder']."/template/".$row_cmt[file];
										}
										else{
											$brak_template = true;
										}
									}
									else{
										$brak_template = true;
									}
								}
								else{
									$brak_template = true;
								}
								if ($brak_template){
									$file_include = "modules/".$row_cmsr['folder']."/index.php";
								}

								if (file_exists($file_include) and strlen($row_cmsr['folder'])>0){
									include($file_include);
								}
								
								echo "</div>";
									
								$moduly .= ob_get_contents();
								ob_end_clean();  
							}

							//parsowanie font size w modułach
							$moduly = str_replace('size="1"','size="1" class="fontsize1"',$moduly);
							$moduly = str_replace('size="2"','size="2" class="fontsize2"',$moduly);
							$moduly = str_replace('size="3"','size="3" class="fontsize3"',$moduly);
							$moduly = str_replace('size="4"','size="4" class="fontsize4"',$moduly);
							$moduly = str_replace('size="5"','size="5" class="fontsize5"',$moduly);
							$moduly = str_replace('size="6"','size="6" class="fontsize6"',$moduly);
							$moduly = str_replace('size="7"','size="7" class="fontsize7"',$moduly);
							
							//parsowanie labeli
							$moduly = preg_replace_callback('/(<label>([^<]*)<\/label>)/i',
								Array($this, 'translate'),$moduly);

							$content = str_replace('<div id="sekcja'.$i_cms.'"></div>','<div id="sekcja'.$i_cms.'">'.$moduly.'</div>',$content);
						}
					}
				}
			}
			//meta tagi
			$r = $this->baza->select("*","cmspage","htmlName='".$_GET['page']."'","","");
			if($this->baza->size_result($r)==1){
				$row_cms = $this->baza->row($r);
				$meta = "<title>".$row_cms[title]."</title>\n";
				$meta .= "<meta name=\"Keywords\" content=\"".$row_cms[keywords]."\" />\n";
				$meta .= "<meta name=\"description\" content=\"".$row_cms[description]."\" />\n";
				
				$content = str_replace('</head>',$meta.'</head>',$content);
			}
			
			$this->html = $content;
		}
	}
	
	function translate($matches){
		$code = $matches[2];
		$r_trans = $this->baza->select("*","cmstranslate","code='$code'","","");
		if($this->baza->size_result($r_trans)==1){
			$row_trans = $this->baza->row($r_trans);
			$xml = $row_trans[xml];
			return $this->trans->getTranslationXML($xml, $_SESSION['site_lang']);
		} else {
			return $code;
		}
	}
}
?>