<?php
/**
 * 仲間 コントローラクラス
 */
class PlaymateController extends AppController {
	/** コントローラ名 */
	var $name   = 'playmate';
	/** 使用モデル配列 */
	var $uses = null;
	
	function beforeFilter() {
		parent::beforeFilter();
	}
	
	function afterFilter() {
		parent::afterFilter();
	}
	
	/**
	 * 仲間一覧
	 */
	function index() {
		$this->pageTitle = '仲間一覧';
	}
}
?>
