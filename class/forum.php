<?
class forum{
	var $baza;				// uchwyt do bazy danych
	var $tab_users;			// tabela użytkowników
	var $tab_topics;		// tabela tematów
	var $tab_items; 		// tabela wpisów
	var $tab_group; 		// tabela grup użytkowników
	var $zalogowany_id;		// id zalogowanego usera z sesji
	var $zalogowany_name;	// name zalogowany user z sesji
	var $path;
	
	function forum($baza,$tab_u,$tab_t,$tab_i,$tab_g){
		$this->baza = $baza;
		$this->tab_topics = $tab_t;
		$this->tab_users = $tab_u;
		$this->tab_group = $tab_g;
		$this->tab_items = $tab_i;
		$this->zalogowany_id = $_SESSION['cms_user_id'];
		$this->zalogowany_name = $_SESSION['cms_user_name'];		
		$this->path = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
	}
	
	function getTopics(){
		$result = $this->baza->select("*",$this->tab_topics,"ft_active='YES'");
		$size_result = $this->baza->size_result($result);
		if ($size_result>0){
			for ($i=0; $i<$size_result; $i++){
				$row = $this->baza->row($result);
				$topics[$i][0] = $row['ft_id'];
				$topics[$i][1] = $row['ft_name'];
				$topics[$i][2] = $row['ft_author'];
				$topics[$i][3] = $row['ft_show_item'];
				$topics[$i][4] = $row['ft_date_add'];
			}
		}
		return $topics;
	}
	
	function getTopic($topic_id){
		$result = $this->baza->select("*",$this->tab_topics,"ft_active='YES' and ft_id=$topic_id","","");
		$size_result = $this->baza->size_result($result);
		if ($size_result>0){
			$row = $this->baza->row($result);
			$topic[0] = $row['ft_id'];
			$topic[1] = $row['ft_name'];
			$topic[2] = $row['ft_author'];
			$topic[3] = $row['ft_show_item'];
			$topic[4] = $row['ft_date_add'];
		}
		return $topic;
	}
	
	function addTopic($name){
		if (strlen(trim($name))>0){
			$r = $this->baza->select("1",$this->tab_topics,"ft_name='$name'");
			if ($this->baza->size_result($r)==0){
				$author = $this->zalogowany_id;
				$dzis = date("Y-m-d");
				$value = "0,'".htmlspecialchars($name)."',$author,0,'$dzis','YES'";
				$result = $this->baza->insert($this->tab_topics,$value);
				if ($result)
					return $this->baza->last_insert_id();
				return -1;
			}
			else 
				return -3;
		}
		return -2;			
	}
	
	function setTopicName($topic_id,$name){
		if (strlen(trim($name))>0){
			$value = "ft_name='".htmlspecialchars($name)."'";
			$where = "ft_id=$topic_id";
			$result = $this->baza->update($this->tab_topics,$value,$where);
			if ($result)
				return 1;
			return -1;
		}
		return -2;			
	}
	
	function setTopicCounter($topic_id){
		$value = "ft_show_item=ft_show_item+1";
		$where = "ft_id=$topic_id";
		$result = $this->baza->update($this->tab_topics,$value,$where);
		if ($result)
			return 1;
		return -1;
	}
	
	function delTopic($topic_id){
		$where = "ft_id=$topic_id";
		$result = $this->baza->delete($this->tab_topics,$where);
		if ($result){
			$where = "fi_topic=$topic_id";
			$result = $this->baza->delete($this->tab_items,$where);
			return 1;
		}
		return -1;
	}
	
	function getItems($topic_id){
		$result = $this->baza->select("*",$this->tab_items,"fi_active='YES' and fi_topic=$topic_id","ORDER BY fi_id","");
		$size_result = $this->baza->size_result($result);
		if ($size_result>0){
			for ($i=0; $i<$size_result; $i++){
				$row = $this->baza->row($result);
				$items[$i][0] = $row['fi_id'];
				$items[$i][1] = $row['fi_author'];
				$items[$i][2] = $row['fi_content'];
				$items[$i][3] = $row['fi_date'];
				$items[$i][4] = $row['fi_time'];
				$items[$i][5] = $row['fi_award'];
			}
		}
		return $items;
	}

	function getItemsAll($topic_id){
		$result = $this->baza->select("*",$this->tab_items,"fi_topic=$topic_id AND (fi_active='YES' or (fi_active='NO' AND fi_author=0))","ORDER BY fi_id");
		$size_result = $this->baza->size_result($result);
		if ($size_result>0){
			for ($i=0; $i<$size_result; $i++){
				$row = $this->baza->row($result);
				$items[$i][0] = $row['fi_id'];
				$items[$i][1] = $row['fi_author'];
				$items[$i][2] = $row['fi_content'];
				$items[$i][3] = $row['fi_date'];
				$items[$i][4] = $row['fi_time'];
				$items[$i][5] = $row['fi_award'];
				$items[$i][6] = $row['fi_active'];
			}
		}
		return $items;
	}
	
	function sizeTopic($topic_id){
		$result = $this->baza->select("1",$this->tab_items,"fi_active='YES' and fi_topic=$topic_id");
		return $this->baza->size_result($result);
	}
	
	function addItem($content,$topic_id,$gosc){
		if (strlen(trim($content))>0){
			if (!$this->isLogIn() and strlen($gosc)==0){
				return -4;	
			}
			else{
				$r = $this->baza->select("1",$this->tab_items,"fi_content='$content' or fi_content='$content"."<p>$gosc</p>"."'","","");
				if ($this->baza->size_result($r)==0){
					$rr=$this->baza->select("fu_email,fu_email_new_item",$this->tab_users.",".$this->tab_items,
						"fi_author=fu_id AND fi_topic=$topic_id","ORDER BY fi_id DESC");
					$row_rr = $this->baza->row($rr);
					//echo $row_rr['fu_email_new_item'].$row_rr['fu_email'];
					if ($this->isLogIn())
						$author = $this->zalogowany_id;
					else $author = 0;
					$dzis = date("Y-m-d");
					$time = date("G:i");
					if (strlen($gosc)>0) $content .= "<p>$gosc</p>";
					if ($this->isLogIn()) $aktywny = 'YES'; else $aktywny = 'NO';
					$value = "0,$author,$topic_id,'".htmlspecialchars($content)."','$dzis','$time','o','$aktywny'";
					$result = $this->baza->insert($this->tab_items,$value,"");
					if ($result){
						if ($row_rr['fu_email_new_item']=='YES')
							$this->emailNewItem($row_rr['fu_email']);
						return $this->baza->last_insert_id();
					}
					return -1;
				}
				else 
					return -3;
			}
		}
		return -2;			
	}
	
	function delItem($item_id){
		$where = "fi_id=$item_id";
		$result = $this->baza->delete($this->tab_items,$where);
		if ($result)
			return 1;
		return -1;
	}
	
	function unactiveItem($item_id){
		$where = "fi_id=$item_id";
		$wart = "fi_active='NO'";
		$result = $this->baza->update($this->tab_items,$wart,$where);
		if ($result)
			return 1;
		return -1;
	}
	
	function activeItem($item_id){
		$where = "fi_id=$item_id";
		$wart = "fi_active='YES'";
		$result = $this->baza->update($this->tab_items,$wart,$where);
		if ($result)
			return 1;
		return -1;
	}
		
	function awardItem($item_id,$a){
		$where = "fi_id=$item_id";
		$wart = "fi_award='$a'";
		$result = $this->baza->update($this->tab_items,$wart,$where);
		if ($result){
			$r = $this->baza->select("*",$this->tab_items.",".$this->tab_users,$where." and fi_author=fu_id");
			$row = $this->baza->row($r);
			if ($row['fu_email_award']=='YES'){
				if ($a=='p' or $a=='n'){
					$this->emailAward($row['fu_email'],$a);
				}
			}
			return 1;
		}
		return -1;
	}
	

	function searchItems($fraza,$autor){
		if (strlen($fraza)>0) $and_fraza = " AND fi_content LIKE '%$fraza%'";
		if (strlen($autor)>0) $and_autor = " AND fu_login LIKE '%$autor%'";
		
		$result = $this->baza->select("*",$this->tab_items.",".$this->tab_topics.",".$this->tab_users,
		"fi_active='YES' AND fi_topic=ft_id AND fi_author=fu_id $and_fraza $and_autor","ORDER BY fi_id DESC","");
		$size_result = $this->baza->size_result($result);
		if ($size_result>0){
			for ($i=0; $i<$size_result; $i++){
				$row = $this->baza->row($result);
				$items[$i][0] = $row['fi_id'];
				$items[$i][1] = $row['fi_author'];
				$items[$i][2] = $row['fi_content'];
				$items[$i][3] = $row['fi_date'];
				$items[$i][4] = $row['fi_time'];
				$items[$i][5] = $row['fi_award'];
				$items[$i][6] = $row['ft_name'];
			}
		}
		return $items;
	}
	
	function setItem($item_id){
		
	}
	
	function isLogIn(){
		return $this->zalogowany_id;
	}

	/*
	function login($login,$password){
		$result = $this->baza->select("*",$this->tab_users,"fu_login='$login' and fu_password='$password' and fu_active='YES' and fu_delete='NO'");
		$size_result = $this->baza->size_result($result);
		if ($size_result>0){
			$row = $this->baza->row($result);
			$this->zalogowany_id = $row['fu_id'];
			$this->zalogowany_name = $row['fu_forename']." ".$row['name'];		
			$_SESSION['user_forum_id'] = $this->zalogowany_id;
			$_SESSION['user_forum_name'] = $this->zalogowany_name;		
			return 1;
		}
		return -1;
	}
	*/
	
	function ileWpisowUser($user_id){
		$result = $this->baza->select("1",$this->tab_items,"fi_author=$user_id AND fi_active='YES'");
		$size_result = $this->baza->size_result($result);
		return $size_result;
	}
	
	function ilePochwalUser($user_id){
		$result = $this->baza->select("1",$this->tab_items,"fi_author=$user_id and fi_award='p' AND fi_active='YES'");
		$size_result = $this->baza->size_result($result);
		return $size_result;
	}
	
	function ileOstrzezenUser($user_id){
		$result = $this->baza->select("1",$this->tab_items,"fi_author=$user_id and fi_award='n' AND fi_active='YES'");
		$size_result = $this->baza->size_result($result);
		return $size_result;
	}
	
	/*
	function logout(){
		unset($_SESSION['user_forum_id']);
		unset($_SESSION['user_forum_name']);
	}
	
	function addUser($forename,$name,$email,$login,$password,$city,$www,$gg){
		if (strlen(trim($forename))>0 and strlen(trim($name))>0 and 
			strlen(trim($email))>0 and strlen(trim($login))>0 and 
			strlen(trim($password))>0){
			$dzis = date("Y-m-d");
			$value = "0,'".htmlspecialchars($forename)."',
			'".htmlspecialchars($name)."',
			'".htmlspecialchars($email)."',
			'".htmlspecialchars($login)."',
			'".htmlspecialchars($password)."',0,
			'".htmlspecialchars($city)."',
			'".htmlspecialchars($www)."',
			'".htmlspecialchars($gg)."','$dzis','','NO','NO','NO','NO','NO'";
			$result = $this->baza->insert($this->tab_users,$value);
			if ($result)
				return $this->baza->last_insert_id();
			return -1;
		}
		return -2;			
	}
	*/
	
	function getUserId(){
		if (!isset($_SESSION['user_forum_id']))
			return 0;
		return $this->zalogowany_id;
	}
	
	function getUsers(){
		$result = $this->baza->select("*",$this->tab_users,"fu_active='YES' and fu_delete='NO'","ORDER BY fu_name,fu_forename");
		$size_result = $this->baza->size_result($result);
		if ($size_result>0){
			for ($i=0; $i<$size_result; $i++){
				$row = $this->baza->row($result);
				$tab_user[$i][0] = $row['fu_id'];
				$tab_user[$i][1] = $row['fu_login'];
			}
			return $tab_user;
		}
		return false;
	}
	
	function getUsersAll(){
		$result = $this->baza->select("*",$this->tab_users,"fu_delete='NO'","ORDER BY fu_active,fu_id");
		$size_result = $this->baza->size_result($result);
		if ($size_result>0){
			for ($i=0; $i<$size_result; $i++){
				$row = $this->baza->row($result);
				$tab_user[$i][0] = $row['fu_id'];
				$tab_user[$i][1] = $row['fu_login'];
				$tab_user[$i][2] = $row['fu_active'];
				$tab_user[$i][3] = $row['fu_delete'];
			}
			return $tab_user;
		}
		return false;
	}
	
	function getUser($user_id=null){
		if ($user_id==null){
			if (!isset($_SESSION['user_forum_id']))	$user_id = 0;
			else $user_id = $this->zalogowany_id;
		}
		$result = $this->baza->select("*",$this->tab_users,"fu_id=$user_id");
		$size_result = $this->baza->size_result($result);
		if ($size_result>0){
			$row = $this->baza->row($result);
			$tab_user[0] = $row['fu_login'];
			$tab_user[1] = $row['fu_footer'];
			return $tab_user;
		}
		return false;
	}
	
	function getUserGroup($user_id=null){
		if ($user_id==null){
			if (!isset($_SESSION['user_forum_id']))	$user_id = 0;
			else $user_id = $this->zalogowany_id;
		}
		$result = $this->baza->select("*",$this->tab_users,"fu_id=$user_id");
		$size_result = $this->baza->size_result($result);
		if ($size_result>0){
			$row = $this->baza->row($result);
			return $row['fu_group'];
		}
		return false;
	}
	function getFullUser($user_id=null){
		if ($user_id==null){
			if (!isset($_SESSION['user_forum_id']))	$user_id = 0;
			else $user_id = $this->zalogowany_id;
		}
		$result = $this->baza->select("*",$this->tab_users,"fu_id=$user_id");
		$size_result = $this->baza->size_result($result);
		if ($size_result>0){
			$row = $this->baza->row($result);
			$tab_user[0] = $row['fu_login'];
			$tab_user[1] = $row['fu_email'];
			$tab_user[2] = $row['fu_city'];
			$tab_user[3] = $row['fu_www'];
			$tab_user[4] = $row['fu_gg'];
			$tab_user[5] = $row['fu_date_join'];
			$tab_user[6] = $row['fu_email_show'];
			$tab_user[7] = $row['fu_footer'];
			$tab_user[8] = $row['fu_email_new_item'];
			$tab_user[9] = $row['fu_email_award'];
			
			$tab_user[10] = $row['fu_forename'];
			$tab_user[11] = $row['fu_name'];
			return $tab_user;
		}
		return false;
	}

	function getUserById($id){
		$result = $this->baza->select("fu_login",$this->tab_users,"fu_id=$id");
		$size_result = $this->baza->size_result($result);
		if ($size_result>0){
			$row = $this->baza->row($result);
			return $row['fu_login'];
		}
		return false;
	}
	
	function setUser($forename,$name,$email,$login,$city,$www,$gg,$footer,$email_reply,$email_award,$show_email,$user_id=null){
		if ($user_id==null)
			$user_id = $this->zalogowany_id;
		
	}
	
	function setUserDane($email,$city,$www,$gg){
		$value = "fu_email='".htmlspecialchars($email)."', 
		fu_city='".htmlspecialchars($city)."', 
		fu_www='".htmlspecialchars($www)."', 
		fu_gg='".htmlspecialchars($gg)."'";
		$user_id = $this->zalogowany_id;
		$where = "fu_id=$user_id";
		$result = $this->baza->update($this->tab_users,$value,$where);
		if ($result)
			return 1;
		return -1;
	}
		
	function setUserSettings($stopka,$emial_wid,$email_odp,$email_award){
		$value = "fu_footer='".htmlspecialchars($stopka)."', 
		fu_email_show='$emial_wid', fu_email_new_item='$email_odp', fu_email_award='$email_award'";
		$where = "fu_id=".$this->zalogowany_id;
		$result = $this->baza->update($this->tab_users,$value,$where,"");
		if ($result)
			return 1;
		return -1;
	}

	function setUserPassword($password_old,$password_new){
		$user_id = $this->zalogowany_id;
		$where = "fu_id=$user_id and fu_password='".htmlspecialchars($password_old)."'";
		$r = $this->baza->select("1",$this->tab_users,$where,"","");
		if ($this->baza->size_result($r)>0){
			$value = "fu_password='$password_new'";
			$result = $this->baza->update($this->tab_users,$value,$where);
			if ($result)
				return 1;
		}
		return -1;
	}
	
	function activeUser($user_id){
		$email = $this->getUserEmail($user_id);
		$value = "fu_active='YES'";
		$where = "fu_id=$user_id";
		$result = $this->baza->update($this->tab_users,$value,$where);
		if ($result){
			$this->emailRegistryOK($email);
			return 1;
		}
		return -1;
	}
	
	function mailActiveUser($user_id){
		$email = $this->getUserEmail($user_id);
		$login = $this->getUserEmail($user_id);
		$hash = md5($email.$login);
		$this->emailConfirmRegistry($email,$user_id,$hash);
	}
	
	function getUserEmail($user_id){
		$result = $this->baza->select("fu_email",$this->tab_users,"fu_id=$user_id");
		$size_result = $this->baza->size_result($result);
		if ($size_result>0){
			$row = $this->baza->row($result);
			return $row['fu_email'];
		}
		return false;
	}
	
	function deactiveUser($user_id){
		$value = "fu_active='NO'";
		$where = "fu_id=$user_id";
		$result = $this->baza->update($this->tab_users,$value,$where);
		if ($result)
			return 1;
		return -1;
	}
	
	function delUser($user_id){
		$value = "fu_active='NO', fu_delete='YES'";
		$where = "fu_id=$user_id";
		$result = $this->baza->update($this->tab_users,$value,$where,"");
		if ($result)
			return 1;
		return -1;
	}
	
	function emailConfirmRegistry($email,$id,$hash){
		$result = $this->baza->select("value","cmsconfig","name='ADM_EMAIL'");
		$row = $this->baza->row($result);		
		$naglowki  = "MIME-Version: 1.0\r\n";
		$naglowki .= "Content-type: text/html; charset=utf-8\r\n";
		$naglowki .= "From: Rejestracja<".$row[value].">\r\n";		
		$naglowki .= "Cc: $email\r\n";
		$naglowki .= "Bcc: $email\r\n";
		$link = $this->path."?registry=$id&hash=$hash";
		mail($email,"Rejestracja na forum","Proszę kliknąć w poniższy link w celu aktywacji konta<br />
		<a href=\"$link\">Aktywacja konta</a>",$naglowki);
		
		//echo $link;
	}
	
	function emailRegistryOK($email){
		$result = $this->baza->select("value","cmsconfig","name='ADM_EMAIL'");
		$row = $this->baza->row($result);
		$naglowki  = "MIME-Version: 1.0\r\n";
		$naglowki .= "Content-type: text/html; charset=utf-8\r\n";
		$naglowki .= "From: Forum <".$row[value].">\r\n";		
		$naglowki .= "Cc: $email\r\n";
		$naglowki .= "Bcc: $email\r\n";
		mail($email,"Konto aktywne","Konto zostało aktywowane z paremetrami podynymi w formularzu rejestracyjnym. ",$naglowki);
	}
	
	function emailNewItem($email){
		$result = $this->baza->select("value","cmsconfig","name='ADM_EMAIL'");
		$row = $this->baza->row($result);
		$naglowki  = "MIME-Version: 1.0\r\n";
		$naglowki .= "Content-type: text/html; charset=utf-8\r\n";
		$naglowki .= "From:Odpowiedz forum<".$row[value].">\r\n";		
		$naglowki .= "Cc: $email\r\n";
		$naglowki .= "Bcc: $email\r\n";
		$a = mail($email,"Odpowiedź na wpis","Na forum pojawiła się odpowiedź na Twój ostatni wpis!",$naglowki);
	}
	
	function emailAward($email,$award){
		$naglowki  = "MIME-Version: 1.0\r\n";
		$naglowki .= "Content-type: text/html; charset=utf-8\r\n";
		$naglowki .= "From: Moderator<forum@forum.kometa.pl>\r\n";		
		$naglowki .= "Cc: $email\r\n";
		$naglowki .= "Bcc: $email\r\n";
		if ($award=='p')
			mail($email,"Forum ocena wpisu","Jeden z Twoich wpisów dostał pochwałę.",$naglowki);
		if ($award=='n')
			mail($email,"Forum ocena wpisu","Dostałeś ostrzeżenie za jeden z Twoich wpisów!",$naglowki);
	}
	
	function emailNewUser($email){
		$result = $this->baza->select("value","cmsconfig","name='ADM_EMAIL'");
		$row = $this->baza->row($result);
		$naglowki  = "MIME-Version: 1.0\r\n";
		$naglowki .= "Content-type: text/html; charset=utf-8\r\n";
		$naglowki .= "From: Rejestracja<".$row[value].">\r\n";		
		$naglowki .= "Cc: $email\r\n";
		$naglowki .= "Bcc: $email\r\n";
		mail($email,"New user in forum","nowy użytkownik się zarejestrował w forum",$naglowki);
	}
}
?>