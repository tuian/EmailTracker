# EmailTracker
An EmailTracker based on PHP.


* Chinese and English support and readme are available.
* 中文和英文的支持和说明可用。

#English Tutorial#
What's this?
====
This is a PHP program which is similar to [ifread](http://www.ifread.com/),an Email Track program.

How does it work
====
A `<img>` will refer to other resources like pictures while some Email will try to load the images by default.

Important disclaimer
====
I am a newbee to PHP and know barely nothing about JS, CSS. 
So the efficiency of my algorithm is pretty bad and the interface is very ancient.
About privacy and interpersonal relationship: it's easy to get your UA; please don't abuse this program.

Prerequisites
====
Support Windows and Linux server with PHP, MySQL.<br>
Reconmmend Windows with XAMPP or Linux with LNMP/LAMP.<br>
PHP7 is better.<br>

Quick start guide
====
1.Prepare your webserver [xampp](http://www.xampps.com/) or LNMP<br>
Copy all the files into your webserver's root directory.<br>
**ATTENTION:**<br>
Linux server please make sure these files are 755 and owned by webserver account(like www, www-data, etc.)<br>
Modify `config.php` and change the parameters.<br>
```
dbUser	//username of your MySQL. Usually it will be root
dbPass	//Password.
logPath	//Path of your webserver's log
mailHost	//SMTP server
mailPort	//Port
mailUsername	//Username(example@example.com)
mailPassword	//Passowrd
mailFromName	//Your name
cronTime	//Time
```
2.
set cron(Linux) or Task Scheduler(Windows) based on cronTime.
3.

Troubleshooting
====
Your should set your timezone corretly. For php.ini,<br>
`date.timezone =Asia/ShangHai`<br>
If you failed to send email,<br>
`allow_url_fopen = On`<br>
`extension=php_openssl.dll`<br>
Linux server may have to modify a param for PHP to read files elsewhere.<br>
`open_basedir = .:/home`<br>
If you cannot open PHPMyadmin after last action, please:<br>
`session.save_path = "/tmp"`

License
====
Publish under GPL v3.<br>
Thanks to PHPMailer and other open source projects!



----------




#中文说明#

这是什么？
====
一个基于PHP的、非常简单的类似[阅否](http://www.ifread.com/)的电子邮件追踪程序

原理
====
`<img>`标签会引用外部资源、有些邮箱默认加载图片。

免责声明
====
本人是PHP新手，并且不太会前端，所以算法效率很低、界面很丑的……
关于隐私、人际关系等：UA是很容易就获取的、请勿滥用。


使用条件
====
Windows或Linux服务器均可。
Windows推荐使用XAMPP，Linux使用LNMP和LAMP均可。推荐使用PHP7


部署方法
===
1.安装[xampp](http://www.xampps.com/)，或者LNMP等
确保Web服务器和PHP、MySQL是处于运行的状态
将全部文件复制到Web服务器的根目录（XAMPP为C:\xampp\htdocs），Linux要确保权限为755、属主为www
修改`config.php`中的参数并<br>
```
dbUser	//数据库用户名，通常为root
dbPass	//数据库密码
logPath		//日志文件的路径
mailHost	//SMTP服务器地址
mailPort	//端口
mailUsername	//用户名(example@example.com)
mailPassword	//密码
mailFromName	//发件人姓名
cronTime	//任务计划时间间隔
```
2.设置计划任务为cronTime的时间间隔（Linux为cron）<br>

疑难解答
====
要注意的是，PHP.ini要设置对应你的时区：
`date.timezone =Asia/ShangHai`
如果出现发送邮件失败，需要
`allow_url_fopen = On`
`extension=php_openssl.dll`
Linux主机需要修改php.ini来使得php能够读取web目录以外的文件,并且额外需要注意权限的问题（推荐755）
`open_basedir = .:/home`
如果修改之后出现phpmyadmin启动失败，则需要在php.ini里接着修改
`session.save_path = "/tmp"`

进展
====
这个小东西已经可以部署啦！基本功能已经实现。目前已经完成的功能有：
* 跟踪用户邮件，并且能够区分不同的用户（根据邮箱和主题的Hash）
* 当三天未读之后，会向用户分发送未读提醒；当邮件被阅读之后，用户会收到提醒邮件
* 邮件中点击删除追踪...


许可证
=====
以GPLv3发布。感谢PHPMailer和其他开源项目！



