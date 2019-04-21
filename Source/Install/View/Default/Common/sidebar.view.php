<?php use Common\Url;
if(!defined('SITE')) exit('Access Denied');?>
<div id="sidebar" class="col-md-2">
	<div class="panel panel-default">
	  <div class="panel-heading">安装步骤</div>
	  <div class="panel-body">
	  	<div data-method='step1' class="panel panel-default">
		  <div class="panel-body">
		   	 1、程序说明
		  </div>
		</div>
		<div data-method='step2' class="panel panel-default">
		  <div class="panel-body">
		   	 2、环境检测
		  </div>
		</div>
		<div data-method='step3' class="panel panel-default">
		  <div class="panel-body">
		   	3、参数配置
		  </div>
		</div>
		<div data-method='step4' class="panel panel-default">
		  <div class="panel-body">
		   	4、正在安装
		  </div>
		</div>
		<div data-method='step5' class="panel panel-default">
		  <div class="panel-body">
		   	5、安装完成
		  </div>
		</div>
	  </div>
	</div>
</div>
<script type="text/javascript">
$('div[data-method=<?php echo Url::getMethod();?>]').addClass('current');
</script>