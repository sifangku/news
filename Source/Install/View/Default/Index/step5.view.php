<?php use Common\Url;
if(!defined('SITE')) exit('Access Denied');?>
<!DOCTYPE html>
<html id="html">
<head>
<meta charset="utf-8" />
<?php $this->inc('title')?>
<script type="text/javascript" src="<?php echo Url::getUrl('/Css/bootstrap/js/bootstrap.min.js')?>"></script>
<script type="text/javascript">
$(function(){
	$('a.continue').click(function(){
		
	});
});
</script>
</head>
<body>
<div class="container">
	<?php $this->inc('head')?>
	<div class="row">
		<?php $this->inc('sidebar')?>
  		<div class="col-md-10">
  			<form class="form-horizontal">
  			<div class="panel panel-default">
			  <div class="panel-heading">安装完成</div>
			  <div class="panel-body">
			  	<p>恭喜您！已成功安装私房库新闻管理系统.您现在可以: </p>
				<a href="<?php echo Url::getUrl(array(E=>'index.php',C=>'index',M=>'index'))?>" target="_blank" class="btn btn-primary">访问首页</a>&nbsp;&nbsp;&nbsp;&nbsp;
				<a href="<?php echo Url::getUrl(array(E=>'admin.php',C=>'manage',M=>'login'))?>" target="_blank" class="continue btn btn-primary">登录后台</a>
			  </div>
			</div>
			<div class="panel panel-default">
			  <div class="panel-body">
			  	<p>本程序为 私房库  <a href="http://www.sifangku.com" target="_blank">www.sifangku.com</a> <a href="http://www.sifangku.com/course/11.html" target="_blank">教学项目</a>，私房库 <a href="http://www.sifangku.com" target="_blank">www.sifangku.com</a> 保留所有版权，感谢您对私房库的大力支持。</p>
			  	<p>本程序出自 私房库课程：<a href="http://www.sifangku.com/course/11.html" target="_blank">《PHP视频教程 - PHP面向对象篇 - MVC架构开发新闻管理系统》</a> 作者：<a href="http://www.sunshengli.com/" target="_blank">孙胜利</a></p>
			  </div>
			</div>
  			</form>
  		</div>
	</div>
</div>



</body>
</html>
<?php exit()?>