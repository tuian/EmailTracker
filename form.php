<!DOCTYPE HTML> 
<html>
<head>
<style>
.error {color: #FF0000;}
</style>
</head>
<body> 

<?php
// 定义变量并设置为空值
$subjectErr = $emailErr =  "";
$subject = $email = $read= "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   if (empty($_POST["sub1"])) {
     $subjectErr = "主题是必填的";
   } else {
     $subject = test_input($_POST["sub1"]);
     
   }
   
   if (empty($_POST["sub2"])) {
     $emailErr = "电邮是必填的";
   } else {
     $email = test_input($_POST["sub2"]);  
   }
     
	 
	if (empty($_POST["sub3"])) 
		$read="Don't send.";
	else
		$read="Send";

   

   
}

function test_input($data) {
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data);
   return $data;
}

?>

<h2>你读了我的邮件吗？</h2>
<p><span class="error">* 必需的字段</span></p>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 
   
   电邮：<input type="email" name="sub2">
   <span class="error">* <?php echo $emailErr;?></span>
   <br><br>
   主题：<input type="text" name="sub1">
   <span class="error">* <?php echo $subjectErr;?></span>
   <br><br>
   <input type="checkbox" name="sub3" value="send" checked="checked" /> 如果对方3天未读邮件，则发送提醒
   <br><br>
   <input type="submit" name="submit" value="追踪"> 
</form>