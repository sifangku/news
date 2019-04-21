<?php use Common\Url;
if(!defined('SITE')) exit('Access Denied');?>
<title></title>
<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
<script type="text/javascript" src="<?php echo Url::getUrl('/Js/jquery-1.11.2.min.js')?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo Url::getUrl('/Css/bootstrap/css/bootstrap.min.css')?>">
<style type="text/css">
#header {
	width:100%;
	border-bottom:1px solid #e3e3e3;
	margin:0 0 20px 0;
}
#header h1 {
	font-size:16px;
    color:#333;
    font-weight:bold;
}

.current {
	background:#337ab7;
	color:#fff;
}
.error {
	color:red;
}
</style>