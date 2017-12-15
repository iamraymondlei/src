<?php
require_once("../config/wsconfig.php");
/**
 * Get preload image list
 * 以所有图片作为基本取$preloadPercent的百分比
 * 只输出visit大于0的记录
 * params:
 * @
 * @
 * @author icm
 */

class GetPreload extends WebService implements iWebService {
    protected $isDebug      = FALSE;
    protected $prefixUrl    = "";
    protected $preloadPercent = 0;
    
    function __construct($config) {
        $this->config = $config;
        $this->preloadPercent = $config["preloadPercent"];
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

            if($this->isDebug){ echo "CheckParam".PHP_EOL; print_r($checkResult); }

            $isPassed = isset($checkResult["isPass"])?$checkResult["isPass"]:TRUE;
            $errorMsg = isset($checkResult["errorMsg"])?$checkResult["errorMsg"]:null;
            $resultCode = isset($checkResult["resultCode"])?$checkResult["resultCode"]:200;
            self::setReturnStates($isPassed,$resultCode,$errorMsg);
        }
    }

    private function getData() {
        if( $this->isPassed ){
            $itemList = self::getVisitItems();
            $employeeDataIds="";
            $articleIds="";
            $audioIds="";
            $videoIds="";
            $imageIds = "";
            foreach($itemList as $item){
                if($item["TableName"] === "EmployeeUploadData"){
                    $employeeDataIds.= $item["ItemId"].",";
                }
                elseif($item["TableName"] === "ArticleList"){
                    $articleIds.= $item["ItemId"].",";
                }
                elseif($item["TableName"] === "AudioList"){
                    $audioIds.= $item["ItemId"].",";
                }
                elseif($item["TableName"] === "VideoList"){
                    $videoIds.= $item["ItemId"].",";
                }
                elseif($item["TableName"] === "ImageList"){
                    $imageIds.= $item["ItemId"].",";
                }
            }

            $employeeData = self::getEmployeeData(trim($employeeDataIds,","));
            $article = self::getArticle(trim($articleIds,","));
            $audio = self::getAudio(trim($audioIds,","));
            $video = self::getVideo(trim($videoIds,","));
            $image = self::getImage(trim($imageIds,","));

            $outputAry = array();
            if($employeeData) $outputAry = array_merge($outputAry,$employeeData);
            if($article) $outputAry = array_merge($outputAry,$article);
            if($audio) $outputAry = array_merge($outputAry,$audio);
            if($video) $outputAry = array_merge($outputAry,$video);
            if($image) $outputAry = array_merge($outputAry,$image);

            $this->outputData = array("ImageList"=>array("Image"=>$outputAry));
        }
    }

    private function getVisitItems() {
        $result = array();

        $sql_sel = "SELECT v.* ";
        $sql_from = "FROM VisitCount v ";
        $sql_where = "WHERE 1=1 ";
        $sql_orderBy = "ORDER BY v.`Visit` DESC, v.`VisitTime` DESC ";

        $sql = $sql_sel.$sql_from.$sql_where.$sql_orderBy.";";
        $itemList = $this->db->query($sql);

        $preloadCount = round($this->preloadPercent * count($itemList));
        if($preloadCount > 0){
            for($index=0; $index<$preloadCount; $index++){
                if($itemList[$index]["Visit"] > 0) $result[] = $itemList[$index];
            }
        }
        return $result;
    }

    private function getEmployeeData($ids) {
        $result = FALSE;
        if(strlen($ids)>0) {
            $sql_sel = "SELECT eud.RepresentImageUrl ";
            $sql_from = "FROM EmployeeUploadData eud ";
            $sql_where = "WHERE eud.EmployeeUploadDataId IN (" . $ids . ") ";

            $sql = $sql_sel . $sql_from . $sql_where . ";";
            $queryResult = $this->db->query($sql);
            $queryResult = $this->setImagePrefix($queryResult);
            foreach($queryResult as $item){
                $result[]["Url"] = $item["RepresentImageUrl"];
            }
        }
        return $result;
    }

    private function getArticle($ids) {
        $result = FALSE;
        if(strlen($ids)>0) {
            $sql_sel = "SELECT al.RepresentImageUrl ";
            $sql_from = "FROM ArticleList al ";
            $sql_where = "WHERE al.ArticleListId IN (" . $ids . ") ";

            $sql = $sql_sel . $sql_from . $sql_where . ";";
            $queryResult = $this->db->query($sql);
            $queryResult = $this->setImagePrefix($queryResult);
            foreach($queryResult as $item){
                $result[]["Url"] = $item["RepresentImageUrl"];
            }
        }
        return $result;
    }

    private function getImage($ids) {
        $result = FALSE;
        if(strlen($ids)>0){
            $sql_sel = "SELECT il.ImageUrl ";
            $sql_from = "FROM ImageList il ";
            $sql_where = "WHERE il.ImageListId IN (".$ids.") ";

            $sql = $sql_sel.$sql_from.$sql_where.";";
            $queryResult = $this->db->query($sql);
            $queryResult = $this->setImagePrefix($queryResult);
            foreach($queryResult as $item){
                $result[]["Url"] = $item["ImageUrl"];
            }
        }
        return $result;
    }

    private function getVideo($ids) {
        $result = FALSE;
        if(strlen($ids)>0) {
            $sql_sel = "SELECT vl.PreviewImageUrl ";
            $sql_from = "FROM VideoList vl ";
            $sql_where = "WHERE vl.VideoListId IN (" . $ids . ") ";

            $sql = $sql_sel . $sql_from . $sql_where . ";";
            $queryResult = $this->db->query($sql);
            $queryResult = $this->setImagePrefix($queryResult);
            foreach($queryResult as $item){
                $result[]["Url"] = $item["PreviewImageUrl"];
            }
        }
        return $result;
    }

    private function getAudio($ids) {
        $result = FALSE;
        if(strlen($ids)>0) {
            $sql_sel = "SELECT al.PerviewImageUrl ";
            $sql_from = "FROM AudioList al ";
            $sql_where = "WHERE al.AudioListId IN (" . $ids . ") ";

            $sql = $sql_sel . $sql_from . $sql_where . ";";
            $queryResult = $this->db->query($sql);
            $queryResult = $this->setImagePrefix($queryResult);
            foreach($queryResult as $item){
                $result[]["Url"] = $item["PerviewImageUrl"];
            }
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

new GetPreload($config);