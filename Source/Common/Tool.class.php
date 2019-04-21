<?php
/**
 * SFKNews
 * 私房库 www.sifangku.com 教学项目
 *
 * @desc 工具类
 * @copyright 私房库 www.sifangku.com 保留所有版权
 * @author 孙胜利 www.sunshengli.com
 * @version 1.01
 *
 **/
namespace Common;
if(!defined('SITE')) exit('Access Denied');
class Tool {
	static function isLoginManage(){
		if(isset($_SESSION['name'])){
			return true;
		}else{
			return false;
		}
	}
	static function isLoginMember(){
		return isset($_SESSION['member']);
	}
	static function skip($url){
		header("Location:{$url}");
	}
}