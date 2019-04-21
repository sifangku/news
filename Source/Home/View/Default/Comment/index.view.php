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
	$('div.container').on('click','img.verify',function(){
		var src=$(this).attr('src');
		$(this).attr('src',src.split('?')[0]+'?tm='+(new Date()).getTime());
	});
	
	/*
	
	*/
	$('a.reply-btn').click(function(){
		if($(this).parent().next().is('div.reply-from')){
			$(this).parent().next().remove();
		}else{
			var replyHtml='<div class="reply-from">'+
								'<form method="post" action="<?php echo Url::getUrl(array(M=>'add','aid'=>get('aid')))?>">'+
									'<input type="hidden" name="pid" value="'+$(this).data('id')+'" />'+
									'<div class="form-group">'+
									    '<textarea name="content" class="form-control" rows="3"></textarea>'+
									'</div>'+
									'<div class="form-group">'+
										'<div class="row">'+
											'<div class="col-md-2"><img class="verify" width=120 src="<?php echo Url::getUrl(array(E=>'tool.php',C=>'verify',M=>'login'))?>" /></div>'+
											'<div class="col-md-2"><input name="verify" type="text" class="form-control" placeholder="验证码"></div>'+
											'<div class="col-md-1"><button type="submit" class="btn btn-primary">发表评论</button></div>'+
										'</div>'+
									'</div>'+
								'</form>'+
							'</div>';
			$(this).parent().after(replyHtml);
		}
		
	});
	$('button.getAncestor').click(function(){
		$(this).attr('disabled','disabled');
		var _this=this;
		$.post('<?php echo Url::getUrl(array(M=>'getAncestor'))?>',{who:$(this).data('who'),current:$(this).data('current')},function(respone){
			var commentId='#comment-'+$(_this).data('who')+'-'+respone.ids[0];
			if($(respone.data).is(commentId)){
				$(respone.data).prepend($(_this).prev()).insertBefore(_this);
			}else{
				$(respone.data).find(commentId).prepend($(_this).prev()).end().insertBefore(_this);
			}
			if(respone.has){
				$(_this).data('current',respone.current).removeAttr('disabled');
			}else{
				$(_this).unwrap().remove();
			}
		},'json');
	});
});
</script>
<style type="text/css">
div.comment {
	margin-top:20px;
	border-bottom:1px dashed #ccc;
	padding-bottom:20px;
}
div.comment-ancestor {
	border-radius:4px;
	border:1px solid #ccc;
	padding:3px 3px 10px 3px;
	margin-bottom:6px;
}
div.comment>div.author-wrap .author {
	float:left;
}
div.comment>div.author-wrap .date {
	float:right;
}
div.reply-from {
	margin-top:6px;
}

</style>
</head>
<body>
<?php $this->inc('head')?>
<div class="container">
	<div class="page-header">
	  <h1>评论： <small><?php echo $this->getData('article')['title']?></small></h1>
	</div>
	<form method="post" action="<?php echo Url::getUrl(array(M=>'add','aid'=>get('aid')))?>">
		<div class="form-group">
		    <textarea name="content" class="form-control" rows="3"></textarea>
		</div>
		<div class="form-group">
			<div class="row">
				<div class="col-md-2"><img class="verify" width=120 src="<?php echo Url::getUrl(array(E=>'tool.php',C=>'verify',M=>'login'))?>" /></div>
				<div class="col-md-2"><input name="verify" type="text" class="form-control" placeholder="验证码"></div>
				<div class="col-md-1"><button type="submit" class="btn btn-primary">发表评论</button></div>
			</div>
		    
		</div>
		
	</form>
	
	<?php 
	foreach ($this->getData('comments') as $value){
	?>
	<div class="comment">
		<div class="author-wrap"><p><small class="author"><?php echo $value['username']?></small><small class="date"><?php echo date('Y-m-d H:i:s',$value['date'])?></small><div style="clear:both;"></div></p></div>
		<?php 
		if(!empty($value['ancestor'])){
			if($value['ancestor_']['has']){
				echo "<div class='comment-ancestor'>{$value['ancestor_']['data']}<button data-who='{$value['id']}' data-current='{$value['ancestor_']['current']}' type='button' class='btn btn-info btn-sm getAncestor'>加载更多</button></div>";
			}else{
				echo $value['ancestor_']['data'];
			}
		}
		?>
		<div class="content-wrap">
			<?php echo $value['content']?>
		</div>
		<div class="reply-wrap text-right">
			<a href="javascript:;" class="reply-btn" data-id="<?php echo $value['id']?>">回复</a>
		</div>
		
	</div>
	<?php 
	}
	?>
	<?php echo $this->getData('page')?>
</div>
<?php $this->inc('footer')?>
</body>
</html>