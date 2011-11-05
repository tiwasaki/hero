<?php
class Logger{
	function write_access_log() {
		$msg = date('Y-m-d H:i:s').
				",".env('REQUEST_URI').
				",".env('HTTP_REFERER').
				",".env('HTTP_USER_AGENT').
				",".env('REMOTE_ADDR')."\n";
		
		$dirpath = LOGS.'accesslogs';
		if(!is_dir($dirpath)) mkdir($dirpath);
		
		$filename = $dirpath.DS.date('Ymd').'.log';
		$log = new File($filename);
		$log->append($msg);
		
		return true;
	}
	
	function write_log($msg, $logname = "debug") {
		if(empty($logname)) return false;
		
		if(!is_string($msg)) $msg = print_r($msg);
		
		$msg = date('Y-m-d H:i:s').
				" [".env('REMOTE_ADDR')."]".
				" -- ".$msg."\n";
		
		$dirpath = LOGS.$logname;
		if(!is_dir($dirpath)) mkdir($dirpath);
		
		$filename = $dirpath.DS.$logname.date('Ymd').'.log';
		$log = new File($filename);
		$log->append($msg);
		
		return true;
	}
}
?>
