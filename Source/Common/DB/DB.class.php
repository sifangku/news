<?php
/**
 * SFKNews
 * 私房库 www.sifangku.com 教学项目
 *
 * @desc 数据库操作类
 * @copyright 私房库 www.sifangku.com 保留所有版权
 * @author 孙胜利 www.sunshengli.com
 * @version 1.01
 *
 **/
namespace Common;
if(!defined('SITE')) exit('Access Denied');
class DB extends \mysqli {
	//上次插入的ID值
	private $lastInsID;
	private $config=array(
		'host'=>'localhost',
		'user'=>'root',
		'password'=>'',
		'database'=>'',
		'port'=>3306,
		'charset'=>'utf8'
	);
	/**
	 * 初始化
	 * @param array $config  参考config属性
	 * @return null
	 **/
	public function __construct($config){
		$this->config=array_merge($this->config,$config);
		@parent::__construct($this->config['host'],$this->config['user'],$this->config['password'],$this->config['database'],$this->config['port']);
		if($this->connect_errno){
			$this->dError($this->connect_error);
		}
		$this->set_charset($this->config['charset']);
	}
	/*
	array(
		'sql'=>'insert into test(id,name,pic) values(?,?,?)',
		'bind'=>array('iss',array(100,'孙胜利的私房库','dqwdqwdwq')),
	  或者'bind'=>array('iss',array('id'=>100,'name'=>'孙胜利的私房库','pic'=>'dqwdqwdwq'))
	)
	*/
	/**
	 * 执行sql语句
	 * @param array $param 可能的键名称：bind、sql 其中bind可以省略，sql中若有参数需要绑定name必须传bind值，bind的值也是一个数组参照array(类型,数据数组)
	 * @return null
	 **/
	public function execute($param){
		$stmt=$this->stmt_init();
		if($stmt->prepare($param['sql'])){
			if(isset($param['bind'])){
				foreach ($param['bind'][1] as $key=>$val){
					$tmp[]=&$param['bind'][1][$key];
				}
				array_unshift($tmp,$param['bind'][0]);
				if(!@call_user_func_array(array($stmt,'bind_param'),$tmp)){
					$this->dError('参数绑定失败.');
				}
			}
			if($stmt->execute()){
				if($stmt->result_metadata()){
					$result=$stmt->get_result();
					return $result->fetch_all(MYSQLI_ASSOC);
				}
				$this->lastInsID=$stmt->insert_id;
				return $stmt->affected_rows;
			}else{
				$this->dError($stmt->error);
			}
		}else{
			$this->dError($stmt->error);
		}
	}
	public function getLastInsID(){
		return $this->lastInsID;
	}
	public function escape($data){
		if(is_string($data)){
			return $this->real_escape_string($data);
		}
		if(is_array($data)){
			foreach ($data as $key=>$val){
				$data[$key]=$this->escape($val);
			}
		}
		return $data;
	}
	public function __destruct(){
		@$this->close();
	}
	private function dError($error){
		throw new \Exception($error);
	}
}