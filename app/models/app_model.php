<?php
/**
 * 共通モデル
 */
class AppModel extends Model {
	function begin()
	{
		$db =& ConnectionManager::getDataSource($this->useDbConfig);
		$db->begin($this);
	}

	function commit()
	{
		$db =& ConnectionManager::getDataSource($this->useDbConfig);
		$db->commit($this);
	}

	function rollback()
	{
		$db =& ConnectionManager::getDataSource($this->useDbConfig);
		$db->rollback($this);
	}
}
?>