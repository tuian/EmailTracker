<?php

$sqlHandler=new mysqli("localhost","root","root","prison");
$sqlHandler->query("set names utf8");
if(mysqli_connect_errno())
	echo "连接prison失败".mysqli_connect_errno();
else
	echo "连接到prison成功<br>";

echo "增删改查<br>";

//1.增加
$sql="insert into prisoner values ('10086','941226','Benny','M','166','45','5','Ruby')";
if($sqlHandler->query($sql))
	echo "插入成功";
else
	echo "插入失败 ".$sqlHandler->error.$sqlHandler->errno;

//2.修改
$sql="update prisoner set roomID='409' where name='迈克尔'";
if($sqlHandler->query($sql))
	echo "修改成功";
else
	echo "修改失败 ".$sqlHandler->error.$sqlHandler->errno;

//3.查询  创建结果集、从结果集中输出数据、释放内存
$result=$sqlHandler->query("select * from prisoner");
while(list($c1,$c2,$c3,$c4)=$result->fetch_row())	//list 把数组中的值赋值给变量
	echo $c1."  ".$c2."  ".$c3."  ".$c4."<br>";
$result->close();	//关闭结果集

//4.删除
$sql="delete from prisoner where name='Benny'";
if($sqlHandler->query($sql))
	echo "删除成功";
else
	echo "删除失败 ".$sqlHandler->error.$sqlHandler->errno;


$sqlHandler->close();	//关闭数据库连接
?>