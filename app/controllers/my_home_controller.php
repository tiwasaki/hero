<?php
/**
 * マイホーム コントローラクラス
 */
class MyHomeController extends AppController {
	/** コントローラ名 */
	var $name   = 'my_home';
	/** 使用モデル配列 */
	var $uses = null;
	
	function beforeFilter() {
		parent::beforeFilter();
	}
	
	function afterFilter() {
		parent::afterFilter();
	}
	
	function index() {
	}
}
?>
