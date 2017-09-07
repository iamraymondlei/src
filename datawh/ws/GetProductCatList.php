<?php
require_once(dirname(__FILE__) . "/../application/config/WsConfig.php");
/**
 * Description of GetProductCatList
 *
 * @author icm
 */

class GetProductCatList extends WebService implements iWebService {
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
            self::getProductCat();
            if($this->isDebug){ echo DatetimeUtil::getTime("getProductCat"); }
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

            if($this->isDebug){ echo "CheckParam".PHP_EOL; print_r($checkResult); }
            
            if($checkResult["isPass"]){
                $checkResult = CheckHttpParam::check(array("NOT_ZERO","NOT_NULL"), $params, "catId");
            }
            
            $isPassed = isset($checkResult["isPass"])?$checkResult["isPass"]:TRUE;
            $errorMsg = isset($checkResult["errorMsg"])?$checkResult["errorMsg"]:null;
            $resultCode = isset($checkResult["resultCode"])?$checkResult["resultCode"]:200;
            self::setReturnStates($isPassed,$resultCode,$errorMsg);
        }
    }
        
    private function getProductCat() {
        $params = $this->params;
        $tableName = 'ProductCatNode';
        $sqlHelper = new MysqlHelper();
        $sqlHelper->field(array('*'));
        $queryResult = $sqlHelper->select($this->db,$tableName);

        if($queryResult["state"] === 200){
            if(count($queryResult["body"])>0){
                if(isset($params["catId"])){
                    $catList = SortCatNode::RearrangeAry($queryResult["body"],"ProductCatNodeId","ParentCatId","ProductCatList","ProductCat",$params["catId"]);
                }
                else{
                    $catList = SortCatNode::RearrangeAry($queryResult["body"],"ProductCatNodeId","ParentCatId","ProductCatList","ProductCat");
                }
                $this->outputData = array("ProductCatList"=>array("ProductCat"=>$catList));
            }
        }
        else{
            self::setReturnStates(FALSE,500,'不能连接数据库或不能预计的错误');
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

new GetProductCatList();