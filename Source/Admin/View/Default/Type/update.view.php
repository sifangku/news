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
				rangelength:[1,255]
		    },
		    uname:{
		    	required:true,
				rangelength:[1,255]
		    },
		    view:{
		    	required:true,
				rangelength:[1,255]
		    },
		    title:{
		    	required:true,
				rangelength:[1,255]
		    },
		    keywords:{
		    	required:true,
				rangelength:[1,255]
		    },
		    description:{
		    	required:true,
				rangelength:[1,500]
		    },
		    sort:{
				required:true,
				number:true,
				range:[0,4294967295]
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
				$("#myModal .modal-footer").html('<a href="<?php echo Url::getUrl(array(C=>'type',M=>'index'))?>" type="button" class="btn btn-primary">确定</a>');
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
			  <div class="panel-heading">添加栏目</div>
			  <div class="panel-body">
				  	<form id="add-type" class="form-horizontal" method="post">
				  		<div class="form-group row">
						    <label for="input1" class="col-md-1 control-label">栏目名称</label>
						    <div class="col-md-9">
						      <input name="name" type="text" class="form-control" id="input1" placeholder="栏目名称" value="<?php echo $this->getData('type')['name']?>">
					   		</div>
						    <div class="col-md-2">
						    	
						    </div>
				  		</div>
					  	<div class="form-group row">
						    <label for="input0" class="col-md-1 control-label">所属栏目</label>
						    <div class="col-md-9">
							    <select name="pid" class="form-control" id="input0">
								  <option value="0">顶级栏目</option>
								  <?php 
								  foreach ($this->getData('arctype') as $val){
									if($val['id']==$this->getData('type')['pid']){
										$selected='selected="selected"';
									}else{
										$selected='';
									}
									if(array_key_exists($val['id'],$this->getData('posterity'))){
										$disabled='disabled="disabled"';
									}else{
										$disabled='';
									}
								  	echo "<option {$selected} {$disabled} value='{$val['id']}'>{$val['style']}{$val['name']}</option>";
								  }
								  ?>
								</select>
						    </div>
						    <div class="col-md-2">
						    	
						    </div>
						  </div>
						  
				  		  <div class="form-group row">
						    <label for="input2" class="col-md-1 control-label">URL目录</label>
						    <div class="col-md-9">
						      <input name="uname" type="text" class="form-control" id="input2" placeholder="URL目录" value="<?php echo $this->getData('type')['uname']?>">
						    </div>
						    <div class="col-md-2">
						    	
						    </div>
						  </div>
						  <div class="form-group row">
						    <label for="input3" class="col-md-1 control-label">栏目界面</label>
						    <div class="col-md-9">
						      <input name="view" type="text" class="form-control" id="input3" placeholder="栏目界面" value="<?php echo $this->getData('type')['view']?>">
						    </div>
						    <div class="col-md-2">
						    	
						    </div>
						  </div>
						  <div class="form-group row">
						    <label for="input4" class="col-md-1 control-label">SEO标题</label>
						    <div class="col-md-9">
						      <input name="title" type="text" class="form-control" id="input4" placeholder="SEO标题" value="<?php echo $this->getData('type')['title']?>">
						    </div>
						    <div class="col-md-2">
						    	
						    </div>
						  </div>
						  <div class="form-group row">
						    <label for="input5" class="col-md-1 control-label">SEO关键字</label>
						    <div class="col-md-9">
						      <input name="keywords" type="text" class="form-control" id="input5" placeholder="SEO关键字" value="<?php echo $this->getData('type')['keywords']?>">
						    </div>
						    <div class="col-md-2">
						    	
						    </div>
						  </div>
						  <div class="form-group row">
						    <label for="input6" class="col-md-1 control-label">SEO描述</label>
						    <div class="col-md-9">
						    	<textarea name="description" class="form-control" id="input6" rows="3" placeholder="SEO描述"><?php echo $this->getData('type')['description']?></textarea>
						    </div>
						    <div class="col-md-2">
						    	
						    </div>
						  </div>
						  <div class="form-group row">
						    <label for="input7" class="col-md-1 control-label">排序</label>
						    <div class="col-md-9">
						      <input name="sort" type="text" class="form-control" id="input7" placeholder="排序" value="<?php echo $this->getData('type')['sort']?>">
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