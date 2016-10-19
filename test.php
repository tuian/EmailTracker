<?php
$p1="232@qq.com";
$p2="我爱你";
$p3="123.4.2.42";
$p4="大连";
$p5="2016年10月19日13:34:48";
$p6="Chrome";
$p7="Windows 10";
$message = '

<div style="color:#555;font:12px/1.5 微软雅黑,Tahoma,Helvetica,Arial,sans-serif;width:650px;margin:
50px auto;border-top: none;box-shadow:0 0px 3px #aaaaaa;" ><table border="0" cellspacing="0" 
cellpadding="0"><tbody><tr valign="top" height="2"><td valign="top"><div style="background-color:
white;border-top:2px solid #12ADDB;line-padding:0 15px 12px;width:650px;color:#555555;font-family:
微软雅黑, Arial;;font-size:12px;"><h2 style="border-bottom:1px solid #DDD;font-size:14px;font-weight:
normal;padding:8px 0 10px 8px;"><span style="color: #12ADDB;font-weight: bold;">&gt; </span>'
.$p1.'你好，你追踪的邮件有新的动态！</h2><div style="padding:0 12px 0 12px;margin-top:18px">
<p>您好，您追踪的邮件『'.$p2.'』有新的状态
<br>

</p><p style="background-color: #EEE;border: 1px solid #DDD;padding: 20px;margin: 15px 0;">IP:'.$p3.'</p><p>
</p><p style="background-color: #EEE;border: 1px solid #DDD;padding: 20px;margin: 15px 0;">位置:'.$p4.'</p><p>
</p><p style="background-color: #EEE;border: 1px solid #DDD;padding: 20px;margin: 15px 0;">时间:'.$p5.'</p><p>
</p><p style="background-color: #EEE;border: 1px solid #DDD;padding: 20px;margin: 15px 0;">UA:'.$p6.'</p><p>
</p><p style="background-color: #EEE;border: 1px solid #DDD;padding: 20px;margin: 15px 0;">OS:'.$p7.'</p><p>

<a style="text-decoration:none; color:#5692BC" target="_blank" href="www.baidu.com">点击这里</a>退订本次追踪

<br>祝您天天开心，欢迎下次使用，谢谢。
</p><p style="float:right;">(此邮件由系统自动发出, 请勿回复)</p></div></div></td></tr>
</tbody></table><div style="color:#fff;background-color: #12ADDB;text-align : center;height:35px;
padding-top:15px">Copyright © 2014-2016 Ruby </div></div>
















'




;
 
 echo $message;
 ?>