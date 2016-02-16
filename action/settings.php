<?
	include("../config/mysql_config.php");
	include('../funclass/tip.class.php');
	include('../funclass/YBClass.php');
	$is_login=is_login();
	if($is_login[0]==1010){//已经登录成功
		$Tip=new tip();
		$YIBAN=new YBClass();
		$tip='';
		$row=$is_login[1];
		$action=isset($_GET['action'])&&trim($_GET['action'])!=''?$_GET['action']:0;
		$ybuser=isset($_GET['user'])&&$_GET['user']!=''?$_GET['user']:0;
		$go='admin_yiban.php';
		$title='管理易班账户';
		$time=10;
		$tip='';
		if($ybuser!=0){
			if($action=='login'){
				$time1=isset($_POST['time1'])&&$_POST['time1']!=''?$_POST['time1']:8;
				$time2=isset($_POST['time2'])&&$_POST['time2']!=''?$_POST['time2']:18;
				$run=isset($_POST['run'])&&$_POST['run']!=''?$_POST['run']:0;
				$sql="UPDATE `ybuser` SET run='{$run}',runtime1='{$time1}',runtime2='{$time2}' where webuserid='{$row['id']}' and ybuser='{$ybuser}'";
				$mysqli->query($sql);
				header('location:admin_yiban_more.php?user='.$ybuser);
				exit();
			}elseif($action=='trends'){
				$submit=isset($_POST['submit'])&&$_POST['submit']!=''?$_POST['submit']:'';
				$trendsmsg=isset($_POST['trendstxt'])&&$_POST['trendstxt']!=''?$_POST['trendstxt']:'';
				if($submit==''){//实时发布动态
					if($trendsmsg!=''){
						$json=$YIBAN->getLogin($ybuser);
						$YIBAN->UBB($trendsmsg);
						$m='';
						if(is_array($json) && $json['code']==200 && array_key_exists('isLogin',$json['data']) && $json['data']['isLogin']==1){
							$re=$YIBAN->trends($ybuser,$trendsmsg);
							if(is_array($re) && $re['code']==200 && array_key_exists('id',$re['data'])){
								$tip=$Tip->warn('动态发布成功，以下是本次动态内容：<br><br>'.$trendsmsg);
								writeLog('['.date("Y-m-d H:i:s").']:用户['.hideStar($row['email']).']的易班帐号['.hideStar($ybuser).']实时发布了一条动态');
							}else{
								$tip=$Tip->warn('很抱歉，实时发布动态失败！<br>原因：登录状态正常，服务器返回数据未知！<br><br>你可以多次尝试，若多次尝试发布仍出现此问题请联系网站管理员！');
							}
							// echo '登录成功';
						}else{
							$tip=$Tip->warn('很抱歉，实时发布动态失败！<br>原因：登录信息失效！<br><br>请你开启登录运行并等待下次成功运行后方可发布动态！');
						}
						// echo $trendsmsg;
						// exit();
					}else{
						$tip=$Tip->warn('很抱歉，实时发布动态失败！<br>原因：您没有填写发布动态信息！');
					}
					
				}else{
					$run=isset($_POST['trends'])&&$_POST['trends']!=''?$_POST['trends']:0;
					$sql="UPDATE `ybuser` SET trends='{$run}',trendsMsg='{$trendsmsg}' where webuserid='{$row['id']}' and ybuser='{$ybuser}'";
					$mysqli->query($sql);
					header('location:admin_yiban_more.php?user='.$ybuser);
					// print_r($_POST);
					exit();
				}
				
			}elseif($action=='blog'){
				
			}elseif($action=='dayin'){
				$run=isset($_GET['run'])&&$_GET['run']!=''?$_GET['run']:0;
				$sql="UPDATE `ybuser` SET dayin='{$run}' where webuserid='{$row['id']}' and ybuser='{$ybuser}'";
				$mysqli->query($sql);
				header('location:admin_yiban_more.php?user='.$ybuser);
				exit();
			}elseif($action==0){
				$tip=$Tip->danger('很抱歉，您传入的参数错误(0)！');
			}else{
				$tip=$Tip->danger('很抱歉，您传入的参数错误(1)！');
			}
		}elseif($action=='run'){
			$run=isset($_GET['run'])&&$_GET['run']!=''?$_GET['run']:0;
			$sql="UPDATE `ybuser` SET run='{$run}' where webuserid='{$row['id']}' ";
			$mysqli->query($sql);
			$tip=$Tip->info('一键'.($run==1?'开启':'关闭').'易班挂机成功！');
		}else{
			$tip=$Tip->danger('很抱歉，您传入的参数错误(2)！');
		}
	}else{
		header('location:login.php');
		exit();
	}

	
	$body=file_get_contents('../tpl/goto.html');
	$body=str_replace('{go}',$go,$body);
	$body=str_replace('{time}',$time,$body);
	$body=str_replace('{address}',$title,$body);
	$body=str_replace('{tip}',$tip,$body);
	echo $body;
	exit();
?>
