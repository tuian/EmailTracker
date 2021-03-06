<?php

//定义数据库相关的常量
define("dbUser",	"root");	//MySQL用户名，通常是root
define("dbPass",	"root");	//MySQL密码，请自行修改
define("logPath",	"C:\\xampp\\apache\\logs\\access.log");	//日志文件的路径
//Windows请使用C:\\xampp\\apache\\logs\\access.log，linux使用/home/wwwlogs/access.log

//定义SMTP参数
define("mailHost","smtp.exmail.qq.com");	//SMTP服务器 
define("mailPort","465");					//端口
define("mailUsername","no-reply@comingon.top");	//用户名
define("mailPassword","941226Mail");	//密码
define("mailFromName","Ruby");	//发件人姓名
define("cronTime","60");		//cron时间

$sqlHandler=new mysqli("localhost",dbUser,dbPass);
$sqlHandler->query("set names utf8");
if(mysqli_connect_errno())
	die('Could not connect: ' .mysqli_connect_errno());

//0.创建数据库、选择数据库
$sql="create database if not exists EmailTracker";	
if(!$sqlHandler->query($sql))
	die("创建数据库失败 ".$sqlHandler->error.$sqlHandler->errno)."<br>";
$sql="use EmailTracker";
if(!$sqlHandler->query($sql))
	die("选择数据库失败 ".$sqlHandler->error.$sqlHandler->errno)."<br>";

//1.创建用户表
$sql="create table if not exists user(uID int not null primary key auto_increment,
								uName varchar(40) not null,
								uPass varchar(80) not null)";
if(!$sqlHandler->query($sql))
	die("创建用户表失败 ".$sqlHandler->error.$sqlHandler->errno)."<br>";

//2.创建追踪表
$sql="create table if not exists tracker(tID int not null,
										 tHashID varchar(40) not null,
										 tTime datetime not null,
										 tIP varchar(20) not null,
										 tLoc varchar(40),
										 tBrowser varchar(30) not null,
										 tOS varchar(20) not null)";
if(!$sqlHandler->query($sql))
	die("创建追踪表失败 ".$sqlHandler->error.$sqlHandler->errno)."<br>";

//3.创建计划任务表
$sql="create table if not exists job(jID int not null ,
									 jHashID varchar(40) not null primary key,
									 tInitTime int not null,
									 jEmail varchar(40) not null,
									 jSubject varchar(40) not null,
									 jIP varchar(20) not null,
									 jBrowser varchar(30) not null,
									 jUnread smallint,
									 jAvailable tinyint)";
if(!$sqlHandler->query($sql))
	die("创建计划任务表失败 ".$sqlHandler->error.$sqlHandler->errno)."<br>";

//关闭数据库连接
$sqlHandler->close();	//关闭数据库连接

?>
