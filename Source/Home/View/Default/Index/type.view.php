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
h2 {
	font-size:22px;
}
div.description {
	margin-top:20px;
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
	 
	</ol>
	<div class="list-group">
		<?php 
		foreach ($this->getData('articles') as $value){
		?>
			<div class="row">
				<div class="col-md-2"><a href="<?php echo Url::getUrl(array(M=>'article','uname'=>$value['uname'],'pubdate'=>$value['pubdate'],'id'=>$value['id']))?>"><img width=150 src="<?php echo Url::getUrl($value['litpic'])?>" /></a></div>
				<div class="col-md-8">
					<h2><a href="<?php echo Url::getUrl(array(M=>'article','uname'=>$value['uname'],'pubdate'=>$value['pubdate'],'id'=>$value['id']))?>"><?php echo $value['title']?></a></h2>
					<div class="description">
						<?php echo $value['description']?>
					</div>
				</div>
				<div class="col-md-2"><?php echo date('Y-m-d h:i:s',$value['pubdate'])?></div>
			</div>
		  	<hr />
		<?php 
		}
		?>
	</div>
	<?php echo $this->getData('page')?>
</div>
<?php $this->inc('footer')?>

</body>
</html>