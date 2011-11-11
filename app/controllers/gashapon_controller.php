<?php
/**
 * ガチャ コントローラクラス
 */
class GashaponController extends AppController {
	/** コントローラ名 */
	var $name   = 'gashapon';
	/** 使用モデル配列 */
	var $uses = null;
	
	function beforeFilter() {
		parent::beforeFilter();
	}
	
	function afterFilter() {
		parent::afterFilter();
	}
	
	/**
	 * ガチャ一覧
	 */
	function index() {
		$this->pageTitle = 'ガチャ一覧';
	}
	
	/**
	 * ガチャ結果
	 */
	function result() {
		$this->pageTitle = 'ガチャ結果';
	}
}
?>
