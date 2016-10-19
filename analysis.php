<?php
require_once("config.php");
require_once("qqwry.php");
//复制指定hashID的日志到/img/hashID下

function copyLog($cpyHashID)
{
$regex="/".$cpyHashID."/i";
$readHandle=fopen(logPath,"r") or die("文件打开失败");//打开日志access.log
$writeHandle=fopen("img/$cpyHashID/specific.log","w") or die("写入文件打开失败");//打开特定日志准备写入specific.log


while(!feof($readHandle))
{
	$logBuffer=fgets($readHandle);

 if (preg_match($regex, $logBuffer)) //如果字符串中包含Windows NT 6.0字符的话
        fwrite($writeHandle,$logBuffer);
		
}

fclose($readHandle);
fclose($writeHandle);
}

function analysis($HashID,$IP,$Browser)
{
	

$fileHandle=fopen(__DIR__."/img/$HashID/specific.log","r") or die("文件打开失败");

$sqlInsertHandler=new mysqli("localhost",dbUser,dbPass,"emailtracker");
$sqlInsertHandler->query("set names utf8");
if(mysqli_connect_errno())
	die("连接失败".mysqli_connect_errno());

$ipRegex="((?:(?:25[0-5]|2[0-4]\d|((1\d{2})|([1-9]?\d)))\.){3}(?:25[0-5]|2[0-4]\d|((1\d{2})|([1-9]?\d))))";
$dateRegex="#(?i)(?<=\[)(.*)(?=\])#";
$sIP=$sLoc=$sTime=$ssOS=$sBrowser=null;

while(!feof($fileHandle)){

	$logBuffer=fgets($fileHandle);//读取一行

	//IP
	if(preg_match($ipRegex,$logBuffer,$ipMatches))
		$sIP=$ipMatches[0];
	
	//地理位置（在线）echo "地理位置是：".getLocOnline($ipMatches[0]);
	//地理位置（离线）
	$sLoc=getIPOffline($sIP);
	
	//时间
	if(preg_match($dateRegex,$logBuffer,$dateMatches))
		$sTime=date("Y-m-d G:i:s", strtotime($dateMatches[1]))." ";  //nginx格式转换成常规格式
	//操作系统
        if (preg_match('/Windows NT 6.0/i', $logBuffer)) 
            $sOS = "Windows Vista";
         elseif (preg_match('/Windows NT 6.1/i', $logBuffer)) 
            $sOS = "Windows 7";
         elseif (preg_match('/Windows NT 6.2/i', $logBuffer)) 
            $sOS = "Windows 8";
         elseif (preg_match('/Windows NT 6.3/i', $logBuffer)) 
            $sOS = "Windows 8.1";
         elseif (preg_match('/Windows NT 10.0/i', $logBuffer)) 
            $sOS = "Windows 10";
         elseif (preg_match('/Windows NT 5.1/i', $logBuffer)) 
            $sOS = "Windows XP";
         elseif (preg_match('/Windows NT 5.2/i', $logBuffer) && preg_match('/Win64/i', $logBuffer)) 
            $sOS = "Windows XP 64 bit";
		elseif (preg_match('/Windows NT 5.0/i', $logBuffer)) 
            $sOS = "Windows 2000 Professional";
         elseif (preg_match('/Android ([0-9.]+)/i', $logBuffer, $matches)) 
            $sOS = "Android " . $matches[1];
         elseif (preg_match('/iPhone sOS ([_0-9]+)/i', $logBuffer, $matches)) 
            $sOS = 'iPhone ' . $matches[1];
		 elseif (preg_match('/iPad/i', $logBuffer)) 
            $sOS = "iPad";
		elseif (preg_match('/Mac sOS X ([_0-9]+)/i', $logBuffer, $matches)) 
            $sOS = 'Mac sOS X ' . $matches[1];
		elseif (preg_match('/Windows Phone ([_0-9]+)/i', $logBuffer, $matches)) 
            $sOS = 'Windows Phone ' . $matches[1];
         else 
            $sOS = '未知操作系统';
        
		//浏览器
        if (preg_match('#(Camino|Chimera)[ /]([a-zA-Z0-9.]+)#i', $logBuffer, $matches)) {
            $sBrowser = 'Camino ' . $matches[2];
        } elseif (preg_match('#SE 2([a-zA-Z0-9.]+)#i', $logBuffer, $matches)) {
            $sBrowser = '搜狗浏览器 2' . $matches[1];
        } elseif (preg_match('#360([a-zA-Z0-9.]+)#i', $logBuffer, $matches)) {
            $sBrowser = '360浏览器 ' . $matches[1];
        } elseif (preg_match('#Maxthon( |\/)([a-zA-Z0-9.]+)#i', $logBuffer, $matches)) {
		$sBrowser = 'Maxthon ' . $matches[2];}
		elseif (preg_match('#Edge( |\/)([a-zA-Z0-9.]+)#i', $logBuffer, $matches)) {
            $sBrowser = 'Edge ' . $matches[2];
        } 
		elseif (preg_match('#MicroMessenger/([a-zA-Z0-9.]+)#i', $logBuffer, $matches) || 
		preg_match('#Mobile MQQsBrowser#i', $logBuffer, $matches)) {
            $sBrowser = '手机QQ 或 微信  ' . $matches[1];
        }	
		elseif (preg_match('#Chrome/([a-zA-Z0-9.]+)#i', $logBuffer, $matches)) {
            $sBrowser = 'Chrome ' . $matches[1];
        } elseif (preg_match('#XiaoMi/MiuisBrowser/([0-9.]+)#i', $logBuffer, $matches)) {
            $sBrowser = '小米浏览器 ' . $matches[1];
        } elseif (preg_match('#Safari/([a-zA-Z0-9.]+)#i', $logBuffer, $matches)) {
            $sBrowser = 'Safari ' . $matches[1];
        } elseif (preg_match('#opera mini#i', $logBuffer)) {
            preg_match('#Opera/([a-zA-Z0-9.]+)#i', $logBuffer, $matches);
            $sBrowser = 'Opera Mini ' . $matches[1];
        } elseif (preg_match('#Opera.([a-zA-Z0-9.]+)#i', $logBuffer, $matches)) {
            $sBrowser = 'Opera ' . $matches[1];
        } elseif (preg_match('#TencentTraveler ([a-zA-Z0-9.]+)#i', $logBuffer, $matches)) {
            $sBrowser = '腾讯TT浏览器 ' . $matches[1];
        } elseif (preg_match('#UCWEB([a-zA-Z0-9.]+)#i', $logBuffer, $matches)) {
            $sBrowser = 'UCWEB ' . $matches[1];
        }elseif (preg_match('#wp-(iphone|android)/([a-zA-Z0-9.]+)#i', $logBuffer, $matches)) {
            $sBrowser = 'WordPress客户端 ' . $matches[1];
        } elseif (preg_match('#MSIE ([a-zA-Z0-9.]+)#i', $logBuffer, $matches)) {
            $sBrowser = 'Internet Explorer ' . $matches[1];
        } 
			elseif (preg_match('#Trident/([a-zA-Z0-9.]+)#i', $logBuffer, $matches)) {
            $sBrowser = 'Internet Explorer 11' ;
        }
		elseif (preg_match('#Outlook Mail ([a-zA-Z0-9.]+)#i', $logBuffer, $matches)) {
            $sBrowser = 'Windows 10 邮件应用'. $matches[1] ;
        }		
		elseif (preg_match('#(Firefox|Phoenix|Firebird|BonEcho|GranParadiso|Minefield|Iceweasel)/([a-zA-Z0-9.]+)#i', $logBuffer, $matches)) {
            $sBrowser = 'Firefox ' . $matches[2];
        } 
		elseif($logBuffer=="")
			break;
		else {
            $sBrowser = '未知浏览器';
        }
        //echo $sOS . "  |  " . $sBrowser."<br>";
		
	//只有运行IE的Windows 不能这么提取。这是提取第一对括号中的内容
	//echo "操作系统原始信息".substr($logBuffer,strpsOS($logBuffer,"(")+1,strpsOS($logBuffer,")")-strpsOS($logBuffer,"(")-1);

//echo "总的信息 ".$sIP.$sLoc.$sTime.$ssOS.$sBrowser."<br>";
//删除

//插入到表tracker中
//echo $sIP." - ".$IP." | ".$sBrowser." - ".$Browser."<br>";
if($sIP!=$IP and $sBrowser!=$Browser){
	$sql="insert into tracker values (0,'$HashID','$sTime','$sIP','$sLoc','$sBrowser','$sOS')";
	if(!$sqlInsertHandler->query($sql))
		echo "插入失败 ".$sqlInsertHandler->error.$sqlInsertHandler->errno;
}


}

	fclose($fileHandle);
	
	
	
	
	
	
	
	
	
}

?>