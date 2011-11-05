<?php
uses ('model' . DS . 'datasources' . DS . 'dbo' . DS . 'dbo_mysql');
/***
 * MySQLのクエリをapp/tmp/logs/debug.logに出力
 * 
 * app/config/database.php の driver を変更します。
 *  var $default = array(
 *	-    'driver' => 'mysql',
 *	+    'driver' => 'mysql_log',
 *	（省略）
 *	  );
 * app/config/core.php に sql_log を追加します。
 * Configure::write('Sql.log', true);
 */
class DboMysqlLog extends DboMysql {
  function showLog($sorted = false) {
    $result = parent::showLog($sorted);
    if (Configure::read('Sql.log') && Configure::read() > 1) {
      $log = $this->_queriesLog;
      if (php_sapi_name() != 'cli') {
        foreach ($log as $k => $i) {
          $info = array(
                    'Nr' => ($k + 1),
                    'Query' => $i['query'],
                    'Error' => $i['error'],
                    'Affected' => $i['affected'],
                    'Num Rows' => $i['numRows'],
                    'Took(ms)' => $i['took'],
                  );
          $this->log($info, LOG_DEBUG);
        }
      }
    }
    return $result;
  }

  function execute($sql) {
    $result = parent::execute($sql);
    if (Configure::read('Sql.log') && Configure::read() <= 1) {
      $this->log($sql, LOG_DEBUG);
    }
    return $result;
  }
}
?>