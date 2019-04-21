<?php use Common\Url;
if(!defined('SITE')) exit('Access Denied');?>
<!DOCTYPE html>
<html id="html">
<head>
<meta charset="utf-8" />
<title></title>
<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
<script type="text/javascript" src="<?php echo Url::getUrl('/Js/jquery-1.11.2.min.js')?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo Url::getUrl('/Css/bootstrap/css/bootstrap.min.css')?>">
<link rel="stylesheet" type="text/css" href="<?php echo Url::getUrl('/Css/home_public.css')?>">
<script type="text/javascript">
$(function(){
	
});
</script>
<style type="text/css">
div.info {
	margin:20px 0;
}
div.content {
	font-size:16px;
	line-height:180%;
}
</style>
</head>
<body>
<?php $this->inc('head')?>
<div class="container">
	<ol class="breadcrumb">
		<li><a href="<?php echo Url::getUrl('/')?>">首页</a></li>
		<?php 
		foreach ($this->getData('position') as $value){
		?>
		<li><a href="<?php echo Url::getUrl(array(M=>'type','uname'=>$value['uname']))?>"><?php echo $value['name']?></a></li>
		<?php 
		}
		?>
	 	<li><?php echo $this->getData('article')['title']?></li>
	</ol>
	<h2><?php echo $this->getData('article')['title']?></h2>
	<div class="info">
		<div class="row">
			  <div class="col-md-3"><?php echo $this->getData('article')['editor']?> <?php echo date('Y-m-d h:i:s',$this->getData('article')['pubdate'])?></div>
			  <div class="col-md-7"></div>
			  <div class="col-md-2">
			  	<a href="<?php echo Url::getUrl(array(C=>'comment',M=>'index','aid'=>$this->getData('article')['id']))?>" target="_blank" type="button" class="btn btn-default">
		  			<span class="glyphicon glyphicon-comment" aria-hidden="true"></span> <?php echo $this->getData('count')?> 跟贴
				</a>
			  </div>
		</div>
	</div>
	
	<div class="content">
		<?php echo $this->getData('article')['content']?>
	</div>
</div>
<?php $this->inc('footer')?>

</body>
</html>