<?php

require_once("analysis.php");
require_once("config.php");
require_once("send.php");
//计划任务的目标
//读取job，分析日志文件，将包含hashID的日志拷贝到对应的用户目录下
$sqlHandler=new mysqli("localhost",dbUser,dbPass,"emailtracker");
$sqlHandler->query("set names utf8");
if(mysqli_connect_errno())
	die("连接失败".mysqli_connect_errno());
//$cID=$cHashID=$cIP=$cBrowser=$cUnread=$cAvailable=null;

$jobResult=$sqlHandler->query("select * from job");
//拷贝日志、分析日志、删除过期追踪记录
while(list($cID,$cHashID,$cEmail,$cSubject,$cIP,$cBrowser,$cUnread,$cAvailable)=$jobResult->fetch_row())	
{
	copyLog($cHashID);	//拷贝日志
	//分析日志
	$sql="delete from tracker where tHashID='$cHashID'";
	if(!$sqlHandler->query($sql))
		echo "删除失败 ".$sqlInsertHandler->error.$sqlInsertHandler->errno;
	analysis($cHashID,$cIP,$cBrowser);
	
	//根据hashID和邮箱进行跨表查询，然后发送邮件（demo里显示）
	//select distinct jemail from tracker inner join job on job.jhashid=tracker.thashid
	//下面这个查询结果会查出来邮箱、IP、时间、位置、浏览器、系统
	//select distinct jemail,tip,ttime,tloc,tbrowser,tos from tracker inner join job on job.jhashid=tracker.thashid
	//调用函数试试echo吧！
	sendEmail($cHashID);
	
}




$jobResult->close();
$sqlHandler->close();
?>