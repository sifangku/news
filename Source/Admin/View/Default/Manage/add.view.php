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
    $('#add-type').validate({
		rules:{
			name:{
				required:true,
				rangelength:[3,32]
		    },
		    pname:{
		    	required:true,
		    	rangelength:[1,32]
		    },
		    password:{
		    	required:true,
				rangelength:[6,32]
		    }
		},
		errorPlacement:function(error,element){
			$(element).parent().next().html(error);
		},
		errorElement:'div',
		highlight:function(element, errorClass, validClass){
		    $(element).parent().parent().addClass('has-error');
		},
		unhighlight:function(element, errorClass, validClass){
		    $(element).parent().parent().removeClass('has-error');
		}
    });
    $('#add-type').ajaxForm({
        dataType:'json',
    	beforeSubmit:function(data,$form,options){
    		$('#myModal').modal();
    	},
    	success:function(responseText){
    	    //<button type="button" class="btn btn-primary">Save changes</button>
    	    if(responseText.status){
				$("#myModal .modal-body").html('<div class="alert alert-success" role="alert">'+responseText.message+'</div>');
				$("#myModal .modal-footer").html('<a href="<?php echo Url::getUrl(array(C=>'manage',M=>'index'))?>" type="button" class="btn btn-primary">确定</a>');
	        }else{
        		$("#myModal .modal-body").html('<div class="alert alert-danger" role="alert">'+responseText.message+'</div>');
        		$("#myModal .modal-footer").html('<button type="button" class="btn btn-primary" data-dismiss="modal">确定</button>');
            }
    	}
    });
});
</script>
<style type="text/css">
#header {
	width:100%;
	height:38px;
	border-bottom:1px solid #e3e3e3;
}
#header h1 {
	font-size:13px;
    line-height:38px;
    margin:0 0 0 20px;
    color:#333;
    font-weight:bold;
}
#main-body {
	margin-top:20px;
}
#add-type .col-md-2 .error {
	padding-top:7px;
	color:#a94442;
}
.form-horizontal .checkbox,
.form-horizontal .radio-inline,
.form-horizontal .checkbox-inline {
	padding-top:4px;
}
input[type="checkbox"] {
	margin-top:1px;
}
</style>
</head>
<body>

<div id="header">
	<h1>私房库 管理中心</h1>
</div>
<div id="main-body" class="container-fluid">
	<div class="row">
		<div class="col-md-2">
			<?php $this->inc('sidebar')?>
		</div>
		<div class="col-md-10">
			<div class="panel panel-default">
			  <div class="panel-heading">添加管理员</div>
			  <div class="panel-body">
				  	<form id="add-type" class="form-horizontal" method="post">
						  <div class="form-group row">
						    <label for="input1" class="col-md-1 control-label">登录名</label>
						    <div class="col-md-9">
						      <input name="name" type="text" class="form-control" id="input1" placeholder="管理员名称">
						    </div>
						    <div class="col-md-2">
						    	
						    </div>
						  </div>
				  		  <div class="form-group row">
						    <label for="input2" class="col-md-1 control-label">笔名</label>
						    <div class="col-md-9">
						      <input name="pname" type="text" class="form-control" id="input2" placeholder="笔名">
						    </div>
						    <div class="col-md-2">
						    	
						    </div>
						  </div>
						  <div class="form-group row">
						    <label for="input3" class="col-md-1 control-label">密码</label>
						    <div class="col-md-9">
						      <input name="password" type="text" class="form-control" id="input3" placeholder="密码">
						    </div>
						    <div class="col-md-2">
						    	
						    </div>
						  </div>
						  <div class="form-group row">
						    <label for="input4" class="col-md-1 control-label">权限</label>
						    <div class="col-md-9">
						    	<?php 
						    	foreach ($this->getData('power') as $controller){
						    	?>
						      	<div class="panel panel-default">
								  <div class="panel-body">
								    <?php 
								    foreach ($controller as $method){
								    ?>
								    <label class="checkbox-inline">
									  <input name="power[]" type="checkbox" value="<?php echo $method['pmark']?>"> <?php echo $method['note']?>
									</label>
									<?php 
									}
									?>
								  </div>
								</div>
								<?php 
								}
								?>
						    </div>
						    <div class="col-md-2">
						    	
						    </div>
						  </div>
						  <div class="form-group row">
						    <div class="col-md-offset-1 col-md-2">
						      <button type="submit" class="btn btn-primary">提交</button>
						    </div>
						  </div>
					</form>
			  </div>
			</div>
		</div>
	</div>
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