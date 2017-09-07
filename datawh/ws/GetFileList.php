<?php
require_once(dirname(__FILE__) . "/../application/config/WsConfig.php");
/**
 * Description of GetFileList
 * params:
 * @id          [Num]
 * @paymentId  [Num]
 * @storeId    [Num]
 * @productId [Num]
 * @dateRange [today] [2017-06-04 00:00:00,2017-06-04 23:59:59]
 * 
 * @author icm
 */

class GetFileList extends WebService implements iWebService {
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
            self::getFile();
            if($this->isDebug){ echo DatetimeUtil::getTime("getFile"); }
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
                $checkResult = CheckHttpParam::check(array("NOT_ZERO"), $params, "type");
            }
            if($checkResult["isPass"]){
                $checkResult = CheckHttpParam::check(array("NOT_ZERO"), $params, "project");
            }
            if($checkResult["isPass"]){
                $checkResult = CheckHttpParam::check(array("NOT_ZERO"), $params, "tag");
            }
            if($checkResult["isPass"]){
                $checkResult = CheckHttpParam::check(array("NOT_ZERO"), $params, "cat");
            }
            if($this->isDebug){ echo "CheckParam".PHP_EOL; print_r($checkResult); }

            $isPassed = isset($checkResult["isPass"])?$checkResult["isPass"]:TRUE;
            $errorMsg = isset($checkResult["errorMsg"])?$checkResult["errorMsg"]:null;
            $resultCode = isset($checkResult["resultCode"])?$checkResult["resultCode"]:200;
            self::setReturnStates($isPassed,$resultCode,$errorMsg);
        }
    }
        
    private function getFile() {
        if( $this->isPassed ){
            $result = self::doSql();
            $this->outputData = array("Count"=>$result["count"],"FileList"=>array("File"=>$result["data"]));
        }
    }
    
    private function doSql() {
        $result = FALSE;
        $params = $this->params;
        $startRow = ($this->page - 1) * $this->pageSize;
        $rowSize = $this->pageSize;
        
        $sql_count = "SELECT DISTINCT f.* ";
        $sql_sel = "SELECT DISTINCT f.*,ft.FileTypeName,u.DisplayName ";
        $sql_from = "FROM File f ";
        $sql_join = "LEFT JOIN FileType ft ON ft.FileTypeId = f.`FileTypeId`";
        $sql_join.= "LEFT JOIN User u ON u.UserId = f.`UserId`";
        
        $sql_where = "WHERE 1=1 ";
        $sql_orderBy = " ORDER BY f.`CreationTime` DESC ";
        $sql_limit = "LIMIT ".$startRow.",".$rowSize." ";
        
        if(isset($params["id"]) && strlen($params["id"])>0){
            $sql_where.= "AND f.FileId = ".$params["id"]." ";
        }
        if(isset($params["keyword"]) && strlen($params["keyword"])>0 && !isset($params["tag"])){            
            $sql_join.= "LEFT JOIN TagList tl ON tl.`FileId` = f.`FileId` ";
            $sql_join.= "LEFT JOIN Tag t ON tl.`TagId` = t.`TagId` ";
            
            $sql_where.= "AND (f.`FileName` LIKE '%".$params["keyword"]."%' OR t.`TagName` LIKE '%".$params["keyword"]."%') ";
        }
        if(isset($params["type"]) && strlen($params["type"])>0){
            $sql_where.= "AND f.FileTypeId = ".$params["type"]." ";
        }
        
        if(isset($params["project"]) && strlen($params["project"])>0){
            $sql_join.= "LEFT JOIN ProjectList pl ON pl.`FileId` = f.`FileId` ";
            $sql_where.= "AND pl.ProjectId IN (".$params["project"].") ";
        }
        if(isset($params["tag"]) && strlen($params["tag"])>0){
            $sql_join.= "LEFT JOIN TagList tl ON tl.FileId = f.`FileId` ";
            $sql_where.= "AND tl.TagId IN (".$params["tag"].") ";
        }
        if(isset($params["cat"]) && strlen($params["cat"])>0 ){
            $sql_join.= "LEFT JOIN FileCatList fcl ON fcl.FileId = f.`FileId` ";
            $sql_where.= "AND fcl.FileCatNodeId IN (".$params["cat"].") ";
        }
        
        $sql = $sql_count.$sql_from.$sql_join.$sql_where.";";
        $dataCountResult = $this->db->query($sql);
       
        if($dataCountResult && count($dataCountResult)>0){
            $sql = $sql_sel.$sql_from.$sql_join.$sql_where.$sql_orderBy.$sql_limit.";";
            $list = $this->db->query($sql);
            $fullData = self::GetDetailInfo($list);
            return array("count"=>count($dataCountResult),"data"=>$fullData);
        }
        else{
            return array("count"=>0,"data"=>array());
        }
    }
    
    private function GetDetailInfo($data) {
        $result = $data;
        $params = $this->params;
        if(isset($params["id"]) && strlen($params["id"])>0 ){
            foreach($data as $index => $item){
                $cat = self::GetFileCat($item["FileId"]);
                $tag = self::GetTag($item["FileId"]);
                $project = self::GetProject($item["FileId"]);
                $result[$index]["ProjectList"] = $project["ProjectList"];
                $result[$index]["FileCatList"] = $cat["FileCatList"];
                $result[$index]["TagList"] = $tag["TagList"];
            }
        }
        return $result;
    }
    
    private function GetFileCat($fileId) {
        $result = array("FileCatList"=>array());
        $sql = "SELECT fcn.`FileCatName`,fcl.* FROM FileCatList fcl LEFT JOIN FileCatNode fcn ON fcn.FileCatNodeId = fcl.FileCatNodeId WHERE fcl.`FileId` = ".$fileId." ORDER BY fcl.`CreationTime` DESC;";
        $queryResult = $this->db->query($sql);
        if(count($queryResult) > 0){
            $result = array("FileCatList"=>array("FileCat"=>$queryResult));
        }
        return $result;
    }
    
    private function GetProject($fileId) {
        $result = array("ProjectList"=>array());
        $sql = "SELECT p.`ProjectName`,pl.* FROM ProjectList pl LEFT JOIN Project p ON p.ProjectId = pl.ProjectId WHERE pl.`FileId` = ".$fileId." ORDER BY pl.`CreationTime` DESC;";
        $queryResult = $this->db->query($sql);
        if(count($queryResult) > 0){
            return array("ProjectList"=>array("Project"=>$queryResult));
        }
        return $result;
    }
    
    private function GetTag($fileId) {
        $result = array("TagList"=>array());
        $sql = "SELECT t.`TagName`,tl.* FROM TagList tl LEFT JOIN Tag t ON t.TagId = tl.TagId WHERE tl.`FileId` = ".$fileId." ORDER BY tl.`CreationTime` DESC;";
        $queryResult = $this->db->query($sql);
        if(count($queryResult) > 0){
            return array("TagList"=>array("Tag"=>$queryResult));
        }
        return $result;
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

new GetFileList();