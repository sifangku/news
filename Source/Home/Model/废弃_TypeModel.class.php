<?php
namespace Home\Model;
use Common\Model;
use Common\Tree;
if(!defined('SITE')) exit('Access Denied');
class TypeModel extends Model {
	public function __construct(){
		$this->tableName='arctype';
		parent::__construct();
	}
	public function selectNav($num=5){
		$param=array(
			'sql'=>"SELECT * FROM `{$this->getTableName()}` where pid=0 order by sort limit 0,{$num}"
		);
		return $this->db->execute($param);
	}
	public function selectNavByUname($uname){
		$param=array(
			'sql'=>"SELECT * FROM `{$this->getTableName()}` where TRIM(BOTH '/' FROM uname)=?",
			'bind'=>array('s',array(trim($uname,'/')))
		);
		return $this->db->execute($param);
	}
	use \Common\tTypeModel;
}