<?php
/**
 * SFKNews
 * 私房库 www.sifangku.com 教学项目
 *
 * @desc Admin模块的权限配置文件
 * @copyright 私房库 www.sifangku.com 保留所有版权
 * @author 孙胜利 www.sunshengli.com
 * @version 1.01
 *
 **/
if(!defined('SITE')) exit('Access Denied');
/*
controller
method
note
pmark
数据库存储方式
array(
	array('controller'=>'Article','method'=>'index','note'=>'新闻列表','pmark'=>1),
	array('controller'=>'Article','method'=>'add','note'=>'添加新闻','pmark'=>1<<1),
	array('controller'=>'Article','method'=>'update','note'=>'修改新闻','pmark'=>1<<2),
	array('controller'=>'Article','method'=>'delete','note'=>'删除新闻','pmark'=>1<<3),
	array('controller'=>'Article','method'=>'showTrash','note'=>'回收站列表','pmark'=>1<<4),
	array('controller'=>'Article','method'=>'revert','note'=>'回收站还原','pmark'=>1<<5),
	array('controller'=>'Article','method'=>'trueDelete','note'=>'回收站永久删除','pmark'=>1<<6)
);
*/
return array(
	'Article'=>array(
		'index'=>array('note'=>'新闻列表','pmark'=>1),
		'add'=>array('note'=>'添加新闻','pmark'=>1<<1),
		'update'=>array('note'=>'修改新闻','pmark'=>1<<2),
		'delete'=>array('note'=>'删除新闻','pmark'=>1<<3),
		'showTrash'=>array('note'=>'回收站列表','pmark'=>1<<4),
		'revert'=>array('note'=>'回收站还原','pmark'=>1<<5),
		'trueDelete'=>array('note'=>'回收站永久删除','pmark'=>1<<6)
	),
	'Type'=>array(
		'index'=>array('note'=>'栏目列表','pmark'=>1<<7),
		'add'=>array('note'=>'添加栏目','pmark'=>1<<8),
		'update'=>array('note'=>'修改栏目','pmark'=>1<<9),
		'delete'=>array('note'=>'删除栏目','pmark'=>1<<10)
	),
	'Manage'=>array(
		'index'=>array('note'=>'管理员列表','pmark'=>1<<11),
		'add'=>array('note'=>'添加管理员','pmark'=>1<<12),
		'update'=>array('note'=>'修改管理员','pmark'=>1<<13),
		'delete'=>array('note'=>'删除管理员','pmark'=>1<<14)
	)
);








