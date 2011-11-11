<?php
/**
 * 遊び方 コントローラクラス
 */
class HowToController extends AppController {
	/** コントローラ名 */
	var $name   = 'how_to';
	/** 使用モデル配列 */
	var $uses = null;
	
	function beforeFilter() {
		parent::beforeFilter();
	}
	
	function afterFilter() {
		parent::afterFilter();
	}
	
	/**
	 * 遊び方
	 */
	function index() {
		$this->pageTitle = '遊び方';
	}
}
?>
