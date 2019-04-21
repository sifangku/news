
<?php
/**
* SFKNews
* 私房库 www.sifangku.com 教学项目
*
* @desc sfk_manage 数据表分析信息
* @copyright 私房库 www.sifangku.com 保留所有版权
* @author 孙胜利 www.sunshengli.com
* @version 1.01
*
**/
//检测是否有令牌
if(!defined('SITE')) exit('Access Denied');
return <<<ETO
a:5:{s:6:"fields";a:7:{i:0;s:2:"id";i:1;s:4:"name";i:2;s:5:"pname";i:3;s:8:"password";i:4;s:5:"power";i:5;s:5:"rtime";i:6;s:6:"vcount";}s:2:"pk";s:2:"id";s:14:"fields_comment";a:7:{s:2:"id";s:0:"";s:4:"name";s:0:"";s:5:"pname";s:0:"";s:8:"password";s:0:"";s:5:"power";s:0:"";s:5:"rtime";s:0:"";s:6:"vcount";s:0:"";}s:11:"fields_type";a:7:{s:2:"id";s:1:"i";s:4:"name";s:1:"s";s:5:"pname";s:1:"s";s:8:"password";s:1:"s";s:5:"power";s:1:"i";s:5:"rtime";s:1:"i";s:6:"vcount";s:1:"i";}s:12:"fields_rules";a:7:{s:2:"id";a:2:{s:6:"number";b:1;s:6:"unique";b:1;}s:4:"name";a:2:{s:7:"notnull";b:1;s:6:"length";s:4:"0,32";}s:5:"pname";a:2:{s:7:"notnull";b:1;s:6:"length";s:4:"0,32";}s:8:"password";a:2:{s:7:"notnull";b:1;s:6:"length";s:4:"0,32";}s:5:"power";a:2:{s:7:"notnull";b:1;s:6:"number";b:1;}s:5:"rtime";a:2:{s:7:"notnull";b:1;s:6:"number";b:1;}s:6:"vcount";a:1:{s:6:"number";b:1;}}}
ETO
;