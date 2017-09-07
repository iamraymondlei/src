<?php
require_once(dirname(__FILE__) . "/../application/config/WsConfig.php");
/**
 * Description of GetStatistics
 *
 * @author icm
 */

class GetStatistics extends WebService implements iWebService {
    protected $isDebug      = FALSE;
    protected $dayFrom      = "" ;
    protected $dayTo        = "" ;
    
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
            self::getStatistics();
            if($this->isDebug){ echo DatetimeUtil::getTime("getStatistics"); }
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

            $isPassed = isset($checkResult["isPass"])?$checkResult["isPass"]:TRUE;
            $errorMsg = isset($checkResult["errorMsg"])?$checkResult["errorMsg"]:null;
            $resultCode = isset($checkResult["resultCode"])?$checkResult["resultCode"]:200;
            self::setReturnStates($isPassed,$resultCode,$errorMsg);
        }
    }
        
    private function getStatistics() {
        $tableName = 'Statistics';
        $result = array();
        $fileTypeList = $this->db->query("SELECT * FROM FileType ft;");
        self::getDateRange();
        foreach($fileTypeList as $type){
            $name = $type["FileTypeName"];
            $typeId = $type["FileTypeId"];
            $fileCount = $this->db->query("SELECT COUNT(*) as Count FROM File f WHERE f.`FileTypeId` = ".$typeId.";");
            $dayRange = self::getFileCountByDate($typeId);
            $result[] = array("FileTypeName"=>$name, "FileCount"=>$fileCount[0]["Count"], "DayList"=>$dayRange);
        }
        
        //list($firstData,$lastData) = self::getMonth(date("Y-m-d"));        
        //$expenseCount = $this->db->query("SELECT SUM(el.`Price`) AS Total FROM ExpenseList el WHERE el.`ExpenseTime` > '".$firstData."' AND el.`ExpenseTime` < '".$lastData."';");
        $this->outputData = array("FileList"=>array("File"=>$result));//"ExpenseCount"=>array("ThisMonth"=>$expenseCount[0]["Total"])
    }
    
    private function getFileCountByDate($typeId) {
        $sql = "SELECT COUNT(f.`FileId`) AS Count, DATE_FORMAT(f.`CreationTime`,'%Y-%m-%d') AS Day FROM File f WHERE f.`FileTypeId` = ".$typeId." AND DATE_FORMAT(f.`CreationTime`,'%Y-%m-%d') BETWEEN '".$this->dayFrom."' AND '".$this->dayTo."' GROUP BY DAY;";
        $queryResult = $this->db->query($sql);        
        $result = array("Day"=>$queryResult);
        return $result;
    }
    
    public function getDateRange(){
        $date = date("Y-m-d");
        $this->dayTo = date("Y-m-d h:m:s",strtotime($date));
        $this->dayFrom = date("Y-m-d h:m:s",strtotime("$this->dayTo -1 month"));
        //echo $this->dayFrom.",".$this->dayTo;
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

new GetStatistics();