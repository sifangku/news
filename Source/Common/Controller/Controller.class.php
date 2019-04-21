<?php
/**
 * SFKNews
 * 私房库 www.sifangku.com 教学项目
 *
 * @desc 控制器基类
 * @copyright 私房库 www.sifangku.com 保留所有版权
 * @author 孙胜利 www.sunshengli.com
 * @version 1.01
 *
 **/
namespace Common;
if(!defined('SITE')) exit('Access Denied');
class Controller {
	protected $view;
	protected $model;
	public function __construct(){
		$this->view=new View();
	}
	public function __call($name,$args){
		var_dump($name);
		var_dump(':((');
	}
	protected function skip($param){
		$default=array(
			'status'=>'info',
			'url'=>'http://sifangku.com',
			'message'=>':))',
			'view'=>'skip'
		);
		$param=array_merge($default,$param);
		$this->view->setData('class',$param['status']);
		$this->view->setData('url',$param['url']);
		$this->view->setData('message',$param['message']);
		$this->view->display($param['view']);
	}
}