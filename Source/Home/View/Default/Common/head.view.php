<?php use Common\Url;
if(!defined('SITE')) exit('Access Denied');?>
<div class="jumbotron">
  <div class="container">
    <h1>权威报</h1>
    <p>一家专业的篮球新闻报导网站！</p>
    <ul class="nav nav-pills">
    	<li role="presentation"><a href="<?php echo Url::getUrl('/')?>">首页</a></li>
    	<?php 
    	foreach ($this->getData('arctypes') as $value){
		// class="active"
    	?>
    	<li role="presentation"><a href="<?php echo Url::getUrl(array(C=>'index',M=>'type','uname'=>$value['uname']))?>"><?php echo $value['name']?></a></li>
    	<?php 
    	}
    	?>
	</ul>
  </div>
</div>