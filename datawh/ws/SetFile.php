<?php
require_once(dirname(__FILE__) . "/../application/config/WsConfig.php");
/**
 * Description of SetFile
 *
 * @author icm
 */

class SetFile extends WebService implements iWebService {
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
            self::setFile();
            if($this->isDebug){ echo DatetimeUtil::getTime("setFile"); }
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
                $checkResult = CheckHttpParam::check(array("NOT_EMPTY","NOT_NULL"), $params, "name");
            }
            if($checkResult["isPass"]){
                $checkResult = CheckHttpParam::check(array("NOT_EMPTY"), $params, "perviewImage");
            }
            if($checkResult["isPass"]){
                $checkResult = CheckHttpParam::check(array("NOT_EMPTY","NOT_NULL"), $params, "fileUrl");
            }
            if($checkResult["isPass"]){
                $checkResult = CheckHttpParam::check(array("NOT_EMPTY","NOT_NULL"), $params, "fileType");
            }
            if($checkResult["isPass"]){
                $checkResult = CheckHttpParam::check(array("NOT_EMPTY","NOT_NULL"), $params, "folder");
            }
            if($checkResult["isPass"]){
                $checkResult = CheckHttpParam::check(array("NOT_EMPTY"), $params, "catList");
            }
//            if($checkResult["isPass"]){
//                $checkResult = CheckHttpParam::check(array("NOT_EMPTY"), $params, "projectList");
//            }

            if($this->isDebug){ echo "CheckParam".PHP_EOL; print_r($checkResult); }

            $isPassed = isset($checkResult["isPass"])?$checkResult["isPass"]:TRUE;
            $errorMsg = isset($checkResult["errorMsg"])?$checkResult["errorMsg"]:null;
            $resultCode = isset($checkResult["resultCode"])?$checkResult["resultCode"]:200;
            self::setReturnStates($isPassed,$resultCode,$errorMsg);
        }
    }
    
    private function setFile() {  
        $params = $this->params;
        $result = FALSE;
        
        if( $this->isPassed ){
            if(!self::isDuplicate()){
                if($params["action"] === "add"){
                    $result = self::doAddSql();
                }
                elseif($params["action"] === "update"){
                    $result = self::doUpdateSql();
                }
            }
            else{
                self::setReturnStates("FALSE","409","该文件已存在");
            }
        }
        $this->outputData = array("Result"=>$result);
    }
    
    private function isDuplicate () {
        $isDuplicate = FALSE;
        $params = $this->params;
        
        $sqlHelper = new MySQLHelper();
        $sqlHelper->field(array('FileId'));
        
        if(isset($params["id"])){
            $sqlHelper->where(array('FileName'=>$params["name"],'FileTypeId'=>$params["fileType"],'FileId'=>array($params["id"],'<>','and')));
        }
        else{
            $sqlHelper->where(array('FileName'=>$params["name"],'FileTypeId'=>$params["fileType"]));
        }
        $queryResult = $sqlHelper->select($this->db,'File');
        
        if($queryResult["state"] === 200){
            if(count($queryResult["body"])===1){
                $this -> userInfo = $queryResult["body"][0];
                $isDuplicate = TRUE;
            }
            else{
                $isDuplicate = FALSE;
            }
        }
        else{
            self::setReturnStates(FALSE,500,'不能连接数据库或不能预计的错误');
        }
        return $isDuplicate;
    }
    
    private function doAddSql() {
        $tableName = "File";
        $params = $this->params;
        $userInfo = $_SESSION['userInfo'];
        $sqlHelper = new MySQLHelper();  
        
        //add file
        $insertData = array('FileName'=>$params["name"],'UserId'=>$userInfo["UserId"],'PerviewImage'=>$params["perviewImage"],'FileTypeId'=>$params["fileType"],'FileUrl'=>$params["fileUrl"],'FileSize'=>$params["fileSize"],'FolderId'=>$params["folder"],'Description'=>$params["description"] );
        $insertFileResult = $sqlHelper->insert($this->db,$tableName,$insertData);
        
        if($insertFileResult["state"] === 200){
            $fileId = $this->db->getInsertId();
            $execTransResult = self::addCatTagProject($fileId);
            
            if($execTransResult){
                return $fileId;
            }
            else{
                $delSqlHelper = new MysqlHelper();
                $delSqlHelper->where(array('FileId'=>$fileId));
                $queryResult = $delSqlHelper->delete($this->db,$tableName);
                self::setReturnStates(FALSE,500,'Insert FileCatList/ProjectList/TagList fail.');
            }
        }
        else{
            self::setReturnStates(FALSE,400,'Insert file fail.');
        }
    }
    
    private function doUpdateSQL() {
        $params = $this->params;
        $userInfo = $_SESSION['userInfo'];
        $sqlHelper = new MySQLHelper();
        $updateData = array('FileName'=>$params["name"],'UserId'=>$userInfo["UserId"],'PerviewImage'=>$params["perviewImage"],'FileTypeId'=>$params["fileType"],'FileUrl'=>$params["fileUrl"],'FileSize'=>$params["fileSize"],'FolderId'=>$params["folder"],'Description'=>$params["description"] );
        $sqlHelper->where(array('FileId'=>$params["id"]));
        $updateResult = $sqlHelper->update($this->db,"File",$updateData);
        
        if($updateResult["state"] === 200){
            self::updateCat($params["id"],$params["catList"]);
            self::updateProject($params["id"],$params["projectList"]);
            //$tagIdList = self::updateTag($params["tagList"]);
            self::updateTagList($params["id"],$params["tagList"]);
        }
        else{
            self::setReturnStates(FALSE,400,'Update file fail.');
        }
            
        return $params["id"];
    }
    
    private function addCatTagProject($fileId) {
        $params = $this->params;
        $sqlAry = array();
        
        //insert file cat list
        if( !empty($params["catList"]) ){
            $insertFileCatValue = "";
            foreach(explode(",", $params["catList"]) as $fileCatId) { $insertFileCatValue.= "('$fileId', '$fileCatId'),"; }
            $insertFileCatSql = "INSERT INTO `FileCatList` (`FileId`, `FileCatNodeId`) VALUES ".rtrim($insertFileCatValue,',').";";
            $sqlAry[] = $insertFileCatSql;
        }
        //insert project list
        if( !empty($params["projectList"]) ){
            $insertProjectCatValue = "";
            foreach(explode(",", $params["projectList"]) as $projectId) { $insertProjectCatValue.= "('$fileId', '$projectId'),"; }
            $insertProjectListSql = "INSERT INTO `ProjectList` (`FileId`, `ProjectId`) VALUES ".rtrim($insertProjectCatValue,',').";";
            $sqlAry[] = $insertProjectListSql;
        }
        //insert tag list
        if( !empty($params["tagList"]) ){
            self::updateTagList($fileId,$params["tagList"]);
//             $tagList = self::updateTag($params["tagList"]);
//             $insertTagValue = "";
//             foreach(explode(",", $tagList) as $tagId) { $insertTagValue.= "('$fileId', '$tagId'),"; }
//             $insertTagListSql = "INSERT INTO `TagList` (`FileId`, `TagId`) VALUES ".rtrim($insertTagValue,',').";";
//             $sqlAry[] = $insertTagListSql;
        }
        
        //执行事务
        $sqlHelper = new MySQLHelper();  
        $execTransResult = $sqlHelper->execTrans($this->db,$sqlAry);
        
        return $execTransResult;
    }
    
    private function updateCat($fileId,$idList) {
        $tableName = 'FileCatList';
        $insertId = explode(",", $idList);
        
        $sqlHelper = new MysqlHelper();
        $sqlHelper->field(array('*'));
        $sqlHelper->where(array('FileId'=>$fileId));
        $queryResult = $sqlHelper->select($this->db,$tableName);
        if(count($queryResult["body"])>0){
            
            $existsId = array();
            foreach($queryResult["body"] as $item){
                if(in_array($item["FileCatNodeId"], $insertId)){
                    $existsId[] = $item["FileCatNodeId"];
                }
                else{
                    //remove
                    $delSqlHelper = new MysqlHelper();
                    $delSqlHelper->where(array('FileCatListId'=>$item["FileCatListId"]));
                    $removeResult = $delSqlHelper->delete($this->db,$tableName);
                }
            }
            
            foreach($insertId as $item){
                if(!in_array($item, $existsId)){
                    $insertSqlHelper = new MysqlHelper();
                    $insertData = array('FileId'=>$fileId,'FileCatNodeId'=>$item);
                    $insertResult = $insertSqlHelper->insert($this->db,$tableName,$insertData);
                }
            }
        }
        else{
            foreach($insertId as $item){
                $insertSqlHelper = new MysqlHelper();
                $insertData = array('FileId'=>$fileId,'FileCatNodeId'=>$item);
                $insertResult = $insertSqlHelper->insert($this->db,$tableName,$insertData);
            }
        }
    }
    
    private function updateProject($fileId,$idList) {
        $tableName = 'ProjectList';
        $insertId = explode(",", $idList);
        $sqlHelper = new MysqlHelper();
        $sqlHelper->field(array('*'));
        $sqlHelper->where(array('FileId'=>$fileId));
        $queryResult = $sqlHelper->select($this->db,$tableName);
        
        if(count($queryResult["body"])>0){
            $existsId = array();
            foreach($queryResult["body"] as $item){
                if(in_array($item["ProjectId"], $insertId)){
                    $existsId[] = $item["ProjectId"];
                }
                else{
                    //remove
                    $delSqlHelper = new MysqlHelper();
                    $delSqlHelper->where(array('ProjectListId'=>$item["ProjectListId"]));
                    $removeResult = $delSqlHelper->delete($this->db,$tableName);
                }
            }
            
            foreach($insertId as $item){
                if(!in_array($item, $existsId)){
                    $insertSqlHelper = new MysqlHelper();
                    $insertData = array('FileId'=>$fileId,'ProjectId'=>$item);
                    $insertResult = $insertSqlHelper->insert($this->db,$tableName,$insertData);
                }
            }
        }
        else{
            foreach($insertId as $item){
                $insertSqlHelper = new MysqlHelper();
                $insertData = array('FileId'=>$fileId,'ProjectId'=>$item);
                $insertResult = $insertSqlHelper->insert($this->db,$tableName,$insertData);
            }
        }
    }
    
    private function updateTag($tagList) {
        $tableName = 'Tag';
        $insertTag = explode(",", $tagList);
        $existsTag = [];
        $sqlHelper = new MysqlHelper();
        $sqlHelper->field(array('*'));
        $queryResult = $sqlHelper->select($this->db,$tableName);
        foreach($queryResult["body"] as $item){
            $existsTag[] = $item["TagName"];
        }
        $newTag = array_diff($insertTag,$existsTag);
        
        if(count($newTag)>0){
            foreach($newTag as $item){
                $insertSqlHelper = new MysqlHelper();
                $insertData = array('TagName'=>$item);
                $insertResult = $insertSqlHelper->insert($this->db,$tableName,$insertData);
            }
        }
        
        $tagIds = "";
        foreach($insertTag as $tag){
            $tagIds.= "'".$tag."',";
        }
        $sql = "SELECT t.TagId FROM Tag t WHERE t.`TagName` IN (".trim($tagIds,",").");";
        $queryResult2 = $this->db->query($sql);
        
        $result = "";
        if(count($queryResult2)>0){
            foreach($queryResult2 as $item){
                $result.= $item["TagId"].",";
            }
        }
        
        return trim($result,",");
    }

    private function updateTagList($fileId,$idList) {
        $tableName = 'TagList';
        $insertId = (strlen($idList)>0)?explode(",", trim($idList)):null;
        $sqlHelper = new MysqlHelper();
        $sqlHelper->field(array('*'));
        $sqlHelper->where(array('FileId'=>$fileId));
        $queryResult = $sqlHelper->select($this->db,$tableName);
        
        if(count($queryResult["body"])>0){
            $existsId = array();
            foreach($queryResult["body"] as $item){
                if(count($insertId) > 0 && in_array($item["TagId"], $insertId)){
                    $existsId[] = $item["TagId"];
                }
                else{
                    //remove
                    $delSqlHelper = new MysqlHelper();
                    $delSqlHelper->where(array('TagListId'=>$item["TagListId"]));
                    $removeResult = $delSqlHelper->delete($this->db,$tableName);
                }
            }
            
            if(count($insertId) > 0){
                foreach($insertId as $item){
                    if(!in_array($item, $existsId)){
                        $insertSqlHelper = new MysqlHelper();
                        $insertData = array('FileId'=>$fileId,'TagId'=>$item);
                        $insertResult = $insertSqlHelper->insert($this->db,$tableName,$insertData);
                    }
                }
            }
        }
        else{
            foreach($insertId as $item){
                $insertSqlHelper = new MysqlHelper();
                $insertData = array('FileId'=>$fileId,'TagId'=>$item);
                $insertResult = $insertSqlHelper->insert($this->db,$tableName,$insertData);
            }
        }
    }
    
    private function destory() {
        $this->params = array();
        $this->outputData = array();
        $this->resultCode = 200;
        $this->errorMsg = null;
        $this->isPassed = FALSE;
        $this->serviceName = __CLASS__;
    }
}

new SetFile();