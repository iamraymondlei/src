<?php

class RequestHttpParam {
    public static function getRequestParam() {
        return self::getRequest();
    }

    public static function getRequestParamByKey($key) {
        $params = array();
        if (isset($key) && strlen($key) > 0) {
            $params = self::getRequest($key);
        }
        return $params;
    }

    private static function getRequest($p_key = null, $p_default = "") {
        $params = array();
        if ($p_key !== null && strlen($p_key) > 0) {
            if (isset($_REQUEST[$p_key])){
                $params = array($p_key => $_REQUEST[$p_key]);
            }
            else{
                $params = array($p_key => $p_default);
            }
        }
        else {
            foreach ($_REQUEST as $key => $request) {
                $params[$key] = trim($request);
            }
        }
        return $params;
    }
}
