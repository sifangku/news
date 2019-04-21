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
use Home\Model\TestModel;
use Common\IngModel;
if(!defined('SITE')) exit('Access Denied');
class TestController extends Controller {
	public function __construct(){
		parent::__construct();
	}
	public function index(){
		$testModel=new TestModel();
		$rule=array(
			'rules'=>array(
				'name'=>array(
					'unique'=>true
				)
			)
		);
		$autoRule=array(
			array('完成字段1','function-test','完成时机'),
			array('完成字段2','method-test','完成时机'),
			array('完成字段3','field-test','完成时机'),
			array('完成字段4','string-test','完成时机'),
			array('完成字段5','ignore','完成时机')
		);
		//$testModel->field('name,a',false)->create(array('name'=>'私房库','a'=>'私房库','完成字段5'=>'   '));
		//$testModel->create(array('name'=>'私房库','a'=>'私房库','完成字段5'=>'   '));
		$model=new IngModel();
		//$model->table('arctype')->field('id',false)->create(array('id'=>38,'name'=>'经济','pid'=>10,'tid'=>10));
		//var_dump($model->table('article')->order('id DESC')->limit(1)->select());
// 		if($model->table('arctype')->create(array('id'=>42,'pid'=>10,'tid'=>39,'name'=>'http://www.sifangku.com/sss','uname'=>'sifangku','view'=>':))','title'=>'dqwdqq','keywords'=>'dwqq','description'=>'dqwdqw','sort'=>100))!==false){
// 			//var_dump($model->add());
// 			var_dump($model->where('id=?')->bind('i',array(42))->update());
// 		};
		var_dump($model->table('arctype')->where('id=?')->bind('i',array(43))->delete());
		
	}
	
}