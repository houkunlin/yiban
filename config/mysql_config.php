<?
$mysql_host="localhost";
$mysql_user="root";
$mysql_pass="";
$mysql_db="yiban";

date_default_timezone_set("PRC");
header("content-Type: text/html; charset=utf-8");

$mysqli=new mysqli($mysql_host,$mysql_user,$mysql_pass,$mysql_db);
if(mysqli_connect_error()){
echo '数据库连接错误';
exit();
}
$mysqli->query("SET NAMES 'UTF8'"); 

//$mysqli->query("SET CHARACTER SET UTF8"); 

//$mysqli->query("SET CHARACTER_SET_RESULTS=UTF8");


function SqlString(&$value){
//我写的，好简陋，直接调用特殊字符加反斜杠的函数（只作用于单引号，双引号，反斜线）
	if(is_array($value)){
		array_walk($value,'SqlString');
	}else{
		$value=AddSlashes($value);
		//echo $value;
	}
}
/*调用我的函数，直接改变数组变量内容*/
array_walk($_GET,'SqlString');
array_walk($_POST,'SqlString');
array_walk($_COOKIE,'SqlString');
array_walk($_REQUEST,'SqlString');
//获取用户真实IP
function get_client_ip() {
	if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
		$ip = getenv("HTTP_CLIENT_IP");
	else
		if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
			$ip = getenv("HTTP_X_FORWARDED_FOR");
		else
			if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
				$ip = getenv("REMOTE_ADDR");
			else
				if (isset ($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
					$ip = $_SERVER['REMOTE_ADDR'];
				else
					$ip = 'unknown';
	return ($ip);
}

function is_login(){
if(isset($GLOBALS['mysqli'])){
$mysqli=$GLOBALS['mysqli'];
}else{
return array(1000,'没有连接数据库！');
}
if(isset($_COOKIE['login'])&&$_COOKIE['login']==1){
@$website_email=$_COOKIE['email'];
@$website_pass=$_COOKIE['pass'];
}else{
return array(1001,'登录失败，未登录！');
}


$result=$mysqli->query("select * from webuser where email='{$website_email}' and pass='{$website_pass}' and state='1'");
	if($result){
		if($result->num_rows>0){
			$row=$result->fetch_array();
			return array(1010,$row);
		}else{
		   return array(1002,'帐号和密码错误！');
		}
		
	}else{
		return array(1003,'数据库连接错误！');
	}

}

function hkl_mysql_query($sql=''){
if(isset($GLOBALS['mysqli'])){
$mysqli=$GLOBALS['mysqli'];
}else{
return array(1000,'没有连接数据库！');
}

if($sql==''){
return array(1004,'SQL语句错误！');
}
$result=$mysqli->query($sql);
	if($result){
		if($result->num_rows>0){
		  $re=array();
		  $i=0;
			while($row=$result->fetch_array()){
			  $re[$i++]=$row;
			}
			return array(1010,$re);
		}else{
		   return array(1002,'没有查询结果！');
		}
		
	}else{
		return array(1003,'数据库连接错误！');
	}

}
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
function writeLog($str='',$file='../log/log.txt'){
	$str.="\n";
	$fp=fopen($file,"a+");
	fwrite($fp,$str);
	fclose($fp);
}
?>
