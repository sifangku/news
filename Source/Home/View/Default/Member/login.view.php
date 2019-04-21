<?php use Common\Url;
if(!defined('SITE')) exit('Access Denied');?>
<!DOCTYPE html>
<html id="html">
<head>
<meta charset="utf-8" />
<title></title>
<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
<script type="text/javascript" src="/News/Js/jquery-1.11.2.min.js"></script>
<link rel="stylesheet" type="text/css" href="/News/Css/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="/News/Css/public.css">
<script type="text/javascript">
$(function(){
	
});
</script>
<style type="text/css">
a {
	color:#000;
}
#top-wrap {
	width:100%;
	height:45px;
	line-height:45px;
	background:#333;
	color:#fff;
	margin:0 0 10px 0;
}
#top-wrap a {
	color:#fff;
}
.wrap {
	width:960px;
	margin:0 auto;
}
#top-wrap div.logo {
	width:100px;
	float:left;
}
#top-wrap div.login {
	float:right;
}
#top-wrap div.logo a {
	display:block;
	line-height:45px;
	font-size:12px;
}
#top-wrap div.logo a:hover {
	text-decoration:none;
}
#ad {
	height:100px;
	background:pink;
	margin-bottom:10px;
}
#nav-wrap {
	width:100%;
	height:47px;
	border-top: 2px #f34540 solid;
    border-bottom: 1px #eee solid;
    line-height:47px;
}
#nav-wrap div.nav ul li {
	float:left;
	padding:0 30px;
    background:url(Images/sprite_icon0813_v1.png) no-repeat 100% -53px;
}
#nav-wrap div.nav ul li a {
	
	font-size:16px;
}
#nav-wrap div.nav ul li:first-child {
	padding-left:0;
}
#nav-wrap div.nav ul li:last-child {
	padding-right:0;
}
#main {
	/*margin-top:35px;*/
}
#main #news {
	width:600px;
	float:left;
}
#main #news div.article-wrap {
	padding:10px 0 0 0;
}
#main #news div.article-wrap div.location {
	margin:15px 0 0 0;
}
#main #news div.article-wrap>h1 {
	margin:20px 0;
	font-size:27px;
}
#main #news div.article-wrap>div.info {
	margin:0 0 20px 0;
}
#main #news div.article-wrap div.article {
	font-size:18px;
	line-height:200%;
}
#main #sidebar {
	width:300px;
	float:right;
}
#main #sidebar div.hot-wrap {
	margin:50px 0 0 0;
}
#main #sidebar div.hot-wrap h2 {
	font-size:16px;
	font-weight:bold;
	padding:15px 0;
}
#main #sidebar div.hot-wrap ul li {
	font-size:14px;
	line-height:30px;
}
#main #sidebar div.hot-wrap ul li span.number {
	font-size:22px;
	width:27px;
	display:block;
	float:left;
	margin-left:5px;
	color:#888;
}
#main #sidebar div.hot-wrap ul li a {
	float:left;
}
#main #sidebar div.hot-wrap ul li span.recommend {
	float:right;
	margin-right:5px;
}
#bottom-wrap {
	background:#333;
	color:#ddd;
	margin-top:35px;
}
#bottom-wrap ul {
	line-height:39px;
    height:39px;
    margin:0 -10px;
}
#bottom-wrap ul li {
	float:left;
}
#bottom-wrap ul li a {
	color:#ddd;
	margin:0 10px;
}
</style>
</head>
<body>
	<div id="top-wrap">
		<div class="wrap">
			<div class="logo">
				<h1><a href="">私房库新闻</a></h1>
			</div>
			<div class="login">
				登录、待做
			</div>
		</div>
	</div>
	<div id="ad" class="wrap">
		广告
	</div>
	<div id="nav-wrap">
		<div class="wrap nav">
			<ul>
				<li><a href='#'>导航</a></li>
				<li><a href='#'>导航</a></li>
				<li><a href='#'>导航</a></li>
				<li><a href='#'>导航</a></li>
			</ul>
		</div>
	</div>
	<div class="container" style="margin-top:50px;">
		<form class="form-horizontal" action="<?php echo Url::getUrl(array('m'=>'login'))?>" method="post">
		  <div class="form-group">
			<label for="input1" class="col-sm-2 control-label">用户名</label>
			<div class="col-sm-10">
			  <input type="text" name="username" class="form-control" id="input1" placeholder="用户名">
			</div>
		  </div>
		  <div class="form-group">
			<label for="input2" class="col-sm-2 control-label">密码</label>
			<div class="col-sm-10">
			  <input type="password" name="password" class="form-control" id="input2" placeholder="密码">
			</div>
		  </div>
		  <div class="form-group">
			<label for="input4" class="col-sm-2 control-label">验证码</label>
			<div class="col-sm-10">
			  <input type="text" name="vcode" class="form-control" id="input4" placeholder="验证码">
			</div>
		  </div>
		  <div class="form-group">
			<label class="col-sm-2 control-label"></label>
			<div class="col-sm-10">
			  <img class="verify" src="<?php echo Url::getUrl(array(E=>'tool.php',C=>'verify',M=>'login'))?>" onclick="javascript:this.src=this.src+'?rand='+Math.random();" />
			</div>
		  </div>
		  <div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
			  <button type="submit" class="btn btn-default">登录</button>
			</div>
		  </div>
		</form>
	</div>
	<div id="bottom-wrap">
		<div class="wrap">
			<ul>
				<li>
					<a href="#">关于我们</a>|
				</li>
				<li>
					<a href="#">关于我们</a>|
				</li>
				<li>
					<a href="#">关于我们</a>|
				</li>
				<li>
					<a href="#">关于我们</a>
				</li>
			</ul>
		</div>
	</div>
</body>
</html>