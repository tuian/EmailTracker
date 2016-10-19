<?php

//加密密码
$pwd="mypassword";
echo "hashID ".md5($pwd)."<br>";
echo $storePassword=password_hash("mypassword",PASSWORD_DEFAULT);
if (password_verify($pwd,$storePassword)) { 
    echo "密码正确";
} else {  
    echo "密码错误";
}

//var_dump(password_get_info($storePassword));

?>