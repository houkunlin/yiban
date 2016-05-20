<?php
	date_default_timezone_set("PRC");
	header("content-Type: text/html; charset=utf-8");
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
	header("Cache-Control: no-cache, must-revalidate");
	header("Pragma: no-cache");
	include('YBClass.php');
//	$re=$a->getTrendsList('15577405667',6870611);
	$a->ZanTong('15577405667','6870611','10164263',1);
	print_r($re);
?>