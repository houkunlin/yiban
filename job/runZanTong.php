<?php
	ignore_user_abort();//关掉浏览器，PHP脚本也可以继续执行.
	set_time_limit(0);//让程序无限制的执行下去
	include("../config/mysql_config.php");
	include('../funclass/YBClass.php');
	$YIBAN=new YBClass();
	$sql="select * from ybuser where run='1'";
	$re0=$mysqli->query($sql);
	$gH=1*mt_rand(1,3);//运行间隔
	if($re0){
		if($re0->num_rows >= 1){
			$h=date("H");
			while($row2=$re0->fetch_array()){
				// print_r($row2);
				if( ($row2['runtime1'] <= $h && $row2['runtime2'] >= $h) || ($row2['runtime1']==$row2['runtime2'] && $row2['runtime1']==0) ){//在运行时间内的
					$now=time();
					$upH=round(($now-$row2['zantongtime'])/3600);//获得两个时间的间隔（小时）
					// echo $upH;
					if($row2['state']==1 && $row2['runzantong']==1 && $upH >= $gH){//上次正常运行的，且开启博文功能，超过10个小时则运行一次
						$json=$YIBAN->getLogin($row2['ybuser']);
						// echo __LINE__.'.';
						if(is_array($json) && $json['code']==200 && array_key_exists('isLogin',$json['data']) && $json['data']['isLogin']==1){//检测登录状态成功
							echo 'run;';
							$myfrends=getMyTrends($mysqli,$row2['ybuser']);
							foreach ($myfrends as $key => $value) {
								echo 'runfrends list;';
								$trends=$YIBAN->getTrendsList($row2['ybuser'],$value['ybid']);
								if($trends['code'] == 200){
									echo 'get trends';
									foreach ($trends['data'] as $key2 => $v2) {
										
										$z=isZanTong($v2['uplist']['list'],$row2['ybid']);
										$t=isZanTong($v2['downlist']['list'],$row2['ybid']);
										$num=0;
										if(!$z){
											$num=1;
										}
										if(!$t){
											$num=2;
										}
										if(!$z && !$t){
											$num=3;
										}
										$YIBAN->ZanTong($row2['ybuser'],$value['ybid'],$v2['_id'],$num);
										$m="zantongtime='".time()."'";
										$sql="UPDATE `ybuser` SET {$m} where id='{$row2['id']}'";
										$reee=$mysqli->query($sql);
									}
								}else{
									break;
								}
							}
							
							
							
						}else{//登录状态失效的
							$m="lastruntime='".time()."',state='5'";
							$sql="UPDATE `ybuser` SET {$m} where id='{$row2['id']}'";
//							$re2=$mysqli->query($sql);
							echo __LINE__.'.';
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
	function getMyTrends($mysqli,$user){
		$sql="select * from ybfrends where myybuser='{$user}'";
		$re=$mysqli->query($sql);
		if($re && $re->num_rows >= 1){
			$n=array();
			while ($row = $re->fetch_array()) {
				$n[]=$row;
			}
			return $n;
		}else{
			return array();
		}
	}
	function isZanTong($list,$ybid){
		foreach ($list as $key => $value) {
			if($value['user_id'] == $ybid){
				return 1;
			}
		}
		return 0;
	}
?>
