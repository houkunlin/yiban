<?php
	ignore_user_abort();//关掉浏览器，PHP脚本也可以继续执行.
	set_time_limit(0);//让程序无限制的执行下去
	include("../config/mysql_config.php");
	include('../funclass/YBClass.php');
	$YIBAN=new YBClass();
	$sql="select * from ybuser where run='1' ";
	$re0=$mysqli->query($sql);
	if($re0){
		if($re0->num_rows >= 1){
			$h=date("H");
			while($row2=$re0->fetch_array()){
				if( ($row2['runtime1'] <= $h && $row2['runtime2'] >= $h) || ($row2['runtime1']==$row2['runtime2'] && $row2['runtime1']==0) ){//在运行时间内的
					$login=1;
					if($row2['state']==1){//上次正常运行的
						$json=$YIBAN->getLogin($row2['ybuser']);
						if(is_array($json) && $json['code']==200 && array_key_exists('isLogin',$json['data']) && $json['data']['isLogin']==1){//检测登录状态成功
							$login=0;
							// print_r($json);
							$m="lastruntime='".time()."',state='1'";
							$nick=$json['data']['user']['nick'];
							// echo $nick;
							$m.=($nick==$row2['nick']?'':",nick='{$nick}'");
							// echo $row2['id'];
							$sql="UPDATE `ybuser` SET {$m} where id='{$row2['id']}'";
							$re2=$mysqli->query($sql);;
							echo __LINE__.'.';
						}else{//登录状态失效的
							echo __LINE__.'.';
						}
					}else{
						
					}
					if($login==1 && $row2['state']!=4){//在上次不是密码错误的时候执行登录操作
						$re=$YIBAN->login($row2['ybuser'],$row2['ybpass']);
						switch($re['code']){
							case 200://登录成功
								$m="lastruntime='".time()."',state='1'";
								$sql="UPDATE `ybuser` SET {$m} where id='{$row2['id']}'";
								$re2=$mysqli->query($sql);
								echo __LINE__.'.';
							break;
							case 201://未登录或验证码错误
								$m="lastruntime='".time()."',state='3'";
								$sql="UPDATE `ybuser` SET {$m} where id='{$row2['id']}'";
								$re2=$mysqli->query($sql);
								echo __LINE__.'.';
							break;
							case 911://有验证码
								$m="lastruntime='".time()."',state='3'";
								$sql="UPDATE `ybuser` SET {$m} where id='{$row2['id']}'";
								$re2=$mysqli->query($sql);
								echo __LINE__.'.';
							break;
							case 415://帐号或密码错误
								$m="lastruntime='".time()."',state='4'";
								$sql="UPDATE `ybuser` SET {$m} where id='{$row2['id']}'";
								$re2=$mysqli->query($sql);
								echo __LINE__.'.';
							break;
							default:
								$m="lastruntime='".time()."',state='4'";
								$sql="UPDATE `ybuser` SET {$m} where id='{$row2['id']}'";
								$re2=$mysqli->query($sql);
								echo __LINE__.'.';
						}
					}
				}
			}
			echo 'run ok';
		}else{
			echo 'not run';
		}
	}else{
		echo 'mysqli connect error';
	}
	echo date("Y-m-d H:i:s").basename(__FILE__);

?>
