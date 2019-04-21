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
use Common\Verify;
use Common\Url;
use Test\Model;
if(!defined('SITE')) exit('Access Denied');
class MemberController extends Controller {
	public function __construct(){
		parent::__construct();
	}
	public function index(){
		
	}
	public function register(){
// 		$this->skip(array(
// 				'status'=>'success',
// 				'url'=>'/News/admin.php/manage/index',
// 				'message'=>'添加成功.'
// 		));
// 		$this->skip(array(
// 				'status'=>'danger',
// 				'url'=>'/News/admin.php/manage/add',
// 				'message'=>$this->model->getError()
// 		));
		if(!empty($_POST)){
			$verify=new Verify();
			if($verify->check(post('vcode'))){
				$model=new Model();
				$rule=array(
					'rules'=>array(
						'username'=>array(
							'length'=>'2,32',
							'unique'=>true
						),
						'password'=>array(
							'length'=>'6,32'
						),
						'password1'=>array(
							'confirm'=>'password'
						)
					)
				);
				$autoRule=array(
					array('password','function-md5')
				);
				if($model->table('member')->validate($rule)->auto($autoRule)->create()){
					if($model->add()){
						$this->skip(array(
								'status'=>'success',
								'url'=>Url::getUrl(array(C=>'member',M=>'login')),
								'message'=>'注册成功.'
						));
					}else{
						$message='注册失败，请联系网站管理员';
					}
				}else{
					$message=$model->getError();
				}
			}else{
				$message='验证码输入错误.';
			}
			$this->skip(array(
				'status'=>'danger',
				'url'=>Url::getUrl(array(C=>'member',M=>'register')),
				'message'=>$message
			));
		}
		$this->view->display();
	}
	public function login(){
		if(!empty($_POST)){
			$verify=new Verify();
			if($verify->check(post('vcode'))){
				$model=new Model();
				$rule=array(
						'rules'=>array(
								'username'=>array(
										'length'=>'2,32'
								),
								'password'=>array(
										'length'=>'6,32'
								)
						)
				);
				if($model->table('member')->validate($rule)->create()){
					$data=$model->field('id,username')->where('username=? AND password=md5(?)')->bind('ss',array(post('username'),post('password')))->select();
					if(is_array($data) && count($data)){
						$_SESSION['member']=$data[0];
						$this->skip(array(
							'status'=>'success',
							'url'=>Url::getUrl(array(C=>'index',M=>'index')),
							'message'=>'登录成功.'
						));
					}else{
						$message='用户名或密码输入错误.';
					}
				}else{
					$message=$model->getError();
				}
			}else{
				$message='验证码输入错误.';
			}
			$this->skip(array(
					'status'=>'danger',
					'url'=>Url::getUrl(array(C=>'member',M=>'login')),
					'message'=>$message
			));
		}
		$this->view->display();
	}
}