<?php
class AppError extends ErrorHandler {
	var $errorName = "Undefined error.";
	var $errorMessage = "Undefined error occurred.";
	
	/**
	 * 本番環境で個別に設定したエラーを出すためにコンストラクタを
	 * オーバーライドする必要がある。
	 */
	function __construct($method, $messages) {
		App::import('Core', 'Sanitize');
		static $__previousError = null;
		
		if ($__previousError != array($method, $messages)) {
			$__previousError = array($method, $messages);
			$this->controller =& new CakeErrorController();
		} else {
			$this->controller =& new Controller();
			$this->controller->viewPath = 'errors';
		}
		
		$options = array('escape' => false);
		$messages = Sanitize::clean($messages, $options);
		
		if (!isset($messages[0])) {
			$messages = array($messages);
		}
		
		if (method_exists($this->controller, 'apperror')) {
			return $this->controller->appError($method, $messages);
		}
		
		if (!in_array(strtolower($method), array_map('strtolower', get_class_methods($this)))) {
			$method = 'error';
		}
		
		if ($method !== 'error') {
			if (Configure::read() == 0) {
				
				// 継承元との関数名の差分を取得
				$customMethods = array_diff(
					get_class_methods($this),
					get_class_methods('ErrorHandler'));
				
				// 作成したカスタムメソッド以外は404へ
				if (!in_array(strtolower($method), array_map('strtolower', $customMethods))) {
					$method = 'error404';
				}
				
//				$method = 'error404';
//				if (isset($code) && $code == 500) {
//					$method = 'error500';
//				}
			}
		}
		
		$this->dispatchMethod($method, $messages);
		$this->_stop();
	}
	
	function error($params) {
		$this->controller->layout = "error";
		if(isset($params['name'])){$this->errorName = $params['name'];}
		if(isset($params['message'])){$this->errorMessage = $params['message'];}
		
		$this->controller->logger->write_log(sprintf('%s : %s', $this->errorName, $this->errorMessage), 'error');
		$this->controller->set('name', $this->errorName);
		$this->controller->set('message', $this->errorMessage);
		$this->_outputMessage('error');
	}
	
	function error404($params) {
		if (!isset($url)) {
			$url = $this->controller->here;
		}
		$url = Router::normalize($url);
		$this->controller->logger->write_log(sprintf('%s : 404 Not Found.', $url), 'error');
		parent::error404($params);
	}
}
?>
