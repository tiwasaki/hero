<?php
/**
 * バトルデッキ コントローラクラス
 */
class DeckController extends AppController {
	/** コントローラ名 */
	var $name   = 'deck';
	/** 使用モデル配列 */
	var $uses = null;
	
	function beforeFilter() {
		parent::beforeFilter();
	}
	
	function afterFilter() {
		parent::afterFilter();
	}
	
	/**
	 * デッキ一覧
	 */
	function index() {
		$this->pageTitle = 'デッキ一覧';
	}
	
	/**
	 * デッキ編集
	 */
	function edit() {
		$this->pageTitle = 'デッキ編集';
	}
	
	/**
	 * カード選択
	 */
	function select() {
		$this->pageTitle = 'カード選択';
	}
}
?>
