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
use Common\Verify;
if(!defined('SITE')) exit('Access Denied');
class VerifyController extends Controller {
	public function __construct(){
		parent::__construct();
	}
	public function login(){
		$verify=new Verify(array('imageW'=>160,'imageH'=>46,'length'=>4,'fontSize'=>22));
		$verify->entry();
	}
	public function test(){
		var_dump($_SESSION);
	}
	public function test1(){
		session_destroy();
	}
}