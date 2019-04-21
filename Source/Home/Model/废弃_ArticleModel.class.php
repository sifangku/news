<?php
namespace Home\Model;
use Common\Model;
if(!defined('SITE')) exit('Access Denied');
class ArticleModel extends Model {
	protected $tableNameType='sfk_arctype'; 
	public function __construct(){
		$this->tableName='article';
		parent::__construct();
	}
	public function selectH($num=1){
		$param=array(
			'sql'=>"SELECT a.id,a.title,b.name,b.uname,a.pubdate FROM {$this->getTableName()} a,{$this->tableNameType} b WHERE a.atid=b.id AND isdelete=0 AND FIND_IN_SET('h',a.flag) order by a.weights desc limit 0,{$num}"
		);
		return $this->db->execute($param);
	}
	public function selectA($num=6){
		$param=array(
			'sql'=>"SELECT a.id,a.title,b.name,b.uname,a.pubdate FROM {$this->getTableName()} a,{$this->tableNameType} b WHERE a.atid=b.id AND isdelete=0 AND FIND_IN_SET('a',a.flag) order by a.weights desc limit 0,{$num}"
		);
		return $this->db->execute($param);
	}
	public function selectC($num=10){
		$param=array(
			'sql'=>"SELECT a.id,a.title,b.name,b.uname,a.pubdate FROM {$this->getTableName()} a,{$this->tableNameType} b WHERE a.atid=b.id AND isdelete=0 AND FIND_IN_SET('c',a.flag) order by a.weights desc limit 0,{$num}"
		);
		return $this->db->execute($param);
	}
	public function selectDetails($id,$date,$type){
		$param=array(
			'sql'=>"SELECT a.id,a.title,a.content,a.pubdate,a.atid FROM {$this->getTableName()} a,{$this->tableNameType} b WHERE a.id=? AND a.isdelete=0 AND FROM_UNIXTIME(a.pubdate,'%Y%m%d')=? AND a.atid=b.id AND TRIM(BOTH '/' FROM b.uname)=?",
			'bind'=>array('iss',array($id,$date,trim($type,'/')))
		);
		return $this->db->execute($param);
	}
	public function selectByAtid($atids,$limit){
		$palceholder=implode(array_fill(0,count($atids),'?'),',');
		$bindType=implode(array_fill(0,count($atids),'i'));
		$param=array(
			'sql'=>"SELECT a.id,a.title,a.pubdate,b.name,b.uname FROM {$this->getTableName()} a,{$this->tableNameType} b WHERE a.atid in({$palceholder}) AND a.isdelete=0 AND a.atid=b.id {$limit}",
			'bind'=>array($bindType,$atids)
		);
		return $this->db->execute($param);
	}
}