<?php

//�����ƶ�Ӧ��־��hashID��/img/hashID/��
require_once("config.php");


//�������

$cID=$cHashID=$cIP=$cBrowser=$cUnread=$cAvailable=null;

$jobResult=$sqlHandler->query("select * from job");
while(list($cID,$cHashID,$cIP,$cBrowser,$cUnread,$cAvailable)=$jobResult->fetch_row())	//list �������е�ֵ��ֵ������
{	
	$regex="/".$cHashID."/i";
$readHandle=fopen(logPath,"r") or die("�ļ���ʧ��");//����־access.log
$writeHandle=fopen("img/$cHashID/specific.log","w") or die("�ļ���ʧ��");//���ض���־׼��д��specific.log

while(!feof($readHandle)){
	$logBuffer=fgets($readHandle);

 if (preg_match($regex, $logBuffer)) //����ַ����а���Windows NT 6.0�ַ��Ļ�
        fwrite($writeHandle,$logBuffer);
}
fclose($readHandle);
fclose($writeHandle);
//�����û�Ŀ¼�µ���־��Ϣ������tracker��

}

$jobResult->close();

$sqlHandler->close();



?>