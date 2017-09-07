<?php
require_once(dirname(__FILE__) . "/../application/config/WsConfig.php");
/**
 * Description of GetProductList
 *
 * @author icm
 */

class GetProductList extends WebService implements iWebService {
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
            self::getStore();
            if($this->isDebug){ echo DatetimeUtil::getTime("getStore"); }
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

            if($checkResult["isPass"]){
                $checkResult = CheckHttpParam::check(array("IS_NUMBER","NOT_ZERO"), $params, "storeCatId");
            }

            if($this->isDebug){ echo "CheckParam".PHP_EOL; print_r($checkResult); }

            $isPassed = isset($checkResult["isPass"])?$checkResult["isPass"]:TRUE;
            $errorMsg = isset($checkResult["errorMsg"])?$checkResult["errorMsg"]:null;
            $resultCode = isset($checkResult["resultCode"])?$checkResult["resultCode"]:200;
            self::setReturnStates($isPassed,$resultCode,$errorMsg);
        }
    }
    
    private function getStore() {    
        $storeList = self::doSql();
        $this->outputData = array("ProductList"=>array("Product"=>$storeList));
    }
    
    private function doSql() {
        $result = FALSE;
        $userInfo = $_SESSION['userInfo'];
        $startRow = ($this->page - 1) * $this->pageSize;
        $rowSize = $this->pageSize;
        
        $sql_count = "SELECT COUNT(*) AS `total` ";
        $sql_sel = "SELECT p.* ";
        $sql_from = "FROM Product p ";
        $sql_join = "";
        $sql_where = "WHERE 1=1 ";
        $sql_orderBy = " ORDER BY p.`CreationTime` DESC ";
        $sql_limit = "LIMIT ".$startRow.",".$rowSize." ";
        
        if($userInfo["FamilyId"] && strlen($userInfo["FamilyId"])>0){
            $sql_where.= "AND p.FamilyId = ".$userInfo["FamilyId"]." ";
        }
        
        $sql = $sql_count.$sql_from.$sql_join.$sql_where.";";
        $dataCountResult = $this->db->query($sql);
       
        if($dataCountResult && $dataCountResult[0]["total"]>0){
            $sql_sel.=", ".$dataCountResult[0]["total"]." AS Count ";
            $sql = $sql_sel.$sql_from.$sql_join.$sql_where.$sql_orderBy.$sql_limit.";";
            
            $result = $this->db->query($sql);
            return $result;
        }
        else{
            return array();
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

new GetProductList();