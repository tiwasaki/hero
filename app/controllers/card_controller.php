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
	
	/**
	 * カード詳細
	 */
	function index() {
		$this->pageTitle = 'カード詳細';
	}
	
	/**
	 * カードリスト
	 */
	function lists() {
		$this->pageTitle = 'カードリスト';
	
	}
	
	/**
	 * 売却確認
	 */
	function sell() {
		$this->pageTitle = '売却確認';
	}
	
	/**
	 * 売却結果
	 */
	function sell_complete() {
		$this->pageTitle = '売却結果';
	}
}
?>
