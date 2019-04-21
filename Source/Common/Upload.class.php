<?php
namespace Common;
if(!defined('SITE')) exit('Access Denied');
class Upload {
	const MODE_DEFAULT=0;//上传模式：默认
	const MODE_PHPINPUT=1;//上传模式：webuploader二进制上传,读取php://input
	/*
	 * mimes		允许上传的文件MiMe类型
	 * maxSize		上传的文件大小限制 (0-不做限制)
	 * exts			允许上传的文件后缀
	 * savePath		文件上传的保存路径
	 * saveName		上传文件命名规则，（数组式：[0]-函数名，[1]-参数数组）|（字符串）
	 * saveExt		文件保存后缀，空则使用原后缀
	 * replace		存在同名是否覆盖
	 * hash			是否生成hash编码
	 * inputName	上传的文件的input名称
	 * mode			上传模式[默认 self::MODE_DEFAULT | webuploader二进制上传 self::MODE_PHPINPUT]
	 */
	protected $config=array(
		'mimes'		=>	array(),			//允许上传的文件MiMe类型（留空为不限制）
		'maxSize'	=>	0,					//上传的文件大小限制 (0-不做限制)
		'exts'		=>	array(),			//允许上传的文件后缀（留空为不限制）
		'savePath'	=>	'./upload',			//文件上传的保存路径
		'saveName'	=>	array('uniqid', array()),//上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
		'saveExt'	=>	'',					//文件保存后缀，空则使用原后缀
		'replace'	=>	false,				//存在同名是否覆盖
		'hash'		=>	false,				//是否生成hash编码
		'inputName'	=>	'userfile',			//上传的文件的input名称,
		'mode'		=>	self::MODE_DEFAULT	//上传模式[默认 | 支持webuploader二进制上传]
	);
	protected $file;//上传的文件
	protected $error;//错误信息
	public function __construct($config=array()){
		$this->config=array_merge($this->config,$config);
	}
	public function __get($name){
		if(array_key_exists($name,$this->config)){
			return $this->config[$name];
		}
	}
	//设置配置参数
	function set(){
		$argsNum=func_num_args();
		if($argsNum==1){
			$config=func_get_arg(0);
			if(is_array($config)){
				$this->config=array_merge($this->config,$config);
			}
		}elseif($argsNum==2){
			$this->config[func_get_arg(0)]=func_get_arg(1);
		}
		return $this;
	}
	public function getError(){
		return $this->error;
	}
	public function upload(){
		switch ($this->mode){
			case self::MODE_DEFAULT:
				if(isset($_FILES[$this->inputName])){
					if(isset($_FILES[$this->inputName]['name']) && is_array($_FILES[$this->inputName]['name'])){
						/*
						 *********本类不支持此方式的多文件上传*********
						<input name="userfile[]" type="file" />
						<input name="userfile[]" type="file" />
						*/
						throw new \Exception('不支持表单控件数组式 多文件上传.');
					}
					$this->file=$_FILES[$this->inputName];
				}else{
					$this->error="input控件 {$this->inputName} 指定错误.";
					return false;
				}
				break;
			case self::MODE_PHPINPUT:
				$this->setPhpInputFileInfo();
				break;
		}
		/* 无效上传 */
		if (empty($this->file['name'])){
			$this->error = "未知确切的上传错误，极有可能是文件名获取失败！";
			return false;
		}
		//后缀
		$this->file['ext'] = pathinfo($this->file['name'], PATHINFO_EXTENSION);
		//检测上传目录
		if(!$this->checkPath($this->savePath)){
			$this->error="上传目录 {$this->savePath} 创建失败,请手动检查.";
			return false;
		}
		//检测合法性
		if (!$this->check()){
			return false;
		}
		//是否生成hash
		if($this->hash){
			$this->file['md5']  = md5_file($this->file['tmp_name']);
			$this->file['sha1'] = sha1_file($this->file['tmp_name']);
		}
		//生成保存文件名
		$savename = $this->getSaveName();
		if(false == $savename){
			return false;
		} else {
			$this->file['savename'] = $savename;
		}
		//保存文件 并记录保存成功的文件
		if($this->save()){
			return $this->file;
		}else{
			return false;
		}
	}
	/**
	 * 当上传模式为self::MODE_PHPINPUT时 设置好文件的相关信息（文件名、mime类型、size 等等）
	 */
	protected function setPhpInputFileInfo(){
		$indexs=array('name','type','size');
		foreach ($indexs as $value){
			if(empty($this->config[$value])){
				if($value=='name'){
					$this->file[$value]=uniqid("file_");
				}else{
					$this->file[$value]='';
				}
			}elseif (is_array($this->config[$value])){
				$param_arr = isset($this->config[$value][1]) ? $this->config[$value][1] : array();
				$this->file[$value]=call_user_func_array($this->$value[0],$param_arr);
			}elseif (is_string($this->config[$value])){
				$this->file[$value]=$this->$value;
			}
		}
	}
	//检测/生成 目录
	protected function checkPath($dir) {
		if(file_exists($dir)){
			if(!is_dir($dir)){
				return false;
			}
		}else{
			if(!mkdir($dir,0777,true)){
				return false;
			}
		}
		return true;
	}
	/**
	 * 检查上传的文件
	 */
	private function check() {
		/* 文件上传失败，捕获错误代码 */
		if ($this->mode==self::MODE_DEFAULT && $this->file['error']) {
			$this->error($this->file['error']);
			return false;
		}

	
		/* 检查是否合法上传 */
		if ($this->mode==self::MODE_DEFAULT && !is_uploaded_file($this->file['tmp_name'])) {
			$this->error = '非法上传文件！';
			return false;
		}
	
		/* 检查文件大小 */
		if (!$this->checkSize()) {
			$this->error = '上传文件大小不符！';
			return false;
		}
	
		/* 检查文件Mime类型 */
		//TODO:FLASH上传的文件获取到的mime类型都为application/octet-stream
		if (!$this->checkMime()) {
			$this->error = '上传文件MIME类型不允许！';
			return false;
		}
	
		/* 检查文件后缀 */
		if (!$this->checkExt()) {
			$this->error = '上传文件后缀不允许';
			return false;
		}
		/* 通过检测 */
		return true;
	}
	//检测大小
	protected function checkSize(){
		return (0 == $this->maxSize) || $this->file['size'] <= $this->maxSize;
	}
	//检测文件类型
	protected function checkMime(){
		return empty($this->config['mimes']) ? true : in_array(strtolower($this->file['type']), $this->mimes);
	}
	//检测后缀
	protected function checkExt(){
		return empty($this->config['exts']) ? true : in_array(strtolower($this->file['ext']),$this->exts);
	}
	/**
	 * 获取错误代码信息
	 * @param string $errorNo  错误号
	 */
	private function error($errorNo) {
		switch ($errorNo) {
			case 1:
				$this->error = '上传的文件超过了 php.ini 中 upload_max_filesize 选项限制的值！';
				break;
			case 2:
				$this->error = '上传文件的大小超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值！';
				break;
			case 3:
				$this->error = '文件只有部分被上传！';
				break;
			case 4:
				$this->error = '没有文件被上传！';
				break;
			case 6:
				$this->error = '找不到临时文件夹！';
				break;
			case 7:
				$this->error = '文件写入失败！';
				break;
			default:
				$this->error = '未知上传错误！';
		}
	}
	//获取文件名保存
	function getSaveName(){
		if (empty($this->config['saveName'])) { //保持文件名不变
			$savename = substr(pathinfo("_{$this->file['name']}", PATHINFO_FILENAME), 1);
		}elseif (is_array($this->config['saveName'])){
			$param_arr = isset($this->config['saveName'][1]) ? $this->config['saveName'][1] : array();
			$savename=call_user_func_array($this->saveName[0],$param_arr);
		}elseif (is_string($this->config['saveName'])){
			$savename=$this->saveName;
		}
		if(empty($savename)){
			$this->error = '文件命名规则错误！';
			return false;
		}
		/* 文件保存后缀，支持强制更改文件后缀 */
		$ext = empty($this->config['saveExt']) ? $this->file['ext'] : $this->saveExt;
		return $savename . '.' . $ext;
	}
	//保存上传的文件
	protected function save(){
		$filename = trim($this->savePath,'/').'/' . iconv("UTF-8", "gb2312", $this->file['savename']);
		/* 不覆盖同名文件 */
		if(!$this->replace && is_file($filename)){
			$this->error = '存在同名文件' . $this->file['savename'];
			return false;
		}
		switch ($this->mode){
			case self::MODE_DEFAULT:
				if (!move_uploaded_file($this->file['tmp_name'],$filename)) {
					$this->error = '文件上传保存错误！';
					return false;
				}
				break;
			case self::MODE_PHPINPUT:
				$fileRead=fopen('php://input','rb');
				if($fileRead === false){
					$this->error = 'php://input读取失败！';
					return false;
				}
				$fileWrite=fopen($filename,'wb');
				if($fileWrite === false){
					$this->error = "目标文件 {$filename} 打开失败！";
					return false;
				}
				while ($buff = fread($fileRead, 4096)) {
					if(fwrite($fileWrite, $buff)===false){
						$this->error = "目标文件 {$filename} 数据写入失败！";
						return false;
					}
				}
				@fclose($fileRead);
				@fclose($fileWrite);
				break;
		}
		return true;
	}
	
}