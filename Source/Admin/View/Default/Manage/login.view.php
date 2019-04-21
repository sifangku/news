<?php use Common\Url;
if(!defined('SITE')) exit('Access Denied');?>
<!DOCTYPE html>
<html id="html">
<head>
<meta charset="utf-8" />
<?php $this->inc('title')?>
<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
<script type="text/javascript" src="<?php echo Url::getUrl('/Js/jquery-1.11.2.min.js')?>"></script>
<script type="text/javascript" src="<?php echo Url::getUrl('/Css/bootstrap/js/bootstrap.min.js')?>"></script>
<script type="text/javascript" src="<?php echo Url::getUrl('/Js/jquery.form.min.js')?>"></script>
<script type="text/javascript" src="<?php echo Url::getUrl('/Js/jqueryvalidation/jquery.validate.min.js')?>"></script>
<script type="text/javascript" src="<?php echo Url::getUrl('/Js/jqueryvalidation/messages_zh.js')?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo Url::getUrl('/Css/bootstrap/css/bootstrap.min.css')?>">
<link rel="stylesheet" type="text/css" href="<?php echo Url::getUrl('/Css/public.css')?>">
<script type="text/javascript">
$(function(){
    $('#loginForm').validate({
		rules:{
			name:{
				required:true,
				rangelength:[2,32]
		    },
		    password:{
		    	required:true,
				rangelength:[5,32]
		    }
		},
		errorElement:'div'
    });
    $('#loginForm').ajaxForm({
        dataType:'json',
    	beforeSubmit:function(data,$form,options){
    		$('#myModal').modal();
    	},
    	success:function(responseText){
    	    //<button type="button" class="btn btn-primary">Save changes</button>
    	    if(responseText.status){
    	    	setTimeout(function (){
					window.location.href='<?php echo Url::getUrl(array(C=>'type',M=>'index'))?>';
            	},1000);
				$("#myModal .modal-body").html('<div class="alert alert-success" role="alert">'+responseText.message+'</div>');
				$("#myModal .modal-footer").html('<a href="<?php echo Url::getUrl(array(C=>'type',M=>'index'))?>" type="button" class="btn btn-primary">确定</a>');
	        }else{
		        if(responseText.show){
		        	$('div.wrapVerify img.verify').trigger('click');
					$('div.wrapVerify').show();
				}
        		$("#myModal .modal-body").html('<div class="alert alert-danger" role="alert">'+responseText.message+'</div>');
        		$("#myModal .modal-footer").html('<button type="button" class="btn btn-primary" data-dismiss="modal">确定</button>');
            }
    	}
    });
    $('img.verify').click(function (){
    	var src=$(this).attr('src');
		$(this).attr('src',src.split('?')[0]+'?tm='+(new Date()).getTime());
    });
    $('input[name="name"]').bind('blur',function(){
		if($(this).val().length>=2){
			$.post('<?php echo Url::getUrl(array(C=>'manage',M=>'isShowVerify'))?>',{name:$(this).val()},function(responseText){
				if(responseText.show){
					$('div.wrapVerify').show();
				}else{
					$('div.wrapVerify').hide();
				}
			},'json');
		}
    });
});
</script>
<style type="text/css">
#login {
	width:300px;
	position:absolute;
	top:50%;
	left:50%;
	margin-left:-150px;
	margin-top:-160px;
}
#login h1 {
	text-align:center;
	margin-bottom:25px;
	color:#337ab7;
	letter-spacing:1.5px;
}
#login form.form-horizontal div.wrap input {
	margin-bottom:20px;
}
#login form.form-horizontal div.wrap {
	position:relative;
}
#login form.form-horizontal div.wrap div.error {
	position:absolute;
	top:15px;
	right:13px;
	color:#c33;
}
</style>
</head>
<body>

<div id="login">
	<h1>私房库</h1>
	<form id="loginForm" class="form-horizontal" role="form" method="post">
	  <div class="wrap">
	      <input type="text" name="name" class="form-control input-lg" placeholder="用户名">
	  </div>
	  <div class="wrap">
	      <input type="password" name="password" class="form-control input-lg" placeholder="密码">
	  </div>
	  <div class="wrap wrapVerify" style="display:none;">
	  		<input style="width:120px;float:left;" type="text" name="verify" class="form-control input-lg" placeholder="验证码">
	  		<img class="verify" style="float:right;" src="<?php echo Url::getUrl(array(E=>'tool.php',C=>'verify',M=>'login'))?>" />
	  		<div style="clear:both;"></div>
	  </div>
	  <div class="wrap">
	      <button type="submit" class="btn btn-primary btn-block btn-lg">登录</button>
	  </div>
	</form>
</div>
<div id="myModal" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">通知</h4>
      </div>
      <div class="modal-body">
        <p>提交中...</p>
      </div>
      <div class="modal-footer">
        
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</body>
</html>