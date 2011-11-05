<?php
/**
 * その他 コントローラクラス
 */
class OtherController extends AppController {
	/** コントローラ名 */
	var $name   = 'other';
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
