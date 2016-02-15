<?
	include("../config/mysql_config.php");
	include('../funclass/tip.class.php');
	$is_login=is_login();
	if($is_login[0]==1010){//已经登录成功
		header('location:user.php');
		exit();
	}
	$Tip=new tip();
	$tip='';
	$reg=isset($_GET["reg"])&&$_GET["reg"]!=""?$_GET["reg"]:0;
	$website_pass=isset($_POST["pass"])&&trim($_POST['pass'])!=''?$_POST["pass"]:'';
	$website_email=isset($_POST["email"])&&trim($_POST['email'])!=''?$_POST["email"]:'';
	if($reg==0){
		$tip=$Tip->info('请您仔细填写注册信息');
	}elseif($reg==1){
		$err='';
		if($website_pass==''||$website_email==''){
			$err.='您的信息没有填写完整！';
		}
		if(!preg_match("/^[_\.0-9a-z-]{6,18}/iU",$website_pass)){
			$err.='密码检测未通过！';
		}
		if(!preg_match("/^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3}$/iU",$website_email)){
			$err.='邮箱检测未通过！';
		}

		if($err==''){//进行注册操作
			$is_have=hkl_mysql_query("select * from webuser where email='{$website_email}'");
			if($is_have[0]==1010){
				//用户名或邮箱已被注册
				$tip=$Tip->warn('很抱歉，您填写的邮箱已被注册！');
			}else{
				//开始进行注册操作
				$pass=md5($website_pass);
				$time=time();
				$ip=get_client_ip();
				$ua=$_SERVER['HTTP_USER_AGENT'];
				$re=$mysqli->query("insert into webuser set pass='{$pass}',email='{$website_email}',regtime='{$time}',regip='{$ip}',regua='{$ua}' ");
				if($re){
					writeLog('['.date("Y-m-d H:i:s").']:用户['.hideStar($website_email).']完成初步注册');
					header('location:reg2.php?email='.urlencode($website_email));
					exit();
					// @setcookie('login','1',time()+7*24*60*60,'/');
					// @setcookie('email',$website_email,time()+7*24*60*60,'/');
					// @setcookie('pass',$pass,time()+7*24*60*60,'/');
					/*
					header('location:user.php');
					exit();*/
					// echo '<pre>';
					// print_r($re);
					// print_r($mysqli);
					// echo '</pre>';
				}else{
					$tip=$Tip->warn('注册失败，数据库写入失败！');
				}
			}

		}else{
			$tip=$Tip->warn($err);
		}
	}else{
		$tip=$Tip->warn('传入参数错误');
	}


	$body=file_get_contents('../tpl/reg.html');
	$body=str_replace('{tip}',$tip,$body);
	echo $body;
?>