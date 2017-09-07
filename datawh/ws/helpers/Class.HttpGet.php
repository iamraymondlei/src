<?php

class HttpGet {
    public static function request($url, $postData=null, $header=array()) {
        $ch = curl_init();
        $httpheader = array('User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/33.0.1750.154 Safari/537.36');
        if( count($header) === 0 )  $httpheader = $header;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheader);
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

