<?php
/**
 * 定数設定ファイル
 */

//テストかどうか
define('TEST', true);

//プロジェクト名
define('PROJECT_NAME', 'hero');

if (isset($_SERVER['HTTPS'])) {
        $protocol = 'https://';
} else {
        $protocol = 'http://';
}
define('PROTOCOL', $protocol);
define('PROJECT_URL', PROTOCOL.$_SERVER['HTTP_HOST'].'/'.PROJECT_NAME.'/');

/** 決済関連の設定 **/
//GREEのアプリID
define('GREE_APP_ID', '9719');
define('GREE_API_SERVER', 'http://os-sb.gree.jp/');
define('GREE_API_URL', GREE_API_SERVER.'api/rest/');
define('GREE_PEOPLE_API_URL', GREE_API_URL.'people/');

define('CONSUMER_KEY', '94df99e8bd52');
define('CONSUMER_SECRET', '51b4b9e009467ccdba466ec1d1b4504e');
?>
