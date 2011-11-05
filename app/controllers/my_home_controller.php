<?php
/**
 * マイホーム コントローラクラス
 */
class MyHomeController extends AppController {
	/** コントローラ名 */
	var $name   = 'my_home';
	/** 使用モデル配列 */
	var $uses = null;
	/** 使用コンポーネント **/
	var $components = array('Oauth');
	
	function beforeFilter() {
		parent::beforeFilter();
	}
	
	function afterFilter() {
		parent::afterFilter();
	}
	
	function index() {
		$url = GREE_PEOPLE_API_URL.'@me/@self';
		$responseArray = json_decode($this->Oauth->requestAPI($url),true);
		$this->set('self', $responseArray['entry']);
		
		$url = GREE_PEOPLE_API_URL.'@me/@all';
		$responseArray = json_decode($this->Oauth->requestAPI($url),true);
		$this->set('friends', $responseArray['entry']);
	}
}
?>
