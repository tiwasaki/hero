<?php
App::import('Core', 'Controller');
App::import('Component', 'Fb');
/***
 * サプライズ訪問を通知するシェル
 */
class SurpriseShell extends Shell {
	/** 使用するモデル **/
	var $uses = array('User', 'Company');
	
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
		$this->_printlog('サプライズ訪問処理開始…','surprise');
		
		//すべてのユーザを取得
		$params = array(
			'conditions' => array('company_id' => $companyId)
		);
		
		$users = $this->User->find('all', $params);
		
		foreach ( $users as $user ) {
			try {
				$this->userProcessing($user, $companyId);
			}catch( Exception $e ){
				$this->_printlog('userId:'.$user['User']['id'], 'surprise');
				$this->_printlog($e->getMessage(), 'surprise');
			}
		}
		
		$this->_printlog('サプライズ訪問処理終了…', 'surprise');
	}
	
	/**
	 * ユーザごとの処理
	 * @param $user ユーザ情報
	 * @param $companyId 会社ID
	 */
	function userProcessing($user, $companyId) {
		$userId = $user['User']['id'];
		if($companyId == ID_CANDY){
			$picture = COMPLETE_IMG_BATCH_URL_CANDY;
			$link = APP_TAB_URL_CANDY;
			$description = APP_DESCRIPTION_CANDY;
		} else if($companyId == ID_DUKE){
			$picture = COMPLETE_IMG_BATCH_URL_DUKE;
			$link = APP_TAB_URL_DUKE;
			$description = APP_DESCRIPTION_DUKE;
		}
		
		$attachment = array(
			'access_token' => $user['User']['access_token'],
			'message' => SURPRISE_MSG,
			'picture' => $picture,
			'link' => $link,
			'name' => $this->Company->getNameByCompanyId($companyId),
			'link' => $link,
			'caption' => $link,
			'description' => $description
		);
		
		$this->_printlog($attachment, 'surprise');
		//ウォールに書き込み
		$this->Fb->postWall($attachment);
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
