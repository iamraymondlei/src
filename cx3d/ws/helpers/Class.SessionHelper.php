<?php
$sessionId = session_id();
if(empty($sessionId)){
    session_start();    
}

/**
 * Session 管理类
 */
class SessionHelper {
    
    // 设置 session
    public static function Set($key, $value){
        if(empty($key)){
            return false;
        }
        $_SESSION[$key] = $value;
        return true;
    }
    
    // 获取 session
    public static function Get($key=''){
        if(empty($key)){
            $session = isset($_SESSION) ? $_SESSION : array();
            return array('session_id'=>session_id(), 'session'=>$session);
        }else{
            if(isset($_SESSION[$key])){
                return $_SESSION[$key];
            }else{
                return null;
            }
        }
    }
    
    // 删除 session
    public static function Destroy($session=true, $cookie=false){
        if($session){
            session_destroy();
            $_SESSION=array();
        }
        if($cookie){
            foreach($_COOKIE as $cookieKey=>$cookieVal){
                setcookie($cookieKey, '', time()-3600);
            }
            $_COOKIE=array();
        }
        return true;
    }
    
    // 检查 session
    public static function Check() {
        $hasSession = FALSE;
        if(isset($_SESSION['userInfo'])){
            $hasSession = TRUE;
        }
        return $hasSession;
    }
}
