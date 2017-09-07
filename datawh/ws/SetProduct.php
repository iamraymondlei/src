<?php
require_once(dirname(__FILE__) . "/../application/config/WsConfig.php");
/**
 * Description of SetProduct
 *
 * @author icm
 */

class SetProduct extends WebService implements iWebService {
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
            self::setProduct();
            if($this->isDebug){ echo DatetimeUtil::getTime("getStore"); }
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
            if($checkResult["isPass"]){
                $checkResult = CheckHttpParam::check(array("NOT_EMPTY"), $params, "unit");
            }

            if($this->isDebug){ echo "CheckParam".PHP_EOL; print_r($checkResult); }

            $isPassed = isset($checkResult["isPass"])?$checkResult["isPass"]:TRUE;
            $errorMsg = isset($checkResult["errorMsg"])?$checkResult["errorMsg"]:null;
            $resultCode = isset($checkResult["resultCode"])?$checkResult["resultCode"]:200;
            self::setReturnStates($isPassed,$resultCode,$errorMsg);
        }
    }
    
    private function setProduct() {  
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
            self::setReturnStates("FALSE","409","该消费品已存在");
        }
        
        $this->outputData = array("Result"=>$result);
    }
    
    private function isDuplicate () {
        $params = $this->params;
        $userInfo = $_SESSION['userInfo'];
        
        $sqlSelect = "SELECT p.* FROM Product p ";
        $sqlWhere = "WHERE p.ProductName = '".$params["name"]."' AND p.FamilyId = '".$userInfo["FamilyId"]."' ";
        if(isset($params["id"])){
            $sqlWhere.= "AND p.`ProductId` <> ".$params["id"]." ";
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
        $userInfo = $_SESSION['userInfo'];
        $arrayDataValue["FamilyId"] = $userInfo["FamilyId"];
        $arrayDataValue["ProductName"] = $params["name"];
        $arrayDataValue["Unit"] = $params["unit"];
        $this->db->insert("Product",$arrayDataValue);
        return $this->db->getInsertId();
    }
    
    public function doUpdateSQL() {
        $params = $this->params;
        $id = $params["id"];
        $arrayDataValue["ProductName"] = $params["name"];
        $arrayDataValue["Unit"] = $params["unit"];
        
        $sql_where = "ProductId=".$id;
        $this->db->update("Product", $arrayDataValue, $sql_where);
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

new SetProduct();