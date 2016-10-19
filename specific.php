<?php

//负责复制对应日志的hashID到/img/hashID/下
require_once("config.php");


//定义变量

$cID=$cHashID=$cIP=$cBrowser=$cUnread=$cAvailable=null;

$jobResult=$sqlHandler->query("select * from job");
while(list($cID,$cHashID,$cIP,$cBrowser,$cUnread,$cAvailable)=$jobResult->fetch_row())	//list 把数组中的值赋值给变量
{	
	$regex="/".$cHashID."/i";
$readHandle=fopen(logPath,"r") or die("文件打开失败");//打开日志access.log
$writeHandle=fopen("img/$cHashID/specific.log","w") or die("文件打开失败");//打开特定日志准备写入specific.log

while(!feof($readHandle)){
	$logBuffer=fgets($readHandle);

 if (preg_match($regex, $logBuffer)) //如果字符串中包含Windows NT 6.0字符的话
        fwrite($writeHandle,$logBuffer);
}
fclose($readHandle);
fclose($writeHandle);
//分析用户目录下的日志信息，存入tracker表

}

$jobResult->close();

$sqlHandler->close();



?>