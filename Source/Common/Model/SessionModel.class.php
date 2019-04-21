<?php
namespace Common;
if(!defined('SITE')) exit('Access Denied');
class SessionModel extends Model {
	public function __construct(){
		
		parent::__construct();
	}
	public function read($session_id){
		$param=array(
			'sql'=>"select `data` from {$this->getTableName()} where id=? and UNIX_TIMESTAMP() < expire",
			'bind'=>array('s',array($session_id))
		);
		$data=$this->getDB()->execute($param);
		if(isset($data[0]['data'])){
			return $data[0]['data'];
		}else{
			return '';
		}
	}
	public function gc(){
		$param=array(
			'sql'=>"delete from {$this->getTableName()} where expire < UNIX_TIMESTAMP()"
		);
		return $this->getDB()->execute($param);
	}
	public function write($session_id,$session_data,$gcMaxlifetime){
		$param=array(
			'sql'=>"replace into {$this->getTableName()}(id,data,expire) values(?,?,UNIX_TIMESTAMP()+{$gcMaxlifetime})",
			'bind'=>array('ss',array($session_id,$session_data))
		);
		return $this->getDB()->execute($param);
	}
	
}