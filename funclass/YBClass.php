<?php
include('http_curl.php');
class YBClass{
	public $headerArray;
	public function __construct(){
		$user='15577405667';
		$pass='houkunlin';
		// $user='18272681381';
		// $pass='ny*0907';
		// login($user,$pass);
		// getLogin($user);
		// trends($user,'我怕时间太快不够将你看仔细,我怕时间太慢日夜担心失去你');
		// getTrendsList($user,'7097487');//获取动态列表
		$blog=array(
		'title'=>'title',
		'content'=>'text',
		'ranges'=>'1',
		'type'=>'1',
		'token'=>''
		);
		// addblog($user,$blog);//发布博客
		// DayIn($user);//签到
		// getJoined($user);//获取加入的群组
		// getMyOrgGroup($user);//获取加入的机构群
		
		$this->headerArray=array(
		'Host'=>'www.yiban.cn',
		'Accept'=>'application/json, text/javascript, */*; q=0.01',
		'Accept-Language'=>'zh-CN,zh;q=0.8,en-US;q=0.5,en;q=0.3',
		'Accept-Encoding'=>'gzip, deflate, br',
		'Content-Type'=>'application/x-www-form-urlencoded; charset=UTF-8',
		'X-Requested-With'=>'XMLHttpRequest',
		'Connection'=>'keep-alive');
	}
	function login($user='',$pass='',$yzm=''){
		$http=new http_curl();
		$http->from="https://www.yiban.cn/login?go=http%3A%2F%2Fwww.yiban.cn%2F";
		$loginUrl="https://www.yiban.cn/login/doLoginAjax";
		$http->file=md5($user).'.cookie';
		// echo $http->file.$user.md5('15577405667').'....'.md5(15577405667);
		if(file_exists($http->cookie.$http->file)){
			unlink($http->cookie.$http->file);//删除原COOKIE数据文件
		}
		$postStr="account={$user}&password={$pass}&captcha={$yzm}";
		$re=$http->post($loginUrl,$postStr,$this->headerArray);
		$json=empty($re[1])?0:json_decode($re[1],true);
		// print_r($re[1]);
		return $json;
		/*Array
		(
			[code] => 200
			[message] => 操作成功
			[data] => Array
				(
					[url] => /user/index/index/user_id/6746755
				)

		)
		{"code":200,"message":"\u64cd\u4f5c\u6210\u529f","data":{"url":"\/user\/index\/index\/user_id\/6746755"}}
		*/
		$return=array('code'=>'1','msg'=>'');
		if($json!=0){
			switch($json['code']){
				case 200://登录成功
				
				break;
				case 201://未登录或验证码错误
				
				break;
				case 911://有验证码
				
				break;
				case 415://帐号或密码错误
				
				break;
				default:
				
			}
		}else{
			$return=array('code'=>'0','msg'=>'获取到的数据解析失败');
		}
		
	}
	function getLogin($user){
		$http=new http_curl();
		$http->from="http://www.yiban.cn/";
		$loginUrl="http://www.yiban.cn/ajax/my/getLogin";
		$http->file=md5($user).'.cookie';
		$postStr='';
		$re=$http->post($loginUrl,$postStr,$this->headerArray);
		$json=empty($re[1])?0:json_decode($re[1],true);
		return $json;
		/*
		Array
		(
			[code] => 200
			[message] => 操作成功
			[data] => Array
				(
					[isLogin] => 1
					[user] => Array
						(
							[id] => 6746755 用户id
							[nick] => 大叔 用户昵称
							[url] => /user/index/index/user_id/6746755 用户主页地址
							[msg_count] => 1
							[avatar] => http://img02.fs.yiban.cn/6746755/avatar/user/200 用户头像
							[isSchoolVerify] => 1 是否通过学校验证
							[isOrganization] => 0
							[ispublic] => 0
							[token] => d08d052f842bd0736369542c4e67fc6f 登录凭证
						)
					[subNav] => 导航上HTML信息
					[checkin] => 0
					[mission] => 0
					[completedMission] => 1
					[loginUrl] => https://www.yiban.cn/login?go=http%3A%2F%2Fwww.yiban.cn%2F
				)
		)
		{"code":200,"message":"\u64cd\u4f5c\u6210\u529f","data":{"isLogin":true,"user":{"id":"6746755","nick":"\u5927\u53d4","url":"\/user\/index\/index\/user_id\/6746755","msg_count":"1","avatar":"http:\/\/img02.fs.yiban.cn\/6746755\/avatar\/user\/200","isSchoolVerify":"1","isOrganization":0,"ispublic":0,"token":"d08d052f842bd0736369542c4e67fc6f"},"subNav":"<nav id=\"sub-nav\">\n\t<div class=\"container iblock-wrap guide-5\">\n\t\t<h2 class=\"iblock sub-menu-first\"><a href=\"\/my\/feed\"><i class=\"icon icon-font content-font font-publish-feed\"><\/i><span class=\"word-space\">\u52a8 \u6001<\/span><\/a><\/h2>\n\t\t<ul class=\"iblock sub-menu-second\">\n\t\t\t<li><a class=\"guide2015_3-2\" href=\"\/user\/friend\/list\"><i class=\"icon icon-font content-font font-friends\"><\/i><span class=\"word-space\">\u8054\u7cfb\u4eba<\/span><\/a><\/li>\n\t\t\t<li><a href=\"\/my\/group\"><i class=\"icon icon-font content-font font-publish-topic\"><\/i><span>\u6211\u7684\u7fa4<\/span><\/a><\/li>\n\t\t<\/ul>\n\t\t<hr>\n\t\t<ul class=\"iblock sub-menu-third clearfix\">\n\t\t\t<li><a href=\"\/album\/web\/index\"><i class=\"icon icon-font border-font font-album\"><\/i><span class=\"word-space\">\u76f8 \u518c<\/span><\/a><\/li>\n\t\t\t<li><a href=\"\/i\/\"><i class=\"icon icon-font border-font font-publish-disk\"><\/i><span>\u8d44\u6599\u5e93<\/span><\/a><\/li>\n\t\t\t\t\t\t\t\t\t<li><a href=\"\/blog\/index\/index\/userid\/6746755\"><i class=\"icon icon-font border-font font-blog\"><\/i><span>\u8f7b\u535a\u5ba2<\/span><\/a><\/li>\n\t\t\t<li class=\"last-child\"><a href=\"\/questionnaire\/index\/index\"><i class=\"icon icon-font border-font font-questionnaire\"><\/i><span class=\"word-space\">\u8f7b\u95ee\u5377<\/span><\/a><\/li>\n\t\t<\/ul>\n\t<\/div>\n<\/nav>\n","checkin":0,"mission":0,"completedMission":true,"loginUrl":"https:\/\/www.yiban.cn\/login?go=http%3A%2F%2Fwww.yiban.cn%2F"}}
		*/
	}
	function trends($user='',$str=''){
		$http=new http_curl();
		$http->from="http://www.yiban.cn/";
		$loginUrl="http://www.yiban.cn/feed/add";
		$http->file=md5($user).'.cookie';
		$postStr="content=".urlencode($str);
		$re=$http->post($loginUrl,$postStr,$this->headerArray);
		$json=empty($re[1])?0:json_decode($re[1],true);
		return $json;
		/*
		Array
		(
			[code] => 200
			[message] => 操作成功
			[data] => Array
				(
					[id] => 8135485 动态的id
				)

		)
		{"code":200,"message":"\u64cd\u4f5c\u6210\u529f","data":{"id":"8135502"}}
		*/

	}
	function getTrendsList($user,$userid){//获取动态列表
		$http=new http_curl();
		$http->from="http://www.yiban.cn/user/index/index/user_id/".$userid;
		$loginUrl="http://www.yiban.cn/feed/list";
		$http->file=md5($user).'.cookie';
		$postStr="user_id={$userid}&lastid=0&num=10&scroll=1";
		$re=$http->post($loginUrl,$postStr,$this->headerArray);
		$json=empty($re[1])?0:json_decode($re[1],true);
		return $json;
		/*
		Array
		(
			[code] => 200
			[message] => 操作成功
			[data] => Array
				(
					[0] => Array
						(
							[_id] => 8135502
							[content] => 全世界的人都离开了你，我也会在你身边，有地狱我们一起猖獗
							[privacy] => 0
							[shield] => 0
							[user_id] => 6746755
							[source] => 4
							[nick] => 侯坤林
							[kind] => 1
							[create_time] => 2016.02.13 21:15
							[images] => Array
								(
								)

							[address] => 
							[lng] => 
							[lat] => 
							[uplist] => Array
								(
									[list] => Array
										(
										)

									[has_more] => 0
								)

							[up] => Array
								(
								)

							[downlist] => Array
								(
									[list] => Array
										(
										)

									[has_more] => 0
								)

							[down] => Array
								(
								)

							[comments] => Array
								(
								)

							[to_userids] => Array
								(
								)

							[share] => 0
							[shareObject] => Array
								(
								)

							[artwork] => 0
							[hiddenAddress] => 0
							[avatar] => http://img02.fs.yiban.cn/6746755/avatar/user/60
							[userUrl] => /user/index/index/user_id/6746755
							[upCount] => 0
							[downCount] => 0
							[be_up] => 0
							[be_down] => 0
							[isSelf] => 1
							[commentCount] => 0
							[to_userids_list] => Array
								(
								)

							[to_userids_count] => 0
							[image_columns] => 1
						)

					[1] => Array
						(
							[_id] => 8128669
							[content] => 我在易班签到，网薪快到碗里来~
							[privacy] => 0
							[kind] => 2
							[address] => 桂林市·广西壮族自治区桂林市永福县135县道靠近仁里
							[artwork] => 0
							[lat] => 24.898823
							[lng] => 110.055725
							[shield] => 0
							[user_id] => 6746755
							[source] => 2
							[nick] => 侯坤林
							[create_time] => 2016.02.12 17:34
							[images] => Array
								(
								)

							[uplist] => Array
								(
									[list] => Array
										(
										)

									[has_more] => 0
								)

							[up] => Array
								(
								)

							[downlist] => Array
								(
									[list] => Array
										(
										)

									[has_more] => 0
								)

							[down] => Array
								(
								)

							[comments] => Array
								(
								)

							[to_userids] => Array
								(
								)

							[share] => 0
							[shareObject] => Array
								(
								)

							[hiddenAddress] => 0
							[avatar] => http://img02.fs.yiban.cn/6746755/avatar/user/60
							[userUrl] => /user/index/index/user_id/6746755
							[upCount] => 0
							[downCount] => 0
							[be_up] => 0
							[be_down] => 0
							[isSelf] => 1
							[commentCount] => 0
							[to_userids_list] => Array
								(
								)

							[to_userids_count] => 0
							[image_columns] => 1
						)
				)

		)
		*/
	}
	function addblog($user,$blog=array()){
		$http=new http_curl();
		$http->from="http://www.yiban.cn/";
		$loginUrl="http://www.yiban.cn/blog/blog/addblog";
		$http->file=md5($user).'.cookie';
		$postStr='';
		if(!array_key_exists('token',$blog) || $blog['token']==''){
			$blog['token']=$this->getHtmlToKen($user);
		}
		foreach($blog as $key=>$value){
			if(empty($postStr)){
				$postStr.=$key.'='.$value;
			}else{
				$postStr.='&'.$key.'='.$value;
			}
		}
		$re=$http->post($loginUrl,$postStr,$this->headerArray);
		$json=empty($re[1])?0:json_decode($re[1],true);
		return $json;
		/*
		Array
		(
			[code] => 200
			[message] => 操作成功
			[data] => ok
			[backurl] => /blog/index/index/userid/6746755/access_token/d9bf26baf3d308e6fff1d29ee2877a13
		)
		{"code":200,"message":"\u64cd\u4f5c\u6210\u529f","data":"ok","backurl":"\/blog\/index\/index\/userid\/6746755\/access_token\/d9bf26baf3d308e6fff1d29ee2877a13"}
		*/
	}
	function getHtmlToKen($user){
		$http=new http_curl();
		$http->from="http://www.yiban.cn/";
		$loginUrl="http://www.yiban.cn/";
		$http->file=md5($user).'.cookie';
		$re=$http->get($loginUrl,$this->headerArray);
		preg_match_all("/g_config.token =.?\"(.*)\";/iU",$re[1],$token);//获取token
		return $token[1][0];
	}
	function DayIn($user){
		$http=new http_curl();
		$http->from="http://www.yiban.cn/";
		$loginUrl="http://www.yiban.cn/ajax/checkin/checkin";
		$http->file=md5($user).'.cookie';
		$postStr='';
		$re=$http->post($loginUrl,$postStr,$this->headerArray);
		$json=empty($re[1])?0:json_decode($re[1],true);//先检查是否签到
		if($json['code']==202){//已经签到过了
			// return $json;
		}elseif($json['code']==200){
			$html=$json['data']['survey'];//获取签到问题内容
			preg_match_all("/data\-value=\"(.*)\"/iU",$html,$optionid);//获取optionid
			$postStr="optionid={$optionid[1][0]}&optionid={$optionid[1][0]}&input=";
			$loginUrl='http://www.yiban.cn/ajax/checkin/answer';
			$re=$http->post($loginUrl,$postStr,$this->headerArray);
			$json=empty($re[1])?0:json_decode($re[1],true);
		}
		return $json;
		/*
		已经签到时的返回结果
		Array
		(
			[code] => 202
			[message] => 您已签到
		)
		未签到时返回的检测签到结果
		Array
		(
			[code] => 200
			[message] => 操作成功
			[data] => Array
				(
					[has_survey] => 1
					[survey] => <h4 class="dialog-title">签到<span>（已签到<strong class="sign-sum">12</strong>天）</span></h4>
		<div id="sign-survey" data-type="0">
			<dl>
				<dt>你对过年送礼的行为是怎么看待的？</dt>
						<dd><i class="survey-option" data-value="3951" data-input="0"></i><span class="survey-reason">一种沿袭下来的风俗，值得传承</span>
						</dd>
						<dd><i class="survey-option" data-value="3953" data-input="0"></i><span class="survey-reason">联系亲朋好友的好方式</span>
						</dd>
						<dd><i class="survey-option" data-value="3955" data-input="0"></i><span class="survey-reason">走过场而已，没什么意义</span>
						</dd>
					</dl>
		</div>
				)

		)
		提交签到问题的返回结果
		Array
		(
			[code] => 200
			[message] => 操作成功
			[data] => Array
				(
					[subCode] => 1
					[subMessage] => 提交调查并签到成功
				)

		)
		*/
	}
	function getJoined($user){//获得所加入的群组
		$http=new http_curl();
		$http->from="http://www.yiban.cn/";
		$loginUrl="http://www.yiban.cn/ajax/group/getJoined";
		$http->file=md5($user).'.cookie';
		$postStr="puid=5000187&group_id=0";
		$re=$http->post($loginUrl,$postStr,$this->headerArray);
		$json=empty($re[1])?0:json_decode($re[1],true);
		return $json;
		/*
		Array
		(
			[code] => 200
			[message] => 操作成功
			[data] => Array
				(
					[0] => Array
						(
							[id] => 260613
							[user_id] => 5000187
							[name] => 电气与计算机工程系
							[brief] => 
							[kind] => 2
							[img] => 0
							[qrCode] => 0
							[auth] => 2
							[type] => 1
							[originId] => 0
							[oldClassId] => 0
							[updateTime] => 2015-12-04 09:30:33
							[createTime] => 2015-11-10 13:16:47
							[sort] => 0
							[label] => 
							[top] => 0
							[memberSum] => 988
							[ispublic] => 0
							[isOrganization] => 1
							[puid] => 5000187
						)

					[1] => Array
						(
							[id] => 286782
							[user_id] => 5000187
							[name] => 校易班学生工作站
							[brief] => 
							[kind] => 4
							[img] => 0
							[qrCode] => 0
							[auth] => 3
							[type] => 1
							[originId] => 0
							[oldClassId] => 0
							[updateTime] => 2016-01-14 03:37:43
							[createTime] => 2016-01-14 03:37:43
							[sort] => 0
							[label] => 
							[top] => 0
							[memberSum] => 28
							[ispublic] => 0
							[isOrganization] => 1
							[puid] => 5000187
						)

				)

		)
		*/
	}
	function getMyOrgGroup($user){//获得所加入的机构号
		$http=new http_curl();
		$http->from="http://www.yiban.cn/";
		$loginUrl="http://www.yiban.cn/ajax/group/getMyOrgGroup";
		$http->file=md5($user).'.cookie';
		$postStr="puid=5000187&group_id=0";
		$re=$http->post($loginUrl,$postStr,$this->headerArray);
		$json=empty($re[1])?0:json_decode($re[1],true);
		return $json;
		/*
		Array
		(
			[code] => 200
			[message] => 操作成功
			[data] => Array
				(
					[0] => Array
						(
							[id] => 17325
							[user_id] => 5000000
							[publicName] => 易班
							[brief] => 全新易班，全“心”启程。师生们随时随地通过网络方式查阅及下载教育教学资料、同学们之间可以通过文字及图片分享文化娱乐。这样的易班，期待您的加入。
							[img] => 0
							[qrCode] => 0
							[bgImg] => 0
							[constellation] => 
							[signature] => 
							[school_id] => 309
							[Region_id] => 0
							[isOrganization] => 1
							[isAdministrative] => 0
							[type] => 1
							[createTime] => 2014-07-16 03:23:56
							[updateTime] => 2015-10-22 08:45:39
							[status] => 1
							[remark] => 
							[source] => 1
							[kind] => 8
							[name] => 易班
							[groups] => 
							[puid] => 5000000
						)

					[3] => Array
						(
							[id] => 12255
							[user_id] => 5000115
							[publicName] => 广西科技大学
							[brief] => 学校简介
							[img] => 0
							[qrCode] => 0
							[bgImg] => 0
							[constellation] => 
							[signature] => 
							[school_id] => 427
							[Region_id] => 2858
							[isOrganization] => 1
							[isAdministrative] => 1
							[type] => 1
							[createTime] => 2014-07-11 15:06:39
							[updateTime] => 2015-10-22 08:45:39
							[status] => 1
							[remark] => 
							[source] => 1
							[kind] => 4
							[name] => 广西科技大学
							[groups] => 
							[puid] => 5000115
						)

					[5] => Array
						(
							[id] => 12327
							[user_id] => 5000187
							[publicName] => 广西科技大学鹿山学院
							[brief] => 学校简介
							[img] => 0
							[qrCode] => 0
							[bgImg] => 0
							[constellation] => 
							[signature] => 
							[school_id] => 545
							[Region_id] => 2858
							[isOrganization] => 1
							[isAdministrative] => 1
							[type] => 1
							[createTime] => 2014-07-11 15:06:41
							[updateTime] => 2015-10-30 10:32:05
							[status] => 1
							[remark] => 
							[source] => 1
							[kind] => 4
							[name] => 广西科技大学鹿山学院
							[groups] => 在此学校所加入群组的内容，同上一个函数的返回结果
							[puid] => 5000187
						)

				)

		)
		*/
	}
	function UBB(&$str){
		include('ubb_rand_txt.txt');
		preg_match_all("/\[(.*)\]/iU",$str,$ubb);//获取到ubb信息
		// print_r($ubb);
		$bq=array(
				'鄙视','擦汗','大笑','得意','翻白眼','哼哼','坏笑','惊恐','开心',
				'可爱','可怜','哭','流汗','难过','亲亲','色迷迷','生气','睡觉',
				'调皮','偷笑','挖鼻孔','委屈','疑问','晕','抓狂','哦呵呵','傲慢',
				'尴尬','鼓掌','害羞','惊讶','骷髅','敲打','糗大了','再见','变猪',
				'冬季','祈祷','哦也','ok','大便','大拇指','倒喝彩','顶','吻',
				'玫瑰','拍手','衰','太阳','心','心碎','耶','月亮','v5',
				'给力','囧','宅','丢鸡蛋','圣诞树','手套','铜钱','袜子','咸蛋超人');
		$bq_len=count($bq);
		foreach($ubb[1] as $key=>$value){
			if($value=='随机'){
				$str=str_replace($ubb[0][$key],$sj[mt_rand(0,count($sj)-1)],$str);
			}elseif($value=='年月日'){
				$str=str_replace($ubb[0][$key],date("Y-m-d"),$str);
			}elseif($value=='时分秒'){
				$str=str_replace($ubb[0][$key],date("H:i:s"),$str);
			}elseif($value=='本站地址'){
				$str=str_replace($ubb[0][$key],'http://'.$_SERVER['SERVER_NAME'],$str);
			}elseif($value=='随机表情'){
				$str=str_replace($ubb[1][$key],$bq[mt_rand(0,$bq_len-1)],$str);
			}elseif($value==''){
				
			}
		}
	}
	function healthList($str='3'){//健康知识列表，传入分类id
		$http=new http_curl();
		$http->from="";
		$Url="http://www.tngou.net/api/lore/list";
		$str='page='.mt_rand(1,20).'&id='.$str;
		$re=$http->post($Url,$str,$this->headerArray);
		$json=empty($re[1])?0:json_decode($re[1],true);
		return $json;
		/*
		
		Array
		(
			[status] => 1
			[total] => 1157
			[tngou] => Array
				(
					[0] => Array
						(
							[count] => 6
							[description] => 文章摘要
							[fcount] => 0
							[id] => 19073
							[img] => /lore/160214/3ae599bc9487f7a31117ad8fa3cf490c.jpg
							[keywords] => 土豆 一起 发芽 龙葵 搭配 
							[loreclass] => 3
							[rcount] => 0
							[time] => 1455435477000
							[title] => 土豆不宜与西红柿搭配：土豆会在人体的胃肠中产生大量的盐酸
						)
				)
		)
		
		*/
	}
	function healthShow($str=''){//最新健康知识列表接口，传入只是列表的文章id
		$http=new http_curl();
		$http->from="";
		$Url="http://www.tngou.net/api/lore/show";
		$str='id='.$str;
		$re=$http->post($Url,$str,$this->headerArray);
		$json=empty($re[1])?0:json_decode($re[1],true);
		return $json;
		/*
		Array
		(
			[count] => 7
			[description] => 文章摘要
			[fcount] => 0
			[id] => 19073
			[img] => /lore/160214/3ae599bc9487f7a31117ad8fa3cf490c.jpg
			[keywords] => 土豆 一起 发芽 龙葵 搭配 
			[loreclass] => 3
			[message] => 文章内容
			[rcount] => 0
			[status] => 1
			[time] => 1455435477000
			[title] => 土豆不宜与西红柿搭配：土豆会在人体的胃肠中产生大量的盐酸
			[url] => http://www.tngou.net/lore/show/19073
		)
		*/
	}
	
	
	
	
}
$a=new YBClass();
?>
