<?php
header("content-Type: text/html; charset=utf-8");
function send_mail($toemail='',$id=''){
	/*
	okszxnncuremgcdi		POP3/SMTP

	tfvumlxfqygcffhi		IMAP/SMTP
	*/
	/*
	ununnunnn@sina.cn
	
	*/
	include('email/class.phpmailer.php');
	$b_set['mail']['smtp'] = 'smtp.sina.cn';//邮箱SMTP
	$b_set['mail']['user'] = 'ununnunnn@sina.cn';//用户名
	$b_set['mail']['pass'] = 'letwe2you.';//密码
	$b_set['mail']['from'] = 'ununnunnn@sina.cn';//发信人地址
	$mail = new PHPMailer(true);
	$mail->IsSMTP();
	$url='http://'.$_SERVER['SERVER_NAME'].'/reg.php?sid='.urlencode(base64_encode($toemail)).'&id='.urlencode(base64_encode($id));
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
		return '邮件发送成功，我们已通过邮件为您通知对方了！';
	} catch (phpmailerException $e) {
		return '邮件发送失败(0)！'.$e->errorMessage();
	} catch (Exception $e) {
		return '邮件发送失败(1)！'.$e->getMessage();
	}
}
echo send_mail('1184511588@qq.com','2');





?>
