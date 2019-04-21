<?php
/**
 * SFKNews
 * 私房库 www.sifangku.com 教学项目
 * 
 * @desc 工具的入口文件
 * @copyright 私房库 www.sifangku.com 保留所有版权
 * @author 孙胜利 www.sunshengli.com
 * @version 1.01
 * 
 **/
//令牌，防止被包含的文件被直接执行
define('SITE','sifangku.com');
//当前的模块
define('MODULE','Tool');
//URL模式
define('URL_MODE',1);
//引入项目的启动程序
include 'Source/Conf/Action.inc.php';