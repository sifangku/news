<?php
/**
 * SFKNews
 * 私房库 www.sifangku.com 教学项目
 *
 * @desc URL线路配置
 * @copyright 私房库 www.sifangku.com 保留所有版权
 * @author 孙胜利 www.sunshengli.com
 * @version 1.01
 *
 **/
if(!defined('SITE')) exit('Access Denied');
return array(
	'/^([\w\/]+)\/(index\.html|page_(\d+)\.html)?$/'=>array('c'=>'index','m'=>'type','type'=>':1','page'=>':3'),
	'/^([\w\/]+)\/(\d{8})\/(\d+)\.html$/'=>array('c'=>'index','m'=>'article','type'=>':1','date'=>':2','id'=>':3')
);