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
<!--引入CSS-->
<link rel="stylesheet" type="text/css" href="<?php echo Url::getUrl('/Js/webuploader/webuploader.css')?>">
<!--引入JS-->
<script type="text/javascript" src="<?php echo Url::getUrl('/Js/webuploader/webuploader.min.js')?>"></script>
<script type="text/javascript">
$(function(){
	var uploader = WebUploader.create({
		//auto:true,
	    // swf文件路径
	    swf: '<?php echo Url::getUrl('/Js/webuploader/Uploader.swf')?>',

	    // 文件接收服务端。
	    server: '<?php echo Url::getUrl('/Js/webuploader/fileupload.php')?>',

	    // 选择文件的按钮。可选。
	    // 内部根据当前运行是创建，可能是input元素，也可能是flash.
	    pick: '#picker',

	    // 不压缩image, 默认如果是jpeg，文件上传前会压缩一把再上传！
	    resize: false
	});
	if(WebUploader.Base.os.android!==undefined){
		alert(WebUploader.Base.os.android);
		uploader.option('sendAsBinary',true);
	}
	var $fileList=$('#fileList');
	// 当有文件被添加进队列的时候
	uploader.on( 'fileQueued', function( file ) {
		$fileList.append('<li id="'+file.id+'" class="list-group-item">'+
						  		'<div class="info">'+
								  	'<div class="file-name">'+file.name+'</div>'+
								  	'<div class="file-size">'+WebUploader.Base.formatSize(file.size)+'</div>'+
								  	'<div class="file-status">'+
								  		'准备上传…'+
								  	'</div>'+
								  	'<div class="file-operate">'+
								  		'<span class="operate-remove glyphicon glyphicon-remove"></span>'+
								  	'</div>'+
								  	'<div style="clear:both;"></div>'+
							  	'</div>'+
							  	'<div class="progress">'+
								  '<div class="progress-bar progress-bar-info"></div>'+
								'</div>'+
						  '</li>');
	});
	// 文件上传过程中创建进度条实时显示。
	uploader.on( 'uploadProgress', function( file, percentage ) {
	    var $li = $( '#'+file.id ),
	        $percent = $li.find('.progress .progress-bar');
	    $li.find('.file-status').text(Math.floor(percentage * 100) + '%');

	    $percent.css( 'width', percentage * 100 + '%' );
	});
	uploader.on( 'uploadSuccess', function( file ) {
		$( '#'+file.id ).find('.file-operate').remove();
		$( '#'+file.id ).find('.file-status').html('<span class="glyphicon glyphicon-ok"></span> 已上传');
	});
	uploader.on( 'uploadError', function( file,reason ) {
		if(reason=='server'){
			var message=WebUploader.error;
		}else{
			var message='上传出错';
		}
		$( '#'+file.id ).find('div.file-status').text(message);
	});
	uploader.on('uploadAccept',function(object,ret){
		if(ret.error){
			WebUploader.error=ret.error.message;
			return false;
		}
	});
	uploader.on( 'error', function(error) {
		/*
		Q_TYPE_DENIED			文件类型不符合				accept
		F_EXCEED_SIZE			单个文件大小超过允许大小		fileSingleSizeLimit
		Q_EXCEED_SIZE_LIMIT		总文件大小超过允许			fileSizeLimit
		Q_EXCEED_NUM_LIMIT		文件总数量超过允许			fileNumLimit
		*/
		alert(error);
	});
	$('#start-upload').click(function(){
		uploader.upload();
	});
	$('#pause-upload').click(function(){
		uploader.stop(true);
	});
	$fileList.on('click','.operate-remove',function(){
		uploader.removeFile($(this).parents('li').attr('id'));
		$(this).parents('li').remove();
	});
});
</script>
<style type="text/css">
.webuploader-pick {
	padding:7px 13px;
	border-radius: 4px;
}
.btn-wrap {
	margin-top:10px;
}
ul.file-list {
	margin-top:10px;
}
.info {
	margin-top:4px;
}
.progress {
	margin:10px 0 3px 0;
	height:6px;
}
@media screen and (min-width:383px) {
	.btn-wrap >.col {
		float:left;
	}
	.btn-wrap .col .btn {
		margin-left:20px;
	}
}
@media screen and (max-width:382px) {
	.btn-wrap .col {
		width:100%;
		margin-bottom:10px;
	}
	.btn-wrap .col:last-child {
		margin-top:3px;
	}
	.btn-wrap .col .btn {
		margin:0;
	}
}
@media screen and (min-width:660px) {
	.file-name,.file-size,.file-status,.file-operate {
		float:left;
	}
    .file-name {
		width:58%;
		overflow:auto;
	}
	.file-size {
		width:13%;
	}
	.file-status {
		width:19%;
	}
	.file-operate {
		width:10%;
	}
}
@media screen and (min-width:432px) and (max-width:659px) {
	.file-name,.file-status,.file-operate {
		float:left;
	}
	.file-name {
		width:54%;
		overflow:auto;
	}
	.file-size {
		display:none;
	}
	.file-status {
		width:36%;
	}
	.file-operate {
		width:10%;
	}
}
@media screen and (max-width:431px) {
	.file-name,.file-size,.file-status,.file-operate {
		width:100%;
	}
    .file-name {
    	overflow:auto;
	}
}
</style>
</head>
<body>
<div class="container-fluid">
	<div class="btn-wrap">
		<div class="col">
	  		<div id="picker" class="btn-picker"><span class="glyphicon glyphicon-plus"></span> 选择文件</div>
	  	</div>
	  	<div class="col">
	  		<div id="start-upload" class="btn btn-primary"><span class="glyphicon glyphicon-play"></span> 开始上传</div>
	  	</div>
	  	<div class="col">
	  		<div id="pause-upload" class="btn btn-warning"><span class="glyphicon glyphicon-pause"></span> 暂停上传</div>
		</div>
		<div style="clear:both;"></div>
	</div>
	<ul id="fileList" class="file-list list-group">
	</ul>
</div>

</body>
</html>