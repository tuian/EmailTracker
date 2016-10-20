<?php

require_once("functions.php");
require_once("config.php");
require_once("/PHPMailer/send_mail.php");
//计划任务的目标
//读取job，分析日志文件，将包含hashID的日志拷贝到对应的用户目录下
$sqlHandler=new mysqli("localhost",dbUser,dbPass,"emailtracker");
$sqlHandler->query("set names utf8");
if(mysqli_connect_errno())
	die("连接失败".mysqli_connect_errno());
//$cID=$cHashID=$cIP=$cBrowser=$cUnread=$cAvailable=null;
$c=0;
$jobResult=$sqlHandler->query("select * from job");
//拷贝日志、分析日志、删除过期追踪记录
while(list($cID,$cHashID,$cInitTime,$cEmail,$cSubject,$cIP,$cBrowser,$cUnread,$cAvailable)=$jobResult->fetch_row())	
{	//echo "job time Unix ".$cInitTime."<br>";
	if((time()-$cInitTime)>259200 and $cUnread==3)	//超过三天未读
	{
	sendUnReadEmail($cHashID,$cEmail,$cSubject,$cUnread);
		exit();
		//是否要die
	}
	
	copyLog($cHashID);	//拷贝日志
	//分析日志
	$sql="delete from tracker where tHashID='$cHashID'";
	if(!$sqlHandler->query($sql))
		echo "删除失败 ".$sqlInsertHandler->error.$sqlInsertHandler->errno;
	analysis($cHashID,$cIP,$cBrowser);
	
	//根据hashID和邮箱进行跨表查询，然后发送邮件（demo里显示）
	//select tip,ttime,tloc,tbrowser,tos from tracker where thashid='9f2dad8eca31fd8e10dca8190eb298f7'  ;
	
	sendEmail($cHashID,$cEmail,$cSubject,$cUnread);

}




$jobResult->close();
$sqlHandler->close();
?>