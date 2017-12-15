<?php
require_once("../config/wsconfig.php");
require_once('../public/plugins/fileupload/9.12.1/php/UploadHandler.php');

class UploadFile extends WebService implements iWebService {
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
            self::setFileUpload();
            if($this->isDebug){ echo DatetimeUtil::getTime("setFileUpload"); }
        }
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
    
    private function setFileUpload() {  
        $params = $this->params;
        $result = FALSE;

        $uploadFilePath = $this->config['filePath'];//Config::$g_upload_file_path;
        $takeFilePath = $this->config['filePhysicalPath'];//Config::$g_take_file_path;
        $dir = isset($params["file"]) ? $uploadFilePath.substr($params["file"], 0, 2)."/" : $uploadFilePath;
        $fileName = $_FILES["files"]["name"];
        $tmpFile = $_FILES["files"]["tmp_name"];
        $url = (is_array($tmpFile)) ? $tmpFile[0] : $tmpFile;
        
        $thumbnailSize = self::getThumbnailSize($url);        
        $upload_handler = new UploadHandler(
            array(
                'upload_dir' => $dir,
                'upload_url' => $takeFilePath,
                'image_versions' => array('' => array('auto_orient' => true),'thumbnail' => array('max_width' => $thumbnailSize["width"], 'max_height' => $thumbnailSize["height"])
                )
            )
        );
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
    
    public function destory() {
        $this->params = array();
        $this->outputData = array();
        $this->resultCode = 200;
        $this->errorMsg = null;
        $this->isPassed = FALSE;
        $this->serviceName = __CLASS__;
    }
}

new UploadFile($config);