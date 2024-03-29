<?php
/**
 * トップ コントローラクラス
 */
class TopController extends AppController {
	/** コントローラ名 */
	var $name   = 'top';
	/** 使用モデル配列 */
	var $uses = null;
	
	function beforeFilter() {
		parent::beforeFilter();
	}
	
	function afterFilter() {
		parent::afterFilter();
	}
	
	/**
	 * トップ
	 */
	function index() {
		$this->pageTitle = 'トップ';
		$me = $this->Gree->getMe();
		if(!empty($me['id'])){
			$this->Session->write('greeUserId', $me['id']);
		}
	}
}
?>
