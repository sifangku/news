<?php
/**
 * SFKNews
 * 私房库 www.sifangku.com 教学项目
 *
 * @desc session入库类
 * @copyright 私房库 www.sifangku.com 保留所有版权
 * @author 孙胜利 www.sunshengli.com
 * @version 1.01
 *
 **/
namespace Common;
if(!defined('SITE')) exit('Access Denied');
class SessionDeal implements \SessionHandlerInterface {
	private $model;
	private $gcMaxlifetime;
	public function __construct(){
		$this->model=new SessionModel();
		$this->gcMaxlifetime=ini_get('session.gc_maxlifetime');
	} 
	//开启session的时候执行		session_start()
	public function open($save_path,$name){
		return true;
	}
	//将会话信息读取到$_SESSION变量中的时候执行		session_start()
	public function read($session_id){
		return $this->model->read($session_id);
	}
	//session垃圾回收机制启动的时候执行	
	public function gc($maxlifetime){
		return $this->model->gc();
	}
	//把$_SESSION数据像资料库里面写的时候执行		脚本即将执行结束的时候一次性将$_SESSION里面的内容写入资料库
	public function write($session_id,$session_data){
		return $this->model->write($session_id,$session_data,$this->gcMaxlifetime);
	}
	//销毁的时候执行	session_destroy();
	//执行了这个就不会再去执行write方法
	public function destroy($session_id){
		return $this->model->delete($session_id);
	}
	//关闭的时候执行	在 write 函数执行完成时执行，它像类里面的析构函数
	public function close(){
		return true;
	}
}