<?php
namespace Admin\Model;
use Common\Model;
use Common\Verify;
if(!defined('SITE')) exit('Access Denied');
class ManageModel extends Model {
	public $rule=array(
		array(
			'rules'=>array(
				'name'=>array(
					'length'=>'3,32',
					'unique'=>true
				),
				'pname'=>array(
					'length'=>'1,32',
					'unique'=>true
				),
				'password'=>array(
					'length'=>'6,32'
				)
			)
		)
	);
	protected $autoRule=array(
		array(
			array('password','method-getPassword'),
			array('power','method-getPower'),
			array('rtime','function-time'),
			array('password','ignore')
		)
	);
	protected function getPassword($password){
		if(!empty($password)){
			return md5(sha1($password));
		}
	}
	protected function getPower($pmarkArr){
		$power=0;
		if(!empty($pmarkArr) && is_array($pmarkArr)){
			foreach ($pmarkArr as $val){
				$power|=$val;
			}
		}
		return $power;
	}
	public function loginValidation(){
		$vcount=$this->field('vcount')->where('name=?')->bind('s',array(post('name')))->select();
		$vcount=isset($vcount[0]['vcount']) ? $vcount[0]['vcount'] : 0;
		if($vcount>=COUNT_VERIFY){
			$verify=new Verify();
			if(mb_strlen(post('verify'))!=4 || !$verify->check(post('verify'))){
				//验证码错误
				$this->error='请输入正确的验证码.';
				return false;
			}
		}
		$data=$this->field('name,pname,power')->where('name=? AND password=?')->bind('ss',array(post('name'),md5(sha1(post('password')))))->select();
		if(!isset($data[0]['name'])){
			//密码输错次数加1
			$this->data['vcount']='vcount+1';
			$param=array(
				'sql'=>"UPDATE {$this->getTableName()} SET vcount=vcount+1 WHERE name=?",
				'bind'=>array('s',array(post('name')))
			);
			$this->getDB()->execute($param);
			$this->error='用户名或密码输入错误.';
			return false;
		}
		//验证成功，vcount清零，将必要的数据存到session中方便在后台以及其他地方使用
		$this->data['vcount']=0;
		$this->where('name=?')->bind('s',array(post('name')))->update();
		//设置session
		$_SESSION=array_merge($_SESSION,$data[0]);
		return true;
	}
	public function isShow(){
		$vcount=$this->field('vcount')->where('name=?')->bind('s',array(post('name')))->select();
		$vcount=isset($vcount[0]['vcount']) ? $vcount[0]['vcount'] : 0;
		if($vcount>=COUNT_VERIFY){
			return true;
		}else{
			return false;
		}
	}
	
}