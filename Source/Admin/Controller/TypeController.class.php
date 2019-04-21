<?php
/**
 * SFKNews
 * 私房库 www.sifangku.com 教学项目
 *
 * @desc 栏目管理
 * @copyright 私房库 www.sifangku.com 保留所有版权
 * @author 孙胜利 www.sunshengli.com
 * @version 1.0
 *
 **/
namespace Admin\Controller;
use Common\Controller;
use Common\Url;
use Admin\Model\ArctypeModel;
use Common\Tree;
if(!defined('SITE')) exit('Access Denied');
class TypeController extends Controller {
	public function __construct(){
		$this->model=new ArctypeModel();
		parent::__construct();
	}
	public function index(){
		$tree=new Tree($this->model->select());
		$this->view->setData('arctype',$tree->getAll());
		$this->view->display();
	}
	public function add(){
		if(!empty($_POST)){
			if($this->model->create()){
				if($this->model->add()){
					if(isAjaxRequest()){
						exit('{"status":1,"message":"添加成功."}');
					}else{
						$this->skip(array('status'=>'success','url'=>Url::getUrl(array(E=>'admin.php',C=>'type',M=>'index')),'message'=>'添加成功.'));
					}
				}else{
					$message='栏目添加失败，请联系网站管理员.';
				}
			}else{
				$message=$this->model->getError();
			}
			if(isAjaxRequest()){
				exit('{"status":0,"message":"'.$message.'"}');
			}else{
				$this->skip(array('status'=>'danger','url'=>Url::getUrl(array(E=>'admin.php',C=>'type',M=>'add')),'message'=>$message));
			}
		}
		$tree=new Tree($this->model->select());
		$this->view->setData('arctype',$tree->getAll());
		$this->view->display();
	}
	public function update(){
		$data=$this->model->where('id=?')->bind('i',array(get('id')))->select();
		if(!isset($data[0])){
			$this->skip(array('status'=>'danger','url'=>Url::getUrl(array(C=>'type',M=>'index')),'message'=>'栏目不存在.'));
		}
		if(!empty($_POST)){
			$_POST['id']=get('id');
			if($this->model->create()){
				if($this->model->where('id=?')->bind('i',array(get('id')))->update()!==false){
					if(isAjaxRequest()){
						exit('{"status":1,"message":"修改成功."}');
					}
					$this->skip(array('status'=>'success','url'=>Url::getUrl(array(E=>'admin.php',C=>'type',M=>'index')),'message'=>'修改成功.'));
				}else{
					$message='栏目修改失败，请联系网站管理员.';
				}
			}else{
				$message=$this->model->getError();
			}
			if(isAjaxRequest()){
				exit('{"status":0,"message":"'.$message.'"}');
			}else{
				$this->skip(array('status'=>'danger','url'=>Url::getUrl(array(E=>'admin.php',C=>'type',M=>'update','id'=>get('id'))),'message'=>$message));
			}
		}
		$tree=new Tree($this->model->select());
		$this->view->setData('arctype',$tree->getAll());
		$this->view->setData('posterity',$tree->getPosterity(get('id'),true));
		$this->view->setData('type',$data[0]);
		$this->view->display();
	}
	public function delete(){
		if(!isAjaxRequest()){
			exit();
		}
		if(!empty($_POST)){
			$tree=new Tree($this->model->field('id,pid,tid')->select());
			$posterity=$tree->getPosterity(post('id'),true);
			$ids='';
			foreach ($posterity as $value){
				$ids.=$value['id'].',';
			}
			$ids=trim($ids,',');
			if(!empty($ids)){
				if($this->model->where("id IN({$ids})")->delete()){
					exit('{"status":1,"message":"删除成功."}');
				}else{
					exit('{"status":0,"message":"删除失败."}');
				}
			}
		}
	}
	
}