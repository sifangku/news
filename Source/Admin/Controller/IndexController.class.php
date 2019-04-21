<?php
namespace Admin\Controller;
use Common\Controller;
use Common\Url;
if(!defined('SITE')) exit('Access Denied');
class IndexController extends Controller {
	public function index(){
		header('Location:'.Url::getUrl(array(C=>'type',M=>'index')));
// 		var_dump('index...');
// 		var_dump($_GET);
	}
	public function test(){
		var_dump('test...');
		var_dump($_GET);
		
	}
}