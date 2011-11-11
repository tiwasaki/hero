<?php
/**
 * チーム コントローラクラス
 */
class TeamController extends AppController {
	/** コントローラ名 */
	var $name   = 'team';
	/** 使用モデル配列 */
	var $uses = null;
	
	function beforeFilter() {
		parent::beforeFilter();
	}
	
	function afterFilter() {
		parent::afterFilter();
	}
	
	function index() {
	}
}
?>
