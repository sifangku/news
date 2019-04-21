<?php use Common\Url;
if(!defined('SITE')) exit('Access Denied');?>
<!DOCTYPE html>
<html id="html">
<head>
<meta charset="utf-8" />
<?php $this->inc('title')?>
<script type="text/javascript" src="<?php echo Url::getUrl('/Css/bootstrap/js/bootstrap.min.js')?>"></script>
<script type="text/javascript">
$(function(){
	$('a.continue').click(function(){
		$("#myModal .modal-body").html('<p>验证中，耐心等待...</p>');
		$('#myModal').modal();
		var formData=$("form").serialize();
		$.post('<?php echo Url::getUrl(array(M=>'step3'))?>',formData,function(response){
			if(response.status==0){
				$("#myModal .modal-body").html('<div class="alert alert-danger" role="alert">'+response.message+'</div>');
        		$("#myModal .modal-footer").html('<button type="button" class="btn btn-primary" data-dismiss="modal">确定</button>');
			}else{
				$('#myModal').modal('hide');
				if(response.warning!=undefined){
		            if (confirm(response.warning)!=true){  
		            	return false;
		            }
				}
				$('div.current').removeClass('current').next().addClass('current');
				var step4Html='<div class="panel panel-default">'+
								  '<div class="panel-heading">正在安装</div>'+
								  '<div class="panel-body">'+
								  	'<img src="<?php echo Url::getUrl('/Images/loading.gif')?>" /> 正在安装中，大概需要几秒到几十秒的时间，请务必耐心等待... '+
								  '</div>'+
								'</div>';
				$('div.col-md-10').html(step4Html);
				$.post('<?php echo Url::getUrl(array(M=>'step4'))?>',formData,function(response){
					if(response.status==0){
						$('div.col-md-10 panel-body').html("安装失败："+response.message+" 请刷新本页面，重新安装");
					}else if(response.status==1){
						window.location.href='<?php echo Url::getUrl(array(M=>'step5'))?>';
					}else{
						alert(response);
					}
				},'json');
			}
		},'json');
		return false;
	});
});
</script>
</head>
<body>
<div class="container">
	<?php $this->inc('head')?>
	<div class="row">
		<?php $this->inc('sidebar')?>
  		<div class="col-md-10">
  			<form class="form-horizontal">
  			<div class="panel panel-default">
			  <div class="panel-heading">参数配置</div>
			  <div class="panel-body">
			  	
			  	<div class="alert alert-success" role="alert">数据库设定：</div>
				  <div class="form-group">
				    <label for="input1" class="col-sm-2 control-label">数据库主机：</label>
				    <div class="col-sm-9">
				      <input name="db_host" type="text" class="form-control" id="input1" placeholder="数据库主机" value="localhost">
				    </div>
				  </div>
				  <div class="form-group">
				    <label for="input5" class="col-sm-2 control-label">数据库端口：</label>
				    <div class="col-sm-9">
				      <input name="db_port" type="text" class="form-control" id="input5" placeholder="数据库端口" value="3306">
				    </div>
				  </div>
				  <div class="form-group">
				    <label for="input2" class="col-sm-2 control-label">数据库用户：</label>
				    <div class="col-sm-9">
				      <input name="db_user" type="text" class="form-control" id="input2" placeholder="数据库用户" value="">
				    </div>
				  </div>
				  <div class="form-group">
				    <label for="input3" class="col-sm-2 control-label">数据库密码：</label>
				    <div class="col-sm-9">
				      <input name="db_pw" type="text" class="form-control" id="input3" placeholder="数据库密码" value="">
				    </div>
				  </div>
				  <div class="form-group">
				    <label for="input4" class="col-sm-2 control-label">数据库名称：</label>
				    <div class="col-sm-9">
				      <input name="db_database" type="text" class="form-control" id="input4" placeholder="数据库名称">
				    </div>
				  </div>
				  
				  <div class="alert alert-success" role="alert">管理员初始密码：</div>
				  <div class="form-group">
				    <label for="input5" class="col-sm-2 control-label">用户名：</label>
				    <div class="col-sm-9">
				      <input name="manage_name" type="text" class="form-control" id="input5" placeholder="用户名">
				    </div>
				  </div>
				  <div class="form-group">
				    <label for="input6" class="col-sm-2 control-label">密　码：</label>
				    <div class="col-sm-9">
				      <input name="manage_pw" type="text" class="form-control" id="input6" placeholder="密　码">
				    </div>
				  </div>
				
			  </div>
			</div>
			<a href="<?php echo Url::getUrl(array(E=>'install.php',C=>'index',M=>'step1'))?>" class="btn btn-primary">返回</a>&nbsp;&nbsp;&nbsp;&nbsp;
			<a href="<?php echo Url::getUrl(array(E=>'install.php',C=>'index',M=>'step3'))?>" class="continue btn btn-primary">继续</a>
  			</form>
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
        
      </div>
      <div class="modal-footer">
        
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


</body>
</html>
<?php exit()?>