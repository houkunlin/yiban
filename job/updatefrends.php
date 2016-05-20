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
					if($row2['state']==1){//上次正常运行的
						if(date("Y-m-d") != date("Y-m-d",$row2['updatefrendstime']==''?time()/2:$row2['updatefrendstime'])){//今天未签到
							$json=$YIBAN->getLogin($row2['ybuser']);
							if(is_array($json) && $json['code']==200 && array_key_exists('isLogin',$json['data']) && $json['data']['isLogin']==1){//检测登录状态成功
								$frends=$YIBAN->getFrends($row2['ybuser'],1);
								$myfrends=getMyTrends($mysqli,$row2['ybuser']);
								$frendInfo=getFrendsInfo($frends,$myfrends);
								print_r($frends);
								$sql="insert into ybfrends (ybid,ybname,myybid,myybuser)value";
								$sql_arr=array();
								foreach ($frendInfo[0] as $key => $value) {
									$sql_arr[]="('{$value}','{$frendInfo[1][$key]}','{$row2['ybid']}','{$row2['ybuser']}')";
								}
								if(count($sql_arr)>=1){
									$sql.=implode(',', $sql_arr);
									$ree=$mysqli->query($sql);
									if($ree){
										echo 'updatefrends ok';
										$m="updatefrendstime='".time()."'";
										$sql="UPDATE `ybuser` SET {$m} where id='{$row2['id']}'";
										$reee=$mysqli->query($sql);
									}else{
										echo 'updatefrends false';
									}
								}
							}else{//登录状态失效的
								$m="lastruntime='".time()."',state='5'";
								$sql="UPDATE `ybuser` SET {$m} where id='{$row2['id']}'";
//								$re2=$mysqli->query($sql);
								// echo __LINE__.'.';
								if(file_exists('../cookie/'.md5($row2['ybuser']).'.cookie')){
									unlink('../cookie/'.md5($row2['ybuser']).'.cookie');
								}
								if(file_exists('../cookie/'.md5($row2['ybuser']).'.cookie2')){
									unlink('../cookie/'.md5($row2['ybuser']).'.cookie2');
								}
							}
						}else{//今天已经签到
							 echo 'in'.__LINE__.'.';
						}
							
					}else{//上次运行返回结果错误的，或者未开启动态功能的
						 echo __LINE__.'last error.';
					}
				}else{
					echo 'no in time<br>';
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
	function getFrendsInfo($frends,$myfrends){
		$newArray=array();
		$newArray[0]=array();
		$newArray[1]=array();
		foreach($frends as $key=>$ar){
			foreach ($ar[0] as $key2 => $ybid) {
				if(!findIdBySql($myfrends,$ybid)){
					$newArray[0][]=$ybid;
					$newArray[1][]=$ar[1][$key2];
				}
			}
		}
		return $newArray;
	}
	function findIdBySql($myfrends,$ybid){/*从数据库里面找到服务器的易班好友信息*/
		foreach ($myfrends as $ar) {
			if($ar['ybid'] == $ybid){
				return 1;
			}
		}
		return 0;
	}
?>