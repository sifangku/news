<?php
/**
 * SFKNews
 * 私房库 www.sifangku.com 教学项目
 *
 * @desc 控制器工厂
 * @copyright 私房库 www.sifangku.com 保留所有版权
 * @author 孙胜利 www.sunshengli.com
 * @version 1.01
 *
 **/
namespace Common;
if(!defined('SITE')) exit('Access Denied');
class ControllerFactory extends Factory{
	static function create($type=null){
		'Admin\Controller\IndexController';
		$controller='\\'.MODULE.'\\Controller\\'.Url::getC(true);
		return parent::create($controller);
	}
}