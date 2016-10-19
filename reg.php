<?php
$tmp="953438619@qq.com-Ëý¶ÁÁËÓÊ¼þ";
$tmp=md5($tmp);
echo $tmp."<br>";

if(!is_readable($tmp))
{
	mkdir($tmp,true);
	copy("test.gif",$tmp."//test.gif");
}	
else
	echo "dir exists"."<br>";



?>