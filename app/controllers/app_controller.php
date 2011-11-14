<?php
/***
 * 共通Controller
 */
class AppController extends Controller {
	var $name = 'AppController';
	var $view = 'Smarty';
	var $helpers = array('Html', 'Form', 'Javascript');
	/** 使用コンポーネント **/
	var $components = array('Oauth','Gree');
	var $logger = false;
	
	/**
	 * コンストラクタ
	 */
	function __construct(){
		parent::__construct();
		// ログコンポーネント
		App::import('Vendor', 'logger');
		$this->logger = new Logger();
		$this->logger->write_access_log();
		
		//PHPのエラーをログに出力するように設定	
		error_reporting(E_ALL);
	    ini_set('log_errors', 1);
	    ini_set('error_log', LOGS . 'error.log');
	}
	
	
	/**
	 * 処理前フィルター
	 */
	function beforeFilter() {
		//キャッシュしない
		header("Cache-Control: no-cache");
	}
	
	/**
	 * 処理後フィルター
	 */
	function afterFilter() {
	}
}
?>