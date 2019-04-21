<?php
namespace Admin\Model;
use Common\Model;
if(!defined('SITE')) exit('Access Denied');
class ArctypeModel extends Model {
	protected $rule=array(
		array(
			'rules'=>array(
				'pid'=>array(
					'number'=>true,
					'method'=>array('checkPid')
				),
				'name'=>array(
					'length'=>'1,10',
					'unique'=>true
				),
				'uname'=>array(
					'length'=>'1,255',
					'unique'=>true
				),
				'view'=>array(
					'length'=>'1,255'
				),
				'title'=>array(
					'length'=>'1,255'
				),
				'keywords'=>array(
					'length'=>'1,255'
				),
				'description'=>array(
					'length'=>'1,500'
				),
				'sort'=>array(
					'number'=>true
				)
			)
		)
	);
	protected $autoRule=array(
		array(
			array('tid','method-getTid')
		)
	);
	protected function checkPid($pid){
		//INSERT : pid存在
		//UPDATE : pid存在，并且不能为自身后代
		if($pid==0){
			return true;
		}else{
			$data=$this->field('id')->where('id=?')->bind('i',array($pid))->select();
			if(!isset($data[0])){
				return false;
			}
		}
		if($this->status==self::UPDATE){
			
		}
		return true;
	}
	/**
	 * 数据入库时获取tid
	 * @return int
	 **/
	protected function getTid(){
		if(post('pid')==0){
			return 0;
		}else{
			$data=$this->field('tid')->where('id=?')->bind('i',array(post('pid')))->select();
			if(isset($data[0]['tid'])){
				return $data[0]['tid'];
			}else{
				return null;
			}
		}
	}
	
}