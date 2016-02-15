<?
	include("../config/mysql_config.php");
	include('../funclass/tip.class.php');
	$Tip=new tip();
	$time=10;
	$go='user.php';
	$title='用户中心';
	$file='../tpl/goto.html';
	$tip='';
	$email=isset($_GET['email'])&&$_GET['email']!=''?$_GET['email']:'';
	if($email!=''){
		$sql="select * from webuser where email='{$email}'";
		$re=$mysqli->query($sql);
		if($re){
			if($re->num_rows == 1){
				$row=$re->fetch_array();
				if($row['state']==0){
					$get=send_mail($row['email'],$row['id']);
					$tip=$Tip->info($get);
					$body=file_get_contents('../tpl/reg2.html');
					$body=str_replace('{email}',$email,$body);
					$body=str_replace('{email2}',urlencode($email),$body);
					$body=str_replace('{tip}',$tip,$body);
					echo $body;
					exit();
				}else{
					$title='登录易班挂机';
					$file='../tpl/goto.html';
					$go='login.php';
					$tip=$Tip->info('您的邮箱【'.$email.'】已经激活，不需要再次进行激活操作！');
				}
			}else{
				$title='易班挂机主页';
				$file='../tpl/goto.html';
				$go='../index.php';
				$tip=$Tip->info('很抱歉，当前邮箱【'.$email.'】暂时还未注册！');
			}
		}else{
			$title='易班挂机主页';
			$file='../tpl/goto.html';
			$go='../index.php';
			$tip=$Tip->info('很抱歉，数据库查询出现错误，请稍候重试！');
		}
	}else{
		$title='易班挂机主页';
		$file='../tpl/goto.html';
		$go='../index.php';
		$tip=$Tip->info('很抱歉，传入参数错误！');
	}
	function send_mail($toemail='',$id=''){
		/*
		okszxnncuremgcdi		POP3/SMTP

		tfvumlxfqygcffhi		IMAP/SMTP
		*/
		/*
		ununnunnn@sina.cn
		
		*/
		include('../email/class.phpmailer.php');
		$b_set['mail']['smtp'] = 'smtp.sina.cn';//邮箱SMTP
		$b_set['mail']['user'] = 'ununnunnn@sina.cn';//用户名
		$b_set['mail']['pass'] = 'letwe2you.';//密码
		$b_set['mail']['from'] = 'ununnunnn@sina.cn';//发信人地址
		$mail = new PHPMailer(true);
		$mail->IsSMTP();
		$url='http://'.$_SERVER['SERVER_NAME'].'/action/reg3.php?sid='.urlencode(base64_encode($toemail)).'&id='.urlencode(base64_encode($id));
		try {
			$mail->CharSet    =	'UTF-8';
			$mail->Host       = $b_set['mail']['smtp'];
			$mail->Port       = 25;
			$mail->SMTPAuth   = true;
			$mail->Username   = $b_set['mail']['user'];
			$mail->Password   = $b_set['mail']['pass'];
			$mail->AddAddress($toemail, '挂机用户' );//收件人姓名
			$mail->SetFrom($b_set['mail']['from'],'挂机网站管理员');//发件人姓名
			$mail->Subject	= '请激活挂机网站用户';//主标题
			$mail->AltBody	= '';//副标题
			$mail->MsgHTML('
			欢迎您注册易班挂机网站，您还差最后一步就可以完成注册了！
			<br>
			点击下面的链接激活您的易班挂机网站帐号，然后您就可以在易班网站添加易班帐号参与挂机了。<br>
			<a href="'.$url.'">'.$url.'</a>
			
			<br><br>
			(本邮件由易班挂机网发送，请勿回复。)
			
			');//正文内容
			// $mail->AddAttachment('llq.zip','浏览器');//添加附件
			$mail->Send();
			return '邮件发送成功，我们的激活邮件已经送到你的邮箱！';
		} catch (phpmailerException $e) {
			return '邮件发送失败(0)！请联系管理员！';//.$e->errorMessage();
		} catch (Exception $e) {
			return '邮件发送失败(1)！请联系管理员！';//.$e->getMessage();
		}
	}
	
	$body=file_get_contents($file);
	$body=str_replace('{go}',$go,$body);
	$body=str_replace('{time}',$time,$body);
	$body=str_replace('{address}',$title,$body);
	$body=str_replace('{tip}',$tip,$body);
	echo $body;
?>