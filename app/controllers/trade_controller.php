<?php
/**
 * トレード コントローラクラス
 */
class TradeController extends AppController {
	/** コントローラ名 */
	var $name   = 'trade';
	/** 使用モデル配列 */
	var $uses = null;
	
	function beforeFilter() {
		parent::beforeFilter();
	}
	
	function afterFilter() {
		parent::afterFilter();
	}
	
	/**
	 * トレードメニュー
	 */
	function index() {
		$this->pageTitle = 'トレードメニュー';
	}
	
	/**
	 * トレード品を受け取る
	 */
	function get_trade_goods() {
		$this->pageTitle = 'トレード品を受け取る';
	}
}
?>
