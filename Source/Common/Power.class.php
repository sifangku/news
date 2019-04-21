<?php
/**
 * SFKNews
 * 私房库 www.sifangku.com 教学项目
 *
 * @desc 权限操作类
 * @copyright 私房库 www.sifangku.com 保留所有版权
 * @author 孙胜利 www.sunshengli.com
 * @version 1.01
 *
 **/
namespace Common;
if(!defined('SITE')) exit('Access Denied');
class Power {
	static private $configAdmin;
	static private $configHome;
	//获取权限标识
	static public function get($module,$controller=null,$method=null){
		switch ($module){
			case 'Admin':
				if(!isset(self::$configAdmin)){
					self::$configAdmin=require_once PATH_SOURCE.'/Conf/Power.Admin.inc.php';
				}
				$config=&self::$configAdmin;
				break;
			case 'Home':
				//...
		}
		if(isset($controller) && isset($method)){
			if(isset($config[$controller][$method])){
				return $config[$controller][$method];
			}else{
				return false;
			}
		}elseif(isset($controller)){
			if(isset($config[$controller])){
				return $config[$controller];
			}else{
				return false;
			}
		}else{
			return $config;
		}
	}
	//添加权限
	static public function add($power,$add){
		return $power | $add;
	}
	//移除权限
	static public function remove($power,$remove){
		return $power & (~$remove);
	}
	//判断给定权限值是否包含某项权限
	static public function has($power,$a){
		/*
		01010010
		00010000
		========
		00010000
		
		01000010
		00010000
		========
		00000000
		
		*/
		return ($power & $a) ? true : false;
	}
}