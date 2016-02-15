<?
include("config/mysql_config.php");
include('funclass/tip.class.php');
$log=file_get_contents('log/log.txt');
//$log=$log==''?'暂时没有动态':$log;
$log2=explode("\n",$log);
$len=count($log2);
$len2=$len>10?$len-10:0;
$log3='';
//$len2+=3;
for($i=$len-1;$i>=$len2;--$i){
	$log3.=$log2[$i]!=''?'<li class="list-group-item">'.$log2[$i].'</li>':'';
}
/*
echo $log3; 
echo $len.'.'.$len2;
*/
$body=file_get_contents('index3.html');
$body=str_replace('{log}',($log3==''?'<li class="list-group-item">暂时没有新的动态</li>':$log3),$body);

echo $body;
?>
