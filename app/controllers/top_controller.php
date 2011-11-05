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
	
	function index() {
		$url = GREE_PEOPLE_API_URL.'@me/@self';
		$responseArray = json_decode($this->Oauth->requestAPI($url),true);
		$this->Session->write('greeUserId', $responseArray['entry']['id']);
	}
}
?>
