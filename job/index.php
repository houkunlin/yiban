<?php
	ignore_user_abort();//关掉浏览器，PHP脚本也可以继续执行.
	set_time_limit(0);//让程序无限制的执行下去
	date_default_timezone_set('PRC');

	@header("content-Type: text/html; charset=utf-8");

	$upd1=time();
	@$upd2=file_get_contents('runtime.txt');
	$upday=round(($upd1-$upd2)/60);
	@$hkl=$_GET["hkl"];
	if($upday < 5 && $upd2 != "" && $hkl == ""){
		echo $upday."分钟前已运行，本次不运行";
		exit();
	}
	$fp=fopen("runtime.txt","w+");
	fwrite($fp,time());
	fclose($fp);
	
	@$a=dirname($_SERVER['PHP_SELF']);
	if($a == "/" || $a == "."){$a="";}
	$host='http://'.$_SERVER['HTTP_HOST'].$a.'/';
	echo $host;
	echo '<ul>';
	echo '<li>'.html($host.'runlogin.php').'</li>';
	echo '<li>'.html($host.'runtrends.php').'</li>';
	echo '<li>'.html($host.'rundayin.php').'</li>';
	// html($host.'');
	// html($host.'');
	echo '</ul>';
	// $i=0;
	// echo "<ol>";
	// foreach (glob("*.php") as $file) {
		// if($file != "index.php"){
			// $url1=$host.$file;
			// echo "<li><a href=\"{$url1}\">{$url1}</a></li>";
		// }
		// $i++;
	// }
	// echo "</ol>";



	echo 'ok'.date("Y-m-d H:i:s");


	function html($url){
		//$url访问地址
		//$post_string需要发送的POST数据，为空则发送GET数据
		$header = array('CLIENT-IP:219.159.105.180','X-FORWARDED-FOR:219.159.105.180');//IP信息
		$agent = 'Mozilla/5.0 (Linux; U; Android 4.2.2; zh-cn; U51GT-W Build/JDQ39) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30';//浏览器信息
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);//设置访问地址
		// curl_setopt($ch,CURLOPT_HTTPHEADER,$header);//模拟IP
		// curl_setopt($ch,CURLOPT_USERAGENT,$agent);//模拟浏览器
		@curl_setopt ($curl,CURLOPT_AUTOREFERER, 1 ); // 自动设置Referer
		@curl_setopt($ch,CURLOPT_FOLLOWLOCATION, 1 ); // 使用自动跳转
		curl_setopt($ch,CURLOPT_NOBODY,1);//不显示内容
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT, 10);//等待时间
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);//最长时间
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}

?>