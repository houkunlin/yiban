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
				// print_r($row2);
				if( ($row2['runtime1'] <= $h && $row2['runtime2'] >= $h) || ($row2['runtime1']==$row2['runtime2'] && $row2['runtime1']==0) ){//在运行时间内的
					$now=time();
					$upH=round(($now-$row2['lastblogtime'])/3600);//获得两个时间的间隔（小时）
					// echo $upH;
					if($row2['state']==1 && $row2['blog']==1 && $upH >= 1){//上次正常运行的，且开启博文功能，超过10个小时则运行一次
						$json=$YIBAN->getLogin($row2['ybuser']);
						// echo __LINE__.'.';
						if(is_array($json) && $json['code']==200 && array_key_exists('isLogin',$json['data']) && $json['data']['isLogin']==1){//检测登录状态成功
							$list=$YIBAN->healthList($row2['blogMsg']);
							if(is_array($list) && array_key_exists('tngou',$list)){
								$bid=$list['tngou'][mt_rand(0,19)]['id'];//获取文章id
								$text=$YIBAN->healthShow($bid);
								if(is_array($text)){
									$blog=array(
										'title'=>$text['title'],
										'content'=>$text['message'].'<br><br>关键词：'.$text['keywords'],
										'ranges'=>'1',
										'type'=>'1',
										'token'=>''
										);
										$re=$YIBAN->addblog($row2['ybuser'],$blog);
										if(is_array($re) && $re['code']==200 && $re['data']=='ok'){//发布博文成功
											$m="lastblogtime='".time()."',state='1'";
											$nick=$json['data']['user']['nick'];
											$m.=$nick==$row2['nick']?'':",nick='{$nick}'";
											$sql="UPDATE `ybuser` SET {$m} where id='{$row2['id']}'";
											$re2=$mysqli->query($sql);
											echo __LINE__.'.';
										}else{//发布动态失败
											echo __LINE__.'.';
										}
								}else{//获取文章内容失败
									echo __LINE__.'.';
								}
							}else{//获取文章列表失败
								echo __LINE__.'.';
							}
							
							
						}else{//登录状态失效的
							$m="lastruntime='".time()."',state='5'";
							$sql="UPDATE `ybuser` SET {$m} where id='{$row2['id']}'";
							$re2=$mysqli->query($sql);
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

?>
