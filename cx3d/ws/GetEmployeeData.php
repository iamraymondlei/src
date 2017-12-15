<?php
require_once("../config/wsconfig.php");
/**
 * Description of VerifyUser
 * params:
 * @ code
 * @ api value=>getUserInfo/getEmployeeInfo
 * @author icm
 */

class GetEmployeeData extends WebService implements iWebService {
    protected $isDebug      = FALSE;
    protected $prefixUrl    = "";
    
    function __construct($config) {
        $this->config = $config;
        $this->prefixUrl = $this->config["outputImagePrefix"];
        if($this->isDebug){ echo DatetimeUtil::getTime("begin"); }
        self::getRequestParams();
        if($this->isDebug){ echo DatetimeUtil::getTime("getRequestParams"); }
        self::checkBaseParams();
        if($this->isDebug){ echo DatetimeUtil::getTime("checkBaseParams"); }
        self::checkWsParams();
        if($this->isDebug){ echo DatetimeUtil::getTime("checkWsParams"); }
        self::connectDB();
        if($this->isDebug){ echo DatetimeUtil::getTime("connectDB"); }
        self::getData();
        if($this->isDebug){ echo DatetimeUtil::getTime("getData"); }
        self::setVisitTime();
        if($this->isDebug){ echo DatetimeUtil::getTime("setVisitTime"); }
        $this->writeWSLog(__CLASS__);
        if($this->isDebug){ echo DatetimeUtil::getTime("WriteWSLog"); }
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
                $checkResult = CheckHttpParam::check(array("IN_RANGE"), $params, "state", array("1", "2", "3"));
            }
            if($checkResult["isPass"]){
                $checkResult = CheckHttpParam::check(array("IN_RANGE"), $params, "type", array("1", "2"));
            }
            if($checkResult["isPass"] && !isset($params["id"])){
                $checkResult = CheckHttpParam::check(array("NOT_EMPTY","NOT_NULL","IS_NUMBER"), $params, "size");
            }
            if($checkResult["isPass"]){
                $checkResult = CheckHttpParam::check(array("NOT_EMPTY","IS_NUMBER"), $params, "id");
            }

            if($this->isDebug){ echo "CheckParam".PHP_EOL; print_r($checkResult); }

            $isPassed = isset($checkResult["isPass"])?$checkResult["isPass"]:TRUE;
            $errorMsg = isset($checkResult["errorMsg"])?$checkResult["errorMsg"]:null;
            $resultCode = isset($checkResult["resultCode"])?$checkResult["resultCode"]:200;
            self::setReturnStates($isPassed,$resultCode,$errorMsg);
        }
    }

    private function getData() {
        if( $this->isPassed ){
            $news = self::doSQL();
            if(count($news["data"])>0){
                $this->outputData = array("ItemList"=>array("Item"=>$news));
            }
        }
    }

    private function doSQL() {
        $params = $this->params;

        $sql_sel = "SELECT eud.`EmployeeUploadDataId` as Id, eud.`DataTypeId`, eud.`State`, e.`EmployeeId`, e.`EmployeeHeadImg`, e.`EmployeeName`, eud.`RepresentImageUrl`, eud.`Value`, eud.`LastUpdate` ";
        $sql_from = "FROM EmployeeUploadData eud ";
        $sql_join = "LEFT JOIN Employee e ON e.`EmployeeId` = eud.`EmployeeId` ";
        $sql_where = "WHERE 1=1 ";
        $sql_orderBy = "ORDER BY eud.`StickyPost` DESC, RAND() ";
        $sql_limit = "";

        if(isset($params["state"]) && strlen($params["state"])>0){
            $sql_where.= "AND eud.`State` = ".$params["state"]." ";
        }
        if(isset($params["type"]) && strlen($params["type"])>0){
            $sql_where.= "AND eud.`DataTypeId` = ".$params["type"]." ";
        }
        if(isset($params["id"]) && strlen($params["id"])>0){
            $sql_where.= "AND eud.`EmployeeId` = ".$params["id"]." ";
            $sql_orderBy = "ORDER BY  eud.`LastUpdate` DESC ";
        }
        if(isset($params["size"]) && strlen($params["size"])>0){
            $sql_limit = " LIMIT ".$params["size"];
        }

        $sql = $sql_sel.$sql_from.$sql_join.$sql_where.$sql_orderBy.$sql_limit.";";
        $result = $this->db->query($sql);
        $result = $this->setImagePrefix($result);
        return array("data"=>$result);
    }

    private function setVisitTime() {
        $itemList = $this->outputData;
        if(count($itemList["ItemList"]["Item"]["data"])>0){
            foreach($itemList["ItemList"]["Item"]["data"] as $item){
                $id = $item["Id"];
                self::updateVisitSQL($id);
            }
        }
    }

    private function updateVisitSQL($id) {
        $sql = "UPDATE EmployeeUploadData eud SET eud.`Visit` = eud.`Visit` + 1, eud.`VisitTime` = NOW() WHERE eud.`EmployeeUploadDataId` = ".$id.";";
        $result = $this->db->query($sql);
        return array($result);
    }

    public function destory() {
        $this->params = array();
        $this->outputData = array();
        $this->resultCode = 200;
        $this->errorMsg = null;
        $this->isPassed = FALSE;
        $this->serviceName = __CLASS__;
    }

    private function setImagePrefix($itemList) {
        foreach ($itemList as $index => $item){
            foreach ($item as $key => $value){
                if( strchr($key,"ImageUrl") && substr($value,0,7) != "http://" && substr($value,0,8) != "https://" ) {
                    $itemList[$index][$key] = $this->prefixUrl.$value;
                }
            }
        }
        return $itemList;
    }
}

new GetEmployeeData($config);