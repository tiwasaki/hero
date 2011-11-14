<?php
/**
 * GREE用 コンポーネント
 */
class GreeComponent extends OauthComponent {
	function initialize(&$controller){
		parent::initialize($controller);
	}
	
	/**
	 * GREEのユーザIDからフレンド一覧を取得する
	 * @param $greeUserId GREEのユーザID
	 * @return フレンド一覧
	 */
	function getFriendsByGreeUserId($greeUserId) {
		$url = GREE_PEOPLE_API_URL.$greeUserId.'/@all';
		return $this->_getArrayByUrl($url);
	}
	
	/**
	 * GREEのユーザIDからユーザ情報を取得する
	 * @param $greeUserId GREEのユーザID
	 * @return ユーザ情報
	 */
	function getUserByGreeUserId($greeUserId) {
		$url = GREE_PEOPLE_API_URL.$greeUserId.'/@self';
		return $this->_getArrayByUrl($url);
	}
	
	/**
	 * 自身のユーザ情報を取得する
	 * @return ユーザ情報
	 */
	function getMe() {
		$url = GREE_PEOPLE_API_URL.'@me/@self';
		return $this->_getArrayByUrl($url);
	}
	
	/**
	 * URLから配列を返す
	 * @param $url URL
	 * @return 配列
	 */
	function _getrayByUrl($url) {
		$responseArray = json_decode(parent::requestAPI($url),true);
		return $responseArray['entry'];
	}
}
?>
