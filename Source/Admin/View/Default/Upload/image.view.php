<?php use Common\Url;
if(!defined('SITE')) exit('Access Denied');?>


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
	    server: '<?php echo Url::getUrl(array(C=>'upload',M=>'upload'))?>',

	    // 选择文件的按钮。可选。
	    // 内部根据当前运行是创建，可能是input元素，也可能是flash.
	    pick: '#picker',
	    accept: {
	        title: 'Images',
	        extensions: 'gif,jpg,jpeg,bmp,png',
	        mimeTypes: 'image/gif,image/jpg,image/jpeg,imge/bmp,image/png'
	    },
	    // 不压缩image, 默认如果是jpeg，文件上传前会压缩一把再上传！
	    resize: false
	});
	if(WebUploader.Base.os.android!==undefined){
		uploader.option('sendAsBinary',true);
	}
	var $fileList=$('#fileList');
	// 当有文件被添加进队列的时候
	uploader.on( 'fileQueued', function( file ) {
		var $li=$('<li id='+file.id+' class="list-group-item">'+
					  	'<div class="info">'+
						  	'<div class="file-thumb"><img></div>'+
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
						  '<div class="progress-bar progress-bar-info">'+
						    
						  '</div>'+
						'</div>'+
				  '</li>');
		var $img = $li.find('div.file-thumb>img');
	    $fileList.append($li);
	    uploader.makeThumb( file, function( error, src ) {
	        if ( error ) {
	            $img.replaceWith('<span>不能预览</span>');
	            return;
	        }
	        $img.attr( 'src', src );
	    }, 100, 100);
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

@media screen and (max-width:343px) {
	.file-thumb {
		width:100%;
		overflow:hidden;
		float:left;
	}
    .file-name {
    	display:none;
	}
	.file-size {
		display:none;
	}
	.file-status {
		width:100%;
	}
	.file-operate {
		width:100%;
		float:left;
	}
}

@media screen and (min-width:344px) and (max-width:431px) {
	.file-thumb {
		width:40%;
		overflow:hidden;
		float:left;
	}
    .file-name {
    	display:none;
	}
	.file-size {
		display:none;
	}
	.file-status {
		width:50%;
		float:left;
	}
	.file-operate {
		width:10%;
		float:left;
	}
}

@media screen and (min-width:432px) and (max-width:659px) {
	.file-thumb {
		width:30%;
		overflow:hidden;
		float:left;
	}
    .file-name {
    	display:none;
	}
	.file-size {
		width:24%;
		float:left;
	}
	.file-status {
		width:36%;
		float:left;
	}
	.file-operate {
		width:10%;
		float:left;
	}
}

@media screen and (min-width:660px) {
	.file-thumb {
		width:20%;
		float:left;
	}
    .file-name {
		width:38%;
		float:left;
	}
	.file-size {
		width:13%;
		float:left;
	}
	.file-status {
		width:19%;
		float:left;
	}
	.file-operate {
		width:10%;
		float:left;
	}
}
</style>

<div class="container-fluid">
	<div class="btn-wrap">
	  <div class="col">
	  	<div id="picker" class="btn-picker"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> 选择图片</div>
	  </div>
	  <div class="col">
	  	<div id="start-upload" class="btn btn-primary"><span class="glyphicon glyphicon-play" aria-hidden="true"></span> 开始上传</div>
	  </div>
	  <div class="col">
	  	<div id="pause-upload" class="btn btn-warning"><span class="glyphicon glyphicon-pause" aria-hidden="true"></span> 暂停上传</div>
	  </div>
	</div>
	<div style="clear:both;"></div>
	<ul id="fileList" class="file-list list-group"></ul>
</div>
