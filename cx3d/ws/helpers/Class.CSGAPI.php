<?php
/* ========================================================

  Author:			Raymond Lui
  Last Modified:	2014-02-20

  ========================================================== */

class CSGAPI {
    private static $host = "http://www.esyun.cn/";
    private static $clientId = "1022";
    private static $clientKey = "C225C6191FB4591F";
    private static $grantType = "authorization_code";
    private static $url;
    private static $urlParams;
    private static $api;
    private static $sign;
    private static $timestamp;
    private static $requestHeader = array(
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36',
        'Content-Type:application/json; charset=utf-8',
        'Accept:application/json'
    );

    private static function generateSign() {
        self::$sign = md5("client_id=".self::$clientId."client_key=".self::$clientKey."timestamp=".self::$timestamp);
    }

    private static function getTimestamp() {
        self::$timestamp = date('Y-m-d H:i:s');
    }

    private static function urlParamsEncode($str) {
        return str_replace(" ","%20",$str);
    }

    private static function composeUrl() {
        $timestamp = self::urlParamsEncode(self::$timestamp);
        self::$url = self::$host.self::$api."?sign=".self::$sign."&timestamp=".$timestamp.self::$urlParams;
    }

    public static function getToken($code) {
        $result = false;
        if (!empty($code)) {
            self::$api = "webServices/romote/accesstokenService/getAccessToken";
            self::$urlParams = "&grant_type=".self::$grantType."&client_id=".self::$clientId."&code=".$code;
            self::getTimestamp();
            self::generateSign();
            self::composeUrl();
            $result = self::request(self::$url);
        }
        return $result;
    }

    public static function getUserInfo($token, $userId, $api) {
        $result = false;
        if (!empty($token) && !empty($userId)) {
            self::$api = "webServices/romote/userInfoService/".$api;
            self::$urlParams = "&access_token=".$token."&id=".$userId;
            self::getTimestamp();
            self::generateSign();
            self::composeUrl();
            $result = self::request(self::$url);
        }
        return $result;
    }

    public static function request($url, $postData=null, $header=array()) {
        $ch = curl_init();
        $httpHeader = array();
        array_push($httpHeader, 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36');
        array_push($httpHeader, 'Content-Type:application/json; charset=utf-8');
        array_push($httpHeader, 'Accept:application/json');

        if( count($header) === 0 )  $httpHeader = $header;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHeader);
        if(isset($postData)){
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        if(substr($url,0,5) === "https"){
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        }
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate'); 	//解释gzip内容
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);           // 60 sec
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);                 // 5 mins
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);

        $httpResult = curl_exec($ch);
        $info = curl_getinfo($ch);
        $err = curl_error($ch);
        curl_close($ch);

        $httpResult = ($httpResult === FALSE)?$httpResult = 'FALSE':$httpResult;
        $httpResult = array('status'=> $info['http_code'], 'header'=>$info, 'body'=>$httpResult, 'error'=>$err);
        return $httpResult;
    }
}