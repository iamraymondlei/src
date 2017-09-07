<?php
require_once(dirname(__FILE__) . "/../application/config/WsConfig.php");
/**
 * Description of SetFileCat
 *
 * @author icm
 */

class SetFileCat extends WebService implements iWebService {
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
            self::setFileCat();
            if($this->isDebug){ echo DatetimeUtil::getTime("setFileCat"); }
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
                $checkResult = CheckHttpParam::check(array("IN_RANGE","NOT_EMPTY"), $params, "action", array("add", "update", "sort"));
            }
            if($checkResult["isPass"] && ($params["action"] === "update" ||  $params["action"] === "sort")){
                $checkResult = CheckHttpParam::check(array("NOT_EMPTY","NOT_NULL"), $params, "id");
            }
            if($checkResult["isPass"] && $params["action"] === "sort"){
                $checkResult = CheckHttpParam::check(array("NOT_EMPTY","NOT_NULL"), $params, "data");
            }
            if($checkResult["isPass"] && $params["action"] !== "sort"){
                $checkResult = CheckHttpParam::check(array("NOT_EMPTY","NOT_NULL"), $params, "name");
            }
            if($checkResult["isPass"] && $params["action"] !== "sort"){
                $checkResult = CheckHttpParam::check(array("NOT_EMPTY","NOT_NULL"), $params, "parentId");
            }

            if($this->isDebug){ echo "CheckParam".PHP_EOL; print_r($checkResult); }

            $isPassed = isset($checkResult["isPass"])?$checkResult["isPass"]:TRUE;
            $errorMsg = isset($checkResult["errorMsg"])?$checkResult["errorMsg"]:null;
            $resultCode = isset($checkResult["resultCode"])?$checkResult["resultCode"]:200;
            self::setReturnStates($isPassed,$resultCode,$errorMsg);
        }
    }
    
    private function setFileCat() {  
        $params = $this->params;
        $result = FALSE;
        
        if($params["action"] === "sort"){
            $data = json_decode($params["data"],true);
            $result = self::doSort($params["id"],$data);
        }
        else{
            if(!self::isDuplicate()){
                if($params["action"] === "add"){
                    $result = self::doAddSql();
                }
                elseif($params["action"] === "update"){
                    $result = self::doUpdateSql();
                }
            }
            else{
                self::setReturnStates("FALSE","409","该分类已存在");
            }
        }
        
        $this->outputData = array("Result"=>$result);
    }
    
    private function isDuplicate () {
        $params = $this->params;
        $isDuplicate = FALSE;
        $sqlHelper = new MySQLHelper();
        $sqlHelper->field(array('FileCatNodeId'));
        if(isset($params["id"])){
            $sqlHelper->where(array('FileCatName'=>$params["name"],'FileCatNodeId'=>array($params["id"],'<>','and')));
        }
        else{
            $sqlHelper->where(array('FileCatName'=>$params["name"]));
        }
        $queryResult = $sqlHelper->select($this->db,'FileCatNode');
        if($queryResult["state"] === 200){
            if(count($queryResult["body"])===1){
                $this -> userInfo = $queryResult["body"][0];
                $isDuplicate = TRUE;
            }
        }
        else{
            self::setReturnStates(FALSE,500,'不能连接数据库或不能预计的错误');
        }
        return $isDuplicate;
    }
    
    private function doAddSql() {
        $params = $this->params;
        $sqlHelper = new MySQLHelper();    
        $insertData = array(
            'FileCatName'=>$params["name"],
            'ParentCatId'=>$params["parentId"]);
        $insertResult = $sqlHelper->insert($this->db,"FileCatNode",$insertData);
        return $this->db->getInsertId();
    }
    
    public function doUpdateSQL() {
        $params = $this->params;        
        $sqlHelper = new MySQLHelper();    
        $updateData = array(
            'FileCatName'=>$params["name"],
            'ParentCatId'=>$params["parentId"] );
        $sqlHelper->where(array('FileCatNodeId'=>$params["id"]));
        $updateResult = $sqlHelper->update($this->db,"FileCatNode",$updateData);
        
        if($updateResult["state"] !== 200){
            //更新失败
        }
            
        return $params["id"];
    }
    
    public function doSort($rootId,$data) {
        foreach($data as $item){
            $sqlHelper = new MySQLHelper();    
            $updateData = array('ParentCatId'=>$rootId);
            $sqlHelper->where(array('FileCatNodeId'=>$item["id"]));
            $sqlHelper->update($this->db,"FileCatNode",$updateData);
            if(isset($item["children"])){
                self::doSort($item["id"],$item["children"]);
            }
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

new SetFileCat();