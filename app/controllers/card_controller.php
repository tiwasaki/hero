<?php
/**
 * カード コントローラクラス
 */
class CardController extends AppController {
	/** コントローラ名 */
	var $name   = 'card';
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
