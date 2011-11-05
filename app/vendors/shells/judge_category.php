<?php
App::import('Core', 'Controller');
App::import('Component', 'Fb');
App::import('Component', 'Card');
/***
 * カテゴリの判定処理を行うシェル
 */
class JudgeCategoryShell extends Shell {
	/** 使用するモデル **/
	var $uses = array(
		'User', 'Event', 'UnitHistory', 'WallWord',
		'Company', 'Unit', 'Category', 'CardFriend'
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
		$this->_printlog('カテゴリの判定処理開始…','judge_category');
		
		//すべてのユーザを取得
		$params = array(
			'conditions' => array('company_id' => $companyId)
		);
		
		$users = $this->User->find('all', $params);
		
		foreach ( $users as $user ) {
			$this->User->begin();
			try {
				$this->userProcessing($user, $companyId);
			}catch( Exception $e ){
				$this->_printlog('userId:'.$user['User']['id'], 'judge_category');
				$this->_printlog($e->getMessage(), 'judge_category');
				$this->User->rollback();
			}
			$this->User->commit();
		}
		
		$this->_printlog('カテゴリの判定処理終了…', 'judge_category');
	}
	
	/**
	 * ユーザごとの処理
	 * @param $user ユーザ情報
	 * @param $companyId 会社ID
	 */
	function userProcessing($user, $companyId) {
		$userId = $user['User']['id'];
		$this->_printlog('id:'.$userId, 'judge_category');
		$this->_printlog('name:'.$user['User']['name'], 'judge_category');
		$this->_printlog('company_id:'.$companyId, 'judge_category');
		
		if($companyId == ID_CANDY){
			$picture = CARD_IMG_URL_BATCH_CANDY;
			$link = APP_URL_CANDY.'?user_id='.$userId;
			$message = APP_MESSAGE_CANDY;
			$add = WALL_MESSAGE_ADD_CANDY;
		} else if($companyId == ID_DUKE){
			$picture = CARD_IMG_URL_BATCH_DUKE;
			$link = APP_URL_DUKE.'?user_id='.$userId;
			$message = APP_MESSAGE_DUKE;
			$add = WALL_MESSAGE_ADD_DUKE;
		}
		
		//現在時刻からイベントを取得
		$event = $this->Event->getCurrentEvent();
		
		//イベントが取得できない場合は終了
		if(empty($event)) return;
		
		$this->_printlog($event, 'judge_category');
		
		//現在の出勤回数からタイプ、昇進か判定
		$oldCategoryId = $user['User']['category_id'];
		list($type, $categoryId, $resultType) = $this->User->judgeType($user);
		
		$this->_printlog('type:'.$type, 'judge_category');
		$this->_printlog('categoryId:'.$categoryId, 'judge_category');
		$this->_printlog('resultType:'.$resultType, 'judge_category');
		
		//カテゴリをUPDATEし、部署に配属
		$accessToken = $user['User']['access_token'];
		$user = $this->_changeUnit($user, $type, $categoryId, $oldCategoryId, $accessToken);
		$this->_printlog($user, 'judge_category');
		
		//出勤回数,ボーナスフラグをリセット
		$reset = array(
			'id' => $userId,
			'count' => 0, 
			'bonus_flg' => 0
		);
		
		if(!$this->User->save($reset)){
			$this->User->rollback();
			return;
		}
		
		$word = $this->WallWord->getWallWordByEventIdAndTypeResultType($event['Event']['id'], $type, $resultType);
		$attachment = array(
			'access_token' => $accessToken,
			'message' => $add."\n".$this->_getWallMessage($word, $user)."\n\n".$message,
			'picture' => $picture.$user['User']['id'].'.png?params='.md5(date('Ymd')),
			'link' => $link,
			'name' => ' ',
			'caption' => ' ',
			'description' => ' '
		);
		
		$this->_printlog($attachment, 'judge_category');
		//ウォールに書き込み
		$this->Fb->postWall($attachment);
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
		
		$unit = $this->Unit->getNotAssignedUnit($companyId, $categoryId, $userId);
		$unitId = $unit['Unit']['id'];
		
		$user['User']['category_id'] = $categoryId;
		
		//新しいカテゴリの方が大きい場合
		if($oldCategoryId < $categoryId){
			$user['User']['max_category_id'] = $categoryId;
		}
		
		$user['User']['unit_id'] = $unitId;
		
		$this->User->create();
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
		
		$this->UnitHistory->create();
		if(!$this->UnitHistory->save($unitHistory)){
			$this->UnitHistory->rollback();
		}
		
		//プレイ中のフレンド情報を取得
		$friends = $this->Fb->getFriendListByUid('uid, pic_square', $facebookUserId, $accessToken);
		$playingFriends = $this->User->getPlayingFriends($friends, $companyId, CARD_FRIEND_COUNT);
		
		$friendIds = $this->User->getFriendIds($playingFriends, $companyId);
		
		//カードフレンド情報を書き込み
		$this->CardFriend->create();
		if(!$this->CardFriend->saveCardFriends($userId, $friendIds)){
			$this->CardFriend->rollback();
		}
		
		//カード作成
		$this->_createCard($userId, $user['User']['facebook_user_id'], $companyId, $categoryId, $unit['Unit']['card_name'], $playingFriends, $accessToken);
		return $user;
	}
	/**
	 * ウォールのメッセージを取得する
	 * @param $word 文言
	 * @param $user ユーザ情報
	 * @return 文言
	 */
	function _getWallMessage($word, $user) {
		$userProfile = $this->Fb->apiParam3('/'.$user['User']['facebook_user_id'] , 'GET', array());
		$name = $userProfile['name'];
		//{{$name}}・・・名前、
		$word = str_replace('{{$name}}', $name, $word);
		
		//{{$company}}・・・社名（キャンディorデューク）
		$condtions = array(
			'id' => $user['User']['company_id']
		);
		$company = $this->Company->find($condtions, 'name');
		$word = str_replace('{{$company}}', $company['Company']['name'], $word);
		
		//{{$dept}}・・・所属部署 
		$condtions = array(
			'id' => $user['User']['unit_id']
		);
		$unit = $this->Unit->find($condtions, 'name');
		$word = str_replace('{{$dept}}', $unit['Unit']['name'], $word);
		
		//{{$from_cat}}・・・旧カテゴリ 
		$condtions = array(
			'id' => $user['User']['old_category_id']
		);
		$oldCategory = $this->Category->find($condtions, 'name');
		$word = str_replace('{{$from_cat}}', $oldCategory['Category']['name'], $word);
		
		//{{$to_cat}}・・・新カテゴリ
		$condtions = array(
			'id' => $user['User']['category_id']
		);
		$category = $this->Category->find($condtions, 'name');
		$word = str_replace('{{$to_cat}}', $category['Category']['name'], $word);
		return $word;
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
