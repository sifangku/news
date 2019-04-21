<?php
/**
 * SFKNews
* 私房库 www.sifangku.com 教学项目
*
* @desc 评论model类
* @copyright 私房库 www.sifangku.com 保留所有版权
* @author 孙胜利 www.sunshengli.com
* @version 1.01
*
**/
namespace Common;
if(!defined('SITE')) exit('Access Denied');
class CommentModel extends Model {
	public function getAncestor($who,$start=0,$length=10,$ancestorDataType='html',$json=false){
		$comment=$this->where('id=?')->bind('i',array($who))->select();
		if(is_array($comment) && count($comment)){
			$ancestorIdArr=explode(',',$comment[0]['ancestor']);
			$total=count($ancestorIdArr);
			if($start<0 || $start>=$total){
				return null;
			}
			if($length<=0){
				return null;
			}
			if($start+$length>$total){
				$length=$total-$start;
			}
			
			$ancestorIdAim=array_slice($ancestorIdArr,$start,$length);
			$dataAncestorAim=$this->table('comment c,member m')->field('c.*,m.username')->where('c.id IN('.implode(',',$ancestorIdAim).') AND c.mid=m.id')->select();
			$dataAncestorAimIndexById=array();
			foreach ($dataAncestorAim as $key=>$value){
				$dataAncestorAimIndexById[$value['id']]=$value;
			}
			$dataAncestorComplete=array();
			$num=$start;
			foreach ($ancestorIdAim as $id){
				if(array_key_exists($id,$dataAncestorAimIndexById)){
					$dataAncestorComplete[$id]=$dataAncestorAimIndexById[$id];
				}else{
					$dataAncestorComplete[$id]=array(
						'id'=>$id,
						'aid'=>$comment[0]['aid'],
						'content'=>'该评论已删除.',
						'mid'=>0,
						'username'=>'火星网友'
					);
				}
				$dataAncestorComplete[$id]['num']=++$num;
			}
			$return=array(
				'length'=>$length,
				'current'=>$start+$length,
				'total'=>$total,
				'has'=>($start+$length)<$total,
				'ids'=>$ancestorIdAim
			);
			switch ($ancestorDataType){
				case 'original':
					$return['data']=$dataAncestorComplete;
					break;
				case 'html':
					$dataBefore='';
					foreach ($dataAncestorComplete as $key=>$value){
						$dataBefore=<<<ETO
									<div id='comment-{$who}-{$value['id']}' class='comment-ancestor'>
										{$dataBefore}
										<div class="author-wrap"><p><small class="num">{$value['num']}楼</small> <small class="author">{$value['username']}</small></p></div>
										<div class="content-wrap">
											{$value['content']}
										</div>
									</div>
ETO;
					}
					$return['data']=$dataBefore;
					break;
			}
			if($json){
				return json_encode($return);
			}else{
				return $return;
			}
		}
	}
}