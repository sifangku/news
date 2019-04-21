
<?php
/**
* SFKNews
* 私房库 www.sifangku.com 教学项目
*
* @desc sfk_article 数据表分析信息
* @copyright 私房库 www.sifangku.com 保留所有版权
* @author 孙胜利 www.sunshengli.com
* @version 1.01
*
**/
//检测是否有令牌
if(!defined('SITE')) exit('Access Denied');
return <<<ETO
a:5:{s:6:"fields";a:13:{i:0;s:2:"id";i:1;s:4:"atid";i:2;s:4:"flag";i:3;s:5:"title";i:4;s:8:"keywords";i:5;s:11:"description";i:6;s:7:"content";i:7;s:6:"editor";i:8;s:3:"eid";i:9;s:7:"pubdate";i:10;s:7:"weights";i:11;s:6:"litpic";i:12;s:8:"isdelete";}s:2:"pk";s:2:"id";s:14:"fields_comment";a:13:{s:2:"id";s:0:"";s:4:"atid";s:0:"";s:4:"flag";s:28:"头条,特推,推荐, 跳转";s:5:"title";s:6:"标题";s:8:"keywords";s:0:"";s:11:"description";s:0:"";s:7:"content";s:0:"";s:6:"editor";s:0:"";s:3:"eid";s:0:"";s:7:"pubdate";s:0:"";s:7:"weights";s:0:"";s:6:"litpic";s:0:"";s:8:"isdelete";s:0:"";}s:11:"fields_type";a:13:{s:2:"id";s:1:"i";s:4:"atid";s:1:"i";s:4:"flag";s:1:"s";s:5:"title";s:1:"s";s:8:"keywords";s:1:"s";s:11:"description";s:1:"s";s:7:"content";s:1:"s";s:6:"editor";s:1:"s";s:3:"eid";s:1:"i";s:7:"pubdate";s:1:"i";s:7:"weights";s:1:"i";s:6:"litpic";s:1:"s";s:8:"isdelete";s:1:"i";}s:12:"fields_rules";a:12:{s:2:"id";a:2:{s:6:"number";b:1;s:6:"unique";b:1;}s:4:"atid";a:2:{s:7:"notnull";b:1;s:6:"number";b:1;}s:5:"title";a:2:{s:7:"notnull";b:1;s:6:"length";s:4:"0,32";}s:8:"keywords";a:2:{s:7:"notnull";b:1;s:6:"length";s:5:"0,128";}s:11:"description";a:2:{s:7:"notnull";b:1;s:6:"length";s:5:"0,512";}s:7:"content";a:1:{s:7:"notnull";b:1;}s:6:"editor";a:2:{s:7:"notnull";b:1;s:6:"length";s:4:"0,32";}s:3:"eid";a:2:{s:7:"notnull";b:1;s:6:"number";b:1;}s:7:"pubdate";a:2:{s:7:"notnull";b:1;s:6:"number";b:1;}s:7:"weights";a:2:{s:7:"notnull";b:1;s:6:"number";b:1;}s:6:"litpic";a:2:{s:7:"notnull";b:1;s:6:"length";s:5:"0,128";}s:8:"isdelete";a:1:{s:6:"number";b:1;}}}
ETO
;