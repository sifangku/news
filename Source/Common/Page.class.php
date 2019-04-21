<?php
/**
 * SFKNews
 * 私房库 www.sifangku.com 教学项目
 *
 * @desc 数据分页
 * @copyright 私房库 www.sifangku.com 保留所有版权
 * @author 孙胜利 www.sunshengli.com
 * @version 1.01
 *
 **/
namespace Common;
class Page {
	//记录总数
	private $totalData;
	//综合之后的配置
	private $config;
	//默认的配置
	private $configDefault=array(
		'pageSize'=>20,
		'numBtn'=>7,
		'getIndex'=>'page',
		'url'=>null
	);
	//总页数
	private $totalPage;
	private $limit;
	private $html;
	public function __construct($totalData,$config=array()){
		$this->totalData=$totalData;
		$this->config=array_merge($this->configDefault,$config);
		$this->totalPage=ceil($this->totalData/$this->config['pageSize']);
		$this->parseGet();
	}
	private function parseGet(){
		if(!isset($_GET[$this->config['getIndex']]) || !is_numeric($_GET[$this->config['getIndex']]) || $_GET[$this->config['getIndex']]<1){
			$_GET[$this->config['getIndex']]=1;
		}
		if($_GET[$this->config['getIndex']]>$this->totalPage){
			$_GET[$this->config['getIndex']]=$this->totalPage;
		}
	}
	private function getUrl($pageNum){
		$url=$this->config['url'];
		$url=str_replace('{getIndex}',$this->config['getIndex'],$url);
		$url=str_replace('{pageNum}',$pageNum,$url);
		return $url;
	}
	public function getLimit(){
		if($this->totalData==0){
			return '0';
		}
		if(!isset($this->limit)){
			$start=($_GET[$this->config['getIndex']]-1)*$this->config['pageSize'];
			$this->limit="{$start},{$this->config['pageSize']}";
		}
		return $this->limit;
	}
	public function getHtml(){
		if(!isset($this->config['url'])){
			throw new \Exception('未知url地址.');
		}
		/*
		'pageSize'=>20,
		'numBtn'=>7,
		'getIndex'=>'page',
		'url'=>null
		 
		*/
		if($this->totalData==0){
			return '';
		}
		if(!isset($this->html)){
			$html=array();
			if($this->config['numBtn']>=$this->totalPage){
				//把所有的页码按钮全部显示
				for($i=1;$i<=$this->totalPage;$i++){//这边的$this->totalPage是限制循环次数以控制显示按钮数目的变量,$i是记录页码号
					if($_GET[$this->config['getIndex']]==$i){
						$html[$i]="<li class='active'><span>{$i}</span></li>";
					}else{
						$html[$i]="<li><a href='{$this->getUrl($i)}'>{$i}</a></li>";
					}
				}
			}else{
				$num_left=floor(($this->config['numBtn']-1)/2);
				$start=$_GET[$this->config['getIndex']]-$num_left;
				$end=$start+($this->config['numBtn']-1);
				if($start<1){
					$start=1;
				}
				if($end>$this->totalPage){
					$start=$this->totalPage-($this->config['numBtn']-1);
				}
				for($i=0;$i<$this->config['numBtn'];$i++){
					if($_GET[$this->config['getIndex']]==$start){
						$html[$start]="<li class='active'><span>{$start}</span></li>";
					}else{
						$html[$start]="<li><a href='{$this->getUrl($start)}'>{$start}</a></li>";
					}
					$start++;
				}
				//如果按钮数目大于等于3的时候做省略号效果
				if(count($html)>=3){
					reset($html);
					$key_first=key($html);
					end($html);
					$key_end=key($html);
					if($key_first!=1){
						array_shift($html);
						array_unshift($html,"<li><a href='{$this->getUrl(1)}'>1...</a></li>");
					}
					if($key_end!=$this->totalPage){
						array_pop($html);
						array_push($html,"<li><a href='{$this->getUrl($this->totalPage)}'>...{$this->totalPage}</a></li>");
					}
				}
			}
			if($_GET[$this->config['getIndex']]!=1){
				$prev=$_GET[$this->config['getIndex']]-1;
				array_unshift($html,"<li><a href='{$this->getUrl($prev)}'>« 上一页</a></li>");
			}
			if($_GET[$this->config['getIndex']]!=$this->totalPage){
				$next=$_GET[$this->config['getIndex']]+1;
				array_push($html,"<li><a href='{$this->getUrl($next)}'>下一页 »</a></li>");
			}
			$this->html=$html=implode(' ',$html);
		}
		return '<ul class="pagination">'.$this->html.'</ul>';
	}
}