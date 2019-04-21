<?php
/**
 * SFKNews
 * 私房库 www.sifangku.com 教学项目
 *
 * @desc 验证码控制器
 * @copyright 私房库 www.sifangku.com 保留所有版权
 * @author 孙胜利 www.sunshengli.com
 * @version 1.01
 *
 **/
namespace Tool\Controller;
use Common\Controller;
if(!defined('SITE')) exit('Access Denied');
class JsController extends Controller {
	public function __construct(){
		parent::__construct();
	}
	public function fileManager(){
		$this->view->display();
	}
	
}