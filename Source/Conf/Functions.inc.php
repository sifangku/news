<?php
/**
 * SFKNews
 * 私房库 www.sifangku.com 教学项目
 *
 * @desc 公共函数
 * @copyright 私房库 www.sifangku.com 保留所有版权
 * @author 孙胜利 www.sunshengli.com
 * @version 1.01
 *
 **/
if(!defined('SITE')) exit('Access Denied');
/**
 * 根据命名空间自动加载类文件
 * @param string $className  类名称、可包含命名空间
 * @return null
 **/
function loadAbs($className){
	$path=PATH_SOURCE.'/'.str_replace('\\','/',$className).'.class.php';
	if(file_exists($path)){
		require $path;
	}
}
/**
 * 加载公共目录下的类文件
 * @param string $className  类名称、可包含命名空间
 * @return mixed
 **/
function loadCommonChild($className){
	foreach (scandir(PATH_COMMON) as $val){
		if($val=='.' || $val=='..' || is_file(PATH_COMMON.'/'.$val)){
			continue;
		}
		$classAttr=explode('\\',$className);
		$class=$classAttr[count($classAttr)-1];
		$path=PATH_COMMON.'/'.$val.'/'.$class.'.class.php';
		if(file_exists($path)){
			require $path;
			return true;
		}
	}
}
function abc(&$a,&$b,&$c){
	var_dump($a);
	var_dump($b);
	var_dump($c);
}
function test($filedVale){
	var_dump($filedVale);
	/*
	
	var_dump($a);
	var_dump($b);
	var_dump($c);
	var_dump('test...');
	*/
	return true;
}
function test1($data){
	return md5($data);
}
function get($index){
	if(isset($_GET[$index])){
		return $_GET[$index];
	}
}
function post($index){
	if(isset($_POST[$index])){
		return $_POST[$index];
	}
}
function isAjaxRequest(){
	if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH']=='XMLHttpRequest'){
		return true;
	}else{
		return false;
	}
}
function createUrl($param){
	switch ($param[E]){
		case 'index.php':
			switch ($param[C]){
				case 'index':
					switch ($param[M]){
						case 'type':
							if(isset($param['page_num'])){
								$str="{$param['page_index']}_{$param['page_num']}.html";
							}else{
								$str='';
							}
							return "/".trim($param['uname'],'/')."/{$str}";
							//return "/{$param[E]}/".trim($param['uname'],'/')."/{$str}";
							break;
						case 'article':
							return "/".trim($param['uname'],'/').'/'.date('Ymd',$param['pubdate']).'/'.$param['id'].'.html';
							//return "/{$param[E]}/".trim($param['uname'],'/').'/'.date('Ymd',$param['pubdate']).'/'.$param['id'].'.html';
							break;
					}
					break;
				default:
					return false;
			}
			break;
		default:
			return false;
	}
	return false;
}