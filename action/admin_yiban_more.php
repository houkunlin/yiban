<?
	include("../config/mysql_config.php");
	include('../funclass/tip.class.php');
	$is_login=is_login();
	if($is_login[0]==1010){//已经登录成功
		$Tip=new tip();
		$tip='';
		$row=$is_login[1];
		$ybuser=isset($_GET['user'])&&$_GET['user']!=''?$_GET['user']:0;
		if($ybuser!=0){
			$sql="select * from ybuser where ybuser='{$ybuser}'";
			$re=$mysqli->query($sql);
			if($re){
				if($re->num_rows == 1){
					$row2=$re->fetch_array();
				}else{
					$tip.=$Tip->info('很抱歉，我们的数据库里没有您的易班帐号【'.$ybuser.'】，如果您添加不了此帐号但是又出现当前问题，请联系网站管理员反应问题！');
					$body=file_get_contents('../tpl/goto.html');
					$body=str_replace('{go}','admin_yiban.php',$body);
					$body=str_replace('{time}',10,$body);
					$body=str_replace('{address}','管理易班账户',$body);
					$body=str_replace('{tip}',$tip,$body);
					echo $body;
					exit();
				}
			}else{
				$tip.=$Tip->info('很抱歉，我们为您获取数据库时出现错误！如果多次出现此错误，请您联系网站管理员！');
				$body=file_get_contents('../tpl/goto.html');
				$body=str_replace('{go}','admin_yiban.php',$body);
				$body=str_replace('{time}',10,$body);
				$body=str_replace('{address}','管理易班账户',$body);
				$body=str_replace('{tip}',$tip,$body);
				echo $body;
				exit();
			}
		}else{
			header('location:admin_yiban.php');
			exit();
		}
	}else{
		header('location:login.php');
		exit();
	}
	
	$body=file_get_contents('../tpl/admin_yiban_more.html');
	$body=str_replace('{ybuser}',$row2['ybuser'],$body);
	$body=str_replace('{ybid}',$row2['ybid'],$body);
	$body=str_replace('{ybnick}',$row2['nick'],$body);
	$body=str_replace('{runtime1}',$row2['runtime1'],$body);
	$body=str_replace('{runtime2}',$row2['runtime2'],$body);
	
	$body=str_replace('{loginState}',$row2['run']==1?($row2['state']==1?'正常':'未知'):'未开启',$body);
	$body=str_replace('{loginStateTime}',$row2['lastruntime']!=''?date("Y-m-d H:i:s",$row2['lastruntime']):'——————',$body);
	$body=str_replace('{trendsState}',$row2['trends']==1?'已开启':'未开启',$body);
	$body=str_replace('{lasttrendstime}',$row2['lasttrendstime']!=''?date("Y-m-d H:i:s",$row2['lasttrendstime']):'——————',$body);
	$body=str_replace('{blogState}',$row2['blog']==1?'已开启':'未开启',$body);
	$body=str_replace('{lastblogtime}',$row2['lastblogtime']!=''?date("Y-m-d H:i:s",$row2['lastblogtime']):'——————',$body);
	$body=str_replace('{dayinState}',$row2['dayin']==1?'已开启':'未开启',$body);
	$body=str_replace('{lastdayintime}',$row2['lastdayintime']!=''?date("Y-m-d H:i:s",$row2['lastdayintime']):'——————',$body);
	
	$body=str_replace('{run}',$row2['run']==1?'开启运行':'关闭运行',$body);
	$body=str_replace('{trends}',$row2['trends']==1?'开启动态运行':'关闭动态运行',$body);
	$body=str_replace('{trendsmsg}',$row2['trendsMsg'],$body);
	// $body=str_replace('{}',$row2[''],$body);
	// $body=str_replace('{}',$row2[''],$body);
	// $body=str_replace('{}',$row2[''],$body);
	// $body=str_replace('{}',$row2[''],$body);
	// $body=str_replace('{}',$row2[''],$body);
	
	echo $body;
?>