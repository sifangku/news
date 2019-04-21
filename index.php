<?php
header('Content-type:text/html;charset=utf-8');
if(!file_exists('Source/Install/install.lock')){
	header('Location:install.php');
}
//令牌，防止被包含的文件被直接执行
define('SITE','sifangku.com');
define('MODULE','Home');
//URL模式
define('URL_MODE',1);
define('URL_ROUTER_ON',true);
include 'Source/Conf/Action.inc.php';