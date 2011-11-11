<?php
/**
 * お誘い コントローラクラス
 */
class InviteController extends AppController {
	/** コントローラ名 */
	var $name   = 'invite';
	/** 使用モデル配列 */
	var $uses = null;
	
	function beforeFilter() {
		parent::beforeFilter();
	}
	
	function afterFilter() {
		parent::afterFilter();
	}
	
	/**
	 * お誘い仲間一覧
	 */
	function index() {
		$this->pageTitle = 'お誘い仲間一覧';
	}
}
?>
