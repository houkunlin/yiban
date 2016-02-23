<?php
/*
*
*http发送GET和post数据
*
*/

class http_curl{

    private $curl;
    public $user_agent = "Mozilla/5.0 (Windows NT 5.1; rv:44.0) Gecko/20100101 Firefox/44.0";
    public $path="";#默认COOKIE地址路径
    public $cookie="../cookie/";#默认COOKIE地址
    public $file="all.cookie";#默认COOKIE文件名，不加后缀，
    public $from="";
    
    public function get($url,$header=array(),$cookie='') {
		$result=array();
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_HTTPHEADER,$header);//模拟头部信息
		curl_setopt($ch,CURLOPT_REFERER,$this->from);#设置来源url
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT, 0);#不作等待
		curl_setopt($ch, CURLOPT_URL, $url);#访问地址url
		curl_setopt($ch, CURLOPT_HEADER, 1);#显示头部header
		curl_setopt($ch, CURLOPT_USERAGENT,$this->user_agent);#浏览器标识
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1); // 使用自动跳转
		curl_setopt($ch,CURLOPT_AUTOREFERER,1); // 自动设置Referer
		curl_setopt($ch,CURLOPT_TIMEOUT, 10);#10秒结束
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		$cookie.=$this->ReadCookie($this->cookie.$this->file);
		curl_setopt($ch,CURLOPT_COOKIE,$cookie);//发送COOKIE信息
		// if($a != 0){
			// if($a == 1){
				// curl_setopt($ch,CURLOPT_NOBODY,1);//不显示内容
			// }
			// if($a == 2){
				// curl_setopt($ch,CURLOPT_TIMEOUT, 1);#1秒结束
			// }
			// if($a == 3){
				// curl_setopt($ch,CURLOPT_NOBODY,1);//不显示内容
				// curl_setopt($ch,CURLOPT_TIMEOUT, 1);#1秒结束
			// }
		// }
		// curl_setopt($ch,CURLOPT_COOKIEFILE,$this->cookie.$this->file);#发送COOKIE
		curl_setopt($ch,CURLOPT_COOKIEJAR,$this->cookie.$this->file.'2');#保存COOKIE
		$data = curl_exec($ch);
		##分离header和body内容
		//$response = curl_exec($ch);
		if (curl_getinfo($ch, CURLINFO_HTTP_CODE) == '200') {
			$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);//获取头部信息的长度
			$header = substr($data, 0, $headerSize);//截取头部信息
			$body = substr($data, $headerSize);//截取正文信息
			/*
			echo "\nheader:\n",$header;
			echo "\nbody:\n",$body;
			*/
			$result[0]=$header;
			$result[1]=$body;
			$result[2]=$headerSize;
			$result[3]=$data;
		}
		curl_close($ch);
		$this->SaveCookie($this->cookie.$this->file,$header);
		return $result;
    }

    public function post($url,$params,$header=array(),$cookie='') {
		$result=array();
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_HTTPHEADER,$header);//模拟头部信息
		curl_setopt($ch,CURLOPT_REFERER,$this->from);#设置来源url
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT, 0);#不作等待
		curl_setopt($ch,CURLOPT_URL, $url);#url地址
		curl_setopt($ch,CURLOPT_HEADER, 1);#显示头部
		curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
		curl_setopt($ch,CURLOPT_AUTOREFERER, 1 ); // 自动设置Referer
		curl_setopt($ch,CURLOPT_POST, 1);
		curl_setopt($ch,CURLOPT_USERAGENT, $this->user_agent);#浏览器标识
		curl_setopt($ch,CURLOPT_POSTFIELDS, $params);#POST数据
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch,CURLOPT_TIMEOUT, 10);#10秒后结束
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		$cookie.=$this->ReadCookie($this->cookie.$this->file);
		curl_setopt($ch,CURLOPT_COOKIE,$cookie);//发送COOKIE信息
		// if($a != 0){
			// if($a == 1){
				// curl_setopt($ch,CURLOPT_NOBODY,1);//不显示内容
			// }
			// if($a == 2){
				// curl_setopt($ch,CURLOPT_TIMEOUT, 1);#1秒结束
			// }
			// if($a == 3){
				// curl_setopt($ch,CURLOPT_NOBODY,1);//不显示内容
				// curl_setopt($ch,CURLOPT_TIMEOUT, 1);#1秒结束
			// }
		// }
		// curl_setopt($ch,CURLOPT_COOKIEFILE,$this->cookie.$this->file);#发送COOKIE
		curl_setopt($ch,CURLOPT_COOKIEJAR,$this->cookie.$this->file.'2');#接收保存COOKIE
		$data = curl_exec($ch);
		##分离header和body内容
		//$response = curl_exec($ch);
		if (curl_getinfo($ch, CURLINFO_HTTP_CODE) == '200') {
			$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);//获取头部信息的长度
			$header = substr($data, 0, $headerSize);//截取头部信息
			$body = substr($data, $headerSize);//截取正文信息
			/*
			echo "\nh
				[1] =>  yiban_user_eader:\n",$header;
			echo "\nbody:\n",$body;
			*/
			$result[0]=$header;
			$result[1]=$body;
			$result[2]=$headerSize;
			$result[3]=$data;
		}
		curl_close($ch);
		$this->SaveCookie($this->cookie.$this->file,$header);
		return $result;
    }
	function SaveCookie($filepath,$header='',$str=''){
		if(isset($header) && !is_array($header) && trim($header)!='' && preg_match("/Set-Cookie/iU",$header)){
			preg_match_all("/Set-Cookie:(.*)=(.*);/iU",$header,$cookie);//获取cookie
			foreach($cookie[1] as $key=>$value){
				if(trim($value)=='yiban_user_token' && $cookie[2][$key]=='deleted'){
					//登录状态失效
				}else{
					$str.=$value.'='.$cookie[2][$key].';';
				}
				
			}
		}
		// echo $header.$str;
		$fp=fopen($filepath,"a+");
		fwrite($fp,$str);
		fclose($fp);
	}
	function ReadCookie($filepath){
		// $cookie='_cnzz_CV1253488264=学校页面|:/Index/Login/index|1455263028052&学校名称|其他|1455263028063; CNZZDATA1253488264=1727949191-1455259643-http://www.yiban.cn/1455258150; timezone=-8;';
		$cookie='_cnzz_CV1253488264=%E5%AD%A6%E6%A0%A1%E9%A1%B5%E9%9D%A2%7C%3A%2FIndex%2FLogin%2Findex%7C1455263028052%26%E5%AD%A6%E6%A0%A1%E5%90%8D%E7%A7%B0%7C%E5%85%B6%E4%BB%96%7C1455263028063; CNZZDATA1253488264=1727949191-1455259643-http%253A%252F%252Fwww.yiban.cn%252F%7C1455258150; timezone=-8;';
		if(!file_exists($filepath)){
			return $cookie;
		}
		return $cookie.file_get_contents($filepath);
	}
}
 
?>
