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
	$('button.delete-type').click(function(){
		$('#myModal .modal-body').html('<div class="alert alert-warning" role="alert">您真的要删除栏目 <span style="color:red;font-weight:bold;">'+$(this).data('name')+'</span> 以及它的所有后代吗？</div>');
		$('#myModal .modal-footer button:last-child').data('id',$(this).data('id'));
		$('#myModal').modal();
	});
	$('#myModal .modal-footer button:last-child').click(function(){
		$('#myModal .modal-body').html('<p>处理中...</p>');
		//alert($(this).data('id'));
		$.post('<?php echo Url::getUrl(array(C=>'type',M=>'delete'))?>',{id:$(this).data('id')},function(responseText){
			if(responseText.status){
				window.location.href='<?php echo Url::getUrl(array(C=>'type',M=>'index'))?>';
	        }else{
        		$("#myModal .modal-body").html('<div class="alert alert-danger" role="alert">'+responseText.message+'</div>');
            }
		},'json');
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
.sfk-table>tbody>tr>td, .sfk-table>tbody>tr>th, .sfk-table>tfoot>tr>td, .sfk-table>tfoot>tr>th, .sfk-table>thead>tr>td, .sfk-table>thead>tr>th {
	vertical-align:middle;
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
			<table class="sfk-table table table-hover">
		      <thead>
		        <tr>
		          <th width=70>排序</th>
		          <th width=60>id</th>
		          <th>栏目名称</th>
		          <th>管理</th>
		        </tr>
		      </thead>
		      <tbody>
		      	<?php 
		      	foreach ($this->getData('arctype') as $val){
		      	?>
		        <tr>
		          <td><input style="width:50px;height:26px;" class="form-control input-sm" type="text" value="<?php echo $val['sort']?>"></td>
		          <td><?php echo $val['id']?></td>
		          <th><?php echo $val['style'].' '.$val['name']?></th>
		          <td>
		          	<a href="<?php echo Url::getUrl(array(E=>'index.php',C=>'index',M=>'type','uname'=>$val['uname']))?>" target="_blank" type="button" class="btn btn-primary btn-xs">预览</a>&nbsp;&nbsp;
		          	<a href="<?php echo Url::getUrl(array(C=>'type',M=>'update','id'=>$val['id']))?>" type="button" class="btn btn-primary btn-xs">更改</a>&nbsp;&nbsp;
		          	<button data-id="<?php echo $val['id']?>" data-name="<?php echo $val['name']?>" type="button" class="delete-type btn btn-primary btn-xs">删除</button>
		          </td>
		        </tr>
		        <?php
		        }
		        ?>
		      </tbody>
		    </table>
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
        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
        <button type="button" class="btn btn-primary">确定</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</body>
</html>