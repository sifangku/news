<?php
/**
 * SFKNews
 * 私房库 www.sifangku.com 教学项目
 *
 * @desc Model基类
 * @copyright 私房库 www.sifangku.com 保留所有版权
 * @author 孙胜利 www.sunshengli.com
 * @version 1.0
 *
 **/
namespace Common;
if(!defined('SITE')) exit('Access Denied');
class Model {
	protected $config;
	protected $db;
	protected $dbName;
	protected $tables;
	protected $tablePrefix;
	public 	  $data;
	protected $dataOriginal;//原始数据（用户提交过来的）
	protected $dataBeforeFilter;//在过滤之前的数据
	protected $status;
	protected $rule;//规则 数组、0为静态规则、1为动态规则
	protected $isAutoValidate=true;
	protected $error;
	protected $autoRule;//自动完成的规则
	protected $isAutoOperation=true;
	protected $options;
	protected $isAutoValidateByTable=true;
	protected $autoCheckFields=true;
	//默认提示信息
	protected $validateMessage=array(
			'notnull'=>'{field}不得为空',
			'require'=>'{field}必须',
			'unique'=>'{field}中已经存在{value}',
			'number'=>'{field}必须是数字',
			'length'=>'{field}长度不被允许',
			'email'=>'{field}格式错误',
			'url'=>'{field}格式错误',
			'currency'=>'{field}格式错误',
			'between'=>'{field}的值必须在{rule_0}-{rule_1}区间内',
			'notbetween'=>'{field}的值不得在{rule_0}-{rule_1}区间内',
			'>'=>'{field}的值必须大于{rule}',
			'<'=>'{field}的值必须小于{rule}',
			'>='=>'{field}的值不得小于{rule}',
			'<='=>'{field}的值不得大于{rule}',
			'=='=>'{field}的值必须等于{rule}',
			'!='=>'{field}的值不得等于{rule}',
			'in'=>'{field}的值必须为 【{rule|、}】 中的一项',
			'notin'=>'{field}的值不得为{rule|、}中的任意一项',
			'multiin'=>'{field}中的任意一值必须为{rule|、}中某项',
			'multinotin'=>'{field}中的任意一值不得为{rule|、}中某项',
			'regex'=>'{field}格式不符合{rule}描述',
			'function'=>'{field}未通过函数：{rule_0}的验证',
			'method'=>'{field}未通过方法：{rule_0}的验证',
			'confirm'=>'{field}与{rule}不一致'
	);
	//验证时机
	const INSERT=1;
	const UPDATE=2;
	//验证条件
	const EXISTS_VALIDATE=-1;//值存在就验证
	const MUST_VALIDATE=-2;//无论如何都要验证
	const VALUE_VALIDATE=-3;//值不为空的时候验证
	static protected $tableInfo;
	public function __construct(Array $config=null,$tablePrefix=null){
		if($config==null){
			if(defined('DB_HOST')){
				$this->config['host']=\DB_HOST;
			}
			if(defined('DB_USER')){
				$this->config['user']=\DB_USER;
			}
			if(defined('DB_PASSWORD')){
				$this->config['password']=\DB_PASSWORD;
			}
			if(defined('DB_DATABASE')){
				$this->config['database']=\DB_DATABASE;
			}
			if(defined('DB_PORT')){
				$this->config['port']=\DB_PORT;
			}
			if(defined('DB_CHARSET')){
				$this->config['charset']=\DB_CHARSET;
			}
		}else{
			$this->config=$config;
		}
		if(!isset($tablePrefix)){
			if(defined('DB_PREFIX')){
				$this->tablePrefix=\DB_PREFIX;
			}else{
				$this->tablePrefix='';
			}
		}else{
			$this->tablePrefix=$tablePrefix;
		}
		$this->parseTableName();
	}
	public function parseTableName(){
		$className=explode('\\',get_class($this));
		
		//$className=explode('\\','BacArticleModel');
		//$className=explode('\\','Model');
		$className=substr($className[count($className)-1],0,-5);
		$className=strtolower(trim(preg_replace('/[A-Z]/','_$0',$className),'_'));
		if($className!=''){
			$this->table($className);
		}
	}
	public function getDB(){
		if(!isset($this->db)){
			$this->db=new DB($this->config);
		}
		return $this->db;
	}
	//获取当前连接的数据库服务器地址
	public function getDBHost(){
		if(isset($this->config['host'])){
			return $this->config['host'];
		}else{
			return 'localhost';
		}
	}
	//获取当前连接的数据库服务器端口号
	public function getDBPort(){
		if(isset($this->config['port'])){
			return $this->config['port'];
		}else{
			return 3306;
		}
	}
	//选择操作的数据库
	public function selectDB($dbname){
		if($this->getDB()->select_db($dbname)){
			$this->dbName=$dbname;
			return true;
		}else{
			return false;
		}
	}
	//获取当前操作的数据库名称
	public function getDBName(){
		if(isset($this->dbName)){
			return $this->dbName;
		}elseif(isset($this->config['database'])){
			return $this->config['database'];
		}else{
			$sql='select database()';
			$data=$this->getDB()->execute(array('sql'=>$sql));
			return $data[0]['database()'];
		}
	}
	//获取表信息
	public function getTableInfo($tableName=null,$option=null,$field=null){
		if(!isset($tableName)){
			$tableName=$this->getTableName();
		}
		$indexDBHost=$this->getDBHost().'_'.$this->getDBPort();
		$indexDBName=$this->getDBName();
		$indexTableName=$tableName;
		
		if(!isset(self::$tableInfo[$indexDBHost][$indexDBName][$indexTableName])){
			$fileName=PATH_CACHE."/DataBase/{$indexDBHost}/{$indexDBName}/{$indexTableName}.inc.php";
			if(!file_exists($fileName)){
				$sql="SHOW FULL COLUMNS FROM {$tableName}";
				$info=$this->getDB()->execute(array('sql'=>$sql));
				foreach ($info as $val){
					//字段
					self::$tableInfo[$indexDBHost][$indexDBName][$indexTableName]['fields'][]=$val['Field'];
					//主键
					if($val['Key']=='PRI'){
						self::$tableInfo[$indexDBHost][$indexDBName][$indexTableName]['pk']=$val['Field'];
					}
					//注释
					self::$tableInfo[$indexDBHost][$indexDBName][$indexTableName]['fields_comment'][$val['Field']]=$val['Comment'];
					//字段类型（bind用）
					if(strpos($val['Type'],'int')!==false){
						self::$tableInfo[$indexDBHost][$indexDBName][$indexTableName]['fields_type'][$val['Field']]='i';
					}elseif(strpos($val['Type'],'float')!==false || strpos($val['Type'],'double'!==false)){
						self::$tableInfo[$indexDBHost][$indexDBName][$indexTableName]['fields_type'][$val['Field']]='d';
					}else{
						self::$tableInfo[$indexDBHost][$indexDBName][$indexTableName]['fields_type'][$val['Field']]='s';
					}
					//字段规则自动生成（入库时检测用）
					/*
					 require
					什么时候字段可以不传
					允许为空
					自增
					有默认值
					number
					int
					float
					double
					decimal
					unique
						
					length
					*/
					if($val['Null']=='YES' || strpos($val['Extra'],'auto_increment')!==false || $val['Default']!==null){
						//self::$tableInfo[$indexDBHost][$indexDBName][$indexTableName]['fields_rules'][$val['Field']]['require']=false;
					}else{
						self::$tableInfo[$indexDBHost][$indexDBName][$indexTableName]['fields_rules'][$val['Field']]['notnull']=true;
					}
					if(strpos($val['Type'],'int')!==false || strpos($val['Type'],'float')!==false || strpos($val['Type'],'double')!==false || strpos($val['Type'],'decimal')!==false){
						self::$tableInfo[$indexDBHost][$indexDBName][$indexTableName]['fields_rules'][$val['Field']]['number']=true;
					}
					if(strpos($val['Key'],'PRI')!==false || strpos($val['Key'],'UNI')!==false){
						self::$tableInfo[$indexDBHost][$indexDBName][$indexTableName]['fields_rules'][$val['Field']]['unique']=true;
					}
					if(strpos($val['Type'],'char')===0 || strpos($val['Type'],'varchar')===0){
						$tmp=explode('(',$val['Type']);
						$length='0,'.rtrim($tmp[1],')');
						self::$tableInfo[$indexDBHost][$indexDBName][$indexTableName]['fields_rules'][$val['Field']]['length']=$length;
					}
				}
				if(!file_exists(dirname($fileName))){
					if(!mkdir(dirname($fileName),0777,true)){
						throw new \Exception('目录创建失败：'.dirname($fileName));
					}
				}
				$contents="
<?php
/**
* SFKNews
* 私房库 www.sifangku.com 教学项目
*
* @desc {$indexTableName} 数据表分析信息
* @copyright 私房库 www.sifangku.com 保留所有版权
* @author 孙胜利 www.sunshengli.com
* @version 1.01
*
**/
//检测是否有令牌
if(!defined('SITE')) exit('Access Denied');
return <<<ETO
".serialize(self::$tableInfo[$indexDBHost][$indexDBName][$indexTableName])."
ETO
;";
				if(!file_put_contents($fileName,$contents)){
						throw new \Exception('文件写入失败失败：'.$fileName);
				}
			}else{
				self::$tableInfo[$indexDBHost][$indexDBName][$indexTableName]=unserialize(include $fileName);
			}
		}
		if(isset($option) && isset($field)){
			if(isset(self::$tableInfo[$indexDBHost][$indexDBName][$indexTableName][$option][$field])){
				return self::$tableInfo[$indexDBHost][$indexDBName][$indexTableName][$option][$field];
			}else{
				return null;
			}
		}elseif(isset($option)){
			if(isset(self::$tableInfo[$indexDBHost][$indexDBName][$indexTableName][$option])){
				return self::$tableInfo[$indexDBHost][$indexDBName][$indexTableName][$option];
			}else{
				return null;
			}
		}else{
			return self::$tableInfo[$indexDBHost][$indexDBName][$indexTableName];
		}
	}
	public function table($tables){
		$this->tables=explode(',',$tables);
		return $this;
	}
	public function getTableName($index=0){
		if(isset($this->tables[$index])){
			return $this->tablePrefix.$this->tables[$index];
		}else{
			throw new \Exception('未指定操作的目标表.');	
		}
	}
	public function create(Array $data=null,$status=null){
		//获取数据
		if(is_null($data)){
			$this->data=$_POST;
		}else{
			$this->data=$data;
		}
		$this->dataOriginal=$this->data;
		//设置当前操作状态
		if(is_null($status)){
			if(array_key_exists($this->getTableInfo(null,'pk'),$this->data)){
				$this->status=self::UPDATE;
			}else{
				$this->status=self::INSERT;
			}
		}else{
			$this->status=$status;
		}
		
		//自动验证
		if($this->isAutoValidate && isset($this->rule[$this->rIndex()])){
			if(!$this->autoValidate()){
				return false;
			}
		}
		//自动完成
		if($this->isAutoOperation && isset($this->autoRule[$this->aIndex()])){
			$this->autoOperation();
		}
		$this->dataBeforeFilter=$this->data;
		
		//字段过滤
		/*
		可以指定数据里面 只能包含某些字段
		可以指定数据里面 不能包含某些字段
		*/
		if(isset($this->options['field'])){
			$fields=$this->options['field'];
		}elseif($this->status==self::INSERT && isset($this->insertFields)){
			$fields=$this->insertFields;
		}elseif($this->status==self::UPDATE && isset($this->updateFields)){
			$fields=$this->updateFields;
		}
		if(isset($fields)){
			$this->fieldFilter($fields);
		}
		//根据数据表字段过滤数据
		if($this->autoCheckFields){
			$this->fieldFilter(array(implode(',',$this->getTableInfo(null,'fields'))));
		}
		//根据数据表字段规则进行验证
		if($this->isAutoValidateByTable && !is_null($this->getTableInfo(null,'fields_rules'))){
			if(isset($this->options['validateByTable'])){
				$mode=$this->options['validateByTable'];
			}else{
				$mode=$this->status;
			}
			if(!$this->autoValidateByTable($mode)){
				return false;
			}
		}
		$this->clean('status,rule,isAutoValidate,autoRule,isAutoOperation,isAutoValidateByTable,options');
		return $this->data;
	}
	protected function autoValidate(){
		foreach ($this->rule[$this->rIndex()]['rules'] as $field=>$ruleArray){
			foreach ($ruleArray as $ruleName=>$rule){
				if($this->hasTime($field,$ruleName) && $this->hasCondition($field,$ruleName)){
					$data=isset($this->data[$field]) ? $this->data[$field] : null;
					switch ($ruleName){
						case 'unique':
							$data=array($field=>$data);
							if($this->status==self::UPDATE){
								$data['pk']=$this->data[$this->getTableInfo(null,'pk')];
							}
							break;
						case 'confirm':
							$data=array($data);
							array_push($data,isset($this->data[$rule]) ? $this->data[$rule] : null);
							break;
					}
					if(!$this->check($data,$ruleName,$rule)){
						$this->error=$this->getValidateMessage($field,$ruleName);
						return false;
					}
				}
			}
		}
		return true;
	}
	protected function autoValidateByTable($mode){
		if($mode==self::INSERT){
			foreach ($this->getTableInfo(null,'fields_rules') as $field=>$ruleArray){
				if(array_key_exists('notnull',$ruleArray)){
					if(!isset($this->data[$field])){
						$this->error=$this->getValidateMessage($field,'notnull');
						return false;
					}
				}
				if(array_key_exists($field,$this->data)){
					foreach ($ruleArray as $ruleName=>$rule){
						$data=$this->data[$field];
						if($ruleName=='unique'){
							$data=array($field=>$data);
						}elseif($ruleName=='notnull'){
							continue;
						}
						if(!$this->check($data,$ruleName,$rule)){
							$this->error=$this->getValidateMessage($field,$ruleName);
							return false;
						}
					}
				}
			}
		}elseif($mode==self::UPDATE){
			foreach($this->data as $field=>$value){
				if(!is_null($this->getTableInfo(null,'fields_rules',$field))){
					foreach ($this->getTableInfo(null,'fields_rules',$field) as $ruleName=>$rule){
						if($ruleName=='unique'){
							$data=array($field=>$value,'pk'=>$this->dataBeforeFilter[$this->getTableInfo(null,'pk')]);
						}else{
							$data=$value;
						}
						if(!$this->check($data,$ruleName,$rule)){
							$this->error=$this->getValidateMessage($field,$ruleName);
							return false;
						}
					}
				}
			}
		}
		return true;
	}
	public function validate($rule){
		if(is_bool($rule)){
			$this->isAutoValidate=$rule;
		}else{
			$this->rule[1]=$rule;
		}
		return $this;
	}
	protected function rIndex(){
		if(isset($this->rule[1])){
			return 1;
		}else{
			return 0;
		}
	}
	//获取某个字段的某个规则的验证时机
	protected function getValidateTime($field,$ruleName){
		$default=array(self::INSERT,self::UPDATE);
		if(isset($this->rule[$this->rIndex()]['time'][$field][$ruleName])){
			if(is_array($this->rule[$this->rIndex()]['time'][$field][$ruleName])){
				return $this->rule[$this->rIndex()]['time'][$field][$ruleName];
			}else{
				return array($this->rule[$this->rIndex()]['time'][$field][$ruleName]);
			}
		}elseif(isset($this->rule[$this->rIndex()]['time'][$field])){
			if(!is_array($this->rule[$this->rIndex()]['time'][$field])){
				return array($this->rule[$this->rIndex()]['time'][$field]);
			}else{
				if(is_string(key($this->rule[$this->rIndex()]['time'][$field]))){
					return $default;
				}else{
					return $this->rule[$this->rIndex()]['time'][$field];
				}
			}
		}else{
			return $default;
		}
	}
	//是否符合验证时机
	protected function hasTime($field,$ruleName){
		return in_array($this->status,$this->getValidateTime($field,$ruleName));
	}
	//获取某个字段的某个规则的验证条件
	protected function getValidateCondition($field,$ruleName){
		if(isset($this->rule[$this->rIndex()]['condition'][$field][$ruleName])){
			return $this->rule[$this->rIndex()]['condition'][$field][$ruleName];
		}elseif(isset($this->rule[$this->rIndex()]['condition'][$field]) && !is_array($this->rule[$this->rIndex()]['condition'][$field])){
			return $this->rule[$this->rIndex()]['condition'][$field];
		}else{
			return self::EXISTS_VALIDATE;
		}
	}
	//是否符合验证条件
	protected function hasCondition($field,$ruleName){
		switch ($this->getValidateCondition($field,$ruleName)){
			case self::EXISTS_VALIDATE:
				return isset($this->data[$field]);
				break;
			case self::MUST_VALIDATE:
				return true;
				break;
			case self::VALUE_VALIDATE:
				return isset($this->data[$field]) && trim($this->data[$field])!='';
				break;
		}
		
	}
	//获取某个字段的某个规则未验证通过时的提示信息
	protected function getValidateMessage($field,$ruleName){
		if(isset($this->rule[$this->rIndex()]['message'][$field][$ruleName])){
			$message=$this->rule[$this->rIndex()]['message'][$field][$ruleName];
		}else{
			$message=$this->validateMessage[$ruleName];
		}
		$pattern='/{([a-z]+)([\|_]?)(.*?)}/';
		if(preg_match_all($pattern,$message,$data,PREG_SET_ORDER)){
			foreach ($data as $val){
				switch ($val[1]){
					case 'field':
						$message=str_replace($val[0],$this->getValidateFieldAlias($field),$message);
						break;
					case 'value':
						$message=str_replace($val[0],isset($this->data[$field]) ? $this->data[$field] : null,$message);
						break;
					case 'rule':
						switch ($val[2]){
							case '_':
							case '|':
								if(is_string($this->rule[$this->rIndex()]['rules'][$field][$ruleName])){
									$tmp=explode(',',$this->rule[$this->rIndex()]['rules'][$field][$ruleName]);
								}else{
									$tmp=$this->rule[$this->rIndex()]['rules'][$field][$ruleName];
								}
								if($val[2]=='_'){
									$message=str_replace($val[0],$tmp[$val[3]],$message);
								}else{
									$message=str_replace($val[0],implode($val[3],$tmp),$message);
								} 
								break;
							case '':
								$message=str_replace($val[0],$this->rule[$this->rIndex()]['rules'][$field][$ruleName],$message);
						}
						break;
				}
			}
		}
		return $message;
	}
	//获取验证提示用的字段名称
	protected function getValidateFieldAlias($field){
		if(isset($this->rule[$this->rIndex()]['fields_alias'][$field])){
			return $this->rule[$this->rIndex()]['fields_alias'][$field];
		}elseif($this->getTableInfo(null,'fields_comment',$field)!=null && $this->getTableInfo(null,'fields_comment',$field)!=''){
			return $this->getTableInfo(null,'fields_comment',$field);
		}else{
			return $field;
		}
	}
	//规则验证
	public function check($data,$ruleName,$rule){
		switch($ruleName){
			case 'notnull':
				if($rule){
					return !is_null($data);
				}else{
					return true;
				}
				break;
			case 'require':
				if($rule){
					return preg_match('/\S+/',$data);
				}else{
					return true;
				}
				break;
			case 'unique':
				if($rule){
					$key=key($data);//获取当前键名称（字段名）
					if(isset($data['pk'])){
						if($key=='pk'){
							next($data);//数组指针向后移动一项
							$key=key($data);
						}
						$sql="select {$key} from {$this->getTableName()} where {$key}=? AND `{$this->getTableInfo(null,'pk')}`!=?";
						$bind=array($this->getTableInfo(null,'fields_type',$key).$this->getTableInfo(null,'fields_type',$this->getTableInfo(null,'pk')),array($data[$key],$data['pk']));
					}else{
						$sql="select {$key} from {$this->getTableName()} where {$key}=?";
						$bind=array($this->getTableInfo(null,'fields_type',$key),array($data[$key]));
					}
					$param=array(
							'sql'=>$sql,
							'bind'=>$bind
					);
					if(count($this->getDB()->execute($param))){
						return false;
					}else{
						return true;
					}
				}else{
					return true;
				}
				break;
			case 'number':
				return $rule ? is_numeric($data) : true;
				break;
			case 'length':
				$dataLength=mb_strlen($data,'utf-8');
				if(is_numeric($rule)){
					return $dataLength==$rule;
				}else{
					$length=explode(',',$rule);
					if(is_numeric($length[0]) && is_numeric($length[1])){
						return $dataLength>=$length[0] && $dataLength<=$length[1];
					}elseif(is_numeric($length[0])){
						return $dataLength>=$length[0];
					}elseif(is_numeric($length[1])){
						return $dataLength<=$length[1];
					}else{
						return false;
					}
				}
				break;
			case 'email':
				if($rule){
					$pattern='/^\w+@[\w-]+\.com$/';
					return preg_match($pattern,$data);
				}else{
					return true;
				}
				break;
			case 'url':
				if($rule){
					$pattern='/^http(s?):\/\/([A-Za-z0-9-]+\.)+[A-Za-z]{2,4}(:\d+)?([\/\?#][\/=\?%\-&~`@[\]\':+!\.#\w]*)?$/';
					return preg_match($pattern,$data);
				}else{
					return true;
				}
				break;
			case 'currency':
				if($rule){
					$pattern='/^\d+(\.\d+)?$/';
					return preg_match($pattern,$data);
				}else{
					return true;
				}
				break;
			case 'between':
			case 'notbetween':
				$between=explode(',',$rule);
				if($ruleName=='between'){
					return $data>=$between[0] && $data<=$between[1];
				}else{
					return $data<$between[0] || $data>$between[1];
				}
				break;
			case '>':
				return $data>$rule;
				break;
			case '<':
				return $data<$rule;
				break;
			case '>=':
				return $data>=$rule;
				break;
			case '<=':
				return $data<=$rule;
				break;
			case '==':
				return $data==$rule;
				break;
			case '!=':
				return $data!=$rule;
				break;
			case 'in':
			case 'notin':
			case 'multiin':
			case 'multinotin':
				if(is_string($rule)){
					$in=explode(',',$rule);
				}elseif(is_array($rule)){
					$in=$rule;
				}else{
					return false;
				}
				switch ($ruleName){
					case 'in':
						return in_array($data,$in);
						break;
					case 'notin':
						return !in_array($data,$in);
						break;
					case 'multiin':
						foreach($data as $val){
							if(!in_array($val,$in)){
								return false;
							}
						}
						return true;
						break;
					case 'multinotin':
						foreach($data as $val){
							if(in_array($val,$in)){
								return false;
							}
						}
						return true;
						break;
				}
				break;
			case 'regex':
				return preg_match($rule,$data);
				break;
			case 'function':
			case 'method':
				if(isset($rule[1])){
					$param=$rule[1];
					array_unshift($param,$data);
				}else{
					$param=array($data);
				}
				return call_user_func_array($ruleName=='function' ? $rule[0] : array($this,$rule[0]),$param);
				break;
			case 'confirm':
				return $data[0]==$data[1];
				break;
		}
	}
	public function auto($autoRule){
		if(is_bool($autoRule)){
			$this->isAutoOperation=$autoRule;
		}else{
			$this->autoRule[1]=$autoRule;
		}
		return $this;
	}
	protected function aIndex(){
		if(isset($this->autoRule[1])){
			return 1;
		}else{
			return 0;
		}
	}
	protected function autoOperation(){
		foreach ($this->autoRule[$this->aIndex()] as $value){
			if(isset($value[2])){
				if(!is_array($value[2])){
					$value[2]=array($value[2]);
				}
			}else{
				$value[2]=array(self::INSERT,self::UPDATE);
			}
			if(in_array($this->status,$value[2])){
				$way=explode('-',$value[1]);
				if(isset($this->data[$value[0]])){
					$data=$this->data[$value[0]];
				}else{
					$data=null;
				}
				switch ($way[0]){
					case 'function':
					case 'method':
						$this->data[$value[0]]=call_user_func($way[0]=='function' ? $way[1] : array($this,$way[1]),$data);
						break;
					case 'field':
						$this->data[$value[0]]=isset($this->data[$way[1]]) ? $this->data[$way[1]] : null;
						break;
					case 'string':
						$this->data[$value[0]]=$way[1];
						break;
					case 'ignore':
						if(trim($data)==''){
							unset($this->data[$value[0]]);
						}
						break;
				}
			}
		}
	}
	//字段过滤
	/*
	可以指定数据里面 只能包含某些字段
	可以指定数据里面 不能包含某些字段
	传参：数组 可以包含两个元素
	     array('filed1,field2')
	     array('filed1,field2',false)
	*/
	protected function fieldFilter($option){
		if(isset($option[0])){
			$fields=explode(',',$option[0]);
			if(isset($option[1]) && !$option[1]){
				$flag=false;
			}else{
				$flag=true;
			}
			foreach ($this->data as $key=>$value){
				if($flag){
					if(!in_array($key,$fields)){
						unset($this->data[$key]);
					}
				}else{
					if(in_array($key,$fields)){
						unset($this->data[$key]);
					}
				}
			}
		}
	}
	public function validateByTable(){
		if(is_bool(func_get_arg(0))){
			$this->isAutoValidateByTable=func_get_arg(0);
		}else{
			$this->options['validateByTable']=func_get_arg(0);
		}
		return $this;
	}
	public function select(){
		$sql='SELECT';
		if(isset($this->options['field'][0])){
			$sql.=' '.$this->options['field'][0];
		}else{
			$sql.=' *';
		}
		if(is_array($this->tables) && count($this->tables)>0 && !empty($this->tables[0])){
			$sql.=' FROM '.$this->tablePrefix.str_replace(',',",{$this->tablePrefix}",implode(',',$this->tables));
		}else{
			throw new \Exception('未指定操作表.');
		}
		if(isset($this->options['join'][0])){
			$sql.=' '.$this->options['join'][0];
		}
		if(isset($this->options['where'][0])){
			$sql.=' WHERE '.$this->options['where'][0];
		}
		if(isset($this->options['group'][0])){
			$sql.=' GROUP BY '.$this->options['group'][0];
		}
		if(isset($this->options['having'][0])){
			$sql.=' HAVING '.$this->options['having'][0];
		}
		if(isset($this->options['order'][0])){
			$sql.=' ORDER BY '.$this->options['order'][0];
		}
		if(isset($this->options['limit'][0])){
			$sql.=' LIMIT '.$this->options['limit'][0];
		}
		$param=array(
			'sql'=>$sql
		);
		if(isset($this->options['bind'])){
			$param['bind']=$this->options['bind'];
		}
		$this->clean('tables,options');
		
		return $this->getDB()->execute($param);
	}
	public function __call($name,$arguments){
		$methods=array(
			'field',
			'join',
			'where',
			'group',
			'having',
			'order',
			'limit',
			'bind'
		);
		if(in_array($name,$methods)){
			$this->options[$name]=$arguments;
			return $this;
		}else{
			throw new \Exception(get_class()."类里面的{$name}不存在.");
		}
	}
	public function add(){
		if(is_array($this->data) && count($this->data)){
			$sql='INSERT INTO '.$this->getTableName().'('.implode(',',array_keys($this->data)).') values('.implode(',',array_fill(0,count($this->data),'?')).')';
			$types='';
			foreach ($this->data as $key=>$value){
				$types.=$this->getTableInfo(null,'fields_type',$key);
			}
			$data=$this->data;
			$this->clean('tables,options,data,dataOriginal,dataBeforeFilter');
			return $this->getDB()->execute(array(
					'sql'=>$sql,
					'bind'=>array($types,$data)
			));
		}else{
			$this->clean('tables,options,data,dataOriginal,dataBeforeFilter');
			return 0;
		}
	}
	public function update(){
		if(is_array($this->data) && count($this->data)){
			$fields='';
			$types='';
			$where='';
			foreach ($this->data as $key=>$value){
				$fields.="{$key}=?,";
				$types.=$this->getTableInfo(null,'fields_type',$key);
			}
			$fields=rtrim($fields,',');
			if(isset($this->options['where'][0])){
				$where=" WHERE {$this->options['where'][0]}";
			}else{
				return 0;
			}
			$sql='UPDATE '.$this->getTableName()." SET {$fields} {$where}";
			$param=array(
					'sql'=>$sql,
					'bind'=>array($types,$this->data)
			);
			if(isset($this->options['bind'])){
				$param['bind'][0].=$this->options['bind'][0];
				$param['bind'][1]=array_merge($param['bind'][1],$this->options['bind'][1]);
			}
			$this->clean('tables,options,data,dataOriginal,dataBeforeFilter');
			return $this->getDB()->execute($param);
		}else{
			$this->clean('tables,options,data,dataOriginal,dataBeforeFilter');
			return 0;
		}
	}
	public function delete(){
		if(isset($this->options['where'][0])){
			$where=" WHERE {$this->options['where'][0]}";
		}else{
			return 0;
		}
		$sql='DELETE FROM '.$this->getTableName()."{$where}";
		$param=array(
				'sql'=>$sql
		);
		if(isset($this->options['bind'])){
			$param['bind']=$this->options['bind'];
		}
		$this->clean('tables,options');
		return $this->getDB()->execute($param);
	}
	protected function clean($param){
		/*
		$this->status=null;
		$this->rule[1]=null;
		$this->isAutoValidate=true;
		$this->autoRule[1]=null;
		$this->isAutoOperation=true;
		$this->isAutoValidateByTable=true;
		$this->options=null;
		
		
		$this->tables=null;
		$this->parseTableName();
		//$this->options=null;
		$this->data=null;
		$this->dataOriginal=null;
		$this->dataBeforeFilter=null;
		
		//create
		$this->clean('status,rule,isAutoValidate,autoRule,isAutoOperation,isAutoValidateByTable,options');
		//add、update
		$this->clean('tables,options,data,dataOriginal,dataBeforeFilter');
		//select
		$this->clean('tables,options');
		//delete
		$this->clean('tables,options');
		//all
		$this->clean('status,rule,isAutoValidate,autoRule,isAutoOperation,isAutoValidateByTable,options,tables,data,dataOriginal,dataBeforeFilter');
		
		if($flag=='create' || $flag=='all'){
			$this->status=null;
			$this->rule[1]=null;
			$this->isAutoValidate=true;
			$this->autoRule[1]=null;
			$this->isAutoOperation=true;
			$this->isAutoValidateByTable=true;
			$this->options=null;
		}
		if($flag=='crud' || $flag=='all'){
			$this->tables=null;
			$this->parseTableName();
			$this->options=null;
			$this->data=null;
			$this->dataOriginal=null;
			$this->dataBeforeFilter=null;
		}
		*/
		if(is_string($param)){
			$param=explode(',',$param);
		}
		foreach ($param as $value){
			switch ($value){
				case 'rule':
				case 'autoRule':
					$this->$value[1]=null;
					break;
				case 'isAutoValidate':
				case 'isAutoOperation':
				case 'isAutoValidateByTable':
					$this->$value=true;
					break;
				default:
					$this->$value=null;
					if($value=='tables'){
						$this->parseTableName();
					}
			}
		}
	}
	public function getError(){
		return $this->error;
	}
}