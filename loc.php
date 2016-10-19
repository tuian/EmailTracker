<?php

function getLoc($ip)
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