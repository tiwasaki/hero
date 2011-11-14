<?php
/**
 * アジト コントローラクラス
 */
class HideoutController extends AppController {
	/** コントローラ名 */
	var $name   = 'hideout';
	/** 使用モデル配列 */
	var $uses = null;
	
	function beforeFilter() {
		parent::beforeFilter();
	}
	
	function afterFilter() {
		parent::afterFilter();
	}
	
	/**
	 * アジト
	 */
	function index() {
		$this->pageTitle = 'アジト';
		$greeUserId = $this->Session->read('greeUserId');
		$this->set('self', $this->Gree->getUserByGreeUserId($greeUserId));
		$this->set('friends', $this->Gree->getFriendsByGreeUserId($greeUserId));
	}
}
?>
