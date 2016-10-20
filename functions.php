<?php

//一些通用的功能函数全部在此定义
//////////////qqwry



/**  
* IP 地理位置查询类  
*   
* @author joyphper  
* @version 1.0  
* @copyright 2010 joyphper.net  
*/ 
 
class IpLocation {  
     /**  
      * QQWry.Dat文件指针  
      *  
      * @var resource  
      */ 
 
     private $fp;  
 
     /**  
      * 第一条IP记录的偏移地址  
      *  
      * @var int  
      */ 
 
     private $firstip;  
 
     /**  
      * 最后一条IP记录的偏移地址  
      *  
      * @var int  
      */ 
 
     private $lastip;  
 
     /**  
      * IP记录的总条数（不包含版本信息记录）  
      *  
      * @var int  
      */ 
       
     private $totalip;  
 
    /**  
      * 构造函数，打开 QQWry.Dat 文件并初始化类中的信息  
      *  
      * @param string $filename  
      * @return IpLocation  
      */ 
 
     public function __construct($filename = "QQWry.Dat") {  
 
         $this->fp = 0;  
 
         if (($this->fp = fopen($filename, 'rb')) !== false) {  
 
             $this->firstip = $this->getlong();  
 
             $this->lastip = $this->getlong();  
 
             $this->totalip = ($this->lastip - $this->firstip) / 7;  
 
             //注册析构函数，使其在程序执行结束时执行  
 
             register_shutdown_function(array(&$this, '__destruct'));  
               
         }  
 
     }  
 
     /**  
 
      * 析构函数，用于在页面执行结束后自动关闭打开的文件。  
      *  
      */ 
 
    public function __destruct() {  
 
         if ($this->fp) {  
 
             fclose($this->fp);  
 
         }  
 
         $this->fp = 0;  
 
     }  
       
     /**  
 
      * 返回读取的长整型数  
      *  
      * @access private  
      * @return int  
      */ 
 
    private function getlong() {  
 
         //将读取的little-endian编码的4个字节转化为长整型数  
 
         $result = unpack('Vlong', fread($this->fp, 4));  
 
         return $result['long'];  
 
     }  
 
     /**  
 
      * 返回读取的3个字节的长整型数  
      *  
      * @access private  
      * @return int  
      */ 
 
     private function getlong3() {  
 
         //将读取的little-endian编码的3个字节转化为长整型数  
 
         $result = unpack('Vlong', fread($this->fp, 3).chr(0));  
 
         return $result['long'];  
 
     }  
 
     /**  
      * 返回压缩后可进行比较的IP地址  
      *  
      * @access private  
      * @param string $ip  
      * @return string  
      */ 
 
     private function packip($ip) {  
          
         // 将IP地址转化为长整型数，如果在PHP5中，IP地址错误，则返回False，  
           
         // 这时intval将Flase转化为整数-1，之后压缩成big-endian编码的字符串  
 
         return pack('N', intval(ip2long($ip)));  
 
     }  
 
     /**  
 
      * 返回读取的字符串  
      *  
      * @access private  
      * @param string $data  
      * @return string  
      */ 
 
     private function getstring($data = "") {  
 
         $char = fread($this->fp, 1);  
 
         while (ord($char) > 0) {        // 字符串按照C格式保存，以结束  
 
             $data .= $char;             // 将读取的字符连接到给定字符串之后  
 
             $char = fread($this->fp, 1);  
 
         }  
 
         return $data;  
 
     }  
 
     /**  
      * 返回地区信息  
      *  
      * @access private  
      * @return string  
      */ 
 
     private function getarea() {  
 
         $byte = fread($this->fp, 1);    // 标志字节  
 
         switch (ord($byte)) {  
 
             case 0:                     // 没有区域信息  
 
                 $area = "";  
 
                 break;  
 
             case 1:  
 
             case 2:                     // 标志字节为1或2，表示区域信息被重定向  
 
                 fseek($this->fp, $this->getlong3());  
 
                 $area = $this->getstring();  
 
                 break;  
 
             default:                    // 否则，表示区域信息没有被重定向  
 
                 $area = $this->getstring($byte);  
 
                 break;  
 
         }  
 
         return $area;  
 
     }  
 
     /**  
      * 根据所给 IP 地址或域名返回所在地区信息  
      *  
      * @access public  
      * @param string $ip  
      * @return array  
      */ 
 
     public function getlocation($ip) {  
 
         if (!$this->fp) return null;            // 如果数据文件没有被正确打开，则直接返回空  
 
         $location['ip'] = gethostbyname($ip);   // 将输入的域名转化为IP地址  
 
         $ip = $this->packip($location['ip']);   // 将输入的IP地址转化为可比较的IP地址  
 
                                                 // 不合法的IP地址会被转化为255.255.255.255  
 
         // 对分搜索  
 
         $l = 0;                         // 搜索的下边界  
 
         $u = $this->totalip;            // 搜索的上边界  
 
         $findip = $this->lastip;        // 如果没有找到就返回最后一条IP记录（QQWry.Dat的版本信息）  
 
         while ($l <= $u) {              // 当上边界小于下边界时，查找失败  
 
             $i = floor(($l + $u) / 2); // 计算近似中间记录  
 
             fseek($this->fp, $this->firstip + $i * 7);  
 
            $beginip = strrev(fread($this->fp, 4));     // 获取中间记录的开始IP地址  
 
             // strrev函数在这里的作用是将little-endian的压缩IP地址转化为big-endian的格式  
 
             // 以便用于比较，后面相同。  
 
             if ($ip < $beginip) {       // 用户的IP小于中间记录的开始IP地址时  
 
                 $u = $i - 1;            // 将搜索的上边界修改为中间记录减一  
 
             }  
 
             else {  
 
                 fseek($this->fp, $this->getlong3());  
 
                 $endip = strrev(fread($this->fp, 4));   // 获取中间记录的结束IP地址  
 
                 if ($ip > $endip) {     // 用户的IP大于中间记录的结束IP地址时  
 
                     $l = $i + 1;        // 将搜索的下边界修改为中间记录加一  
 
                 }  
 
                 else {                  // 用户的IP在中间记录的IP范围内时  
 
                     $findip = $this->firstip + $i * 7;  
 
                     break;              // 则表示找到结果，退出循环  
 
                 }  
 
             }  
 
         }  
 
         //获取查找到的IP地理位置信息  
 
         fseek($this->fp, $findip);  
 
         $location['beginip'] = long2ip($this->getlong());   // 用户IP所在范围的开始地址  
 
         $offset = $this->getlong3();  
 
         fseek($this->fp, $offset);  
 
         $location['endip'] = long2ip($this->getlong());     // 用户IP所在范围的结束地址  
 
         $byte = fread($this->fp, 1);    // 标志字节  
 
         switch (ord($byte)) {  
 
             case 1:                     // 标志字节为1，表示国家和区域信息都被同时重定向  
 
                 $countryOffset = $this->getlong3();         // 重定向地址  
 
                 fseek($this->fp, $countryOffset);  
 
                 $byte = fread($this->fp, 1);    // 标志字节  
 
                 switch (ord($byte)) {  
 
                     case 2:             // 标志字节为2，表示国家信息又被重定向  
 
                         fseek($this->fp, $this->getlong3());  
 
                         $location['country'] = $this->getstring();  
 
                         fseek($this->fp, $countryOffset + 4);  
 
                         $location['area'] = $this->getarea();  
 
                         break;  
 
                     default:            // 否则，表示国家信息没有被重定向  
 
                         $location['country'] = $this->getstring($byte);  
 
                         $location['area'] = $this->getarea();  
 
                         break;  
 
                 }  
 
                 break;  
 
             case 2:                     // 标志字节为2，表示国家信息被重定向  
 
                 fseek($this->fp, $this->getlong3());  
 
                 $location['country'] = $this->getstring();  
 
                 fseek($this->fp, $offset + 8);  
 
                 $location['area'] = $this->getarea();  
 
                 break;  
 
             default:                    // 否则，表示国家信息没有被重定向  
 
                 $location['country'] = $this->getstring($byte);  
 
                 $location['area'] = $this->getarea();  
 
                 break;  
 
         }  
 
         if ($location['country'] == " CZ88.NET") { // CZ88.NET表示没有有效信息  
 
             $location['country'] = "未知";  
 
         }  
 
         if ($location['area'] == " CZ88.NET") {  
 
             $location['area'] = "";  
 
         }  
 
         return $location;  
 
     }  
}  














//

//IP

function getIP()
{
    static $realip;
    if (isset($_SERVER)){
        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
            $realip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
            $realip = $_SERVER["HTTP_CLIENT_IP"];
        } else {
            $realip = $_SERVER["REMOTE_ADDR"];
        }
    } else {
        if (getenv("HTTP_X_FORWARDED_FOR")){
            $realip = getenv("HTTP_X_FORWARDED_FOR");
        } else if (getenv("HTTP_CLIENT_IP")) {
            $realip = getenv("HTTP_CLIENT_IP");
        } else {
            $realip = getenv("REMOTE_ADDR");
        } 
    }
    return $realip;
}
function getUA()
{
	$UA=$_SERVER['HTTP_USER_AGENT'];
if (preg_match('#(Camino|Chimera)[ /]([a-zA-Z0-9.]+)#i', $UA, $matches)) {
            $browser = 'Camino ' . $matches[2];
        } elseif (preg_match('#SE 2([a-zA-Z0-9.]+)#i', $UA, $matches)) {
            $browser = '搜狗浏览器 2' . $matches[1];
        } elseif (preg_match('#360([a-zA-Z0-9.]+)#i', $UA, $matches)) {
            $browser = '360浏览器 ' . $matches[1];
        } elseif (preg_match('#Maxthon( |\/)([a-zA-Z0-9.]+)#i', $UA, $matches)) {
		$browser = 'Maxthon ' . $matches[2];}
		elseif (preg_match('#Edge( |\/)([a-zA-Z0-9.]+)#i', $UA, $matches)) {
            $browser = 'Edge ' . $matches[2];
        } 
		elseif (preg_match('#MicroMessenger/([a-zA-Z0-9.]+)#i', $UA, $matches) || 
		preg_match('#Mobile MQQBrowser#i', $UA, $matches)) {
            $browser = '手机QQ 或 微信  ' . $matches[1];
        }
		
		elseif (preg_match('#Chrome/([a-zA-Z0-9.]+)#i', $UA, $matches)) {
            $browser = 'Chrome ' . $matches[1];
        } elseif (preg_match('#XiaoMi/MiuiBrowser/([0-9.]+)#i', $UA, $matches)) {
            $browser = '小米浏览器 ' . $matches[1];
        } elseif (preg_match('#Safari/([a-zA-Z0-9.]+)#i', $UA, $matches)) {
            $browser = 'Safari ' . $matches[1];
        } elseif (preg_match('#opera mini#i', $UA)) {
            preg_match('#Opera/([a-zA-Z0-9.]+)#i', $UA, $matches);
            $browser = 'Opera Mini ' . $matches[1];
        } elseif (preg_match('#Opera.([a-zA-Z0-9.]+)#i', $UA, $matches)) {
            $browser = 'Opera ' . $matches[1];
        } elseif (preg_match('#TencentTraveler ([a-zA-Z0-9.]+)#i', $UA, $matches)) {
            $browser = '腾讯TT浏览器 ' . $matches[1];
        } elseif (preg_match('#UCWEB([a-zA-Z0-9.]+)#i', $UA, $matches)) {
            $browser = 'UCWEB ' . $matches[1];
        }elseif (preg_match('#wp-(iphone|android)/([a-zA-Z0-9.]+)#i', $UA, $matches)) {
            $browser = 'WordPress客户端 ' . $matches[1];
        } elseif (preg_match('#MSIE ([a-zA-Z0-9.]+)#i', $UA, $matches)) {
            $browser = 'Internet Explorer ' . $matches[1];
        } 
			elseif (preg_match('#Trident/([a-zA-Z0-9.]+)#i', $UA, $matches)) {
            $browser = 'Internet Explorer 11' ;
        }
		elseif (preg_match('#Outlook Mail ([a-zA-Z0-9.]+)#i', $UA, $matches)) {
            $browser = 'Windows 10 邮件应用'. $matches[1] ;
        }
		
		elseif (preg_match('#(Firefox|Phoenix|Firebird|BonEcho|GranParadiso|Minefield|Iceweasel)/([a-zA-Z0-9.]+)#i', $UA, $matches)) {
            $browser = 'Firefox ' . $matches[2];
        } else {
            $browser = '未知浏览器';
        }
return $browser;
}

function getLocOnline($ip)	//使用本地版本的更好...
{
$url="http://ip.taobao.com/service/getIpInfo.php?ip=".$ip;
$ipinfo=json_decode(file_get_contents($url));
if($ipinfo->code=='1'){
return false;
}
$city = $ipinfo->data->country.$ipinfo->data->region.$ipinfo->data->city." ".$ipinfo->data->isp;
return $city;
}


function getIPOffline($IP)
{
$iplocation = new IpLocation();   
$location = $iplocation->getlocation($IP);   
return iconv("GB2312","UTF-8//IGNORE",$location["country"]." ".$location["area"]);
	
}



//日志分析


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

//插入到表tracker中cid和用户相关 cAval控制是否发送过邮件。
//1没发送过 所以要发送
//0发送过  所以不发送、。
//echo $sIP." - ".$IP." | ".$sBrowser." - ".$Browser."<br>";
if($sIP!=$IP and $sBrowser!=$Browser){
	$sql="insert into tracker values (0,'$HashID','$sTime','$sIP','$sLoc','$sBrowser','$sOS')";
	if(!$sqlInsertHandler->query($sql))
		echo "插入失败 ".$sqlInsertHandler->error.$sqlInsertHandler->errno;
}


}

	fclose($fileHandle);
	
	
	
	
	
	
	
	
	
}



function my_del($path)
{
    if(is_dir($path))
    {
            $file_list= scandir($path);
            foreach ($file_list as $file)
            {
                if( $file!='.' && $file!='..')
                {
                    my_del($path.'/'.$file);
                }
            }
            @rmdir($path);  //这种方法不用判断文件夹是否为空,  因为不管开始时文件夹是否为空,到达这里的时候,都是空的     
    return true;
	}
    else
    {
        @unlink($path);    //这两个地方最好还是要用@屏蔽一下warning错误,看着闹心
		return false;
    }
 
}



?>