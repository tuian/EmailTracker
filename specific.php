<?php
require_once("config.php");

$cID=$cHashID=$cIP=$cBrowser=$cUnread=$cAvailable=null;
$jobResult=$sqlHandler->query("select * from job");
while(list($cID,$cHashID,$cIP,$cBrowser,$cUnread,$cAvailable)=$jobResult->fetch_row())	//list 
{	
	$regex="/".$cHashID."/i";
$readHandle=fopen(logPath,"r") or die("log文件打开失败");//access.log
$writeHandle=fopen("img/$cHashID/specific.log","w") or die("特定日志文件复制失败");//specific.log

while(!feof($readHandle)){
	$logBuffer=fgets($readHandle);

 if (preg_match($regex, $logBuffer)) //包含$regex的
        fwrite($writeHandle,$logBuffer);
}
fclose($readHandle);
fclose($writeHandle);

}

$jobResult->close();
$sqlHandler->close();

?>