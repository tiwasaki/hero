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
		$url = GREE_PEOPLE_API_URL.$greeUserId.'/@self';
		$responseArray = json_decode($this->Oauth->requestAPI($url),true);
		$this->set('self', $responseArray['entry']);
		
		$url = GREE_PEOPLE_API_URL.$greeUserId.'/@all';
		$responseArray = json_decode($this->Oauth->requestAPI($url),true);
		$this->set('friends', $responseArray['entry']);
	}
}
?>
