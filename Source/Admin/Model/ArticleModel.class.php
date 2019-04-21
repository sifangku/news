<?php
namespace Admin\Model;
use Common\Model;
if(!defined('SITE')) exit('Access Denied');
class ArticleModel extends Model {
	protected $rule=array(
		array(
			'rules'=>array(
				'atid'=>array(
					'number'=>true,
					'method'=>array('checkAtid')
				),
				'flag'=>array(
					'multiin'=>'h,a,c'
				),
				'title'=>array(
					'length'=>'1,32'
				),
				'keywords'=>array(
					'length'=>'1,128'
				),
				'description'=>array(
					'length'=>'1,512'
				)
			)
		)
	);
	protected $autoRule=array(
		array(
			array('editor','string-孙胜利'),
			array('eid','string-1'),
			array('pubdate','function-time'),
			array('flag','method-getFlag')
		)
	);
	protected function checkAtid($atid){
		$model=new Model();
		$data=$model->table('arctype')->field('id')->where('id=?')->bind('i',array($atid))->select();
		return isset($data[0]['id']);
	}
	protected function getFlag($flag){
		return !empty($flag) ? implode(',',$flag) : null;
	}
	
}