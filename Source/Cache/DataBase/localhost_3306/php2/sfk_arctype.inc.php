
<?php
/**
* SFKNews
* 私房库 www.sifangku.com 教学项目
*
* @desc sfk_arctype 数据表分析信息
* @copyright 私房库 www.sifangku.com 保留所有版权
* @author 孙胜利 www.sunshengli.com
* @version 1.01
*
**/
//检测是否有令牌
if(!defined('SITE')) exit('Access Denied');
return <<<ETO
a:5:{s:6:"fields";a:10:{i:0;s:2:"id";i:1;s:3:"pid";i:2;s:3:"tid";i:3;s:4:"name";i:4;s:5:"uname";i:5;s:4:"view";i:6;s:5:"title";i:7;s:8:"keywords";i:8;s:11:"description";i:9;s:4:"sort";}s:2:"pk";s:2:"id";s:14:"fields_comment";a:10:{s:2:"id";s:0:"";s:3:"pid";s:0:"";s:3:"tid";s:0:"";s:4:"name";s:0:"";s:5:"uname";s:0:"";s:4:"view";s:0:"";s:5:"title";s:0:"";s:8:"keywords";s:0:"";s:11:"description";s:0:"";s:4:"sort";s:0:"";}s:11:"fields_type";a:10:{s:2:"id";s:1:"i";s:3:"pid";s:1:"i";s:3:"tid";s:1:"i";s:4:"name";s:1:"s";s:5:"uname";s:1:"s";s:4:"view";s:1:"s";s:5:"title";s:1:"s";s:8:"keywords";s:1:"s";s:11:"description";s:1:"s";s:4:"sort";s:1:"i";}s:12:"fields_rules";a:10:{s:2:"id";a:2:{s:6:"number";b:1;s:6:"unique";b:1;}s:3:"pid";a:2:{s:7:"notnull";b:1;s:6:"number";b:1;}s:3:"tid";a:2:{s:7:"notnull";b:1;s:6:"number";b:1;}s:4:"name";a:3:{s:7:"notnull";b:1;s:6:"unique";b:1;s:6:"length";s:5:"0,255";}s:5:"uname";a:2:{s:7:"notnull";b:1;s:6:"length";s:5:"0,255";}s:4:"view";a:2:{s:7:"notnull";b:1;s:6:"length";s:5:"0,255";}s:5:"title";a:2:{s:7:"notnull";b:1;s:6:"length";s:5:"0,255";}s:8:"keywords";a:2:{s:7:"notnull";b:1;s:6:"length";s:5:"0,255";}s:11:"description";a:2:{s:7:"notnull";b:1;s:6:"length";s:5:"0,500";}s:4:"sort";a:2:{s:7:"notnull";b:1;s:6:"number";b:1;}}}
ETO
;