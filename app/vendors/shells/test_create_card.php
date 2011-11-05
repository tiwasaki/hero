<?php
App::import('Core', 'Controller');
App::import('Component', 'Fb');
App::import('Component', 'Card');
/***
 * 全ての部署のカードを作成する
 */
class TestCreateCardShell extends Shell {
	/** 使用するモデル **/
	var $uses = array(
		'User', 'Event', 'Unit'
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
		$this->_printlog('全ての部署のカード作成処理開始…','test_create_card');
		
		//指定したユーザーを取得
		$params = array(
			'conditions' => array('company_id' => $companyId, 'id' => 112)
		);
		
		$user = $this->User->find('first', $params);
		
		$this->User->begin();
		$this->userProcessing($user, $companyId);
		
		$this->User->commit();
		
		$this->_printlog('全ての部署のカード作成処理終了…', 'test_create_card');
	}
	
	/**
	 * ユーザごとの処理
	 * @param $user ユーザ情報
	 * @param $companyId 会社ID
	 */
	function userProcessing($user, $companyId) {
		$userId = $user['User']['id'];
		if($companyId == ID_CANDY){
			$picture = CARD_IMG_URL_BATCH_CANDY;
			$link = APP_URL_CANDY.'?user_id='.$userId;
			$description = APP_DESCRIPTION2_CANDY;
		} else if($companyId == ID_DUKE){
			$picture = CARD_IMG_URL_BATCH_DUKE;
			$link = APP_URL_DUKE.'?user_id='.$userId;
			$description = APP_DESCRIPTION2_DUKE;
		}
		
		//現在時刻からイベントを取得
		$event = $this->Event->getCurrentEvent();
		
		//イベントが取得できない場合は終了
		if(empty($event)) return;
		
		$this->_printlog($event, 'test_create_card');
		
		//現在の出勤回数からタイプ、昇進か判定
		$oldCategoryId = $user['User']['category_id'];
		list($type, $categoryId, $resultType) = $this->User->judgeType($user);
		$this->_printlog('type:'.$type, 'test_create_card');
		
		//カテゴリをUPDATEし、部署に配属
		$accessToken = $user['User']['access_token'];
		$user = $this->_changeUnit($user, $type, $categoryId, $oldCategoryId, $accessToken);	
		$this->_printlog($user, 'test_create_card');
	}
	
	/**
	 * カテゴリをUPDATEし、部署に配属
	 * @param $user ユーザ情報
	 * @param $type タイプ
	 * @param $categoryId カテゴリID
	 * @param $oldCategoryId 旧カテゴリID
	 * @param $accessToken アクセストークン
	 * @return $user 新ユーザ情報
	 */
	function _changeUnit($user, $type, $categoryId, $oldCategoryId, $accessToken) {
		$facebookUserId = $user['User']['facebook_user_id'];
		$companyId = $user['User']['company_id'];
		
		$userId = $user['User']['id'];
		
		//全ての部署を取得
		$units = $this->Unit->find('all');
		
		foreach($units as $unit){
			$unitId = $unit['Unit']['id'];
		
			$user['User']['category_id'] = $categoryId;
			
			//新しいカテゴリの方が大きい場合
			if($oldCategoryId < $categoryId){
				$user['User']['max_category_id'] = $categoryId;
			}
			
			$user['User']['unit_id'] = $unitId;
			
			if(!$this->User->save($user)){
				$this->User->rollback();
			}
			
			//旧カテゴリを保存
			$user['User']['old_category_id'] = $oldCategoryId;
			
			//配属部署履歴に書き込み
			$unitHistory = array(
				'user_id' => $userId,
				'company_id' => $companyId,
				'category_id' => $categoryId,
				'unit_id' => $unitId,
			);
			//カード作成
			$this->_createCard($userId, $user['User']['facebook_user_id'], $companyId, $categoryId, $unit['Unit']['card_name'], $playingFriends, $accessToken, $unitId);
		}
		return $user;
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
	 * @param $unitId 部署ID
	 */
	function _createCard($userId, $facebookUserId, $companyId, $categoryId, $unitName, $playingFriends, $accessToken,$unitId) {
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
		
		//カード作成テストなのでテストメソッドを使用する
		$this->Card->createCard2($baseImgPath, $starImgPath, $cardImgPath, $userId, $facebookUserId, $name, $unitName, $playingFriends,$unitId);
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
