<?php
/**
 * SFKNews
 * 私房库 www.sifangku.com 教学项目
 *
 * @desc 处理树形结构的数据（比如无限级分类的栏目）
 * @copyright 私房库 www.sifangku.com 保留所有版权
 * @author 孙胜利 www.sunshengli.com
 * @version 1.0
 *
 **/
namespace Common;
class Tree {
	//原始数据
	private $treeDataOriginal;
	//id索引的数据
	private $treeDataId;
	//pid索引的数据
	private $treeDataPid;
	//后代
	private $posterity;
	public function __construct($data){
		$this->treeDataOriginal=$data;
		foreach($this->treeDataOriginal as $val){
			$this->treeDataId[$val['id']]=$val;
			$this->treeDataPid[$val['pid']][$val['id']]=$val;
		}
		//$this->parsePosterity(3);
		//var_dump($this->posterity);
// 		foreach ($this->posterity as $val){
// 			echo "<p>{$val['style']}{$val['name']}</p>";
// 		}
	}
	//根据id获取子栏目
	public function getChildren($id){
		if(isset($this->treeDataPid[$id])){
			return $this->treeDataPid[$id];
		}else{
			return false;
		}
	}
	//递归解析子栏目
	private function parsePosterity($id){
		$children=$this->getChildren($id);
		if($children){
			foreach ($children as $val){
				$val['level']=count($this->getAncestor($val['id']));
				$val['style']=implode('',array_fill(0,$val['level'],'&nbsp;|&nbsp--&nbsp'));
				$this->posterity[$val['id']]=$val;
				$this->parsePosterity($val['id']);
			}
		}
	}
	//获取后代,第二个参数表示是否包括自身
	public function getPosterity($id,$self=false){
		$this->posterity=array();
		if($self){
			$this->posterity[$id]=$this->treeDataId[$id];
		}
		$this->parsePosterity($id);
		return $this->posterity;
	}
	//获取父栏目
	public function getParent($id){
		if(isset($this->treeDataId[$id]['pid'])){
			if(isset($this->treeDataId[$this->treeDataId[$id]['pid']])){
				return $this->treeDataId[$this->treeDataId[$id]['pid']];
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	//获取祖先
	public function getAncestor($id,$self=false,$sort=false){
		if($self){
			$tmp[$id]=$this->treeDataId[$id];
		}else{
			$tmp=array();
		}
		while ($parent=$this->getParent($id)){
			$id=$parent['id'];
			$tmp[$id]=$parent;
		}
		if($sort){
			$tmp=array_reverse($tmp,true);
		}
		return $tmp;
	}
	//获取顶级栏目
	public function getTop(){
		if(isset($this->treeDataPid[0])){
			return $this->treeDataPid[0];
		}else{
			return array();
		}
	}
	public function getAll(){
		$this->posterity=array();
		foreach ($this->getTop() as $val){
			$val['level']=0;
			$val['style']='';
			$this->posterity[$val['id']]=$val;
			$this->parsePosterity($val['id']);
		}
		return $this->posterity;
	}
	
	
}