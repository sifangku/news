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
		if($('span.error').length){
			alert($('span.error:eq(0)').text());
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
			  <div class="panel-heading">服务器系统环境信息</div>
			  <div class="panel-body">
			  	<div class="alert alert-danger" role="alert">
			  		请务必保证 （1）服务器支持PATH_INFO URL模式，（2）开启 URL重写模块，否则即使安装成功，系统也不能正常使用！
			  	</div>
			  	<table class="table table-bordered">
				  <tr>
				  	<th>参数</th>
				  	<th>值</th>
				  	<th>建议</th>
				  </tr>
				  <?php 
				  foreach ($this->getData('server') as $value){
				  ?>
				  <tr>
				  	<td><?php echo $value[0]?></td>
				  	<td><?php echo $value[1]?></td>
				  	<td><?php echo $value[2] ? '[√]' : "<span class='error'>[×]{$value[3]}</span>"?></td>
				  </tr>
				  <?php 
				  }
				  ?>
				  
				  
				</table>
			  </div>
			</div>
			
			<div class="panel panel-default">
			  <div class="panel-heading">目录权限检测</div>
			  <div class="panel-body">
			  	<table class="table table-bordered">
				  <tr>
				  	<th>目录名</th>
				  	<th>要求</th>
				  	<th>状态</th>
				  </tr>
				  <?php 
				  foreach ($this->getData('dir') as $value){
				  ?>
				  <tr>
				  	<td><?php echo $value[0]?></td>
				  	<td><?php echo $value[1]?></td>
				  	<td><?php echo $value[2] ? '[√]' : "<span class='error'>[×]{$value[3]}</span>"?></td>
				  </tr>
				  <?php 
				  }
				  ?>
				  
				  
				</table>
			  </div>
			</div>
			<a href="<?php echo Url::getUrl(array(E=>'install.php',C=>'index',M=>'step1'))?>" class="btn btn-primary">返回</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo Url::getUrl(array(E=>'install.php',C=>'index',M=>'step3'))?>" class="continue btn btn-primary">继续</a>
  		</div>
	</div>
</div>
</body>
</html>
<?php exit()?>