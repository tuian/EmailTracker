<?php
//追踪部分的代码
require_once("functions.php");
require_once("config.php");
require_once("form.php");
require_once("PHPMailer/send_mail.php");

if($read=="Send")
	$unread=3;
else
	$unread=-1;
if(!empty($subject) and !empty($email))
{	
	
	
	//创建变量
	$hashID=md5($email.$subject);
	$imgSrc="img/$hashID/track.png";
	$flag1=$flag2=false;
	$trackerIP=getIP();
	$trackerUA=getUA();
	$trackTime=time();
	//echo $trackTime;
	$sql="insert into job values(0,'$hashID','$trackTime','$email','$subject','$trackerIP','$trackerUA',$unread,1)";
	
	//插入任务到job表中（游客jID为0），创建hash，拷贝追踪文件。
	//创建连接
	//关于jAvailable，0代表无效，需要删除这个条目；1代表有效。
	//关于jUnread  
	//3代表剩余3天发送未读邮件提醒，0表示立刻发送未读邮件提醒，-1表示不发送未读邮件提醒
	$sqlHandler=new mysqli("localhost",dbUser,dbPass,"EmailTracker");
	$sqlHandler->query("set names utf8");
	if(mysqli_connect_errno())
		echo "连接失败".mysqli_connect_errno();
	
	
//插入job
if($sqlHandler->query($sql))
{	$flag1=true;
}
else
	echo "已经存在了同样主题和邮件的追踪记录了！";

//拷贝追踪图片
if(!is_readable("img"))
	mkdir("img",755,true);
if(!is_readable("img/$hashID"))
{
	mkdir("img/$hashID",755,true);
	copy("track.png",$imgSrc);
	$flag2=true;
}	

if($flag1==true and $flag2==true)
{	echo "追踪成功，请右键复制追踪图片地址<br>";
echo "<img src=$imgSrc alt=\"wrong\" />";
}
else
	echo "追踪失败！<br>";

$sqlHandler->close();	
}


?>

</body>
</html>