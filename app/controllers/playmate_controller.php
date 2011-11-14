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
	 * 仲間詳細
	 * @param GREEユーザID
	 */
	function index($greeUserId) {
		$this->pageTitle = '仲間詳細';
		$this->set('self', $this->Gree->getUserByGreeUserId($greeUserId));
	}
	
	/**
	 * 仲間一覧
	 */
	function lists() {
		$this->pageTitle = '仲間一覧';
		$greeUserId = $this->Session->read('greeUserId');
		$this->set('friends', $this->Gree->getFriendsByGreeUserId($greeUserId));
	}
}
?>
