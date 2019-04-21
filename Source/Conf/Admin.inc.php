<?php
use Common\Url;
use Common\Tool;
use Common\Power;
/**
 * SFKNews
 * 私房库 www.sifangku.com 教学项目
 *
 * @desc Admin模块专用代码
 * @copyright 私房库 www.sifangku.com 保留所有版权
 * @author 孙胜利 www.sunshengli.com
 * @version 1.0
 *
 **/

if(!defined('SITE')) exit('Access Denied');
if((Url::getC()=='Manage' && Url::getMethod()=='login') || (Url::getC()=='Manage' && Url::getMethod()=='isShowVerify')){
	//不需要登录
	if(Tool::isLoginManage()){
		Tool::skip(Url::getUrl('/admin.php/index/index'));
	}
}else{
	//是否登录
	if(!Tool::isLoginManage()){
		Tool::skip(Url::getUrl('/admin.php/manage/login'));
	}
}
if($powerArr=Power::get(MODULE,Url::getC(),Url::getMethod())){
	if(!Power::has($_SESSION['power'],$powerArr['pmark'])){
		exit('权限不足.');
	}
}
