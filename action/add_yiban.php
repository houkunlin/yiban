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
	$yiban=isset($_GET['yiban'])&&$_GET['yiban']!=''?$_GET['yiban']:0;
	if($yiban==1){
		$yiban_user=isset($_POST['yiban_user'])&&trim($_POST['yiban_user'])!=''?trim($_POST['yiban_user']):'';
		$yiban_pass=isset($_POST['yiban_pass'])&&trim($_POST['yiban_pass'])!=''?trim($_POST['yiban_pass']):'';
		if($yiban_user!=''&&$yiban_pass!=''){
			$is_yiban_have= hkl_mysql_query("select * from ybuser where ybuser='{$yiban_user}'");
			if($is_yiban_have[0]==1002){
				$re=$YIBAN->login($yiban_user,$yiban_pass);
				switch($re['code']){
					case 200://登录成功
						$json=$YIBAN->getLogin($yiban_user);
						$m='';
						if($json['code']==200 && array_key_exists('isLogin',$json['data']) && $json['data']['isLogin']==1){
							$m=",ybid='{$json['data']['user']['id']}',nick='{$json['data']['user']['nick']}'";
						}
						$sql="insert into ybuser set webuserid='{$row["id"]}',ybuser='{$yiban_user}',ybpass='{$yiban_pass}'".$m;
						$re2=$mysqli->query($sql);
						if($re2){
							$sql="UPDATE `webuser` SET gj_num=gj_num+1 where id='{$row['id']}'";
							$mysqli->query($sql);
							$tip.=$Tip->success('易班帐号['.$yiban_user.']添加成功！');
							writeLog('['.date("Y-m-d H:i:s").']:用户['.hideStar($row['email']).']添加了一个易班帐号['.hideStar($yiban_user).']');
							$body=file_get_contents('../tpl/goto.html');
							$body=str_replace('{go}','admin_yiban_more.php?user='.$yiban_user,$body);
							$body=str_replace('{time}',5,$body);
							$body=str_replace('{address}','易班账户['.$yiban_user.']详情',$body);
							$body=str_replace('{tip}',$tip,$body);
							echo $body;
							exit();
						}else{
							$tip.=$Tip->danger('数据库写入失败，请稍候重试，或者联系网站管理员！');
						}
					break;
					case 201://未登录或验证码错误
						$tip.=$Tip->info('很抱歉，遇到了验证码，我们无法处理这个问题！');
					break;
					case 911://有验证码
						$tip.=$Tip->info('很抱歉，遇到了验证码，我们无法处理这个问题！');
					break;
					case 415://帐号或密码错误
						$tip.=$Tip->info('您的易班帐号或者密码错误！');
					break;
					default:
					$json=$YIBAN->getLogin($yiban_user);
					if(is_array($json) && $json['code']==200 && array_key_exists('isLogin',$json['data']) && $json['data']['isLogin']==1){
						$m=",ybid='{$json['data']['user']['id']}',nick='{$json['data']['user']['nick']}'";
						$sql="insert into ybuser set webuserid='{$row["id"]}',ybuser='{$yiban_user}',ybpass='{$yiban_pass}'".$m;
						$re2=$mysqli->query($sql);
						if($re2){
							$tip.=$Tip->success('易班帐号['.$yiban_user.']添加成功！');
							$body=file_get_contents('../tpl/goto.html');
							$body=str_replace('{go}','admin_yiban_more.php?user='.$yiban_user,$body);
							$body=str_replace('{time}',5,$body);
							$body=str_replace('{address}','易班账户['.$yiban_user.']详情',$body);
							$body=str_replace('{tip}',$tip,$body);
							echo $body;
							exit();
						}else{
							$tip.=$Tip->danger('数据库写入失败，请稍候重试，或者联系网站管理员！');
						}
					}else{
						$tip=$Tip->warn('未知错误，服务器端返回信息解析错误，无法找到原因。');
						$tip.=$Tip->info('经过我们对此问题的跟踪测试，遇到此问题时绝大多数是您的帐号或密码错误导致的，请您确保输入的密码正确，若帐号和密码没有问题，请联系我们的网站管理员！');
					}
					// print_r($re);
				}
				if(file_exists('../cookie/'.md5($yiban_user).'.cookie')){
					unlink('../cookie/'.md5($yiban_user).'.cookie');
				}
				// echo '../cookie/'.md5($yiban_user).'.cookie';
			}elseif($is_yiban_have[0]==1010) {
				$tip.=$Tip->warn('很抱歉，易班帐号['.$yiban_user.']已经存在我们的数据库里面了，不需要再次添加！');
			}else{
				$tip.=$Tip->warn($is_yiban_have[1]);
			}
		}else{
			$tip=$Tip->warn('请输入易班帐号和密码！');
		}
	}else{
	    $tip=$Tip->info('请填写您在易班网的帐号和密码！');
	}
}else{
	header('location:login.php');
	exit();
}

$body=file_get_contents('../tpl/add_yiban.html');
$body=str_replace('{tip}',$tip,$body);

echo $body;
?>