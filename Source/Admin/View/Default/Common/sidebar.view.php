<?php use Common\Url;
if(!defined('SITE')) exit('Access Denied');?>
<script type="text/javascript">
$(function(){
	$('div.list-group>a.list-group-item').each(function(){
		if($(this).attr('href')==window.location.pathname){
			$(this).addClass('active');
		}
	});
});
</script>
<div class="list-group">
  <a href="<?php echo Url::getUrl(array(C=>'type',M=>'index'))?>" class="list-group-item">栏目列表</a>
  <a href="<?php echo Url::getUrl(array(C=>'type',M=>'add'))?>" class="list-group-item">添加栏目</a>
</div>
<div class="list-group">
  <a href="<?php echo Url::getUrl(array(C=>'article',M=>'index'))?>" class="list-group-item">新闻列表</a>
  <!-- <a href="#" class="list-group-item">我的新闻</a> -->
  <a href="<?php echo Url::getUrl(array(C=>'article',M=>'add'))?>" class="list-group-item">添加新闻</a>
</div>
<div class="list-group">
	<a href="<?php echo Url::getUrl(array(C=>'article',M=>'showTrash'))?>" class="list-group-item">回收站</a>
</div>

<div class="list-group">
  <a href="<?php echo Url::getUrl(array(C=>'manage',M=>'index'))?>" class="list-group-item">管理员列表</a>
  <a href="<?php echo Url::getUrl(array(C=>'manage',M=>'add'))?>" class="list-group-item">添加管理员</a>
</div>

<div class="list-group">
  <a href="<?php echo Url::getUrl(array(C=>'manage',M=>'logout'))?>" class="list-group-item">退出</a>
</div>