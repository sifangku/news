<?php
/**
 * SFKNews
 * 私房库 www.sifangku.com 教学项目
 *
 * @desc 文件管理
 * @copyright 私房库 www.sifangku.com 保留所有版权
 * @author 孙胜利 www.sunshengli.com
 * @version 1.0
 *
 **/
namespace Admin\Controller;
use Common\Controller;
use Common\FileManager;
if(!defined('SITE')) exit('Access Denied');
class FilemanagerController extends Controller {
	public function __construct(){
		FileManager::setBasedir(PATH_APP);
		parent::__construct();
	}
	public function index(){
		header('Content-type:text/html;charset=utf-8');
		$path = is_null(get('path')) ? './Uploads' : '.'.get('path');
		$dirArr=explode('/',trim(FileManager::getWebPath($path),'/'));
		$dirList=array();
		foreach ($dirArr as $key=>$value){
			$dirList[$key]['path_web']='/'.implode('/',array_slice($dirArr,0,$key+1));
			$dirList[$key]['name']=$value;
		}
		array_unshift($dirList,array('path_web'=>'/','name'=>'程序根目录'));
		$this->view->setData('cwd',$dirList);
		if($pathArr=FileManager::read($path)){
			$this->view->setData('files',$pathArr);
		}else{
			$this->view->setData('files',array());
		}
		$this->view->display();
	}
	
}