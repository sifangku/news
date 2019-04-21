<?php
/**
 * SFKNews
 * 私房库 www.sifangku.com 教学项目
 *
 * @desc URL解析类，解析出 控制器与控制器里的方法
 * @copyright 私房库 www.sifangku.com 保留所有版权
 * @author 孙胜利 www.sunshengli.com
 * @version 1.01
 *
 **/
namespace Common;
if(!defined('SITE')) exit('Access Denied');
class Url {
	//getUrl方法传参用，表示指定按照哪种风格返回	开始
	const AUTO=-1;
	const COMMON=0;
	const PATHINFO=1;
	const ROUTER=2;
	//getUrl方法传参用，表示指定按照哪种风格返回	结束
	//各模块url格式、数组
	static private $style;
	static private $controller;
	static private $method;
	//回调函数，生成路由样式的URL地址
	static private $createUrl='createUrl';
	static private function init(){
		self::parseUrlStyle();
		switch (\URL_MODE){
			case 0:
				break;
			case 1:
				if(URL_ROUTER_ON){
					if(self::parseRouteRules()){
						break;
					}
				}
				if(isset($_SERVER['PATH_INFO'])){
					self::parsePathInfo();
				}
				break;
		}
		self::parseUrl();
	}
	/**
	 * 解析路由线路规则
	 * @return null
	 **/
	static private function parseRouteRules(){
		if(isset($_SERVER['PATH_INFO'])){
			if(file_exists(URL_ROUTE_RULES)){
				$urlRouteRules=include URL_ROUTE_RULES;
				foreach ($urlRouteRules as $key=>$val){
					if(preg_match($key,$_SERVER['PATH_INFO'],$matches)){
						foreach ($val as $key1=>$val1){
							if(strpos($val1,':')===0){
								if(isset($matches[ltrim($val1,':')])){
									$val1=$matches[ltrim($val1,':')];
								}else{
									$val1=null;
								}
							}
							$_GET[$key1]=$val1;
						}
						return true;
					}
				}
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	
	/**
	 * 解析pathinfo模式
	 * @return null
	 **/
	static private function parsePathInfo(){
		//admin.php?c=index&m=test&id=10&page=100
		//admin.php/控制器/方法/参数A名/参数A值/参数B名/参数B值
		//admin.php/index/test/id/10/page/100
		//pathinfo模式
		/*
			/index/test/id/10/page/100
		*/
		preg_match_all('/([^\/]+)\/([^\/]+)/',$_SERVER['PATH_INFO'],$data);
		if(count($data[0])){
			foreach ($data[0] as $key=>$val){
				$tmp=explode('/',$val);
				if($key==0){
					$_GET[\INDEX_CONTROLLER]=$tmp[0];
					$_GET[\INDEX_METHOD]=$tmp[1];
				}else{
					$_GET[$tmp[0]]=$tmp[1];
				}
			}
		}else{
			$tmp=explode('/',$_SERVER['PATH_INFO']);
			if(isset($tmp[1])){
				$_GET[\INDEX_CONTROLLER]=$tmp[1];
			}
		}
	}
	/**
	 * 解析URL，将数据存储进对应的属性里
	 * @return null
	 **/
	static private function parseUrl(){
		if(!isset($_GET[\INDEX_CONTROLLER]) || $_GET[\INDEX_CONTROLLER]==''){
			$_GET[\INDEX_CONTROLLER]='index';
		}
		if(!isset($_GET[\INDEX_METHOD]) || $_GET[\INDEX_METHOD]==''){
			$_GET[\INDEX_METHOD]='index';
		}
		self::$controller=ucfirst($_GET[\INDEX_CONTROLLER]);
		self::$method=$_GET[\INDEX_METHOD];
	}
	/**
	 * 对外使用获取控制器
	 * @param bool $complete 是否获取完整的控制器名称（是否带Controller后缀）
	 * @return string
	 **/
	static public function getC($complete=false){
		if(!isset(self::$controller)){
			self::init();
		}
		if($complete){
			return self::$controller.'Controller';
		}else{
			return self::$controller;
		}
	}
	/**
	 * 对外使用获取控制器里应该执行的方法
	 * @return string
	 **/
	static public function getMethod(){
		if(!isset(self::$method)){
			self::init();
		}
		return self::$method;
	}
	static public function setCreateUrl($funcName){
		self::$createUrl=$funcName;
	}
	/**
	 * 记录各入口文件对应的url风格
	 **/
	static private function parseUrlStyle(){
		$filename=PATH_CACHE.'/UrlStyle/'.ENTRY;
		$flag=false;
		$lastTime=filemtime(ENTRY);
		if(!file_exists($filename)){
			$flag=true;
		}else{
			$data=include $filename;
			if($data['last_time']<$lastTime){
				$flag=true;
			}
		}
		if($flag){
			if(URL_MODE==0){
				$style=self::COMMON;
			}elseif(URL_MODE==1){
				if(URL_ROUTER_ON){
					$style=self::ROUTER;
				}else{
					$style=self::PATHINFO;
				}
			}else{
				$style=self::COMMON;
			}
			$contents=<<<EOT
<?php
/**
 * SFKNews
 * 私房库 www.sifangku.com 教学项目
 *
 * @desc 模块对应的url风格 记录
 * @copyright 私房库 www.sifangku.com 保留所有版权
 * @author 孙胜利 www.sunshengli.com
 * @version 1.01
 *
 **/
//检测是否有令牌
if(!defined('SITE')) exit('Access Denied');
return array('last_time'=>{$lastTime},'url_style'=>{$style});
EOT;
			file_put_contents($filename,$contents);
		}
	}
	/**
	 * 获取各入口的url样式格式
	 * @return string
	 **/
	static public function getUrlStyle($entry){
		if(!isset(self::$style[$entry])){
			$filename=PATH_CACHE.'/UrlStyle/'.$entry;
			$flag=false;
			if(!file_exists($filename)){
				$flag=true;
			}else{
				$data=include $filename;
				if($data['last_time']<filemtime($entry)){
					$flag=true;
				}
			}
			if($flag){
				//通过PHP访问的url地址
				$url=$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].':'.$_SERVER['SERVER_PORT'].BASIC_DIR.'/'.$entry;
				$ch=curl_init($url);
				curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
				curl_setopt($ch,CURLOPT_TIMEOUT,4);
				curl_exec($ch);
				curl_close($ch);
			}
			if(file_exists($filename)){
				$data=include $filename;
				self::$style[$entry]=$data['url_style'];
			}else{
				$message="<p>目标url访问失败,请手动访问<a href='{$url}' target='_blank'>{$url}</a></p>";
				throw new \Exception($message);
			}
		}
		return self::$style[$entry];
	}
	/**
	 * 获取url访问地址
	 * @return string
	 **/
	static public function getUrl($param=array(),$style=self::AUTO){
		if(is_string($param)){
			return BASIC_DIR.$param;
		//获取某个模块的控制器内某个方法的访问地址
		}elseif(is_array($param)){
			$param=array_merge(array(
				E=>ENTRY,
				C=>strtolower(self::getC()),
				M=>strtolower(self::getMethod())
			),$param);
			if($style==self::AUTO){
				//分析到底按照哪一种风格返回
				$style=self::getUrlStyle($param[E]);
			}
			switch ($style){
				case self::COMMON:
					$url="/{$param[E]}?".C."={$param[C]}&".M."={$param[M]}";
					array_splice($param,0,3);
					foreach ($param as $key=>$val){
						$url.="&{$key}={$val}";
					}
					break;
				case self::ROUTER:
					$url='';
					if(function_exists(self::$createUrl)){
						//执行self::$createUrl这个函数
						$url=call_user_func(self::$createUrl,$param);
						//并不是入口文件下的所有的控制器里面所有的方法都是需要生成路由样式的url地址
						if($url!==false){
							break;
						}
					}
				case self::PATHINFO:
					$url="/{$param[E]}/{$param[C]}/{$param[M]}";
					array_splice($param,0,3);
					foreach ($param as $key=>$val){
						$url.="/{$key}/{$val}";
					}
					break;
				default:
					break;
			}
			return BASIC_DIR.$url;
		}else{
			
		}
	}
}