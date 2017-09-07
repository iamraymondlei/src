<?php

class CheckHttpParam {
    
    public static function check($opList, $paramList, $paramName, $valueRange=array()){
        if(strlen($paramName) === 0 || empty($paramName)) return false;
        $result = array("isPass"=>true,"errorMsg"=>null,"resultCode"=>200);
        $errorMsg = self::notNull($paramList,$paramName);
        $paramValue = (isset($paramList[$paramName]))?$paramList[$paramName]:"";
        if($errorMsg === null && count($opList)>0){
            foreach($opList as $op){
                $validateResult = self::validateParamByOp($op,$paramName,$paramValue,$valueRange);
                if(!$validateResult["isPass"]){
                    $result = array("isPass"=>$validateResult["isPass"], "errorMsg"=>$validateResult["errorMsg"], "resultCode"=>$validateResult["resultCode"]);
                    break;
                }
            }
        }
        elseif(!in_array("NOT_NULL", $opList)){
            $result = array("isPass"=>true,"errorMsg"=>null,"resultCode"=>200);
        }
        elseif($errorMsg!==null){
            $result = array("isPass"=>false,"errorMsg"=>$errorMsg,"resultCode"=>400);
        }
        return $result;
    }
            
    public static function checkGoqoIdGoqoModelGoqoVer($param){
        $isPass = true;
        $errorMsg = null;
        $resultCode = 200;
        if ((isset($param["goqoId"]) && isset($param["goqoModel"]) && isset($param["goqoVer"]))) {
            $goqoUserState = self::checkGoqoUser($param["goqoId"], $param["goqoModel"], $param["goqoVer"]);
            if (!$goqoUserState || $goqoUserState != 200) {
                $isPass = false;
                $errorMsg = "没有效的goqoId";
                $resultCode = 401;
            }
            else {
                $isPass = true;
            }
        }
        else {
            $isPass = false;
            $errorMsg = "缺少参数goqoId,goqoModel,goqoVer";
            $resultCode = 400;
        }
        return array("isPass"=>$isPass,"errorMsg"=>$errorMsg,"resultCode"=>$resultCode);
    }
    
    private static function validateParamByOp($op,$paramName,$paramValue="",$valueRange=array()){
        $errorMsg = null;
        $isPass = false;
        $resultCode = 400;
        switch ($op){
            case "NOT_EMPTY":   $errorMsg = self::notEmpty($paramName,$paramValue);
                break;
            case "NOT_ZERO":    $errorMsg = self::notZero($paramName,$paramValue);
                break;
            case "IN_RANGE":    $errorMsg = self::inRange($paramName,$paramValue,$valueRange);
                break;
            case "IS_NUMBER":   $errorMsg = self::isNumber($paramName,$paramValue);
                break;
            default;
        }
        
        if($errorMsg === null){
            $isPass = true;
            $resultCode = 200;
        }
        return array("isPass"=>$isPass,"errorMsg"=>$errorMsg,"resultCode"=>$resultCode);
    }
    
    private static function notNull($param,$paramName){
        $isPass = false;
        $errorMsg = null;
        if(isset($param[$paramName])) {
            $isPass = true;
        }
        else {
            $errorMsg = "缺少參數：".$paramName."。";
        }
        return $errorMsg;
    }
    
    private static function notEmpty($paramName,$paramValue){
        $isPass = false;
        $errorMsg = null;
        if(trim($paramValue) != "") {
            $isPass = true;
        }
        else {
            $errorMsg = "參數".$paramName."的值不能為空。";
        }
        return $errorMsg;
    }
    
    private static function notZero($paramName,$paramValue){
        $isPass = false;
        $errorMsg = null;
        if(!empty($paramValue)) {
            $isPass = true;
        }
        else {
            $errorMsg = "參數".$paramName."的值不能為0。";
        }
        return $errorMsg;
    }
    
    private static function inRange($paramName,$paramValue,$valueRange){
        $isPass = false;
        $errorMsg = null;
        if(in_array($paramValue, $valueRange)) {
            $isPass = true;
        }
        if(!$isPass){
            $errorMsg = "參數".$paramName."的值不在(";
            foreach($valueRange as $allowValue) {
                $errorMsg.= (($allowValue==="")?"空值":$allowValue).",";
            }
            $errorMsg = trim($errorMsg,",").")允許值範圍內。";
        }
        return $errorMsg;
    }
    
    private static function isNumber($paramName,$paramValue){
        $isPass = false;
        $errorMsg = null;
        if(strstr($paramValue,'.') && is_float($paramValue)){
            $isPass = true;
        }
        elseif(is_numeric($paramValue)){
            $isPass = true;
        }
        else {
            $errorMsg = "參數".$paramName."的值不是数字。";
        }
        return $errorMsg;
    }
    
    private static function checkGoqoUser($goqoId, $goqoModel, $goqoVer) {
        $result = false;
        if (WsConfig::$goqoIdValidation) {
            if (isset($goqoId) && isset($goqoModel) && isset($goqoVer)) {
                $cli = new cGoqoLicensingClient();
                $exist = $cli->ValidateAppClient($goqoId, $goqoModel, $goqoVer);
                if (!$exist)
                    $result = 401;
                else
                    $result = 200;
            }
            else {
                $result = 400;
            }
        } else {
            $result = 200;
        }
        return $result;
    }

}