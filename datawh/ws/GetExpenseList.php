<?php
require_once(dirname(__FILE__) . "/../application/config/WsConfig.php");
/**
 * Description of GetExpenseList
 * params:
 * @id          [Num]
 * @paymentId  [Num]
 * @storeId    [Num]
 * @productId [Num]
 * @dateRange [today] [2017-06-04 00:00:00,2017-06-04 23:59:59]
 * 
 * @author icm
 */

class GetExpenseList extends WebService implements iWebService {
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
            self::getExpense();
            if($this->isDebug){ echo DatetimeUtil::getTime("getExpense"); }
            self::closeDB();
        }
        if($this->isDebug){ echo DatetimeUtil::getTime("closeDB"); }
        self::output();
        if($this->isDebug){ echo DatetimeUtil::getTime("output"); }
        self::destory();
    }
    
    public function checkWsParams() {
        if( $this->isPassed ){
            $this->serverName = __CLASS__;
            $params = $this->params;
            $checkResult = array("isPass"=>$this->isPassed, "errorMsg"=>$this->resultCode, "resultCode"=>$this->errorMsg);

            if($checkResult["isPass"] && isset($params["id"])){
                $checkResult = CheckHttpParam::check(array("IS_NUMBER","NOT_ZERO"), $params, "id");
            }
            if($checkResult["isPass"]){
                $checkResult = CheckHttpParam::check(array("IS_NUMBER","NOT_ZERO"), $params, "paymentId");
            }
            if($checkResult["isPass"]){
                $checkResult = CheckHttpParam::check(array("IS_NUMBER","NOT_ZERO"), $params, "storeId");
            }
            if($checkResult["isPass"]){
                $checkResult = CheckHttpParam::check(array("IS_NUMBER","NOT_ZERO"), $params, "productId");
            }
            if($checkResult["isPass"]){
                $checkResult = CheckHttpParam::check(array("NOT_ZERO"), $params, "dateRange");
            }
            if($this->isDebug){ echo "CheckParam".PHP_EOL; print_r($checkResult); }

            $isPassed = isset($checkResult["isPass"])?$checkResult["isPass"]:TRUE;
            $errorMsg = isset($checkResult["errorMsg"])?$checkResult["errorMsg"]:null;
            $resultCode = isset($checkResult["resultCode"])?$checkResult["resultCode"]:200;
            self::setReturnStates($isPassed,$resultCode,$errorMsg);
        }
    }
        
    private function getExpense() {
        $result = self::doSql();
        $this->outputData = array("Count"=>$result["count"],"Amount"=>$result["amount"],"ExpenseList"=>array("Expense"=>$result["data"]));
    }
    
    private function doSql() {
        $result = FALSE;
        $params = $this->params;
        $startRow = ($this->page - 1) * $this->pageSize;
        $rowSize = $this->pageSize;
        
        $sql_count = "SELECT COUNT(*) AS `total`, SUM(el.Price) AS `amount` ";
        $sql_sel = "SELECT el.*, p.`ProductName`, p.`Unit` ";
        $sql_from = "FROM ExpenseList el ";
        $sql_join = "LEFT JOIN Product p ON p.`ProductId` = el.`ProductId` ";
        $sql_join.= "LEFT JOIN Payment pm ON pm.PaymentId = el.`PaymentId` ";
        
        $sql_where = "WHERE 1=1 ";
        $sql_orderBy = " ORDER BY el.`ExpenseTime` DESC ";
        $sql_limit = "LIMIT ".$startRow.",".$rowSize." ";
        
        if(isset($params["id"]) && strlen($params["id"])>0){
            $sql_where.= "AND el.ExpenseId = ".$params["id"]." ";
        }
        if(isset($params["keyword"]) && strlen($params["keyword"])>0){
            $sql_where.= "AND p.`ProductName` LIKE '%".$params["keyword"]."%' ";
        }
        if(isset($params["productId"]) && strlen($params["productId"])>0){
            $sql_where.= "AND el.ProductId = ".$params["productId"]." ";
        }
        if(isset($params["paymentId"]) && strlen($params["paymentId"])>0){
            $sql_where.= "AND el.PaymentId = ".$params["paymentId"]." ";
        }
        if(isset($params["storeId"]) && strlen($params["storeId"])>0 ){
            $sql_join.= "LEFT JOIN Store s ON s.`StoreId` = el.`StoreId` ";
            $sql_where.= "AND el.StoreId IN (".$params["storeId"].") ";
        }
        if(isset($params["dateRange"])){
            if($params["dateRange"] === "today"){
                $sql_where.= "AND el.ExpenseTime >= '".date("Y-m-d")." 00:00:00' AND el.ExpenseTime <= '".date("Y-m-d")." 23:59:59' ";
            }
            else{
                list($beginDate,$endDate) = explode(",", $params["dateRange"]);
                $sql_where.= "AND el.ExpenseTime >= '".$beginDate."' AND el.ExpenseTime <= '".$endDate."' ";
            }
        }
        
        $sql = $sql_count.$sql_from.$sql_join.$sql_where.";";
        $dataCountResult = $this->db->query($sql);
       
        if($dataCountResult && $dataCountResult[0]["total"]>0){
            $sql = $sql_sel.$sql_from.$sql_join.$sql_where.$sql_orderBy.$sql_limit.";";
            $result = $this->db->query($sql);
            return array("count"=>$dataCountResult[0]["total"],"amount"=>$dataCountResult[0]["amount"],"data"=>$result);
        }
        else{
            return array("count"=>0,"amount"=>0,"data"=>array());
        }
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

new GetExpenseList();