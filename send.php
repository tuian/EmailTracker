<?php

function sendEmail($hashID){
require_once("config.php");
//要不要distinct？
//select distinct jemail,tip,ttime,tloc,tbrowser,tos from tracker inner join job on job.jhashid=tracker.thashid
$sqlHandler=new mysqli("localhost",dbUser,dbPass,"emailtracker");
$sqlHandler->query("set names utf8");
if(mysqli_connect_errno())
	die("连接失败".mysqli_connect_errno());
$all=$sendAdd=null;
$sql="select distinct jemail,tip,ttime,tloc,tbrowser,tos from tracker inner join job on job.jhashID=tracker.thashID order by ttime";
$count=0;
$jobResult=$sqlHandler->query($sql);
//拷贝日志、分析日志、删除过期追踪记录
while(list($email,$tip,$time,$loc,$browser,$os)=$jobResult->fetch_row())
{	
	$all=$all.++$count."次阅读".$tip."  ".$time." ".$loc."  ".$browser."  ".$os."---Next---<br>";
	
	$sendAdd=$email;
	
}

echo "发送邮件给$sendAdd   邮件内容如下：<br>";
	echo $all;

}

?>