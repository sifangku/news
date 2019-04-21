<?php
/**
 * SFKNews
 * 私房库 www.sifangku.com 教学项目
 *
 * @desc 配置项
 * @copyright 私房库 www.sifangku.com 保留所有版权
 * @author 孙胜利 www.sunshengli.com
 * @version 1.01
 *
 **/
if(!defined('SITE')) exit('Access Denied');
//整个项目在操作系统中的绝对路径
define('PATH_APP',dirname(dirname(dirname(__FILE__))));
//项目的核心程序所在的绝对路径
define('PATH_SOURCE',PATH_APP.'/'.'Source');
//当前运行的模块在操作系统中的路径
define('PATH_MODULE',PATH_SOURCE.'/'.MODULE);
//公共类在操作系统中的绝对路径
define('PATH_COMMON',PATH_SOURCE.'/Common');
//当前模块的View在操作系统中的绝对路径
define('PATH_VIEW',PATH_MODULE.'/View');
//当前皮肤的绝对路径
defined('PATH_VIEW_SKIN') ? null : define('PATH_VIEW_SKIN',PATH_VIEW.'/Default');
//缓存目录
define('PATH_CACHE',PATH_SOURCE.'/Cache');
//控制器$_GET形式访问的参数名称
define('INDEX_CONTROLLER','c');
//控制器里的方法$_GET形式访问的参数名称
define('INDEX_METHOD','m');
//URL模式，0表示普通模式，1表示pathinfo模式
defined('URL_MODE') ? null : define('URL_MODE',0);
//URL路由
defined('URL_ROUTER_ON') ? null : define('URL_ROUTER_ON',false);
//URL路由规则文件
defined('URL_ROUTE_RULES') ? null : define('URL_ROUTE_RULES',PATH_SOURCE.'/Conf/Route.'.MODULE.'.inc.php');
//session
defined('SESSION') ? null : define('SESSION',true);
//用户名密码输错次数  触发显示验证码
defined('COUNT_VERIFY') ? null : define('COUNT_VERIFY',5);

//获取程序在web根目录下面的位置
if(strpos($_SERVER['SCRIPT_NAME'],'/',1)){
	define('BASIC_DIR',dirname($_SERVER['SCRIPT_NAME']));
}else{
	define('BASIC_DIR','');
}

//getUrl方法获取 某入口的某控制器的某方法的 url地址 传参用的常量	开始
define('C',INDEX_CONTROLLER);
define('M',INDEX_METHOD);
define('E','entry');
//getUrl方法获取 某入口的某控制器的某方法的 url地址 传参用的常量	结束
//当前入口的脚本文件名
define('ENTRY',basename($_SERVER['SCRIPT_NAME']));

//数据库配置信息
define('DB_HOST','localhost');
define('DB_USER','root');
define('DB_PASSWORD','12345678');
define('DB_DATABASE','php2');
define('DB_PORT',3306);
define('DB_CHARSET','utf8');
define('DB_PREFIX','sfk_');