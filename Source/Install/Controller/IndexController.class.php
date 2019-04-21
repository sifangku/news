<?php
/**
 * SFKNews
 * 私房库 www.sifangku.com 教学项目
 *
 * @desc 安装控制器
 * @copyright 私房库 www.sifangku.com 保留所有版权
 * @author 孙胜利 www.sunshengli.com
 * @version 1.0
 *
 **/
namespace Install\Controller;
use Common\Controller;
use Common\Url;
use Common\Model;
if(!defined('SITE')) exit('Access Denied');
class IndexController extends Controller {
	public function __construct(){
		parent::__construct();
		header('Content-type:text/html;charset=utf-8');
		switch (Url::getMethod()){
			case 'index':
			case 'step1':
			case 'step2':
			case 'step3':
			case 'step4':
				if(file_exists('Source/Install/install.lock')){
					exit('您已安装完成本系统，若想重新安装，请删除Source/Install/install.lock文件后，再访问本页！');
				}
		}
	}
	public function index(){
		header('Location:'.Url::getUrl(array(M=>'step1')));
	}
	//程序说明
	public function step1(){
		
		$this->view->display();
	}
	//环境检测
	public function step2(){
		//服务器信息
		//'rewrite 支持'
		//'pathinfo 支持'
		$server=array(
			array('服务器域名',$_SERVER['HTTP_HOST'],true),
			array('服务器操作系统',PHP_OS,true),
			array('服务器解译引擎',$_SERVER['SERVER_SOFTWARE'],true),
			array('PHP版本',PHP_VERSION,version_compare(PHP_VERSION,'5.4.0')>=0,'至少5.4.0及以上的PHP版本!'),
			array('系统安装目录',dirname($_SERVER['SCRIPT_FILENAME']),true),
			array('GD 支持',function_exists('gd_info') ? '支持' : '不支持',function_exists('gd_info'),'必须开启GD库扩展'),
			array('mysqli 支持',function_exists('mysqli_connect') ? '支持' : '不支持',function_exists('mysqli_connect'),'必须开启mysqli扩展'),
			array('php_fileinfo 支持',function_exists('mime_content_type') ? '支持' : '不支持',function_exists('mime_content_type'),'必须开启php_fileinfo扩展')
		);
		$dir=array(
			array('/','可读',is_readable(dirname($_SERVER['SCRIPT_FILENAME'])),dirname($_SERVER['SCRIPT_FILENAME']).' 目录必须可读'),
			array('/Source/Cache/','可写',is_writable(dirname($_SERVER['SCRIPT_FILENAME']).'/Source/Cache/'),dirname($_SERVER['SCRIPT_FILENAME']).'/Source/Cache/'.' 目录必须可写'),
			array('/Source/Install/','可写,',is_writable(dirname($_SERVER['SCRIPT_FILENAME']).'/Source/Install/'),dirname($_SERVER['SCRIPT_FILENAME']).'/Source/Install/'.' 目录必须可写'),
			array('/Uploads/','可写',is_writable(dirname($_SERVER['SCRIPT_FILENAME']).'/Uploads/'),dirname($_SERVER['SCRIPT_FILENAME']).'/Uploads/'.' 目录必须可写'),
			array('/Source/Conf/Conf.inc.php','可写',is_writable(dirname($_SERVER['SCRIPT_FILENAME']).'/Source/Conf/Conf.inc.php'),dirname($_SERVER['SCRIPT_FILENAME']).'/Source/Conf/Conf.inc.php'.' 必须可写')
		);
		$this->view->setData('server',$server);
		$this->view->setData('dir',$dir);
		$this->view->display();
	}
	//参数配置
	public function step3(){
		if(!empty($_POST)){
			if(empty($_POST['db_host'])){
				exit('{"status":0,"message":"数据库地址不得为空！"}');
			}
			if(empty($_POST['db_port'])){
				exit('{"status":0,"message":"数据库服务端口不得为空！"}');
			}
			if(empty($_POST['db_user'])){
				exit('{"status":0,"message":"数据库用户名不得为空！"}');
			}
			if(empty($_POST['db_database'])){
				exit('{"status":0,"message":"数据库名称不得为空！"}');
			}
			if(empty($_POST['manage_name']) || mb_strlen($_POST['manage_name'])<3){
				exit('{"status":0,"message":"后台管理员名称不得少于3位！"}');
			}
			if(empty($_POST['manage_pw']) || mb_strlen($_POST['manage_pw'])<5){
				exit('{"status":0,"message":"后台管理员密码不得少于5位！"}');
			}
			$link=@mysqli_connect($_POST['db_host'],$_POST['db_user'],$_POST['db_pw'],'',$_POST['db_port']);
			if(mysqli_connect_errno()){
				exit('{"status":0,"message":"数据库连接失败!"}');
			}
			if(mysqli_select_db($link,$_POST['db_database'])){
				exit('{"status":1,"warning":"数据库 '.$_POST['db_database'].' 已经存在，本程序将清空其数据，不可还原，你认真的吗？"}');
			}
			exit('{"status":1,"message":"OK"}');
		}
		$this->view->display();
	}
	//正在安装
	public function step4(){
		if(!empty($_POST)){
			$model=new Model(array(
				'host'=>$_POST['db_host'],
				'user'=>$_POST['db_user'],
				'password'=>$_POST['db_pw'],
				'database'=>'',
				'port'=>$_POST['db_port']
			));
			if(!$model->selectDB($_POST['db_database'])){
				$param=array(
					'sql'=>"CREATE DATABASE IF NOT EXISTS `{$_POST['db_database']}` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci"
				);
				if($model->getDB()->execute($param)==false){
					exit('{"status":0,"message":"数据库'.$_POST['db_database'].'创建失败!"}');
				}
				$model->selectDB($_POST['db_database']);
			}
			$query=array(
					'CREATE TABLE IF NOT EXISTS `sfk_arctype` (
					  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
					  `pid` int(10) unsigned NOT NULL,
					  `tid` int(10) unsigned NOT NULL,
					  `name` varchar(255) NOT NULL,
					  `uname` varchar(255) NOT NULL,
					  `view` varchar(255) NOT NULL,
					  `title` varchar(255) NOT NULL,
					  `keywords` varchar(255) NOT NULL,
					  `description` varchar(500) NOT NULL,
					  `sort` int(10) unsigned NOT NULL,
					  PRIMARY KEY (`id`),
					  UNIQUE KEY `name` (`name`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1',
					
					"CREATE TABLE IF NOT EXISTS `sfk_article` (
					  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
					  `atid` int(10) unsigned NOT NULL,
					  `flag` set('h','a','c') DEFAULT NULL COMMENT '头条,特推,推荐, 跳转',
					  `title` char(32) NOT NULL COMMENT '标题',
					  `keywords` varchar(128) NOT NULL,
					  `description` varchar(512) NOT NULL,
					  `content` text NOT NULL,
					  `editor` char(32) NOT NULL,
					  `eid` int(10) unsigned NOT NULL,
					  `pubdate` int(10) unsigned NOT NULL,
					  `weights` int(10) unsigned NOT NULL,
					  `litpic` varchar(128) NOT NULL,
					  `isdelete` tinyint(4) DEFAULT '0',
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1",
					
					"CREATE TABLE IF NOT EXISTS `sfk_comment` (
					  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
					  `aid` int(10) unsigned NOT NULL COMMENT '评论目标id',
					  `content` varchar(512) NOT NULL COMMENT '评论内容',
					  `mid` int(10) unsigned NOT NULL,
					  `ancestor` varchar(1024) NOT NULL DEFAULT '' COMMENT '祖先id集合',
					  `date` int(10) unsigned NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1",
					
					"CREATE TABLE IF NOT EXISTS `sfk_manage` (
					  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
					  `name` char(32) NOT NULL,
					  `pname` char(32) NOT NULL,
					  `password` char(32) NOT NULL,
					  `power` int(10) unsigned NOT NULL,
					  `rtime` int(10) unsigned NOT NULL,
					  `vcount` int(10) unsigned DEFAULT '0',
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1",
					
					"CREATE TABLE IF NOT EXISTS `sfk_member` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `username` char(32) NOT NULL,
					  `password` char(32) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1",
					
					"CREATE TABLE IF NOT EXISTS `sfk_session` (
					  `id` char(32) NOT NULL,
					  `data` varchar(2550) NOT NULL,
					  `expire` int(11) unsigned NOT NULL,
					  PRIMARY KEY (`id`),
					  KEY `id` (`id`)
					) ENGINE=MEMORY DEFAULT CHARSET=utf8",
					
					"REPLACE INTO `sfk_manage` (`id`, `name`, `pname`, `password`, `power`, `rtime`, `vcount`) VALUES(1, '{$_POST['manage_name']}', '{$_POST['manage_name']}', md5(sha1('{$_POST['manage_pw']}')), 32767, UNIX_TIMESTAMP(), 0)",
					
					"REPLACE INTO `sfk_member` (`id`, `username`, `password`) VALUES(1, '匿名网友', '')"
			);
			foreach ($query as $value){
				$param=array('sql'=>$value);
				//该执行 出错时 会直接抛出异常！
				$model->getDB()->execute($param);
			}
			$filename='Source/Conf/Conf.inc.php';
			
			$str_file=file_get_contents($filename);
			$pattern="/'DB_HOST',.*?\)/";
			if(preg_match($pattern,$str_file)){
				$_POST['db_host']=addslashes($_POST['db_host']);
				$str_file=preg_replace($pattern,"'DB_HOST','{$_POST['db_host']}')", $str_file);
			}
			$pattern="/'DB_USER',.*?\)/";
			if(preg_match($pattern,$str_file)){
				$_POST['db_user']=addslashes($_POST['db_user']);
				$str_file=preg_replace($pattern,"'DB_USER','{$_POST['db_user']}')", $str_file);
			}
			$pattern="/'DB_PASSWORD',.*?\)/";
			if(preg_match($pattern,$str_file)){
				$_POST['db_pw']=addslashes($_POST['db_pw']);
				$str_file=preg_replace($pattern,"'DB_PASSWORD','{$_POST['db_pw']}')", $str_file);
			}
			$pattern="/'DB_DATABASE',.*?\)/";
			if(preg_match($pattern,$str_file)){
				$_POST['db_database']=addslashes($_POST['db_database']);
				$str_file=preg_replace($pattern,"'DB_DATABASE','{$_POST['db_database']}')", $str_file);
			}
			$pattern="/\('DB_PORT',.*?\)/";
			if(preg_match($pattern,$str_file)){
				$_POST['db_port']=addslashes($_POST['db_port']);
				$str_file=preg_replace($pattern,"('DB_PORT',{$_POST['db_port']})", $str_file);
			}
			if(!file_put_contents($filename, $str_file)){
				exit('"{status":0,"message":"配置文件写入失败，请检查文件'.$filename.'的权限!"}');
			}
			if(!file_put_contents('Source/Install/install.lock',':))')){
				exit('{"status":0,"message":"文件Source/Install/install.lock创建失败，但是您的系统其实已经安装了，您可以手动建立Source/Install/install.lock文件!"}');
			}
			
			exit('{"status":1,"message":"系统安装成功！"}');
		}
		
	}
	//安装完成
	public function step5(){
		$this->view->display();
	}
	
	
}