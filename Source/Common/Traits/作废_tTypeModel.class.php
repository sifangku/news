<?php
/**
 * SFKNews
 * 私房库 www.sifangku.com 教学项目
 *
 * @desc trais 供 Admin以及Home模块下载Model使用
 * @copyright 私房库 www.sifangku.com 保留所有版权
 * @author 孙胜利 www.sunshengli.com
 * @version 1.01
 *
 **/
namespace Common;
if(!defined('SITE')) exit('Access Denied');
trait tTypeModel {
	public function getFamily($param){
		if(!isset($param['fields'])){
			$param['fields']='*';
		}
		if(isset($param['id'])){
			$dbParam=array(
					'sql'=>"SELECT {$param['fields']} FROM {$this->getTableName()} WHERE tid=(SELECT `tid` FROM {$this->getTableName()} WHERE id=?)",
					'bind'=>array($this->getFieldsType('id'),array($param['id']))
			);
			return $this->db->execute($dbParam);
		}
		if(isset($param['tid'])){
			$dbParam=array(
					'sql'=>"SELECT {$param['fields']} FROM {$this->getTableName()} WHERE tid=?",
					'bind'=>array($this->getFieldsType('tid'),array($param['tid']))
			);
			return $this->db->execute($dbParam);
		}
	}
}