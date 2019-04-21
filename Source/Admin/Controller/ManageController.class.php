<?php
/**
 * SFKNews
 * 私房库 www.sifangku.com 教学项目
 *
 * @desc 管理员
 * @copyright 私房库 www.sifangku.com 保留所有版权
 * @author 孙胜利 www.sunshengli.com
 * @version 1.01
 *
 **/
namespace Admin\Controller;
use Common\Controller;
use Common\Power;
use Admin\Model\ManageModel;
use Common\Page;
use Common\Verify;
use Common\Url;
if(!defined('SITE')) exit('Access Denied');
class ManageController extends Controller {
	public function __construct(){
		$this->model=new ManageModel();
		parent::__construct();
	}
	public function index(){
		$count=$this->model->field('count(id) count')->select();
		$count=isset($count[0]['count']) ? $count[0]['count'] : 0;
		$page=new Page($count,array('url'=>Url::getUrl(array(C=>'manage',M=>'index','{getIndex}'=>'{pageNum}'))));
		$this->view->setData('manage',$this->model->limit($page->getLimit())->select());
		$this->view->setData('page',$page->getHtml());
		$this->view->display();
	}
	public function add(){
		if(!empty($_POST)){
			if($this->model->create()){
				if($this->model->add()){
					if(isAjaxRequest()){
						exit('{"status":1,"message":"添加成功."}');
					}else{
						$this->skip(array('status'=>'success','url'=>Url::getUrl(array(E=>'admin.php',C=>'manage',M=>'index')),'message'=>'添加成功.'));
					}
				}else{
					$message='管理员添加失败，请联系网站管理员.';
				}
			}else{
				$message=$this->model->getError();
			}
			if(isAjaxRequest()){
				exit('{"status":0,"message":"'.$message.'"}');
			}else{
				$this->skip(array('status'=>'danger','url'=>Url::getUrl(array(E=>'admin.php',C=>'manage',M=>'add')),'message'=>$message));
			}
		}
		$this->view->setData('power',Power::get(MODULE));
		$this->view->display();
	}
	public function update(){
		$data=$this->model->where('id=?')->bind('i',array(get('id')))->select();
		if(!isset($data[0])){
			$this->skip(array('status'=>'danger','url'=>Url::getUrl(array(C=>'manage',M=>'index')),'message'=>'管理员不存在.'));
		}
		if(!empty($_POST)){
			$_POST['id']=get('id');
			//值不为空时，则验证password
			$this->model->rule[0]['condition']['password']=ManageModel::VALUE_VALIDATE;
			if($this->model->create()){
				if($this->model->where('id=?')->bind('i',array(get('id')))->update()!==false){
					if(isAjaxRequest()){
						exit('{"status":1,"message":"修改成功."}');
					}
					$this->skip(array('status'=>'success','url'=>Url::getUrl(array(E=>'admin.php',C=>'manage',M=>'index')),'message'=>'修改成功.'));
				}else{
					$message='管理员修改失败，请联系网站管理员.';
				}
			}else{
				$message=$this->model->getError();
			}
			if(isAjaxRequest()){
				exit('{"status":0,"message":"'.$message.'"}');
			}else{
				$this->skip(array('status'=>'danger','url'=>Url::getUrl(array(E=>'admin.php',C=>'manage',M=>'update','id'=>get('id'))),'message'=>$message));
			}
		}
		$this->view->setData('manage',$data[0]);
		$this->view->display();
	}
	public function delete(){
		if(!isAjaxRequest()){
			exit();
		}
		if(!empty($_POST)){
			if($this->model->where('id=?')->bind('i',array(post('id')))->delete()){
				exit('{"status":1,"message":"删除成功."}');
			}else{
				exit('{"status":0,"message":"删除失败."}');
			}
		}
	}
	public function login(){
		if(!empty($_POST)){
			if(!isAjaxRequest()){
				exit();
			}
			if($this->model->loginValidation()){
				//成功
				exit('{"status":1,"message":"登录成功."}');
			}else{
				//是否显示验证码
				$show='';
				if($this->model->isShow()){
					$show=',"show":1';
				}
				//失败
				exit('{"status":0,"message":"'.$this->model->getError().'"'.$show.'}');
			}
		}
		$this->view->display();
	}
	public function logout(){
		session_unset();//Free all session variables
		session_destroy();//销毁一个会话中的全部数据
		setcookie(session_name(),'',time()-3600,'/');//销毁保存在客户端的卡号（session id）
		header('Location:'.Url::getUrl(array(M=>'login')));
	}
	//是否显示验证码
	public function isShowVerify(){
		if(!isAjaxRequest()){
			exit();
		}
		if($this->model->isShow()){
			exit('{"show":1}');
		}else{
			exit('{"show":0}');
		}
	}
	public function test(){
		var_dump($_SESSION);
	}
}