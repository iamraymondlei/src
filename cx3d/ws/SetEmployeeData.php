<?php
require_once("../config/wsconfig.php");
require_once('../public/plugins/fileupload/9.12.1/php/UploadHandler.php');
//错误显示
ini_set('display_errors', 1);

class SetEmployeeData extends WebService implements iWebService {
    protected $isDebug      = FALSE;

    function __construct($config) {
        $this->config = $config;
        if($this->isDebug){ echo DatetimeUtil::getTime("checkSession"); }
        $this->isPassed = TRUE;
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
            self::checkIdExist();
            if($this->isDebug){ echo DatetimeUtil::getTime("cheeckIdExist"); }
            self::setData();
            if($this->isDebug){ echo DatetimeUtil::getTime("setData"); }
            $this->writeWSLog(__CLASS__);
            if($this->isDebug){ echo DatetimeUtil::getTime("writeWSLog"); }
            self::output();
            if($this->isDebug){ echo DatetimeUtil::getTime("output"); }
            self::destory();
        }
    }

    public function checkWsParams() {
        if( $this->isPassed ){
            $this->serverName = __CLASS__;
            $params = $this->params;

            $checkResult = array("isPass"=>$this->isPassed, "errorMsg"=>$this->resultCode, "resultCode"=>$this->errorMsg);

            if($checkResult["isPass"]){
                $checkResult = CheckHttpParam::check(array("NOT_EMPTY","NOT_NULL"), $params, "id");
            }
            if($checkResult["isPass"] && isset($params["videoUrl"])){
                $checkResult = CheckHttpParam::check(array("NOT_EMPTY","NOT_NULL"), $params, "videoUrl");
            }
            if($checkResult["isPass"] && isset($params["content"])){
                $checkResult = CheckHttpParam::check(array("NOT_EMPTY","NOT_NULL"), $params, "content");
            }
            if($checkResult["isPass"]) {
                $checkResult = self::checkUploadFile();
            }
            if($this->isDebug){ echo "CheckParam".PHP_EOL; print_r($checkResult); }

            $isPassed = isset($checkResult["isPass"])?$checkResult["isPass"]:TRUE;
            $errorMsg = isset($checkResult["errorMsg"])?$checkResult["errorMsg"]:null;
            $resultCode = isset($checkResult["resultCode"])?$checkResult["resultCode"]:200;
            self::setReturnStates($isPassed,$resultCode,$errorMsg);
        }
    }

    private function checkUploadFile() {
        $checkResult["isPass"] = TRUE;
        $checkResult["errorMsg"] = "";
        $checkResult["resultCode"] = 200;

        if( isset($_FILES["files"]) ){
            if($_FILES["files"]["size"] == 0){
                //空白图
                $checkResult["isPass"] = FALSE;
                $checkResult["errorMsg"] = "图片大小不能为0";
                $checkResult["resultCode"] = 401;
            }
//            elseif($_FILES["files"]["size"] > 1024*1024){
//                //不能大于1M
//                $checkResult["isPass"] = FALSE;
//                $checkResult["errorMsg"] = "图片大小不能大于1M";
//                $checkResult["resultCode"] = 401;
//            }
            elseif(!in_array($_FILES["files"]["type"],array("image/jpeg","image/png","image/gif"))){
                $checkResult["isPass"] = FALSE;
                $checkResult["errorMsg"] = "图片不是jpg/png/gif格式";
                $checkResult["resultCode"] = 401;
            }
        }
        else{
            $checkResult["isPass"] = FALSE;
            $checkResult["errorMsg"] = "缺少图片数据";
            $checkResult["resultCode"] = 400;
        }
        return $checkResult;
    }

    private function setFileUpload() {
        if($this->isPassed){
            $params = $this->params;
            $result = FALSE;
            $uploadFilePath = $this->config['filePath'];//Config::$g_upload_file_path;
            $takeFilePath = $this->config['filePhysicalPath'];//Config::$g_take_file_path;
            $dir = isset($params["file"]) ? substr($params["file"], 0, 2)."/" : $uploadFilePath;
            $fileName = $_FILES["files"]["name"];
            $tmpFile = $_FILES["files"]["tmp_name"];
            $url = (is_array($tmpFile)) ? $tmpFile[0] : $tmpFile;

            $thumbnailSize = self::getThumbnailSize($url);
            $upload_handler = new UploadHandler(
                array(
                    'print_response'=>FALSE,
                    'upload_dir' => $dir,
                    'upload_url' => $takeFilePath,
                    'image_versions' => array(
                        '' => array('auto_orient' => true),
                        'thumbnail' => array('max_width' => $thumbnailSize["width"], 'max_height' => $thumbnailSize["height"])
                    )
                )
            );
            $uploadResult = $upload_handler->response;
            if(isset($uploadResult["files"])){
                $url = $uploadResult["files"][0]->name;
                $result = "/".substr($url, 0, 2)."/".$url;
            }
            return $result;
        }
    }

    private function setData() {
        if($this->isPassed){
            $params = $this->params;
            $imageUrl= self::setFileUpload();
            $saveResult = null;

            if($imageUrl && isset($params["videoUrl"]) || !empty($params["videoUrl"])) {
                $type = 2;
                $val = $params["videoUrl"];
//                $id = self::checkRecordExist($params["id"],$type);
//                if($id > 0){
//                    $saveResult =  self::updateEmployeeData($id,$imageUrl, $val);
//                }
//                else{
//
//                }
                $saveResult =  self::saveEmployeeData($params["id"],$imageUrl,$type, $val);
            }
            if($imageUrl && isset($params["content"]) || !empty($params["content"])) {
                $type = 1;
                $val = $params["content"];
//                $id = self::checkRecordExist($params["id"],$type);
//                if($id > 0){
//                    $saveResult =  self::updateEmployeeData($id,$imageUrl, $val);
//                }
//                else{
//
//                }
                $saveResult =  self::saveEmployeeData($params["id"],$imageUrl,$type, $val);
            }

            if($saveResult === null){
                $saveResult["isPass"] = FALSE;
                $saveResult["errorMsg"] = "数据保存异常";
                $saveResult["resultCode"] = 500;
            }
            else{
                $saveResult["isPass"] = TRUE;
                $saveResult["errorMsg"] = "成功";
                $saveResult["resultCode"] = 200;
            }
            self::setReturnStates($saveResult["isPass"],$saveResult["resultCode"],$saveResult["errorMsg"]);
        }
    }

    private function saveEmployeeData($id,$imgUrl,$type,$val) {
        if( $this->isPassed ) {
            $sql_insertKey = "INSERT INTO `EmployeeUploadData` (`EmployeeId`, `DataTypeId`, `RepresentImageUrl`, `Value`, `State`, `StickyPost`) ";
            $sql_val = "VALUES ('" . $id . "', '" . $type . "', '" . $imgUrl . "', '" . $val . "', '1', '0'); ";
            $sql = $sql_insertKey . $sql_val;
            $result = $this->db->query($sql);
            return $result;
        }
    }

    private function updateEmployeeData($id,$imgUrl,$val) {
        if( $this->isPassed ) {
            $sql_update = "UPDATE `EmployeeUploadData` ";
            $sql_val = "SET `Value` = '".$val."', `State` = 1, `StickyPost` = 0 ,`RepresentImageUrl` = '".$imgUrl."' WHERE `EmployeeUploadDataId` = '".$id."';";
            $sql = $sql_update . $sql_val;
            $result = $this->db->query($sql);
            return $result;
        }
    }

    private function getThumbnailSize($url) {
        $thumbPotSize = $this->config['thumbPotSize'];
        $max_width = $thumbPotSize;
        $max_height = $thumbPotSize;

        $size = getimagesize($url, $info);
        $width = ($size[0]) ? $size[0] : $thumbPotSize;
        $height = ($size[1]) ? $size[1] : $thumbPotSize;

        $longLineName = "w";
        $longLine = $width;
        $shortLine = $height;
        if ($width < $height) {
            $longLineName = "h";
            $longLine = $height;
            $shortLine = $width;
        }

        if ($longLine > $thumbPotSize) {
            $shortLine = floor($shortLine * $thumbPotSize / $longLine);  //新短邊=短邊 * $thumbPotSize/長邊;
            $longLine = $thumbPotSize;                        //新長邊=$thumbPotSize;
            //用新長邊、新短邊生成縮略圖;
            $max_width = ($longLineName === "w") ? $longLine : $shortLine;
            $max_height = ($longLineName === "w") ? $shortLine : $longLine;
        } else {
            //縮略圖地址 = 原圖地址
            $max_width = $width;
            $max_height = $height;
        }

        return array("width" => $max_width, "height" => $max_height);
    }

    private function checkIdExist() {
        if( $this->isPassed ) {
            $params = $this->params;
            $sql = "SELECT * FROM Employee WHERE Employee.`EmployeeId` = " . $params["id"] . ";";
            $result = $this->db->query($sql);
            if(count($result) === 0){
                $saveResult["isPass"] = FALSE;
                $saveResult["errorMsg"] = "无效用户Id";
                $saveResult["resultCode"] = 401;
                self::setReturnStates($saveResult["isPass"],$saveResult["resultCode"],$saveResult["errorMsg"]);
            }
            elseif(count($result) > 1){
                $saveResult["isPass"] = FALSE;
                $saveResult["errorMsg"] = "用户Id存在重复";
                $saveResult["resultCode"] = 500;
                self::setReturnStates($saveResult["isPass"],$saveResult["resultCode"],$saveResult["errorMsg"]);
            }
        }
    }

    private function checkRecordExist($employeeId,$typeId) {
        $result = FALSE;
        if( $this->isPassed ) {
            $sql = "SELECT EmployeeUploadDataId FROM EmployeeUploadData WHERE `EmployeeId` = ".$employeeId." AND DataTypeId = ".$typeId.";";
            $sqlResult = $this->db->query($sql);
            if(count($sqlResult) == 1){
                $result = $sqlResult[0]["EmployeeUploadDataId"];
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
}

new SetEmployeeData($config);