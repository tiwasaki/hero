<?php
/**
 * アイテム コントローラクラス
 */
class ItemController extends AppController {
	/** コントローラ名 */
	var $name   = 'item';
	/** 使用モデル配列 */
	var $uses = null;
	
	function beforeFilter() {
		parent::beforeFilter();
	}
	
	function afterFilter() {
		parent::afterFilter();
	}
	
	/**
	 * アイテム詳細
	 */
	function index() {
		$this->pageTitle = 'アイテム詳細';
	}
	
	/**
	 * アイテムリスト
	 */
	function lists() {
		$this->pageTitle = 'アイテムリスト';
	}
	
	/**
	 * 使用確認
	 */
	function uses() {
		$this->pageTitle = '使用確認';
	}
	
	/**
	 * 使用結果
	 */
	function use_complete() {
		$this->pageTitle = '使用結果';
	}
}
?>
