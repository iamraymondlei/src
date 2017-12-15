<?php
//------------------------------------------
//date_default_timezone_set('UTC');
define('GOQO_LICENSING_API_CLIENT', '1.2');
if ( defined('GOQO_LICENSE_SERVER_URL') == false ) {	
	define('GOQO_LICENSE_SERVER_URL', 'https://licensing.goqo.com.cn');
}
define('GOQO_LICENSING_API_PLACEHOLDER', 'ICM GOQO License Server');

if ( !class_exists('cGoqoJSONRPCClient') ) {  // -- START cGoqoJSONRPCClient --------
/** 
 * General JSON-RPC Client
 * <ul>
 * <li>include signature generation and validate
 * <li>debugging
 * <li>common route to call JSON-RPC server
 * </ul>
 * @version 2012-05-16
 */
class cGoqoJSONRPCClient {
	protected $flagDebug = false; //false;
	protected $lastServerError = '';
	protected $securityLevel = 2;	
	protected $placeholder = '';
	protected $apiHost = 'https://www.goqo.com';
	protected $apiEndpoint = "/index.html";
	protected $serverPublicCertificate = '';
	protected $clientPrivateCertificate = '';
	
    function __construct() {
    }
    function __destruct() {
    }
    
	function hex2bin($hex_str) {
		return pack("H*" , $hex_str);
    }
    // verify signature with server public key
	protected function verifyServerSignature($sXml, $signature) {
		$pubkeyid = openssl_get_publickey($this->serverPublicCertificate);
		$ok = openssl_verify($sXml, $this->hex2bin($signature), $pubkeyid);
		openssl_free_key($pubkeyid);
		return $ok;
	}
	// generate signature using client private key 
	protected function getClientSignature($sXml) {		
		$signature = '';
		$priv_key = openssl_get_privatekey($this->clientPrivateCertificate);
		// Compute the signature using OPENSSL_ALGO_SHA1, OPENSSL_ALGO_MD5 by default.
		openssl_sign($sXml, $signature, $priv_key, OPENSSL_ALGO_SHA1);	
		// Free the key.
		openssl_free_key($priv_key);	
		$hex = bin2hex( $signature );		
		return $hex;
	}
	// replace signature field (xml version)
	protected function replaceXMLSignature($xml, $signature) {    
    	return preg_replace("/(signature=)(\"[^\"]*\")/is", "\\1\"$signature\"", $xml);
	}
	// replace signature field (json version)
	protected function replaceJSONSignature($raw, $signature) {
    	return preg_replace("/(\"as\":)(\"[^\"]*\")/is", "\\1\"$signature\"", $raw);
	}
    // print debug message on response stream
    protected function debug($s, $comment = '') {
    	if ( $this->flagDebug ) {
    		if ( !empty($comment) ) echo $comment ."\t";
    		print_r($s);
    		echo "\n";
    	}
    }	
	// send request to server
    protected function queryApi($method, $data) {
    	// clear last error
    	$this->lastServerError = '';
    	
	   	$date = new DateTime('now');
	    $dt = $date->format('Y-m-d\TH:i:s\Z');    	

	    $contentType = 'application/json';
	    $postdata = $data;
	    if ( !is_array($data) ) {
	    	//	$postdata = array($data);
	    }
	    // id:1 use MD5, id:2 use keypairs
	    $content = array(
    		'id' => $this->securityLevel,
    		'as' => $this->placeholder,
    		'method' => $method,    	
     		'dt' => $dt,
    		'params' => array($postdata)
	    );
	    // calculate as
	    $js_str = json_encode($content);
	    if ( $this->securityLevel == 2 ) {
	    	$signature = $this->getClientSignature($js_str);
	    } else {
	    	$signature = md5($js_str);
	    }
	    $reqdata = $this->replaceJSONSignature($js_str, $signature);    
 
    	$this->debug($reqdata, '<br/>request');
    	$http = curl_init($this->apiHost . $this->apiEndpoint); 
    	try {
    		curl_setopt($http, CURLOPT_HTTPHEADER, array("content-type: $contentType"));
		    curl_setopt($http, CURLOPT_POSTFIELDS, $reqdata);
		    curl_setopt($http, CURLOPT_RETURNTRANSFER, 1);
		    curl_setopt($http, CURLOPT_SSL_VERIFYPEER, FALSE);
		    curl_setopt($http, CURLOPT_SSL_VERIFYHOST, FALSE);
		    $postResult = curl_exec($http);
		    $info = curl_getinfo($http);
		    $err = curl_error($http);
    	} catch(Exception $ex) {
    		$err = @curl_error($http);
    	}
    	curl_close($http);
    	return array('header'=>$info, 'body'=>$postResult, 'error'=>$err);      	
    }     
    // process response from server
	protected function getApiResult($httpRes) {
		$this->debug($httpRes, '<br/>response');
		if ( isset($httpRes) && isset($httpRes['body']) ) {
			$body = $httpRes['body'];
			$result = json_decode($body, true);
			if ( isset($result['as']) && $result['as'] != null ) {
				$valid = 0;
				$authstring = $result['as'];
				$js_str = $this->replaceJSONSignature($body, $this->placeholder);
				if ( $this->securityLevel == 2 ) {
					$valid = $this->verifyServerSignature($js_str, $authstring);
				} else {
					if ( md5($js_str) == $authstring ) {
						$valid = 1;
					}
				}				
				
				if ($valid != 1 ) {
					$this->lastServerError = "invalid server signature";
					return NULL;
				}
			}	
			if ( isset($result['error']) && $result['error'] != null ) {
				$this->lastServerError = $result['error'];
			}
			return $result['result'];		
		}
		return NULL;
	}
	// test request is success or not (i.e. HTTP_STATUS == 200) 
	protected function isSuccess($result) {		
		if ( isset($result) && isset($result['header']) ) {
			$http_code = $result['header']['http_code'];
			if ( $http_code == 200 ) {
				return true;
			}
			// error cases
			$this->debug($result, 'response');
			if ( $http_code == 400 ) {
	    		throw new Exception('Bad Request');
	    	} else if ( $http_code == 403 ) {
	    		throw new Exception('Forbidden');
	    	}
		}
    	return false;
	}	
    // switch debug on
    public function debugOn() {
    	$this->flagDebug = true;
    }
    // switch debug off
    public function debugOff() {
    	$this->flagDebug = false;
    }
	/**
	 * switch json as method
	 * @param int $newVal (1:use MD5, 2:use 512 bit RSA key pair)
	 */
    public function setSecurityLevel($newVal=2) {
    	$this->securityLevel = $newVal;
    }
    /**
     * get last server error
     */
    public function GetLastError() {
    	return $this->lastServerError;    	
    }	
	// get current server side api info with server time 
	public function GetApiInfo() {
		$result = $this->queryApi(__FUNCTION__, array());
    	if ( !$this->isSuccess($result) ) {
			throw new Exception('Access '. __FUNCTION__ .' error');
    	}
		return $this->getApiResult($result);
	}
	// get current server side api info with server time 
	public function GetClassInfo() {
		$result = array(
			'debug' => $this->flagDebug,
			'securityLevel' => $this->securityLevel,	
			'placeholder' => $this->placeholder,
			'host' => $this->apiHost,
			'endpoint' => $this->apiEndpoint,
			'server_public_cert' => $this->serverPublicCertificate,
			'client_private_cert' => $this->clientPrivateCertificate	
		);
		return $result;
	}	
}
} // -- END cGoqoJSONRPCClient --------

class cGoqoLicensingClient extends cGoqoJSONRPCClient {	
	// basic setup to extends jsonrpc client
	protected $placeholder = GOQO_LICENSING_API_PLACEHOLDER;
	protected $apiHost = GOQO_LICENSE_SERVER_URL;
	protected $apiEndpoint = '/licenseapi.php';	
	function __construct() {
		$this->serverPublicCertificate = <<<ENDCERT
-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDbeJoOjAdm/GsWYUH/w/xLKKVA
i6p0YkDpZLmLJ5Sb+opofTxiugClH8TRTvYwK4sEg1DII18v5uXcBJU8vLGgXL9Q
oB40r9xl+stCk4vvCg8GYRaF7kWInVOFIdwt+GNKFo4IdczlTAMygV74trioxbKG
IC7FwKwEkARIfkfR6QIDAQAB
-----END PUBLIC KEY-----
ENDCERT;
		$this->clientPrivateCertificate = <<<ENDCERT
-----BEGIN RSA PRIVATE KEY-----
MIICWwIBAAKBgQDgdXZ+V3TM7ZMAPe94aTUPpNKxcr24JeMq5cVPZCU7FzCJaG5L
LlgP4yp2Q0uIEWvWCV/3sr6apFgE8sFoAY4EF4Mw0e0ClirR360YWKf1SBAybKUA
eDLAROulDkWtG+67sbXw9r4Axu9GdEUl81hiLwln0j8uZTJ4RLuE6cxSZQIDAQAB
AoGAE6fVn4yzCrsHM285CDdWUS9iz+03Vefjc785PxGK/RizxGSju+usBIAlGMQd
2iWEZvLdN4isjkgz+QL1EtGICQO1qK1DsHbccNAyw/PLKwB6hN8EajXqI/Td+2yA
runGBxWrwyZtdV1DZnY6QDpFOgHQnA3s95KN3HcGg7Rb2aECQQDykBqbToQE2kye
+4KuXloaWO05aKa8cm4PGZKJUFJx1Uq3ik92RYi43TffIzOHitHw+YpIx1MV1266
ngwGvlI9AkEA7OSd6VfkvOZexd1WYy2w9myr829ThLDCTrbN78me5JT28hp3EnFA
fKl3M+bpV7WkROdpn7nlxPcSa99YuxtLSQJAHthwabuMUkmoYLc/IQlij+MrF5fV
TDWJBWaNGeUa9trWy4xNGo8xGcEX78o8LUpb9DbnfOXMDQT+UcDzapAdSQJAARso
KZjBdqhUtPPNnXTvKJdlTiOs7ietIJT17TKxzTts08CP4cQpmutnpRRk7oXomHHE
KnzBt+O3cE2Aqo78EQJATy83HQYBcNVGuosglUTojNWBLbHDdOp0brY+ZIWZYRBw
piRPvLmQk9hnqFi7ZJMqd5mKuJiYW/aKpll7+5TPnw==
-----END RSA PRIVATE KEY-----
ENDCERT;
	}
    
	/// LICENSING ///
    // get clientid by goqoid
    function GetClientByGoqoId($sGoqoId) {
    	$data = array(
    		'goqoid'=>$sGoqoId,
    	);
    	$result = $this->queryApi(__FUNCTION__, $data);
    	if ( !$this->isSuccess($result) ) {
			throw new Exception('Access '. __FUNCTION__ .' error');
    	}
		return $this->getApiResult($result);
    }
    
    // get activation info by goqoid
    function GetGoqoIdInfo($sGoqoId) {
    	$data = array(
    		'goqoid'=>$sGoqoId,
    	);
    	$result = $this->queryApi(__FUNCTION__, $data);
    	if ( !$this->isSuccess($result) ) {
			throw new Exception('Access '. __FUNCTION__ .' error');
    	}
		return $this->getApiResult($result);
    }    
    
    // ensure goqoid still valid
    function ValidateGoqoId($sGoqoId) {
    	$data = array(
    		'goqoid'=>$sGoqoId,
    	);
    	$result = $this->queryApi(__FUNCTION__, $data);
    	if ( !$this->isSuccess($result) ) {
			throw new Exception('Access '. __FUNCTION__ .' error');
    	}
		return $this->getApiResult($result);
    }
        
    // ensure product model exist
    function ValidateModel($sGoqoModel, $sGoqoVer='') {
    	$data = array(
    		'goqomodel'=>$sGoqoModel,
    		'goqover'=>$sGoqoVer
    	);
    	$result = $this->queryApi(__FUNCTION__, $data);
    	if ( !$this->isSuccess($result) ) {
			throw new Exception('Access '. __FUNCTION__ .' error');
    	}
		return $this->getApiResult($result);
	}
	
    // ensure client and product model exist
    function ValidateAppClient($sGoqoId, $sGoqoModel, $sGoqoVer='') {
    	$data = array(
    		'goqoid'=>$sGoqoId,
    		'goqomodel'=>$sGoqoModel,
    		'goqover'=>$sGoqoVer
    	);
    	$result = $this->queryApi(__FUNCTION__, $data);
    	if ( !$this->isSuccess($result) ) {
			throw new Exception('Access '. __FUNCTION__ .' error');
    	}
		return $this->getApiResult($result);
	}	
	
	// Get activation code(s) from specified Batch
	function GetActivationCodes($nBatchId, $nNumOfCodes=1) {
		$data = array(
    		'batchId'=>$nBatchId,
    		'noOfCodes'=>$nNumOfCodes
    	);		
		$result = $this->queryApi(__FUNCTION__, $data);
    	if ( !$this->isSuccess($result) ) {
			throw new Exception('Access '. __FUNCTION__ .' error');
    	}
   		return $this->getApiResult($result);
	}

	// Revoke reserved activation code
	function RevokeActivationCode($sActivationCode) {
		$data = array(
    		'activationcode'=>$sActivationCode
    	);		
		$result = $this->queryApi(__FUNCTION__, $data);
    	if ( !$this->isSuccess($result) ) {
			throw new Exception('Access '. __FUNCTION__ .' error');
    	}
   		return $this->getApiResult($result);
	}	
	
	// Get activation code info
	function GetActivationCodeInfo($sActivationCode) {
		$data = array(
    		'activationcode'=>$sActivationCode
    	);		
		$result = $this->queryApi(__FUNCTION__, $data);
    	if ( !$this->isSuccess($result) ) {
			throw new Exception('Access '. __FUNCTION__ .' error');
    	}
   		return $this->getApiResult($result);
	}
	
	/// ACCESSLOG ///
	// write remote access log to licensing server
	function WriteAccessLog($sActionName, $sGoqoId, $sActivationCode, $sClientHash, $sResultCode, $sResultMessage, $clientInfo=false) {
	    // Write remote access log
		$clientModel = '';
		$clientVersion = '';
		$clientOSVersion = '';
	    if ( !empty($clientInfo) ) {
	        $clientModel = $clientInfo->Model;
	        $clientVersion = $clientInfo->ClientVersion;
	        $clientOSVersion = $clientInfo->OSVersion;
	    }	    
    	$data = array(
    		'action' => $sActionName,
    		'ipaddr' => $_SERVER['REMOTE_ADDR'],
    		'ua' => $_SERVER['HTTP_USER_AGENT'],
    		'goqoid' => $sGoqoId,
    		'acode' => $sActivationCode,
    		'hash' => $sClientHash,
    		'resultcode' => $sResultCode,
    		'resultmsg' => $sResultMessage,
    		'model' => $clientModel,
    		'ver' => $clientModel,
    		'osver' => $clientModel
    	);
    	$result = $this->queryApi(__FUNCTION__, $data);
    	if ( !$this->isSuccess($result) ) {
			throw new Exception('Access '. __FUNCTION__ .' error');
    	}    	
		return $this->getApiResult($result);    
	}
	
	// read access log from licensing server
	function ReadAccessLog($filter) {
    	$result = $this->queryApi(__FUNCTION__, $filter);
    	if ( !$this->isSuccess($result) ) {
			throw new Exception('Access '. __FUNCTION__ .' error');
    	}    	
		return $this->getApiResult($result);    
	}
}
//------------------------------------------