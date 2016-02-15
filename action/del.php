<?
	include("../config/mysql_config.php");
	include('../funclass/tip.class.php');
	$is_login=is_login();
	if($is_login[0]==1010){//已经登录成功
		$Tip=new tip();
		$tip='';
		$row=$is_login[1];
		$del=isset($_GET['del'])&&$_GET['del']!=''?$_GET['del']:'';
		$ok=isset($_GET['ok'])&&$_GET['ok']!=''?$_GET['ok']:0;
		$ybusertxt='';
		$time=10;
		$go='user.php';
		$title='用户中心';
		$file='../tpl/del.html';
		if($del!=''){
			if($del=='all'){
				if($ok==1){
					$file='../tpl/goto.html';
					$tip.=$Tip->info('帐号清空成功！');
					writeLog('['.date("Y-m-d H:i:s").']:用户['.hideStar($row['email']).']清空了他的易班挂机');
					$sql="DELETE FROM `ybuser` WHERE webuserid='{$row['id']}'";
					$mysqli->query($sql);
					$sql="UPDATE `webuser` SET gj_num='0' where id='{$row['id']}'";
					$mysqli->query($sql);
				}else{
					$ybusertxt='本账号下的全部挂机';
				}
			}else{
				if($ok==1){
					$go='admin_yiban.php';
					$title='管理易班帐号';
					$file='../tpl/goto.html';
					$tip.=$Tip->info('帐号删除成功！');
					writeLog('['.date("Y-m-d H:i:s").']:用户['.hideStar($row['email']).']删除了他的易班挂机['.hideStar($del).']');
					$sql="DELETE FROM `ybuser` WHERE webuserid='{$row['id']}' and ybuser='{$del}'";
					$mysqli->query($sql);
					$sql="UPDATE `webuser` SET gj_num=gj_num-1 where id='{$row['id']}'";
					$mysqli->query($sql);
					if(file_exists('../cookie/'.md5($del).'.cookie')){
						unlink('../cookie/'.md5($del).'.cookie');
					}
					if(file_exists('../cookie/'.md5($del).'.cookie2')){
						unlink('../cookie/'.md5($del).'.cookie2');
					}
				}else{
					$ybusertxt=$del;
					$go='admin_yiban_more.php?user='.$del;
					$title='易班挂机详情';
				}
			}
			
		}else{
			$tip.=$Tip->info('很抱歉，传入参数错误1！');
			$file='../tpl/goto.html';
		}
	}else{
		header('location:login.php');
		exit();
	}

	$body=file_get_contents($file);
	$body=str_replace('{go}',$go,$body);
	$body=str_replace('{time}',$time,$body);
	$body=str_replace('{address}',$title,$body);
	$body=str_replace('{tip}',$tip,$body);
	$body=str_replace('{ybuser}',$ybusertxt,$body);
	$body=str_replace('{ybuser2}',$del,$body);
	// $body=str_replace('{}',$,$body);
	// $body=str_replace('{}',$,$body);
	echo $body;
	
	
	
?>
