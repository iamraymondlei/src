<?php
require_once('Class.Log.php');

class IpHelper {
    /**
     * IpHelper::GetRealIp()
     * 获取用户ip
     * @return string
     */
    public static function GetRealIp(){
        $ip=false;
        if(!empty($_SERVER["HTTP_CLIENT_IP"])){
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        }
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ips = explode (", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
            if ($ip) { array_unshift($ips, $ip); $ip = FALSE; }
            for ($i = 0; $i < count($ips); $i++) {
                if (!eregi ("^(10|172\.16|192\.168)\.", $ips[$i])) {
                    $ip = $ips[$i];
                    break;
                }
            }
        }
        $ip = ($ip ? $ip : $_SERVER['REMOTE_ADDR']);

        return ($ip == '::1') ? '127.0.0.1' : $ip;
    }
}
