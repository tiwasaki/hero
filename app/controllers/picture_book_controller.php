<?php
/**
 * 図鑑 コントローラクラス
 */
class PictureBookController extends AppController {
	/** コントローラ名 */
	var $name   = 'picture_book';
	/** 使用モデル配列 */
	var $uses = null;
	
	function beforeFilter() {
		parent::beforeFilter();
	}
	
	function afterFilter() {
		parent::afterFilter();
	}
	
	/**
	 * コレクション一覧
	 */
	function index() {
		$this->pageTitle = 'コレクション一覧';
	}
	
	/**
	 * ヒーロー図鑑
	 */
	function hero() {
		$this->pageTitle = 'ヒーロー図鑑';
	}
	
	/**
	 * 怪人図鑑
	 */
	function mysterious_person() {
		$this->pageTitle = '怪人図鑑';
	}
	
	/**
	 * お宝図鑑
	 */
	function treasure() {
		$this->pageTitle = 'お宝図鑑';
	}
}
?>
