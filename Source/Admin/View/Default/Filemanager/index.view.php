<?php use Common\Url;
if(!defined('SITE')) exit('Access Denied');?>
<link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
<style type="text/css">
.list-group>.list-group-item>.row i.fa-folder {
	color:#1e91cf;
}
.list-group>.list-group-item>.row i.fa-file {
	color:#ddd;
}
</style>
<ol class="breadcrumb">
	<?php 
	foreach ($this->getData('cwd') as $value){
		echo "<li><a class='chdir' data-path='{$value['path_web']}' href='#'>{$value['name']}</a></li>";
	}
	?>
</ol>
<ul class="list-group">
	<?php 
	foreach ($this->getData('files') as $value){
	?>
	  <li class="list-group-item">
	  	<div class="row">
	  		<?php 
			//是否为目录（文件夹）
			if($value['is_dir']){
			?>
			<div class="col-md-12"><i class="fa fa-folder"></i> <a class='chdir' data-path='<?php echo $value['path_web']?>' href='#'><?php echo $value['name']?></a></div>
			<?php
			//简单判断是否为图片
			}elseif (strpos($value['type'],'image/')!==false){
			?>
			<div class="col-md-6">
				<i class="fa fa-file"></i>  <?php echo $value['name']?>
    			
    		</div>
			<div class="col-md-4">
  				<img width=100 src="<?php echo Url::getUrl($value['path_web'])?>" />
  			</div>
  			<div class="col-md-2">
  				<label><input name="file[]" value="<?php echo $value['path_web']?>" data-filetype="image" type="checkbox" style="width:50px;height:50px;" /></label>
  			</div>
			<?php 
			}else{
			?>
			<div class="col-md-12"><i class="fa fa-file"></i> <?php echo $value['name']?></div>
			<?php 
			}
			?>
	  	</div>
	  	
	  </li>
	<?php 
	}
	?>
</ul>
<script type="text/javascript">
	$('a.chdir').click(function(){
		$('#fileManageModal .modal-body').html('<div style="text-align:center;"><img src="<?php echo Url::getUrl('/Images/loading.gif')?>" /></div>');
		$.get('<?php echo Url::getUrl(array(E=>'admin.php',C=>'filemanager',M=>'index'))?>',{path:$(this).data('path')},function(data){
			$('#fileManageModal .modal-body *').remove();
			$('#fileManageModal .modal-body').html(data);
		});
		return false;
	});
	
// 	$('a.pick').click(function(){
// 		$('#input3').val($(this).data('path'));
// 		$('#myModal1').modal('hide');
// 		return false;
// 	});
// 	$('#ok').click(function(){
		
// 	});


</script>

