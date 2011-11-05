<?php
App::import('Core', 'Controller');
App::import('Component', 'Fb');
App::import('Component', 'Card');
/***
 * 現在の情報でカード再作成を行うシェル
 */
class CreateCardShell extends Shell {
	/** 使用するモデル **/
	var $uses = array(
		'User', 'Unit', 'CardFriend'
	);
	
	/**
	 * シェル 初期化
	 */
	function startup(){
    	$this->controller = new Controller();
    	$this->Fb = new FbComponent($this);
    	$this->Fb->startup($this->controller);
    	$this->Card = new CardComponent($this);
    	$this->Card->startup($this->controller);
    }
  
	/***
	 * メイン処理
	 */
	function main() {
		//コマンドライン引数
		$shellType = $this->args[0];
		
		if($shellType == DIR_NAME_CANDY){
			$appId = APP_ID_CANDY;
			$secret = SECRET_CANDY;
			$companyId = ID_CANDY;
		} else if($shellType == DIR_NAME_DUKE){
			$appId = APP_ID_DUKE;
			$secret = SECRET_DUKE;
			$companyId = ID_DUKE;
		}
		
		$this->Fb->init($appId, $secret);
		$this->_printlog('カード再作成処理開始…','create_card');
		
		//すべてのユーザを取得
		$params = array(
			'conditions' => array('company_id' => $companyId)
		);
		
		$users = $this->User->find('all', $params);
		
		foreach ( $users as $user ) {
			$this->CardFriend->begin();
			try {
				$this->userProcessing($user, $companyId);
			}catch( Exception $e ){
				$this->_printlog('userId:'.$user['User']['id'], 'create_card');
				$this->_printlog($e->getMessage(), 'create_card');
				$this->CardFriend->rollback();
			}
			$this->CardFriend->commit();
		}
		
		$this->_printlog('カード再作成処理終了…', 'create_card');
	}
	
	/**
	 * ユーザごとの処理
	 * @param $user ユーザ情報
	 * @param $companyId 会社ID
	 */
	function userProcessing($user, $companyId) {
		$userId = $user['User']['id'];
		$facebookUserId = $user['User']['facebook_user_id'];
		$categoryId = $user['User']['category_id'];
		$accessToken = $user['User']['access_token'];
		
		//プレイ中のフレンド情報を取得
		$friends = $this->Fb->getFriendListByUid('uid, pic_square', $facebookUserId, $accessToken);
		$playingFriends = $this->User->getPlayingFriends($friends, $companyId, CARD_FRIEND_COUNT);
		
		$friendIds = $this->User->getFriendIds($playingFriends, $companyId);
		$this->CardFriend->create();
		//カードフレンド情報を書き込み
		if(!$this->CardFriend->saveCardFriends($userId, $friendIds)){
			$this->CardFriend->rollback();
		}
		
		$unit = $this->Unit->getUserUnit($userId);
		
		//カード作成
		$this->_createCard($userId, $facebookUserId, $companyId, $categoryId, $unit['Unit']['card_name'], $playingFriends, $accessToken);
	}
	
	/**
	 * カードの作成処理
	 * @param $userId ユーザID
	 * @param $facebookUserId FacebookユーザID
	 * @param $companyId 会社ID
	 * @param $categoryId カテゴリID
	 * @param $unitName 部署名
	 * @param $playingFriends フレンド配列
	 * @param $accessToken アクセストークン
	 */
	function _createCard($userId, $facebookUserId, $companyId, $categoryId, $unitName, $playingFriends, $accessToken) {
		//プロフィールの取得
		$userProfile = $this->Fb->apiParam3('/'.$facebookUserId, 'GET', array());
		$name = $userProfile['first_name']."\n".$userProfile['last_name'];
		
		//社員証を作成
		if($companyId == ID_CANDY){
			$baseImgPath = BASE_IMG_PATH_CANDY;
			$starImgPath = IMG_PATH_CANDY.$this->_getStarImageName($categoryId);
			$cardImgPath = CARD_IMG_PATH_CANDY;
		} else if($companyId == ID_DUKE){
			$baseImgPath = BASE_IMG_PATH_DUKE;
			$starImgPath = IMG_PATH_DUKE.$this->_getStarImageName($categoryId);
			$cardImgPath = CARD_IMG_PATH_DUKE;
		}
		$me = $this->Fb->getMe('pic_big', $accessToken);
		$this->Card->createCard($baseImgPath, $starImgPath, $cardImgPath, $userId, $facebookUserId, $name, $unitName, $playingFriends, $me[0]['pic_big']);
	}
	
	/**
	 * カテゴリIDから星画像の名前を取得する
	 * @param $categoryId カテゴリID
	 */
	function _getStarImageName($categoryId) {
		$starImgName = Configure::read('STAR_IMG_NAME');
		return $starImgName[$categoryId];
	}
	
	/***
	 * コンソールログ出力とファイルログの書き込みを同時に行う
	 * @param $msg メッセージ
	 * @param $file 書き込み先ファイル
	 */
	function _printlog($msg, $file) {
		$this->out($msg);
		$this->log($msg,$file);
	}
}
?>
