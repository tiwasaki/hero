<?php
/**
 * GREE用 コンポーネント
 */
class GreeComponent extends OauthComponent {
	function initialize(&$controller){
		parent::initialize();
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
	 * URLから配列を返す
	 * @param $url URL
	 * @return 配列
	 */
	function _getArrayByUrl($url) {
		$responseArray = json_decode(parent::requestAPI($url),true);
		return $responseArray['entry'];
	}
}
?>
