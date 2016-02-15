<?
	include("../config/mysql_config.php");
	include('../funclass/tip.class.php');
	$is_login=is_login();
	if($is_login[0]==1010){//已经登录成功
		$Tip=new tip();
		$tip='';
		$row=$is_login[1];
		$tag_in1=array(
		'basic1'=>' class="active" ',
		'change1'=>'',
		'safe1'=>'',
		'settings1'=>''
		);
		$tag_in2=array(
		'basic2'=>' in active ',
		'change2'=>'',
		'safe2'=>'',
		'settings2'=>''
		);
		$change=isset($_GET['change'])&&$_GET['change']!=''? $_GET['change'] :0;
		if($change==1){
			$tag_in1=array(
			'basic1'=>'',
			'change1'=>' class="active" ',
			'safe1'=>'',
			'settings1'=>''
			);
			$tag_in2=array(
			'basic2'=>'',
			'change2'=>' in active ',
			'safe2'=>'',
			'settings2'=>''
			);
			$pass_ok=0;
			$sql='';
			$err='';
			$pass=isset($_POST['pass'])&&$_POST['pass']!=''? $_POST['pass'] :''; 
			$pass1=isset($_POST['pass1'])&&$_POST['pass1']!=''? $_POST['pass1'] :'';
			$pass2=isset($_POST['pass2'])&&$_POST['pass2']!=''? $_POST['pass2'] :'';
			$pass3=md5($pass2);
			if($pass!=''){
				if(md5($pass)==$_COOKIE['pass']){
					if(!preg_match("/^[_\.0-9a-z-]{6,18}/iU",$pass2)){//密码验证未通过
						$err.='密码未通过验证';
					}elseif($pass1==$pass2){
						$pass_ok=1;
						$sql="update webuser set pass='{$pass3}' where id='{$row["id"]}'";
					}else{//两次密码不同
						$err.='输入修改的密码不一致';
					}
				}else{//密码不正确
					$err.='当前密码输入错误';
				}
			}else{
				$err='您没有修改任何内容！';
			}
			
			
			
			if(!empty($sql)){
				$re=$mysqli->query($sql);
				if($re){
					$tip=$Tip->info('修改信息成功！');
					if($pass_ok!=1){//没有修改密码
						$is_login=is_login();
						if($is_login[0]==1010){//已经登录成功
							$row=$is_login[1];
						}else{
							header('location:login.php');
							exit();
						}
					}else{//密码修改成功
						$tip=$Tip->info('恭喜您，密码修改成功，稍后将跳转到登录页面！');
						@setcookie('login','',time(),'/');
						@setcookie('email','',time(),'/');
						@setcookie('pass','',time(),'/');
						$body=file_get_contents('../tpl/goto.html');
						$body=str_replace('{go}','login.php',$body);
						$body=str_replace('{time}',7,$body);
						$body=str_replace('{address}','用户登录',$body);
						$body=str_replace('{tip}',$tip,$body);
						echo $body;
						exit();
					}
				}else{
					$tip=$Tip->warn('很抱歉，数据库写入失败，请稍候重试，或者联系网站管理员！');
				}
			}else{
				$tip=$Tip->warn($err);
			}
			//    echo $sql;

		}

	}else{
		header('location:login.php');
		exit();
	}

	$body=file_get_contents('../tpl/user.html');
	$body=str_replace('{tip}',$tip,$body);
	$body=str_replace('{email}',$row['email'],$body);
	$body=str_replace('{vip}',$row['vip'],$body);
	$body=str_replace('{gj_num}',$row['gj_num'],$body);
	$body=str_replace('{last_time}',date("Y-m-d H:i:s",$row['lasttime']),$body);
	$body=str_replace('{last_ip}',$row['lastip'],$body);
	foreach($tag_in1 as $key=>$value){
	$body=str_replace('{'.$key.'}',$value,$body);
	}
	foreach($tag_in2 as $key=>$value){
	$body=str_replace('{'.$key.'}',$value,$body);
	}




	echo $body;
?>