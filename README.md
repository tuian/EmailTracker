# EmailTracker
An EmailTracker based on PHP.
使用方法
===
下载[xampp](http://www.xampps.com/)，默认路径安装。之后打开（管理员权限）<br>
启动Apache和MySQL，PHP切换选择到PHP7（最好是PHP7）<br>
将整个软件仓库的内容拷贝到C:\xampp\htdocs下<br>
浏览器打开localhost即可看到界面！（和JSP有点类似嘛）

GitHub使用方法
====
比较“弱智”的办法是使用[GitHub Desktop](https://desktop.github.com/)，官网如果慢就使用[这个](http://download.csdn.net/detail/u013929731/9371385)<br>
之后Clone or download选择Open In Desktop就可以Clone到本地。
然后你进行一些修改，包括添加文件什么的，可以commit（就是说说你这次改了什么），之后点击Sync就可以同步到网上<br>
如果修改比较大，觉得有必要合并到主线，那就pull request<br>
PS,开发版本会在dev这个分支下（Development）,目前稳定的会放在master分支下！<br>


进展
====
2016-10-19  更新<br>
这个小东西已经可以部署啦！基本功能已经实现。目前已经完成的功能有：
* 跟踪用户邮件，并且能够区分不同的用户（根据邮箱和主题的Hash）<br>
* 当三天未读之后，会向用户分发送未读提醒；当邮件被阅读之后，用户会收到提醒邮件<br>
2016-10-20  更新<br>
* 邮件中点击删除追踪...<br>
接下来要开发的内容....<br>注册用户

Bug修复
====
修复了错误设置datatime和int类型的bug

部署方法
====
复制到Web服务器的根目录（XAMPP为C:\xampp\htdocs），修改`config.php`中的部分参数<br>
要注意的是，PHP.ini要如下设置：<br>
`date.timezone =Asia/ShangHai`<br>
如果出现发送邮件失败，需要<br>
`allow_url_fopen = On`<br>
`extension=php_openssl.dll`<br>

其他
====
等到你毕业之后，我打算把这个以GPL开源出去，没问题吧！


License
=====
Publish under GPL v3.
Thanks to PHPMailer and other open source projects!