<?php
require_once("../config/wsconfig.php");
/**
 * Description of VerifyUser
 * params:
 * @ code
 * @ api value=>getUserInfo/getEmployeeInfo
 * @author icm
 */

class GetNews extends WebService implements iWebService {
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
        self::GetData();
        if($this->isDebug){ echo DatetimeUtil::getTime("GetData"); }
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
                $checkResult = CheckHttpParam::check(array("NOT_EMPTY","NOT_NULL"), $params, "id");
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
            $news = self::getNews();
            if(count($news["data"])>0){
                self::updateVisitTimeSQL("News",$news["data"][0]["NewsId"]);
                $articles = self::getArticle();
                foreach($articles["data"] as $articleItem){self::updateVisitTimeSQL("ArticleList",$articleItem["ArticleListId"]);}
                $images = self::getImage();
                foreach($images["data"] as $articleItem){self::updateVisitTimeSQL("ImageList",$articleItem["ImageListId"]);}
                $videos = self::getVideo();
                foreach($videos["data"] as $articleItem){self::updateVisitTimeSQL("VideoList",$articleItem["VideoListId"]);}
                $audios = self::getAudio();
                foreach($audios["data"] as $articleItem){self::updateVisitTimeSQL("AudioList",$articleItem["AudioListId"]);}

                $news["data"][0]["ArticleList"] = array("Article"=>$articles["data"]);
                $news["data"][0]["ImageList"] = array("Image"=>$images["data"]);
                $news["data"][0]["AudioList"] = array("Audio"=>$audios["data"]);
                $news["data"][0]["VideoList"] = array("Video"=>$videos["data"]);
                $this->outputData = array("NewsList"=>array("News"=>$news["data"]));
            }
            else{
                self::setReturnStates(TRUE,401,"无效Id");
            }
        }
    }

    private function getNews() {
        $result = FALSE;
        $params = $this->params;

        $sql_sel = "SELECT n.* ";
        $sql_from = "FROM News n ";
        $sql_where = "WHERE 1=1 ";
        $sql_orderBy = " ORDER BY n.`LastUpdate` DESC ";

        if(isset($params["id"]) && strlen($params["id"])>0){
            $sql_where.= "AND n.NewsId = ".$params["id"]." ";
        }

        $sql = $sql_sel.$sql_from.$sql_where.$sql_orderBy.";";
        $result = $this->db->query($sql);
        return array("data"=>$result);
    }

    private function getArticle() {
        $result = FALSE;
        $params = $this->params;

        $sql_sel = "SELECT al.* ";
        $sql_from = "FROM ArticleList al ";
        $sql_where = "WHERE 1=1 ";
        $sql_orderBy = " ORDER BY al.`LastUpdate` DESC ";

        if(isset($params["id"]) && strlen($params["id"])>0){
            $sql_where.= "AND al.NewsId = ".$params["id"]." ";
        }

        $sql = $sql_sel.$sql_from.$sql_where.$sql_orderBy.";";
        $result = $this->db->query($sql);
        $result = $this->setImagePrefix($result);
        return array("data"=>$result);
    }

    private function getImage() {
        $result = FALSE;
        $params = $this->params;

        $sql_sel = "SELECT il.* ";
        $sql_from = "FROM ImageList il ";
        $sql_where = "WHERE 1=1 ";
        $sql_orderBy = " ORDER BY il.`LastUpdate` DESC ";

        if(isset($params["id"]) && strlen($params["id"])>0){
            $sql_where.= "AND il.NewsId = ".$params["id"]." ";
        }

        $sql = $sql_sel.$sql_from.$sql_where.$sql_orderBy.";";
        $result = $this->db->query($sql);
        $result = $this->setImagePrefix($result);
        return array("data"=>$result);
    }

    private function getVideo() {
        $result = FALSE;
        $params = $this->params;

        $sql_sel = "SELECT vl.* ";
        $sql_from = "FROM VideoList vl ";
        $sql_where = "WHERE 1=1 ";
        $sql_orderBy = " ORDER BY vl.`LastUpdate` DESC ";

        if(isset($params["id"]) && strlen($params["id"])>0){
            $sql_where.= "AND vl.NewsId = ".$params["id"]." ";
        }

        $sql = $sql_sel.$sql_from.$sql_where.$sql_orderBy.";";
        $result = $this->db->query($sql);
        $result = $this->setImagePrefix($result);
        return array("data"=>$result);
    }

    private function getAudio() {
        $result = FALSE;
        $params = $this->params;

        $sql_sel = "SELECT al.* ";
        $sql_from = "FROM AudioList al ";
        $sql_where = "WHERE 1=1 ";
        $sql_orderBy = " ORDER BY al.`LastUpdate` DESC ";

        if(isset($params["id"]) && strlen($params["id"])>0){
            $sql_where.= "AND al.NewsId = ".$params["id"]." ";
        }

        $sql = $sql_sel.$sql_from.$sql_where.$sql_orderBy.";";
        $result = $this->db->query($sql);
        $result = $this->setImagePrefix($result);
        return array("data"=>$result);
    }

    private function updateVisitTimeSQL($table,$id) {
        $sql = "UPDATE ".$table." t SET t.`Visit` = t.`Visit` + 1, t.`VisitTime` = NOW() WHERE t.`".$table."Id` = ".$id.";";
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

new GetNews($config);