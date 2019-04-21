<?php use Common\Url;
if(!defined('SITE')) exit('Access Denied');?>
var fileManageHtml='<div class="modal fade" id="fileManageModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">'+
					  '<div class="modal-dialog" role="document">'+
					    '<div class="modal-content">'+
					      '<div class="modal-header">'+
								'<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
								'<h4 class="modal-title">图片管理器</h4>'+
							'</div>'+
							'<div class="modal-body">'+
								'<div style="text-align:center;"><img src="<?php echo Url::getUrl('/Images/loading.gif')?>" /></div>'+
							'</div>'+
							'<div class="modal-footer">'+
								'<button data-status="upload" type="button" class="btn btn-info">上传</button>&nbsp;&nbsp;'+
								'<button type="button" class="btn btn-primary">确认</button>'+
							'</div>'+
					    '</div>'+
					  '</div>'+
					'</div>';
function fileManage(callBack){
	$('#fileManageModal').remove();//移除旧窗口
	var $fileManageHtml=$(fileManageHtml);//创建新旧窗口
	//绑定按钮事件
	$fileManageHtml.find('.modal-footer>button:first-child').click(function(){
		$(this).attr('disabled','disabled');
		$('#fileManageModal .modal-body *').remove();//移除原数据与事件以及元素
 		$('#fileManageModal .modal-body').html('<div style="text-align:center;"><img src="<?php echo Url::getUrl('/Images/loading.gif')?>" /></div>');
		var _this=this;
		var url='';
		var status='';
		var text='';
		switch($(this).data('status')){
			case 'upload':
				url='<?php echo Url::getUrl(array(E=>'admin.php',C=>'upload',M=>'image'))?>';
				status='return';
				text='返回';
				break;
			case 'return':
				url='<?php echo Url::getUrl(array(E=>'admin.php',C=>'filemanager',M=>'index'))?>';
				status='upload';
				text='上传';
				break;
		}
		$.get(url,function(data){
			$(_this).data('status',status).text(text);
 			$('#fileManageModal .modal-body').html(data);
 			$(_this).removeAttr('disabled');
 		});
	});
	
	$fileManageHtml.find('.modal-footer>button:last-child').click(function(){
		var checkTypes=[];
		var checkValues=[];
		$('#fileManageModal').find('input[type=checkbox]:checked').each(function(){
			checkTypes.push($(this).data('filetype'));
			checkValues.push($(this).val());
		});
		if (typeof callBack === "function"){
           callBack(checkValues,checkTypes);
        }
	});
	
	
	$fileManageHtml.modal().appendTo('body');
	$.get('<?php echo Url::getUrl(array(E=>'admin.php',C=>'filemanager',M=>'index'))?>',function(data){
		$('#fileManageModal .modal-body').html(data);
	});
}
