<?php
/**
 * ショップ コントローラクラス
 */
class ShopController extends AppController {
	/** コントローラ名 */
	var $name   = 'shop';
	/** 使用モデル配列 */
	var $uses = null;
	
	function beforeFilter() {
		parent::beforeFilter();
	}
	
	function afterFilter() {
		parent::afterFilter();
	}
	
	/**
	 * アイテム一覧
	 */
	function index() {
		$this->pageTitle = 'アイテム一覧';
	}
	
	/**
	 * 個数選択
	 */
	function confirm() {
		$this->pageTitle = '個数選択';
	}
	
	/**
	 * 結果
	 */
	function complete() {
		$this->pageTitle = '結果';
	}
}
?>
