<?php
/**
 * SFKNews
 * 私房库 www.sifangku.com 教学项目
 *
 * @desc 评论
 * @copyright 私房库 www.sifangku.com 保留所有版权
 * @author 孙胜利 www.sunshengli.com
 * @version 1.01
 *
 **/
namespace Home\Controller;
use Common\Controller;
use Common\Verify;
use Common\Url;
use Common\Model;
use Common\CommentModel;
use Common\Tool;
use Common\Tree;
use Common\Page;
if(!defined('SITE')) exit('Access Denied');
class CommentController extends Controller {
	public function __construct(){
		parent::__construct();
		$this->model=new Model();
		$this->tree=new Tree($this->model->table('arctype')->select());
		$this->view->setData('arctypes',$this->tree->getTop());
	}
	public function index(){
		$model=new CommentModel();
		$article=$model->table('article')->where('id=?')->bind('i',array(get('aid')))->select();
		if(is_array($article) && count($article)){
			$count=$model->table('comment c,member m')->field('count(c.id) count')->where('c.aid=? AND c.mid=m.id')->bind('i',array(get('aid')))->select();
			if(isset($count[0]['count']) && $count[0]['count']>0){
				$url=Url::getUrl(array(M=>'index','aid'=>get('aid'),'{getIndex}'=>'{pageNum}'));
				$page=new Page($count[0]['count'],array('url'=>$url));
				$this->view->setData('page',$page->getHtml());
				$comments=$model->table('comment c,member m')->field('c.*,m.username')->where('c.aid=? AND c.mid=m.id')->bind('i',array(get('aid')))->order('id DESC')->limit($page->getLimit())->select();
			}else{
				$this->view->setData('page','');
				$comments=array();
			}

			$this->view->setData('article',$article[0]);
			foreach ($comments as $key=>$value){
				if(!empty($value['ancestor'])){
					$comments[$key]['ancestor_']=$model->getAncestor($value['id'],0,2);
				}
			}
			$this->view->setData('comments',$comments);
			$this->view->display();
		}
	}
	public function add(){
		if(!empty($_POST)){
			$verify=new Verify();
			if(mb_strlen(post('verify'))!=4 || !$verify->check(post('verify'))){
				//验证码错误
				$this->skip(array(
						'status'=>'danger',
						'url'=>$_SERVER['HTTP_REFERER'],
						'message'=>'请输入正确的验证码.'
				));
			}
			$model=new Model();
			$article=$model->table('article')->where('id=?')->bind('i',array(get('aid')))->select();
			if(is_array($article) && count($article)){
				$rule=array(
					'rules'=>array(
						'content'=>array(
							'length'=>'2,'
						)
					)
				);
				$autoRule=array(
					array('aid','string-'.get('aid')),
					array('mid',"string-1"),
					array('date','function-time'),
					array('content','function-strip_tags'),
					array('content','function-nl2br')
				);
				if($model->table('comment')->validate($rule)->auto($autoRule)->create()){
					if(!is_null(post('pid'))){
						$data=$model->data;
						$parent=$model->where('id=?')->bind('i',array(post('pid')))->select();
						if(is_array($parent) && count($parent)){
							$data['ancestor']=trim($parent[0]['ancestor'].','.$parent[0]['id'],',');
							$model->data=$data;
						}
					}
					if($model->table('comment')->add()){
						$this->skip(array(
							'status'=>'success',
							'url'=>$_SERVER['HTTP_REFERER'],
							'message'=>'评论成功.'
						));
					}else{
						$message='评论失败，请联系网站管理员.';
					}
				}else{
					$message=$model->getError();
				}
				$this->skip(array(
					'status'=>'danger',
					'url'=>$_SERVER['HTTP_REFERER'],
					'message'=>$message
				));
			}
		}
	}
	public function getAncestor(){
		$model=new CommentModel();
		echo $model->getAncestor(post('who'),post('current'),5,'html',true);
	}
}