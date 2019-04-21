<?php if(!defined('SITE')) exit('Access Denied');?>
<!DOCTYPE html>
<html id="html">
<head>
<meta charset="utf-8" />
<title>bootstrap</title>
<meta http-equiv="refresh" content="3,url=<?php echo $this->getData('url')?>" />
<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
<link rel="stylesheet" type="text/css" href="<?php echo Url::getUrl('/Css/bootstrap/css/bootstrap.min.css')?>">
<link rel="stylesheet" type="text/css" href="<?php echo Url::getUrl('/Css/public.css')?>">
<!-- 
<script type="text/javascript" src=""></script>
-->
<style type="text/css">
#header {
	width:100%;
	height:38px;
	border-bottom:1px solid #e3e3e3;
}
#header h1 {
	font-size:13px;
    line-height:38px;
    margin:0 0 0 20px;
    color:#333;
    font-weight:bold;
}
#main-body {
	margin-top:20px;
}
</style>
</head>
<body>
<div id="header">
	<h1>私房库 管理中心</h1>
</div>
<div id="main-body" class="container-fluid">
	<div class="alert alert-<?php echo $this->getData('class')?>" role="alert"><?php echo $this->getData('message')?></div>
	<a href="<?php echo $this->getData('url')?>" type="button" class="btn btn-primary">自动跳转中... 点击立即跳转</a>
	<!-- 
	<div class="alert alert-info" role="alert">...</div>
	<div class="alert alert-warning" role="alert">...</div>
	<div class="alert alert-danger" role="alert">...</div>
	 -->
</div>
</body>
</html>
<?php exit()?>