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

</style>
</head>
<body>
<?php $this->inc('head')?>
<div class="container">
	<?php 
    	foreach ($this->getData('h') as $value){
    ?>
			  	<h2><a href="<?php echo Url::getUrl(array(M=>'article','uname'=>$value['uname'],'pubdate'=>$value['pubdate'],'id'=>$value['id']))?>"><?php echo $value['title']?></a></h2>
			    <hr />
			    <div style="margin:10px 0 60px 0;">
			    	<?php echo $value['content']?>
			    </div>
    <?php 
    	}
    ?>
</div>
<?php $this->inc('footer')?>

</body>
</html>