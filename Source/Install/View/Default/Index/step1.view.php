<?php use Common\Url;
if(!defined('SITE')) exit('Access Denied');?>
<!DOCTYPE html>
<html id="html">
<head>
<meta charset="utf-8" />
<?php $this->inc('title')?>
<script type="text/javascript">
$(function(){
	$('a.continue').click(function(){
		if($('#input1').is(':checked')){
			
		}else{
			alert('您必须阅读 程序说明 才能安装！');
			return false;
		}
		
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
  			<div class="panel panel-default">
			  <div class="panel-heading">程序说明</div>
			  <div class="panel-body">
			  	<p>本程序为 私房库  <a href="http://www.sifangku.com" target="_blank">www.sifangku.com</a> <a href="http://www.sifangku.com/course/11.html" target="_blank">教学项目</a>，私房库 <a href="http://www.sifangku.com" target="_blank">www.sifangku.com</a> 保留所有版权，感谢您对私房库的大力支持。</p>
			  	<p>本程序出自 私房库课程：<a href="http://www.sifangku.com/course/11.html" target="_blank">《PHP视频教程 - PHP面向对象篇 - MVC架构开发新闻管理系统》</a> 作者：<a href="http://www.sunshengli.com/" target="_blank">孙胜利</a></p>
			  </div>
			</div>
			<form class="form-inline">
			  <div class="form-group">
			    <label for="input1">
					&nbsp;<input id="input1" type="checkbox"> 我已经阅读以上程序说明&nbsp;&nbsp;&nbsp;&nbsp;
			    </label>
			  </div>
			  <a href="<?php echo Url::getUrl(array(E=>'install.php',C=>'index',M=>'step2'))?>" class="continue btn btn-primary">继续</a>
			</form>
  		</div>
	</div>
</div>
</body>
</html>
<?php exit()?>