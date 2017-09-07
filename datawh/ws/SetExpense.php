<?php
require_once(dirname(__FILE__) . "/../application/config/WsConfig.php");
/**
 * Description of SetExpense
 *
 * @author icm
 */

class SetExpense extends WebService implements iWebService {
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
            self::setExpense();
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
                $checkResult = CheckHttpParam::check(array("NOT_EMPTY","IS_NUMBER"), $params, "id");
            }
            if($checkResult["isPass"]){
                $checkResult = CheckHttpParam::check(array("NOT_NULL","NOT_EMPTY"), $params, "dateTime");
            }
            if($checkResult["isPass"]){
                $checkResult = CheckHttpParam::check(array("NOT_NULL","NOT_EMPTY","IS_NUMBER"), $params, "storeId");
            }
            if($checkResult["isPass"]){
                $checkResult = CheckHttpParam::check(array("NOT_NULL","NOT_EMPTY","IS_NUMBER"), $params, "productId");
            }
            if($checkResult["isPass"]){
                $checkResult = CheckHttpParam::check(array("NOT_NULL","NOT_EMPTY"), $params, "price");
            }
            if($checkResult["isPass"]){
                $checkResult = CheckHttpParam::check(array("NOT_NULL","NOT_EMPTY"), $params, "quantity");
            }
            if($checkResult["isPass"]){
                $checkResult = CheckHttpParam::check(array("NOT_NULL","NOT_EMPTY"), $params, "paymentId");
            }

            if($this->isDebug){ echo "CheckParam".PHP_EOL; print_r($checkResult); }

            $isPassed = isset($checkResult["isPass"])?$checkResult["isPass"]:TRUE;
            $errorMsg = isset($checkResult["errorMsg"])?$checkResult["errorMsg"]:null;
            $resultCode = isset($checkResult["resultCode"])?$checkResult["resultCode"]:200;
            self::setReturnStates($isPassed,$resultCode,$errorMsg);
        }
    }
    
    private function setExpense() {  
        $params = $this->params;
        $result = FALSE;
        if($params["action"] === "add"){
            $result = self::doAddSql();
        }
        elseif($params["action"] === "update"){
            $result = self::doUpdateSql();
        }
        $this->outputData = array("Result"=>$result);
    }
    
    private function doAddSql() {
        $params = $this->params;
        $userInfo = $_SESSION['userInfo'];
        $arrayDataValue["UserId"] =  $userInfo["UserId"];
        $arrayDataValue["StoreId"] = $params["storeId"];
        $arrayDataValue["ProductId"] = $params["productId"];
        $arrayDataValue["Price"] = $params["price"];
        $arrayDataValue["Quantity"] = $params["quantity"];
        $arrayDataValue["PaymentId"] = $params["paymentId"];
        $arrayDataValue["Description"] = $params["description"];
        $arrayDataValue["ExpenseTime"] = $params["dateTime"];
        $this->db->insert("ExpenseList",$arrayDataValue);
        return $this->db->getInsertId();
    }
    
    public function doUpdateSQL() {
        $params = $this->params;
        $id = $params["id"];
        $userInfo = $_SESSION['userInfo'];
        $arrayDataValue["UserId"] =  $userInfo["UserId"];
        $arrayDataValue["StoreId"] = $params["storeId"];
        $arrayDataValue["ProductId"] = $params["productId"];
        $arrayDataValue["Price"] = $params["price"];
        $arrayDataValue["Quantity"] = $params["quantity"];
        $arrayDataValue["PaymentId"] = $params["paymentId"];
        $arrayDataValue["Description"] = $params["description"];
        $arrayDataValue["ExpenseTime"] = $params["dateTime"];
        
        $sql_where = "ExpenseId=".$id;
        $this->db->update("ExpenseList", $arrayDataValue, $sql_where);
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

new SetExpense();