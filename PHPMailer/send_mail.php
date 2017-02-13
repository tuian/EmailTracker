<?php
require_once('class.phpmailer.php');
require_once(dirname(dirname(__FILE__))."/config.php");


//未读邮件提醒


function sendUnreadEmail($HashID,$EmailAdd,$Subject,$Unread)
{

	$emailBody=
	'
	<div style="color:#555;font:12px/1.5 微软雅黑,Tahoma,Helvetica,Arial,sans-serif;width:650px;margin:
50px auto;border-top: none;box-shadow:0 0px 3px #aaaaaa;" ><table border="0" cellspacing="0" 
cellpadding="0"><tbody><tr valign="top" height="2"><td valign="top"><div style="background-color:
white;border-top:2px solid #12ADDB;line-padding:0 15px 12px;width:650px;color:#555555;font-family:
微软雅黑, Arial;;font-size:12px;"><h2 style="border-bottom:1px solid #DDD;font-size:14px;font-weight:
normal;padding:8px 0 10px 8px;"><span style="color: #12ADDB;font-weight: bold;">&gt; </span>'
.$EmailAdd.' 你好，你追踪的邮件尚未被阅读！</h2><div style="padding:0 12px 0 12px;margin-top:18px">
<p>您好，您追踪的邮件『'.$Subject.'』尚未被阅读<br>'.'

<a style="text-decoration:none; color:#5692BC" target="_blank" href="delete.php?id='.$HashID.'">点击这里</a>退订本次追踪

<br>祝您天天开心，欢迎下次使用，谢谢。
</p><p style="float:right;">(此邮件由系统自动发出, 请勿回复)</p></div></div></td></tr>
</tbody></table><div style="color:#fff;background-color: #12ADDB;text-align : center;height:35px;
padding-top:15px">Copyright © 2014-2016 Ruby </div></div>
	
	';
	
	//之后把unread改成0吧哈哈哈，这样就调用不到这里了。
		//echo $emailBody;	//debug
		
	
	$sqlHandler=new mysqli("localhost",dbUser,dbPass,"EmailTracker");
	$sqlHandler->query("set names utf8");
	if(mysqli_connect_errno())
	die("连接失败".mysqli_connect_errno());
	$sql="update job set jUnread=0 where jHashID='$HashID'";
	if(!$sqlHandler->query($sql))
		echo "修改失败 ".$sqlHandler->error.$sqlHandler->errno;

	$sqlHandler->close();

//结束
//发送邮件


$mail = new PHPMailer(); //实例化
$mail->IsSMTP(); // 启用SMTP
$mail->Host = mailHost; //SMTP服务器 以163邮箱为例子
$mail->Port = mailPort;  //邮件发送端口
$mail->SMTPAuth   = true;  //启用SMTP认证
$mail->SMTPSecure = "ssl";
$mail->CharSet  = "UTF-8"; //字符集
$mail->Encoding = "base64"; //编码方式

$mail->Username = mailUsername;  //你的邮箱
$mail->Password = mailPassword;  //你的密码
$mail->Subject = "你的邮件有新的状态！"; //邮件标题

$mail->From = mailUsername;  //发件人地址（也就是你的邮箱）
$mail->FromName = mailFromName;  //发件人姓名

$address = $EmailAdd;//收件人email   ***
$mail->AddAddress($address, "嗨");//添加收件人（地址，昵称）

//$mail->AddAttachment('xx.xls','我的附件.xls'); // 添加附件,并指定名称
$mail->IsHTML(true); //支持html格式内容
//$mail->AddEmbeddedImage("logo.jpg", "my-attach", "logo.jpg"); //设置邮件中的图片  *
$mail->Body = $emailBody;

//发送
if(!$mail->Send()) {
  echo "发送失败: " . $mail->ErrorInfo;
} else {
	//$_SESSION['ip'] = get_client_ip();
	//$_SESSION['time'] = time();
  echo "1";
}

//发邮件结束


echo "计划任务成功完成<br>";
}








//已读回执

function sendEmail($HashID,$EmailAdd,$Subject,$Unread)
{

//获取发件人信息
$sqlHandler=new mysqli("localhost",dbUser,dbPass,"EmailTracker");
$sqlHandler->query("set names utf8");
if(mysqli_connect_errno())
	die("连接失败".mysqli_connect_errno());

$emailBody=$recordTime=null;
$sql="select tip,ttime,tloc,tbrowser,tos from tracker 
where thashid='$HashID' order by ttime";	//由于order by time，所以肯定是发送最新的信息
$count=0;
$jobResult=$sqlHandler->query($sql);

while(list($ip,$time,$loc,$browser,$os)=$jobResult->fetch_row())
{	
	$emailBody=
	'
	<div style="color:#555;font:12px/1.5 微软雅黑,Tahoma,Helvetica,Arial,sans-serif;width:650px;margin:
50px auto;border-top: none;box-shadow:0 0px 3px #aaaaaa;" ><table border="0" cellspacing="0" 
cellpadding="0"><tbody><tr valign="top" height="2"><td valign="top"><div style="background-color:
white;border-top:2px solid #12ADDB;line-padding:0 15px 12px;width:650px;color:#555555;font-family:
微软雅黑, Arial;;font-size:12px;"><h2 style="border-bottom:1px solid #DDD;font-size:14px;font-weight:
normal;padding:8px 0 10px 8px;"><span style="color: #12ADDB;font-weight: bold;">&gt; </span>'
.$EmailAdd.' 你好，你追踪的邮件有新的动态！</h2><div style="padding:0 12px 0 12px;margin-top:18px">
<p>您好，您追踪的邮件『'.$Subject.'』第'.++$count.'次被阅读<br>本次信息如下'.'
<br>

</p><p style="background-color: #EEE;border: 1px solid #DDD;padding: 20px;margin: 15px 0;">IP： '.$ip.'</p><p>
</p><p style="background-color: #EEE;border: 1px solid #DDD;padding: 20px;margin: 15px 0;">位置： '.$loc.'</p><p>
</p><p style="background-color: #EEE;border: 1px solid #DDD;padding: 20px;margin: 15px 0;">时间： '.$time.'</p><p>
</p><p style="background-color: #EEE;border: 1px solid #DDD;padding: 20px;margin: 15px 0;">浏览器： '.$browser.'</p><p>
</p><p style="background-color: #EEE;border: 1px solid #DDD;padding: 20px;margin: 15px 0;">操作系统 :'.$os.'</p><p>

<a style="text-decoration:none; color:#5692BC" target="_blank" href="delete.php?id='.$HashID.'" >点击这里</a>退订本次追踪

<br>祝您天天开心，欢迎下次使用，谢谢。
</p><p style="float:right;">(此邮件由系统自动发出, 请勿回复)</p></div></div></td></tr>
</tbody></table><div style="color:#fff;background-color: #12ADDB;text-align : center;height:35px;
padding-top:15px">Copyright © 2014-2016 Ruby </div></div>
	
	';
	
	$recordTime=$time;
	//++$count."次阅读".$tip."  ".$time." ".$loc."  ".$browser."  ".$os."---Next---<br>";
	//$sendAdd=$email;
}


//Bug No.1 如何不让每次计划任务都发送邮件？按照2分钟一次的计划任务来算
//...发送过的计划任务的时间一定是在两分钟之前的！$Unread


	$testTime=time()-strtotime($recordTime);
	
		if($testTime>=cronTime)	//120秒之内
	{	echo "日志中最新的时间：".$recordTime."<br>";
		echo "当前时间：".date("Y-m-d G:i:s")."<br>";
		echo "当前Unix时间戳".time()."<br>";
		echo "日志中的时间戳 ".strtotime($recordTime)."<br>";
		echo "两者相差多少秒 ".$testTime."<br>--------<br>";
		echo "很长时间之前的记录，一定是发送过了或者是3天内没人读<br>";
		return 1;
	}
	//else
		//echo $emailBody;	//debug
	
$jobResult->close();
$sqlHandler->close();

//结束
//发送邮件


$mail = new PHPMailer(); //实例化
$mail->IsSMTP(); // 启用SMTP
$mail->Host = mailHost; //SMTP服务器 以163邮箱为例子
$mail->Port = mailPort;  //邮件发送端口
$mail->SMTPAuth   = true;  //启用SMTP认证
$mail->SMTPSecure = "ssl";
$mail->CharSet  = "UTF-8"; //字符集
$mail->Encoding = "base64"; //编码方式

$mail->Username = mailUsername;  //你的邮箱
$mail->Password = mailPassword;  //你的密码
$mail->Subject = "你的邮件有新的状态！"; //邮件标题

$mail->From = mailUsername;  //发件人地址（也就是你的邮箱）
$mail->FromName = mailFromName;  //发件人姓名

$address = $EmailAdd;//收件人email   ***
$mail->AddAddress($address, "嗨");//添加收件人（地址，昵称）

//$mail->AddAttachment('xx.xls','我的附件.xls'); // 添加附件,并指定名称
$mail->IsHTML(true); //支持html格式内容
//$mail->AddEmbeddedImage("logo.jpg", "my-attach", "logo.jpg"); //设置邮件中的图片  *
$mail->Body = $emailBody;

//发送
if(!$mail->Send()) {
  echo "发送失败: " . $mail->ErrorInfo;
} else {
	//$_SESSION['ip'] = get_client_ip();
	//$_SESSION['time'] = time();
  echo "1";
}

//发邮件结束


echo "计划任务成功完成<br>";
}

?>
