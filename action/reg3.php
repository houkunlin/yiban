<?
	include("../config/mysql_config.php");
	include('../funclass/tip.class.php');
	$Tip=new tip();
	$time=10;
	$go='user.php';
	$title='用户中心';
	$file='../tpl/goto.html';
	$tip='';
	$sid=isset($_GET['sid'])&&$_GET['sid']!=''?$_GET['sid']:'';
	$id=isset($_GET['id'])&&$_GET['id']!=''?$_GET['id']:'';
	// echo $sid.'()'.$id;
	$sid=base64_decode(urldecode($sid));
	$id=base64_decode(urldecode($id));
	
	$sql="select * from webuser where email='{$sid}'";
	$re=$mysqli->query($sql);
	if($re){
		if($re->num_rows == 1){
			$row=$re->fetch_array();
			if($id!=$row['id']){
				$title='发送激活邮件';
				$file='../tpl/goto.html';
				$time=5;
				$go='../reg2.php?email='.urlencode(base64_encode($sid)).'&id='.urlencode(base64_encode($row['id']));
				$tip=$Tip->info('很抱歉，您的激活参数错误，请尝试重新获取激活邮件！');
			}elseif($row['state']==0){
				$sql="UPDATE `webuser` SET state='1' where email='{$sid}' and id='{$id}'";
				$mysqli->query($sql);
				$title='登录易班挂机';
				$file='../tpl/goto.html';
				$go='login.php';
				writeLog('['.date("Y-m-d H:i:s").']:用户['.hideStar($sid).']完成注册激活');
				$tip=$Tip->info('您的帐号【'.$sid.'】已经激活，正在为您跳转到登陆界面！');
			}else{
				$title='登录易班挂机';
				$file='../tpl/goto.html';
				$go='login.php';
				$tip=$Tip->info('您的邮箱【'.$sid.'】已经激活，不需要再次进行激活操作！');
			}
		}else{
			$title='易班挂机主页';
			$file='../tpl/goto.html';
			$go='../index.php';
			$tip=$Tip->info('很抱歉，当前邮箱【'.$sid.'】暂时还未注册！');
		}
	}else{
		$title='易班挂机主页';
		$file='../tpl/goto.html';
		$go='../index.php';
		$tip=$Tip->info('很抱歉，数据库查询失败，请稍候重试！');
	}
		
	$body=file_get_contents($file);
	$body=str_replace('{go}',$go,$body);
	$body=str_replace('{time}',$time,$body);
	$body=str_replace('{address}',$title,$body);
	$body=str_replace('{tip}',$tip,$body);
	echo $body;
?>
