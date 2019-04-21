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
<script type="text/javascript" src="<?php echo Url::getUrl('/Js/ueditor/ueditor.config.js')?>"></script>
<script type="text/javascript" src="<?php echo Url::getUrl('/Js/ueditor/ueditor.all.min.js')?>"></script>
<script type="text/javascript" src="<?php echo Url::getUrl(array(E=>'tool.php',C=>'js',M=>'fileManager'))?>"></script>
<script type="text/javascript">
$(function(){
	$('#pick').click(function(){
		fileManage(function($values){
			if($values.length==1){
				$('#input3').val($values[0]);
				$('#fileManageModal').modal('hide');
			}else{
				alert('只能选择一张图片.');
			}
		});
	});
	
    $('#add-type').validate({
		rules:{
			atid:{
				required:true,
				min:1
		    },
		    title:{
		    	required:true,
				rangelength:[1,32]
		    },
			litpic:{
		    	required:true,
				rangelength:[1,512]
		    },
		    keywords:{
		    	required:true,
				rangelength:[1,128]
		    },
		    description:{
		    	required:true,
				rangelength:[1,512]
		    },
			weights:{
				required:true,
				number:true,
				range:[0,4294967295]
		    }
		},
		messages: {
			atid:{
				min:'请选择一个栏目'
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
				$("#myModal .modal-footer").html('<a href="<?php echo Url::getUrl(array(C=>'article',M=>'index'))?>" type="button" class="btn btn-primary">确定</a>');
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
			  <div class="panel-heading">添加新闻</div>
			  <div class="panel-body">
				  	<form id="add-type" class="form-horizontal" method="post">
					  	<div class="form-group row">
						    <label for="input0" class="col-md-1 control-label">所属栏目</label>
						    <div class="col-md-9">
							    <select name="atid" class="form-control" id="input0">
								  <option value="0">===请选择===</option>
								  <?php 
								  foreach ($this->getData('arctype') as $val){
								  	echo "<option  value='{$val['id']}'>{$val['style']}{$val['name']}</option>";
								  }
								  ?>
								</select>
						    </div>
						    <div class="col-md-2">
						    	
						    </div>
						  </div>
						  <div class="form-group row">
						    <label for="input1" class="col-md-1 control-label">新闻标题</label>
						    <div class="col-md-9">
						      <input name="title" type="text" class="form-control" id="input1" placeholder="新闻标题">
						    </div>
						    <div class="col-md-2">
						    	
						    </div>
						  </div>
						  <div class="form-group row">
						    <label for="input2" class="col-md-1 control-label">新闻内容</label>
						    <div class="col-md-9">
							    <script id="container" name="content" type="text/plain">孙胜利的私房库</script>
								<script type="text/javascript">
								var ue = UE.getEditor('container',{
									initialFrameWidth:'100%',
									initialFrameHeight:512
								});
								</script>
						      <!--<input name="content" type="text" class="form-control" id="input2" placeholder="新闻内容">-->
						    </div>
						    <div class="col-md-2">
						    	
						    </div>
						  </div>
						  <div class="form-group row">
						    <label for="input3" class="col-md-1 control-label">缩略图</label>
						    <div class="col-md-8">
						      <input name="litpic" type="text" class="form-control" id="input3" placeholder="缩略图">
						    </div>
						    <div class="col-md-1">
						      <button id="pick" type="button" class="btn btn-primary">选择图片</button>
						    </div>
						    <div class="col-md-2">
						    	
						    </div>
						  </div>
				  		  <div class="form-group row">
						    <label for="input4" class="col-md-1 control-label">SEO关键字</label>
						    <div class="col-md-9">
						      <input name="keywords" type="text" class="form-control" id="input4" placeholder="关键字">
						    </div>
						    <div class="col-md-2">
						    	
						    </div>
						  </div>
						  <div class="form-group row">
						    <label for="input5" class="col-md-1 control-label">SEO描述</label>
						    <div class="col-md-9">
						    	<textarea name="description" class="form-control" id="input5" rows="3" placeholder="SEO描述"></textarea>
						    </div>
						    <div class="col-md-2">
						    	
						    </div>
						  </div>
						  
						  <div class="form-group row">
						    <label for="input6" class="col-md-1 control-label">权重</label>
						    <div class="col-md-9">
						      <input name="weights" type="text" class="form-control" id="input6" placeholder="权重">
						    </div>
						    <div class="col-md-2">
						    	
						    </div>
						  </div>
						  <div class="form-group row">
						    <label for="input7" class="col-md-1 control-label">属性</label>
						    <div class="col-md-9">
							    <label class="checkbox-inline">
								  <input name="flag[]" type="checkbox" value="h"> 头条
								</label>
								<label class="checkbox-inline">
								  <input name="flag[]" type="checkbox" value="a"> 特别推荐
								</label>
								<label class="checkbox-inline">
								  <input name="flag[]" type="checkbox" value="c"> 推荐
								</label>
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