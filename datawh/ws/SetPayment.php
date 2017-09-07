<?php
require_once(dirname(__FILE__) . "/../application/config/WsConfig.php");
/**
 * Description of SetPayment
 *
 * @author icm
 */

class SetPayment extends WebService implements iWebService {
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
            self::setPayment();
            if($this->isDebug){ echo DatetimeUtil::getTime("setPayment"); }
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
    
    private function setPayment() {  
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
            self::setReturnStates("FALSE","409","该支付方式已存在");
        }
        
        $this->outputData = array("Result"=>$result);
    }
    
    private function isDuplicate () {
        $params = $this->params;
        
        $sqlSelect = "SELECT p.* FROM Payment p ";
        $sqlWhere = "WHERE p.PaymentMethod = '".$params["name"]."' ";
        if(isset($params["id"])){
            $sqlWhere.= "AND p.`PaymentId` <> ".$params["id"]." ";
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
        $arrayDataValue["PaymentMethod"] = $params["name"];
        $this->db->insert("Payment",$arrayDataValue);
        return $this->db->getInsertId();
    }
    
    public function doUpdateSQL() {
        $params = $this->params;
        $id = $params["id"];
        $arrayDataValue["PaymentMethod"] = $params["name"];
        
        $sql_where = "PaymentId=".$id;
        $this->db->update("Payment", $arrayDataValue, $sql_where);
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

new SetPayment();