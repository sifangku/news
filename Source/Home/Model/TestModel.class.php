<?php
namespace Home\Model;
use Common\IngModel;
if(!defined('SITE')) exit('Access Denied');
class TestModel extends IngModel {
	protected $insertFields=array('name,a');
	protected $updateFields=array('name',false);
	public function __construct(){
		$this->rule[0]=array(
			'rules'=>array(
				'name'=>array(
					'require'=>true,
					'unique'=>true,
					'length'=>'0,10',
					'in'=>'孙胜利,私房库,sifangku.com,sunshengli.com'
				),
				'a'=>array(
					'length'=>'3,6',
					'confirm'=>'name'
				)
			),
			'time'=>array(
				'name'=>array(
					'unique'=>parent::INSERT,
					'length'=>array(parent::INSERT,parent::UPDATE)
				),
				'a'=>parent::UPDATE
			),
			'condition'=>array(
				'a'=>array(
					'length'=>parent::MUST_VALIDATE
				),
				'name'=>parent::VALUE_VALIDATE
			),
			'message'=>array(
				'name'=>array(
					'require'=>'{field}必须填写'
				)
			),
			'fields_alias'=>array(
				'name'=>'姓名'
			)
		);
		$this->autoRule[0]=array(
			array('name','function-test1'),
			array('完成字段2','method-test'),
			array('完成字段3','field-name'),
			array('完成字段4','string-孙胜利'),
			array('完成字段5','ignore'),
			array('a','function-md5')
		);
		
		parent::__construct();
	}
	function test($data){
		return md5($data);
	}
}