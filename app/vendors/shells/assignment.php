<?php
App::import('Core', 'Controller');
App::import('Component', 'Fb');
/***
 * 配属決定処理を行うシェル
 */
class AssignmentShell extends Shell {
	/** 使用するモデル **/
	var $uses = array(
		'User', 'WallWord', 'Company', 'Unit'
	);
	
	/**
	 * シェル 初期化
	 */
	function startup(){
    	$this->controller = new Controller();
    	$this->Fb = new FbComponent($this);
    	$this->Fb->startup($this->controller);
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
		
		$this->_printlog('配属決定処理…','assignment');
		
		//すべてのユーザを取得
		$params = array(
			'conditions' => array('company_id' => $companyId)
		);
		$users = $this->User->find('all', $params);
		
		foreach ( $users as $user ) {
			try {
				$this->userProcessing($user, $companyId);
			}catch( Exception $e ){
				$this->_printlog('userId:'.$user['User']['id'], 'assignment');
				$this->_printlog($e->getMessage(), 'assignment');
			}
		}
		
		$this->_printlog('配属決定処理…', 'assignment');
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
			$description = APP_DESCRIPTION_CANDY;
			$linkName = APP_TAB_URL_CANDY;
		} else if($companyId == ID_DUKE){
			$picture = CARD_IMG_URL_BATCH_DUKE;
			$link = APP_URL_DUKE.'?user_id='.$userId;
			$description = APP_DESCRIPTION_DUKE;
			$linkName = APP_TAB_URL_DUKE;
		}
		
		//固定で配属決定を実行
		$word = $this->WallWord->getWallWordByEventIdAndTypeResultType(EVENT_ID_ASSIGNMENT, COUNT_TYPE_1, RESULT_TYPE_KEEP);
		$attachment = array(
			'access_token' => $user['User']['access_token'],
			'message' => $this->_getWallMessage($word, $user),
			'picture' => $picture.$user['User']['id'].'.png',
			'link' => $link,
			'name' => $this->Company->getNameByCompanyId($companyId),
			'link' => $link,
			'caption' => ' ',
			'description' => $description
		);
		
		$this->_printlog($attachment, 'assignment');
		//ウォールに書き込み
		$this->Fb->postWall($attachment);
	}
	
	/**
	 * ウォールのメッセージを取得する
	 * @param $word 文言
	 * @param $user ユーザ情報
	 * @return 文言
	 */
	function _getWallMessage($word, $user) {
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
		return $word;
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
