<?
	include("../config/mysql_config.php");
	include('../funclass/tip.class.php');
	$is_login=is_login();
	if($is_login[0]==1010){//已经登录成功
		$Tip=new tip();
		$tip='';
		$row=$is_login[1];

	}else{
		header('location:login.php');
		exit();
	}
	$email='1184511588@qq.com';
	function hideStar($str) { //用户名、邮箱、手机账号中间字符串以*隐藏 
		if (strpos($str, '@')) { 
			$email_array = explode("@", $str); 
			$prevfix = (strlen($email_array[0]) < 4) ? "" : substr($str, 0, 3); //邮箱前缀 
			$count = 0; 
			$str = preg_replace('/([\d\w+_-]{0,100})@/', '***@', $str, -1, $count); 
			$rs = $prevfix . $str; 
		} else { 
			$pattern = '/(1[3458]{1}[0-9])[0-9]{4}([0-9]{4})/i'; 
			if (preg_match($pattern, $str)) { 
				$rs = preg_replace($pattern, '$1****$2', $str); // substr_replace($name,'****',3,4); 
			} else { 
				$rs = substr($str, 0, 3) . "***" . substr($str, -1); 
			} 
		} 
		return $rs; 
	}
	echo hideStar($email);
	$body=file_get_contents('../tpl/add_yiban.html');

	echo $body;
?>