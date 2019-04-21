<?php
/**
 * SFKNews
 * 私房库 www.sifangku.com 教学项目
 *
 * @desc 首页
 * @copyright 私房库 www.sifangku.com 保留所有版权
 * @author 孙胜利 www.sunshengli.com
 * @version 1.01
 *
 **/
namespace Home\Controller;
use Common\Controller;
use Common\Url;
use Common\Tree;
use Common\Page;
use Common\Model;
if(!defined('SITE')) exit('Access Denied');
class IndexController extends Controller {
	public function __construct(){
		parent::__construct();
		$this->model=new Model();
		$this->tree=new Tree($this->model->table('arctype')->select());
		$this->view->setData('arctypes',$this->tree->getTop());
	}
	public function index(){
		$this->view->setData('h',$this->model->table('article a,arctype b')->field('a.*,b.uname')->where('a.isdelete=0 AND FIND_IN_SET("h",a.flag) AND a.atid=b.id')->order('a.weights desc')->limit(5)->select());
		$this->view->display();
	}
	public function type(){
		//查询栏目
		$arctype=$this->model->table('arctype')->where('TRIM(BOTH "/" FROM uname)=?')->bind('s',array(trim(get('type'),'/')))->select();
		if(is_array($arctype) && count($arctype)){
			//当前位置
			$this->view->setData('position',$this->tree->getAncestor($arctype[0]['id'],true,true));
			//当前栏目以及栏目的后代栏目id
			$ids=implode(',',array_keys($this->tree->getPosterity($arctype[0]['id'],true)));
			//查询数目、分页用
			$count=$this->model->table('article')->field('count(id) count')->where('isdelete=0 AND atid IN('.$ids.')')->select();
			if(isset($count[0]['count']) && $count[0]['count']>0){
				$url=Url::getUrl(array(M=>'type','uname'=>$arctype[0]['uname'],'page_index'=>'{getIndex}','page_num'=>'{pageNum}'));
				$page=new Page($count[0]['count'],array('url'=>$url));
				$this->view->setData('page',$page->getHtml());
				$this->view->setData('articles',$this->model->table('article a,arctype b')->field('a.*,b.uname')->where('a.isdelete=0 AND a.atid IN('.$ids.') AND a.atid=b.id')->limit($page->getLimit())->select());
			}else{
				$this->view->setData('page','');
				$this->view->setData('articles',array());
			}
		}else{
			throw new \Exception('栏目不存在.');
		}
		$this->view->display();
	}
	public function article(){
		$article=$this->model->table('article a,arctype b')->field('a.*,b.uname')->where('a.id=? AND a.isdelete=0 AND FROM_UNIXTIME(a.pubdate,"%Y%m%d")=? AND a.atid=b.id AND TRIM(BOTH "/" FROM b.uname)=?')->bind('iss',array(get('id'),get('date'),trim(get('type'),'/')))->select();
		if(is_array($article) && count($article)){
			
			$countComment=$this->model->table('comment')->field('count("id") count')->where('aid=?')->bind('i',array($article[0]['id']))->select();
			$countComment=isset($countComment[0]['count']) ? $countComment[0]['count'] : 0;
			$this->view->setData('position',$this->tree->getAncestor($article[0]['atid'],true,true));
			$this->view->setData('count',$countComment);
			$this->view->setData('article',$article[0]);
		}else{
			throw new \Exception('新闻不存在.');
		}
		
		$this->view->display();
	}
	
	
}