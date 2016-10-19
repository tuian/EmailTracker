<?php


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




?>