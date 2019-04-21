<?php
//header('Access-Control-Allow-Origin: http://www.baidu.com'); //设置http://www.baidu.com允许跨域访问
//header('Access-Control-Allow-Headers: X-Requested-With,X_Requested_With'); //设置允许的跨域header
define('SITE','sifangku.com');
date_default_timezone_set("Asia/chongqing");
error_reporting(E_ERROR);
header("Content-Type: text/html; charset=utf-8");
$CONFIG = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", file_get_contents("config.json")), true);
$setConfigArr=array(
	'imagePathFormat',
	'scrawlPathFormat',
	'snapscreenPathFormat',
	'catcherPathFormat',
	'videoPathFormat',
	'filePathFormat',
	'imageManagerListPath',
	'fileManagerListPath'
);
if(substr_count($_SERVER['SCRIPT_NAME'],'/')==4){
	/*
		判断是否项目在web根目录
		比如：/Js/ueditor/php/controller.php
		其中/数量为4
	*/
	$baseDir='';
}else{
	/*
		比如：/News/Js/ueditor/php/controller.php
		得到/News
	*/
	$baseDir=dirname(dirname(dirname(dirname($_SERVER['SCRIPT_NAME']))));
}
foreach ($setConfigArr as $value){
	$CONFIG[$value]=$baseDir.$CONFIG[$value];
}

$action = $_GET['action'];

switch ($action) {
    case 'config':
        $result =  json_encode($CONFIG);
        break;

    /* 上传图片 */
    case 'uploadimage':
    /* 上传涂鸦 */
    case 'uploadscrawl':
    /* 上传视频 */
    case 'uploadvideo':
    /* 上传文件 */
    case 'uploadfile':
        $result = include("action_upload.php");
        break;

    /* 列出图片 */
    case 'listimage':
        $result = include("action_list.php");
        break;
    /* 列出文件 */
    case 'listfile':
        $result = include("action_list.php");
        break;

    /* 抓取远程文件 */
    case 'catchimage':
        $result = include("action_crawler.php");
        break;

    default:
        $result = json_encode(array(
            'state'=> '请求地址出错'
        ));
        break;
}

/* 输出结果 */
if (isset($_GET["callback"])) {
    if (preg_match("/^[\w_]+$/", $_GET["callback"])) {
        echo htmlspecialchars($_GET["callback"]) . '(' . $result . ')';
    } else {
        echo json_encode(array(
            'state'=> 'callback参数不合法'
        ));
    }
} else {
    echo $result;
}