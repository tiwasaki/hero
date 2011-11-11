<?php
/**
 * 特訓 コントローラクラス
 */
class TrainingController extends AppController {
	/** コントローラ名 */
	var $name   = 'training';
	/** 使用モデル配列 */
	var $uses = null;
	
	function beforeFilter() {
		parent::beforeFilter();
	}
	
	function afterFilter() {
		parent::afterFilter();
	}
	
	/**
	 * 特訓するカードの選択
	 */
	function index() {
		$this->pageTitle = '特訓するカードの選択';
	}
	
	/**
	 * 特訓確認
	 */
	function confirm() {
	
	}
}
?>
