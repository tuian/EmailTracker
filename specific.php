<?php

//¸ºÔð¸´ÖÆ¶ÔÓ¦ÈÕÖ¾µÄhashIDµ½/img/hashID/ÏÂ
require_once("config.php");


//¶¨Òå±äÁ¿

$cID=$cHashID=$cIP=$cBrowser=$cUnread=$cAvailable=null;

$jobResult=$sqlHandler->query("select * from job");
while(list($cID,$cHashID,$cIP,$cBrowser,$cUnread,$cAvailable)=$jobResult->fetch_row())	//list °ÑÊý×éÖÐµÄÖµ¸³Öµ¸ø±äÁ¿
{	
	$regex="/".$cHashID."/i";
$readHandle=fopen(logPath,"r") or die("ÎÄ¼þ´ò¿ªÊ§°Ü");//´ò¿ªÈÕÖ¾access.log
$writeHandle=fopen("img/$cHashID/specific.log","w") or die("ÎÄ¼þ´ò¿ªÊ§°Ü");//´ò¿ªÌØ¶¨ÈÕÖ¾×¼±¸Ð´Èëspecific.log

while(!feof($readHandle)){
	$logBuffer=fgets($readHandle);

 if (preg_match($regex, $logBuffer)) //Èç¹û×Ö·û´®ÖÐ°üº¬Windows NT 6.0×Ö·ûµÄ»°
        fwrite($writeHandle,$logBuffer);
}
fclose($readHandle);
fclose($writeHandle);
//·ÖÎöÓÃ»§Ä¿Â¼ÏÂµÄÈÕÖ¾ÐÅÏ¢£¬´æÈëtracker±í

}

$jobResult->close();

$sqlHandler->close();



?>