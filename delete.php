<?php

require_once("config.php");
require_once("functions.php");
$deleteID=$_GET['id'];

$sqlHandler=new mysqli("localhost",dbUser,dbPass,"emailtracker");
$sqlHandler->query("set names utf8");
if(mysqli_connect_errno())
	die('Could not connect: ' .mysqli_connect_errno());

$sql1="delete from job where jhashid='$deleteID'";
$sql2="delete from job where jhashid='$deleteID'";

if(!$sqlHandler->query($sql1) or !$sqlHandler->query($sql2) or !my_del("img/$deleteID"))
{
	echo "删除追踪失败！该追踪已被删除或不存在 ".$sqlHandler->error.$sqlHandler->errno;
}
else
{
	echo '
<script>
alert(\'退订成功，点击确定跳转到主页...\');location.href=\'index.php\';
</script>
';
		
}

$sqlHandler->close();


//删除文件夹函数

 










?>

