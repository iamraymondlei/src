<?php
require_once(dirname(__FILE__) . "/../application/config/WsConfig.php");
/**
 * Description of SetStore
 *
 * @author icm
 */

class SetStore extends WebService implements iWebService {
    protected $isDebug      = FALSE;
    
    function __construct() {
        if($this->isDebug){ echo DatetimeUtil::getTime("checkSession"); }
        self::checkSession();
        if( $this->isPassed ){
            if($this->isDebug){ echo DatetimeUtil::getTime("begin"); }
            self::getRequestParams();
            if($this->isDebug){ echo DatetimeUtil::getTime("getRequestParams"); }
            self::checkBaseParams();
            if($this->isDebug){ echo DatetimeUtil::getTime("checkBaseParams"); }
            self::checkWsParams();
            if($this->isDebug){ echo DatetimeUtil::getTime("checkWsParams"); }
            self::connectDB();
            if($this->isDebug){ echo DatetimeUtil::getTime("connectDB"); }
            self::setStore();
            if($this->isDebug){ echo DatetimeUtil::getTime("setStore"); }
            self::closeDB();
            if($this->isDebug){ echo DatetimeUtil::getTime("closeDB"); }
        }
        self::output();
        if($this->isDebug){ echo DatetimeUtil::getTime("output"); }
        self::destory();
    }
    
    public function checkWsParams() {
        if( $this->isPassed ){
            $this->serverName = __CLASS__;
            $params = $this->params;
            $checkResult = array("isPass"=>$this->isPassed, "errorMsg"=>$this->resultCode, "resultCode"=>$this->errorMsg);

            if($checkResult["isPass"]){
                $checkResult = CheckHttpParam::check(array("IN_RANGE","NOT_EMPTY"), $params, "action", array("add", "update"));
            }
            if($checkResult["isPass"] && $params["action"] === "update"){
                $checkResult = CheckHttpParam::check(array("NOT_EMPTY"), $params, "id");
            }
            if($checkResult["isPass"]){
                $checkResult = CheckHttpParam::check(array("NOT_EMPTY"), $params, "name");
            }

            if($this->isDebug){ echo "CheckParam".PHP_EOL; print_r($checkResult); }

            $isPassed = isset($checkResult["isPass"])?$checkResult["isPass"]:TRUE;
            $errorMsg = isset($checkResult["errorMsg"])?$checkResult["errorMsg"]:null;
            $resultCode = isset($checkResult["resultCode"])?$checkResult["resultCode"]:200;
            self::setReturnStates($isPassed,$resultCode,$errorMsg);
        }
    }
    
    private function setStore() {  
        $params = $this->params;
        $result = FALSE;
        
        if(!self::isDuplicate()){
            if($params["action"] === "add"){
                $result = self::doAddSql();
            }
            elseif($params["action"] === "update"){
                $result = self::doUpdateSql();
            }
        }
        else{
            self::setReturnStates("FALSE","409","该店铺已存在");
        }
        
        $this->outputData = array("Result"=>$result);
    }
    
    private function isDuplicate () {
        $params = $this->params;
        
        $sqlSelect = "SELECT s.* FROM Store s ";
        $sqlWhere = "WHERE s.StoreName = '".$params["name"]."' ";
        if(isset($params["id"])){
            $sqlWhere.= "AND s.`StoreId` <> ".$params["id"]." ";
        }
        $sql = $sqlSelect.$sqlWhere.";";
        $result = $this->db->query($sql);
        
        if(count($result) > 0){
            return TRUE;
        }
        else{
            return FALSE;
        }
    }
    
    private function doAddSql() {
        $params = $this->params;
        $arrayDataValue["StoreName"] = $params["name"];
        if(isset($params["address"])){ $arrayDataValue["Address"] = $params["address"]; }
        $this->db->insert("Store",$arrayDataValue);
        return $this->db->getInsertId();
    }
    
    public function doUpdateSQL() {
        $params = $this->params;
        $id = $params["id"];
        $arrayDataValue["StoreName"] = $params["name"];
        if(isset($params["address"])){ $arrayDataValue["Address"] = $params["address"]; }
        
        $sql_where = "StoreId=".$id;
        $this->db->update("Store", $arrayDataValue, $sql_where);
        return $id;
    }
    
    public function destory() {
        $this->params = array();
        $this->outputData = array();
        $this->resultCode = 200;
        $this->errorMsg = null;
        $this->isPassed = FALSE;
        $this->serviceName = __CLASS__;
    }
}

new SetStore();