<?php
/**
 * SFKNews
 * 私房库 www.sifangku.com 教学项目
 *
 * @desc 新闻管理
 * @copyright 私房库 www.sifangku.com 保留所有版权
 * @author 孙胜利 www.sunshengli.com
 * @version 1.0
 *
 **/
namespace Admin\Controller;
use Common\Controller;
use Common\Tree;
use Common\Page;
use Admin\Model\ArctypeModel;
use Admin\Model\ArticleModel;
use Common\Url;
if(!defined('SITE')) exit('Access Denied');
class ArticleController extends Controller {
	public function __construct(){
		$this->model=new ArticleModel();
		parent::__construct();
	}
	public function index(){
		$count=$this->model->table('article a,arctype b')->field('count(a.id) count')->where('a.isdelete=0 AND a.atid=b.id')->select();
		$count=isset($count[0]['count']) ? $count[0]['count'] : 0;
 		$page=new Page($count,array('url'=>Url::getUrl(array(C=>'article',M=>'index','{getIndex}'=>'{pageNum}'))));
		
 		$this->view->setData('article',$this->model->table('article a,arctype b')->field('a.*,b.name,b.uname')->where('a.isdelete=0 AND a.atid=b.id')->limit($page->getLimit())->select());
 		$this->view->setData('page',$page->getHtml());
		$this->view->display();
	}
	public function add(){
		if(!empty($_POST)){
			if($this->model->create()){
				if($this->model->add()){
					if(isAjaxRequest()){
						exit('{"status":1,"message":"添加成功."}');
					}
					$this->skip(array('status'=>'success','url'=>Url::getUrl(array(C=>'article',M=>'index')),'message'=>'添加成功.'));
				}else{
					$message='新闻添加失败，请联系网站管理员.';
				}
			}else{
				$message=$this->model->getError();
			}
			if(isAjaxRequest()){
				exit('{"status":0,"message":"'.$message.'"}');
			}else{
				$this->skip(array('status'=>'danger','url'=>Url::getUrl(array(C=>'article',M=>'add')),'message'=>$message));
			}
		}
		$typeModel=new ArctypeModel();
		$tree=new Tree($typeModel->select());
		$this->view->setData('arctype',$tree->getAll());
		$this->view->display();
	}
	public function update(){
		$data=$this->model->where('id=?')->bind('i',array(get('id')))->select();
		if(!isset($data[0])){
			$this->skip(array('status'=>'danger','url'=>Url::getUrl(array(C=>'article',M=>'index')),'message'=>'新闻不存在.'));
		}
		if(!empty($_POST)){
			$_POST['id']=get('id');
			if($this->model->create()){
				if($this->model->where('id=?')->bind('i',array(get('id')))->update()!==false){
					if(isAjaxRequest()){
						exit('{"status":1,"message":"修改成功."}');
					}
					$this->skip(array('status'=>'success','url'=>Url::getUrl(array(E=>'admin.php',C=>'article',M=>'index')),'message'=>'修改成功.'));
				}else{
					$message='修改失败，请联系网站管理员.';
				}
			}else{
				$message=$this->model->getError();
			}
			if(isAjaxRequest()){
				exit('{"status":0,"message":"'.$message.'"}');
			}else{
				$this->skip(array('status'=>'danger','url'=>Url::getUrl(array(E=>'admin.php',C=>'article',M=>'update','id'=>get('id'))),'message'=>$message));
			}
		}
		$typeModel=new ArctypeModel();
		$tree=new Tree($typeModel->select());
		$this->view->setData('arctype',$tree->getAll());
		if(!empty($data[0]['flag'])){
			$data[0]['flag']=explode(',',$data[0]['flag']);
		}else{
			$data[0]['flag']=array();
		}
		$this->view->setData('article',$data[0]);
		$this->view->display();
	}
	public function delete(){
		if(!isAjaxRequest()){
			exit();
		}
		if(!empty($_POST)){
			//方法一：
// 			if($this->model->create(array('isdelete'=>1),ArticleModel::UPDATE)){
// 				if($this->model->where('id=?')->bind('i',array(post('id')))->update()){
// 					exit('{"status":1,"message":"删除成功."}');
// 				}else{
// 					exit('{"status":0,"message":"删除失败."}');
// 				}
// 			}else{
// 				exit('{"status":0,"message":"'.$this->model->getError().'"}');
// 			}
			
			//方法二：
			$this->model->data['isdelete']=1;
			if($this->model->where('id=?')->bind('i',array(post('id')))->update()){
				exit('{"status":1,"message":"删除成功."}');
			}else{
				exit('{"status":0,"message":"删除失败."}');
			}
		}
	}
	public function showTrash(){
		$count=$this->model->table('article a,arctype b')->field('count(a.id) count')->where('a.isdelete=1 AND a.atid=b.id')->select();
		$count=isset($count[0]['count']) ? $count[0]['count'] : 0;
 		$page=new Page($count,array('url'=>Url::getUrl(array(C=>'article',M=>'index','{getIndex}'=>'{pageNum}'))));
		
 		$this->view->setData('article',$this->model->table('article a,arctype b')->field('a.*,b.name')->where('a.isdelete=1 AND a.atid=b.id')->limit($page->getLimit())->select());
		$this->view->setData('page',$page->getHtml());
		$this->view->display();
	}
	public function revert(){
		if(!isAjaxRequest()){
			exit();
		}
		if(!empty($_POST)){
			$this->model->data['isdelete']=0;
			if($this->model->where('id=?')->bind('i',array(post('id')))->update()){
				exit('{"status":1,"message":"还原成功."}');
			}else{
				exit('{"status":0,"message":"还原失败."}');
			}
		}
	}
	public function trueDelete(){
		if(!isAjaxRequest()){
			exit();
		}
		if(!empty($_POST)){
			if($this->model->where('id=?')->bind('i',array(post('id')))->delete()){
				exit('{"status":1,"message":"删除成功."}');
			}else{
				exit('{"status":0,"message":"'.$this->model->getError().'"}');
			}
		}
	}
}