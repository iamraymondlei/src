<?php
require_once("../config/wsconfig.php");
/**
 * Description of VerifyUser
 * params:
 * @ code
 * @ api value=>getUserInfo/getEmployeeInfo
 * @author icm
 */

class DeleteEmployeeData extends WebService implements iWebService {
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
        self::checkIdExist();
        if($this->isDebug){ echo DatetimeUtil::getTime("checkIdExist"); }
        self::setData();
        if($this->isDebug){ echo DatetimeUtil::getTime("setData"); }
        $this->writeWSLog(__CLASS__);
        if($this->isDebug){ echo DatetimeUtil::getTime("writeWSLog"); }
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
                $checkResult = CheckHttpParam::check(array("NOT_EMPTY","NOT_NULL","IS_NUMBER"), $params, "id");
            }

            if($this->isDebug){ echo "CheckParam".PHP_EOL; print_r($checkResult); }

            $isPassed = isset($checkResult["isPass"])?$checkResult["isPass"]:TRUE;
            $errorMsg = isset($checkResult["errorMsg"])?$checkResult["errorMsg"]:null;
            $resultCode = isset($checkResult["resultCode"])?$checkResult["resultCode"]:200;
            self::setReturnStates($isPassed,$resultCode,$errorMsg);
        }
    }

    private function setData() {
        if( $this->isPassed ){
            $news = self::DeleteEmployeeData();
            if( count($news["data"])>0 ){
                self::DeleteEmployeeData();
                self::DeleteVisitCountData();
                self::setReturnStates(TRUE,200,"执行成功");
            }
        }
    }

    private function DeleteEmployeeData() {
        $params = $this->params;
        $where = "`EmployeeUploadDataId` = '".$params["id"]."' ";
        $result = $this->db->delete("EmployeeUploadData",$where);
        return array("data"=>$result);
    }

    private function DeleteVisitCountData() {
        $params = $this->params;
        $where = "`TableName` = 'EmployeeUploadData' AND `ItemId` = '".$params["id"]."' ";
        $result = $this->db->delete("VisitCount",$where);
        return array("data"=>$result);
    }

    private function checkIdExist() {
        if( $this->isPassed ) {
            $params = $this->params;
            $sql = "SELECT * FROM EmployeeUploadData WHERE EmployeeUploadData.`EmployeeUploadDataId` = " . $params["id"] . ";";
            $result = $this->db->query($sql);
            if(count($result) === 0){
                $saveResult["isPass"] = FALSE;
                $saveResult["errorMsg"] = "无效Id";
                $saveResult["resultCode"] = 401;
                self::setReturnStates($saveResult["isPass"],$saveResult["resultCode"],$saveResult["errorMsg"]);
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

new DeleteEmployeeData($config);