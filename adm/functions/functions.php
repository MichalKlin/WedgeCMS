<?
function reload_page($url, $time){
	echo $url;
	echo "<script language='JavaScript' type='text/JavaScript'>
	window.setTimeout('window.location=\"$url\"',$time);
	</script>";
}

function check_rules($baza,$object, $rule){
	$res = $baza->select($rule."AdmRules","cmsadmobject,cmsadmrules,cmsadmgroupuser,cmsadmuser",
		"cmsadmuser.idAdmUser=".$_SESSION['panel_admin_user_id']." and 
		 cmsadmuser.admGroupUser=cmsadmgroupuser.idAdmGroupUser and 
		 cmsadmrules.admGroupUser=cmsadmgroupuser.idAdmGroupUser and 
		 cmsadmrules.object=cmsadmobject.idAdmObject and 
		 cmsadmobject.nameAdmObject='$object'
		 ","","");
	if ($baza->size_result($res)>0){
		$row = $baza->row($res);
		if ($row[$rule.'AdmRules']=='on')
			return true;
		else 
			return false;	
	}
	return false;
}
?>