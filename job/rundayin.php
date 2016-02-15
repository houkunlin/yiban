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
					if($row2['state']==1 && $row2['dayin']==1){//上次正常运行的，且开启动态功能
						if(date("Y-m-d") != date("Y-m-d",$row2['lastdayintime'])){//今天未签到
							$json=$YIBAN->getLogin($row2['ybuser']);
							if(is_array($json) && $json['code']==200 && array_key_exists('isLogin',$json['data']) && $json['data']['isLogin']==1){//检测登录状态成功
								$re=$YIBAN->DayIn($row2['ybuser']);
								if(is_array($re) && array_key_exists('code',$re) && $re['code']==202){//已经签到
									$m="lastdayintime='".time()."',state='1'";
									$sql="UPDATE `ybuser` SET {$m} where id='{$row2['id']}'";
									$re2=$mysqli->query($sql);
									echo __LINE__.'.';
								}elseif(is_array($re) && array_key_exists('code',$re) && $re['code']==200 && array_key_exists('subCode',$re['data']) && $re['data']['subCode']==1){//此时我们执行了签到操作
									$m="lastdayintime='".time()."',state='1'";
									$sql="UPDATE `ybuser` SET {$m} where id='{$row2['id']}'";
									$re2=$mysqli->query($sql);
									echo __LINE__.'.';
								}else{//签到失败
									echo __LINE__.'.';
								}
							}else{//登录状态失效的
								$m="lastruntime='".time()."',state='5'";
								$sql="UPDATE `ybuser` SET {$m} where id='{$row2['id']}'";
								$re2=$mysqli->query($sql);
								// echo __LINE__.'.';
							}
						}else{//今天已经签到
							// echo __LINE__.'.';
						}
							
					}else{//上次运行返回结果错误的，或者未开启动态功能的
						// echo __LINE__.'.';
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