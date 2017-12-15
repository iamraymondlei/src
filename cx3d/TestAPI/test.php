<?php
header('Content-type: application/json; charset=utf-8');
date_default_timezone_set("PRC");
//错误显示
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once "Class.XMLUtil.php";

class HttpGet {
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
/**
 * Created by PhpStorm.
 * User: icm
 * Date: 2017/10/11
 * Time: 10:48
 */

$url = "http://www.esyun.cn/";
$api = "webServices/romote/accesstokenService/getAccessToken";

$grant_type = "authorization_code";
$client_id = "1022";
$code = $_GET["code"];
$timestamp = date('Y-m-d H:i:s');
$sign = md5("client_id=1022client_key=C225C6191FB4591Ftimestamp=".$timestamp);

$url = $url.$api."?grant_type=".$grant_type."&client_id=".$client_id."&sign=".$sign."&timestamp=".str_replace(" ","%20",$timestamp)."&code=".$code;

$result = HttpGet::request($url);
if($result["status"] == 200){
    $xmlContent = $result["body"];
    $json = XMLUtil::xmlToJson($xmlContent);
    $data = json_decode($json,true);
    GetUserInfo($data["access_token"],$data["uid"]);
}
else{
    echo $result["body"];
}

function GetUserInfo($token,$userId) {
    $url = "http://www.esyun.cn/";
    $api = "webServices/romote/userInfoService/getEmployeeInfo";
    $timestamp = date('Y-m-d H:i:s');
    $sign = md5("client_id=1022client_key=C225C6191FB4591Ftimestamp=".$timestamp);
    $url = $url.$api."?access_token=".$token."&id=".$userId."&sign=".$sign."&timestamp=".str_replace(" ","%20",$timestamp);
    $result = HttpGet::request($url);
    if($result["status"] == 200){
        echo $result["body"];
    }
    else{
        echo $result["body"];
    }
}