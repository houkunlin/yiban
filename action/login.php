<?
	include("../config/mysql_config.php");
	include('../funclass/tip.class.php');

	$Tip=new tip();
	$tip='';
	$login=isset($_GET["login"])&&$_GET["login"]!=""?$_GET["login"]:0;
	if($login==1){
		$website_email=isset($_POST["email"])?trim($_POST["email"]):'';
		$website_pass=isset($_POST["pass"])&&trim($_POST['pass'])!=''?md5($_POST["pass"]):'';
	}

	$is_login=is_login();
	if($is_login[0]==1010&&$login!=2){//已经登录成功
		$tip=$Tip->info('您通过cookie登录，欢迎您再次回到易班挂机网！');

		header('location:user.php');
		exit();
	}


	if($login==0){
	//进入登录页面
		$tip=$Tip->info('请登录易班挂机网！');
	}elseif($login==1){
	//登录
		if($website_email=='' || $website_pass==''){
			$tip=$Tip->warn('请输入帐号和密码！');
			//print_r($_SERVER);
		}else{
		
			$result=$mysqli->query("select * from webuser where email='{$website_email}'");
			if($result){
				if($result->num_rows>0){
					$row=$result->fetch_array();
					if($row['state']==0){
						@setcookie('login','',time(),'/');
						@setcookie('email','',time(),'/');
						@setcookie('pass','',time(),'/');
						header('location:reg2.php?email='.urlencode($row['email']));
						exit();
					}elseif($row['pass']==$website_pass){
						$time=time();
						$ip=get_client_ip();
						$ua=$_SERVER['HTTP_USER_AGENT'];
						@$mysqli->query("update webuser set lasttime='{$time}',lastip='{$ip}',lastua='{$ua}' where id='{$row["id"]}'");
						
						@setcookie('login','1',time()+7*24*60*60,'/');
						@setcookie('email',$website_email,time()+7*24*60*60,'/');
						@setcookie('pass',$website_pass,time()+7*24*60*60,'/');
						
						$tip=$Tip->info('恭喜您登录成功！<br>本次登录时间为：'.date("Y-m-d H:i:s",$time).'<br>本次登录IP为：'.$ip);

						header('location:user.php');
						exit();
					}else{
						$tip=$Tip->warn('密码错误');
						
						@setcookie('login','',time(),'/');
						@setcookie('email','',time(),'/');
						@setcookie('pass','',time(),'/');
					}
					
				}else{
					$tip=$Tip->warn('不存在此帐号！');
					
					@setcookie('login','',time(),'/');
					@setcookie('email','',time(),'/');
					@setcookie('pass','',time(),'/');
				}
				
			}else{
				$tip=$Tip->warn('数据库查询错误，请稍候重试，或者联系网站管理员！');
				
				@setcookie('login','',time(),'/');
				@setcookie('email','',time(),'/');
				@setcookie('pass','',time(),'/');
			}
		}
	}elseif($login==2){
	//退出
		$tip=$Tip->info('退出成功！');
		@setcookie('login','',time(),'/');
		@setcookie('email','',time(),'/');
		@setcookie('pass','',time(),'/');
	}else{
	//未知原因
		//$tip=$Tip->danger('检测到您非法登录，请不要非法登录！');
		@setcookie('login','',time(),'/');
		@setcookie('email','',time(),'/');
		@setcookie('pass','',time(),'/');
		$tip=$Tip->warn('请不要跳过程序设置！');
	}
	$body=file_get_contents('../tpl/login.html');
	$body=str_replace('{tip}',$tip,$body);
	echo $body;
?>
