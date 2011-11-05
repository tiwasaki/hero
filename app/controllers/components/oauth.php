<?php
App::import('Vendor', 'oauth', array('file' => 'OAuth'.DS.'OAuth.php'));

class OauthComponent extends Object {
	var $consumer = null;
	var $request = null;
	var $oauth_valid = false;
	
	var $oauth_param_keys = array(
		'opensocial_app_id', 'opensocial_owner_id',
		'oauth_consumer_key', 'oauth_nonce',
		'oauth_signature', 'oauth_signature_method',
		'oauth_timestamp', 'oauth_version',
		'xoauth_signature_publickey',
		'error', 'result', 'eventtype', 'id',
		'invite_member', 'point_code', 'status', 'updated',
		'm_from', 'm_ref'
	);
	
	function initialize(&$controller){
		$this->consumer = new OAuthConsumer(CONSUMER_KEY, CONSUMER_SECRET);
		//Build a request object from the current request
		$this->request = OAuthRequest::from_request();
		
		$params = $this->request->get_parameters();
//		$this->log($this->request, LOG_DEBUG);
		foreach ($params as $key => $value) {
			if(!in_array($key, $this->oauth_param_keys)) {
				$this->request->unset_parameter($key);
			}
		}
	}
	
	function checkSignature() {
		$signature = $this->request->get_parameter('oauth_signature');
		
		$method = new OAuthSignatureMethod_HMAC_SHA1();
		$built = $method->build_signature($this->request, $this->consumer, null);
//		$this->log('Built Signature :' . $built, LOG_DEBUG);
		
		return $method->check_signature($this->request, $this->consumer, null, $signature);
	}
	
	function checkLifeCycleSignature() {
		$request = OAuthRequest::from_request();
		$signature = $request->get_parameter('oauth_signature');
		
		$method = new MixiSignatureMethod(); 
		return $method->check_signature($request, null, null, $signature); 
	}
	
	function getAppId() {
		return $this->request->get_parameter('opensocial_app_id');
	}
	
	function getOwnerId() {
		return $this->request->get_parameter('opensocial_owner_id');
	}
	
	function checkPointApiSignature() {
		$signature = $this->request->get_parameter('oauth_signature');
		$method = new OAuthSignatureMethod_HMAC_SHA1();
		return $method->check_signature($this->request, $this->consumer, null, $signature);
	}
	
	function requestAPI($base_feed, $params = array(), $postData = null, $contentType = null) {
		// Setup OAuth request based our previous credentials and query
		$params['xoauth_requestor_id'] = $this->getOwnerId();
		$method = ($postData) ? "POST" : "GET";
		$request = OAuthRequest::from_consumer_and_token($this->consumer, null, $method, $base_feed, $params);
		
		// Sign the constructed OAuth request using HMAC-SHA1
		$request->sign_request(new OAuthSignatureMethod_HMAC_SHA1(), $this->consumer, null);
		
		// Make signed OAuth request to the Contacts API server
		$url = $base_feed . '?' . implode_assoc('=', '&', $params);
		
		//$this->log($url, LOG_DEBUG);
		
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_FAILONERROR, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_ENCODING, 'gzip');
		
		$auth_header = $request->to_header();
		//$this->log($auth_header, LOG_DEBUG);
		if($postData) {
			if(empty($contentType)) {
				$contentType = 'Content-Type: application/json';
			}
			curl_setopt($curl, CURLOPT_HTTPHEADER, array($contentType, $auth_header));
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
		} else {
			curl_setopt($curl, CURLOPT_HTTPHEADER, array($auth_header));
		}
		
		$response = curl_exec($curl);
		if (!$response) {
			$response = curl_error($curl);
		}
		curl_close($curl);
		
		return $response;
	}
	
	
}

/**
 * Joins key:value pairs by inner_glue and each pair together by outer_glue
 * @param string $inner_glue The HTTP method (GET, POST, PUT, DELETE)
 * @param string $outer_glue Full URL of the resource to access
 * @param array $array Associative array of query parameters
 * @return string Urlencoded string of query parameters
 */
function implode_assoc($inner_glue, $outer_glue, $array) {
	$output = array();
	foreach($array as $key => $item) {
		$output[] = $key . $inner_glue . urlencode($item);
	}
	return implode($outer_glue, $output);
}
?>
