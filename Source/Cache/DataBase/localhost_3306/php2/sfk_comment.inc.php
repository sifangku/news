
<?php
/**
* SFKNews
* 私房库 www.sifangku.com 教学项目
*
* @desc sfk_comment 数据表分析信息
* @copyright 私房库 www.sifangku.com 保留所有版权
* @author 孙胜利 www.sunshengli.com
* @version 1.01
*
**/
//检测是否有令牌
if(!defined('SITE')) exit('Access Denied');
return <<<ETO
a:5:{s:6:"fields";a:6:{i:0;s:2:"id";i:1;s:3:"aid";i:2;s:7:"content";i:3;s:3:"mid";i:4;s:8:"ancestor";i:5;s:4:"date";}s:2:"pk";s:2:"id";s:14:"fields_comment";a:6:{s:2:"id";s:0:"";s:3:"aid";s:14:"评论目标id";s:7:"content";s:12:"评论内容";s:3:"mid";s:0:"";s:8:"ancestor";s:14:"祖先id集合";s:4:"date";s:0:"";}s:11:"fields_type";a:6:{s:2:"id";s:1:"i";s:3:"aid";s:1:"i";s:7:"content";s:1:"s";s:3:"mid";s:1:"i";s:8:"ancestor";s:1:"s";s:4:"date";s:1:"i";}s:12:"fields_rules";a:6:{s:2:"id";a:2:{s:6:"number";b:1;s:6:"unique";b:1;}s:3:"aid";a:2:{s:7:"notnull";b:1;s:6:"number";b:1;}s:7:"content";a:2:{s:7:"notnull";b:1;s:6:"length";s:5:"0,512";}s:3:"mid";a:2:{s:7:"notnull";b:1;s:6:"number";b:1;}s:8:"ancestor";a:1:{s:6:"length";s:6:"0,1024";}s:4:"date";a:2:{s:7:"notnull";b:1;s:6:"number";b:1;}}}
ETO
;