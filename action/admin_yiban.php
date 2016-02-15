<?
	include("../config/mysql_config.php");
	include('../funclass/tip.class.php');
	$is_login=is_login();
	if($is_login[0]==1010){//已经登录成功
		$Tip=new tip();
		$tip='';
		$row=$is_login[1];
		$yiban_list=hkl_mysql_query("select * from ybuser where webuserid='{$row["id"]}'");
		if($yiban_list[0]==1010){
			$i=1;
			$list='';
			foreach($yiban_list[1] as $row2){
				$list.='
					  <a href="admin_yiban_more.php?user='.$row2['ybuser'].'" class="list-group-item">'.($i++).').<span class="hklbq">'.$row2['ybuser'].'</span><span class="hklbq">'.($row2['run']==1?'开启':'未开启').'</span></a> ';
			}

		}elseif($yiban_list[0]==1002){
			$list='
				  <div class="alert alert-warning">你没有添加易班账户，<a href="add_yiban.php">点击这里添加账户</a></div> ';
		}else{
			$list=$Tip->info($yiban_list[1]);
		}

	}else{
		header('location:login.php');
		exit();
	}

	$body=file_get_contents('../tpl/admin_yiban.html');
	$body=str_replace('{list}',$list,$body);

	echo $body;
?>