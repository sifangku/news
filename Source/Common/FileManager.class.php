<?php
namespace Common;
if(!defined('SITE')) exit('Access Denied');
class FileManager {
	//限定目录（安全配置：所有操作只限定的这个目录内）
	static public function setBasedir($dir){
		return ini_set('open_basedir',$dir);
	}
	//读目录
	/**
	 * 读取目录
	 * @param bool $complete 是否获取完整的控制器名称（是否带Controller后缀）
	 * @return string
	 **/
	static public function read($path){
		if(!is_dir($path)){
			return false;
		}
		$oldCwd=getcwd();
		//切换当前工作目录为指定目录
		if(chdir($path)){
			//打开当前目录
			if(($dh=opendir('./'))!==false){
				$files=array();
				while (($file=readdir($dh))!==false){
					if($file=='.' || $file=='..'){
						continue;
					}
					$file=iconv("gb2312","utf-8",$file);
					$files[$file]['name']=$file;
					$files[$file]['path']=getcwd().'/'.$file;
					$files[$file]['path_web']=str_replace(array(PATH_APP,'\\'),array('','/'),$files[$file]['path']);
					$files[$file]['is_dir']=is_dir($file) ? true : false;
					$files[$file]['type']=@mime_content_type($files[$file]['path']);
				}
				ksort($files);
				chdir($oldCwd);
				return $files;
			}
		}
	}
	//创建目录
	static public function create($path){
		return mkdir($path,0777,true);
	}
	//删	目录/文件
	static public function delete($path){
		return rmdir($path);
	}
	//获取某个目录的Web目录
	static public function getWebPath($path){
		if(!is_dir($path)){
			return false;
		}
		$oldCwd=getcwd();
		if(chdir($path)){
			$cwd=getcwd();
			chdir($oldCwd);
			return str_replace(array(PATH_APP,'\\'),array('','/'),$cwd);
		}
	}
}