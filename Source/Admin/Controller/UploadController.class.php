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
use Common\Upload;
if(!defined('SITE')) exit('Access Denied');
class UploadController extends Controller {
	public function __construct(){
		parent::__construct();
	}
	public function image(){
		$this->view->display();
	}
	public function upload(){
		$config=array(
				'inputName'=>'file',
				//'saveName'=>'',
				'savePath'=>'./Uploads/thumbnail'.date('/Y/m/d')
		);
		$upload=new Upload($config);
		if(!$upload->upload()){
			exit('{"error":{"message":"'.$upload->getError().'"}}');
		}else{
			echo ':))';
		}
	}
	
}