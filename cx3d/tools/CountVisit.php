<?php
date_default_timezone_set("PRC");

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once("../config/wsconfig.php");

class CountVisit extends WebService {
    protected $isDebug      = FALSE;
    protected $prefixUrl    = "";
    protected $countAry     = array();
    protected $visitAry     = array();

    function __construct($config) {
        $this->config = $config;
        self::connectDB();
        self::getEmployeeUploadData();
        self::getArticle();
        self::getImage();
        self::getAudio();
        self::getVideo();
        self::saveToDB();
    }

    private function getEmployeeUploadData() {
        $sql_sel = "SELECT eud.* ";
        $sql_from = "FROM EmployeeUploadData eud ";

        $sql = $sql_sel.$sql_from.";";
        $itemList = $this->db->query($sql);

        foreach($itemList as $item){
            $this->countAry[] = array("TableName"=>"EmployeeUploadData","ItemId"=>$item["EmployeeUploadDataId"],"Visit"=>$item["Visit"],"VisitTime"=>$item["VisitTime"]);
            $this->visitAry[] = $item["Visit"];
        }
    }

    private function getArticle() {
        $sql_sel = "SELECT al.* ";
        $sql_from = "FROM ArticleList al ";

        $sql = $sql_sel.$sql_from.";";
        $itemList = $this->db->query($sql);

        foreach($itemList as $item){
            $this->countAry[] = array("TableName" => "ArticleList", "ItemId" => $item["ArticleListId"], "Visit" => $item["Visit"], "VisitTime" => $item["VisitTime"]);
            $this->visitAry[] = $item["Visit"];
        }
    }

    private function getImage() {
        $sql_sel = "SELECT il.* ";
        $sql_from = "FROM ImageList il ";

        $sql = $sql_sel.$sql_from.";";
        $itemList = $this->db->query($sql);

        foreach($itemList as $item){
            $this->countAry[] = array("TableName" => "ImageList", "ItemId" => $item["ImageListId"], "Visit" => $item["Visit"], "VisitTime" => $item["VisitTime"]);
            $this->visitAry[] = $item["Visit"];
        }
    }

    private function getVideo() {
        $sql_sel = "SELECT vl.* ";
        $sql_from = "FROM VideoList vl ";

        $sql = $sql_sel.$sql_from.";";
        $itemList = $this->db->query($sql);

        foreach($itemList as $item){
            $this->countAry[] = array("TableName" => "VideoList", "ItemId" => $item["VideoListId"], "Visit" => $item["Visit"], "VisitTime" => $item["VisitTime"]);
            $this->visitAry[] = $item["Visit"];
        }
    }

    private function getAudio() {
        $sql_sel = "SELECT al.* ";
        $sql_from = "FROM AudioList al ";

        $sql = $sql_sel.$sql_from.";";
        $itemList = $this->db->query($sql);

        foreach($itemList as $item){
            $this->countAry[] = array("TableName" => "AudioList", "ItemId" => $item["AudioListId"], "Visit" => $item["Visit"], "VisitTime" => $item["VisitTime"]);
            $this->visitAry[] = $item["Visit"];
        }
    }

    private function saveToDB() {
        $itemList = $this->countAry;
        if(count($itemList) > 0){
            $this->db->query("DELETE FROM `VisitCount`;");
            foreach($itemList as $item){
                $sql = "INSERT INTO `VisitCount` (`TableName`, `ItemId`, `Visit`, `VisitTime`) VALUES ('".$item['TableName']."', '".$item['ItemId']."', '".$item['Visit']."', '".$item['VisitTime']."');";
                $this->db->query($sql);
            }
        }
    }
}

new CountVisit($config);
