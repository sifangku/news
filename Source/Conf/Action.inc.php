<?php
use Common\Http;
/**
 * SFKNews
 * 私房库 www.sifangku.com 教学项目
 *
 * @desc 项目的核心启动文件
 * @copyright 私房库 www.sifangku.com 保留所有版权
 * @author 孙胜利 www.sunshengli.com
 * @version 1.01
 *
 **/
//检测是否有令牌
if(!defined('SITE')) exit('Access Denied');
if(!function_exists('mysqli_connect')){
	header('Content-type:text/html;charset=utf-8');
	exit('必须开启mysqli扩展.');
}
//项目的配置文件
include 'Conf.inc.php';
//公共函数
include 'Functions.inc.php';
spl_autoload_register('loadAbs');
spl_autoload_register('loadCommonChild');
//开启session
if(SESSION){
	session_set_save_handler(new Common\SessionDeal());
	session_start();
}
//执行各模块的独立php文件
if(file_exists(PATH_SOURCE.'/Conf/'.MODULE.'.inc.php')){
	include PATH_SOURCE.'/Conf/'.MODULE.'.inc.php';
}
try {
	//获取当前应该执行的对应控制器的方法名称
	$method=Common\Url::getMethod();
	//创建指定类型的控制器以及执行指定的方法
	Common\ControllerFactory::create()->$method();
}catch (Exception $e){
// 	Http::sendHttpStatus(404);
// 	exit('Not Found');
	exit($e->getMessage());
}
