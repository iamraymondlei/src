<?php
/**
 * Description of WebService
 *
 * @author icm
 */

class WebService {
    protected $isDebug      = FALSE;
    protected $isPassed     = FALSE;
    protected $params       = array();
    protected $outputData   = array();
    protected $resultCode   = 200;
    protected $errorMsg;
    protected $serverName;
    protected $db;
    protected $config       = array();

    protected $format       ='json';
    protected $page         ='1';
    protected $pageSize     ='10';
    protected $goqoId;
    protected $goqoModel;
    protected $goqoVer;
    
    public function checkSession() {
        $hasSession = SessionHelper::check();
        if(!$hasSession){
            self::setReturnStates(FALSE,500,'未通过登录验证且不在可信IP范围');
        }
        else{
            $this->isPassed = TRUE;
        }
    }
    
    public function getRequestParams() {
        $params = RequestHttpParam::getRequestParam();
        $this->params = $params;
        
        if(isset($params['debug'])){ 
            $this->isDebug = $params['debug']; 
        }
        
        if( $this->isDebug ){ echo "getRequestParams：".PHP_EOL; print_r($this->params); }
    }
    
    public function checkBaseParams() {
        $params = $this->params;
        //$checkResult = CheckHttpParam::checkGoqoIdGoqoModelGoqoVer($params);
        //if($this->isDebug){ echo "CheckGoqoIdGoqoModelGoqoVer".PHP_EOL; print_r($checkResult); }
        $checkResult = array("isPass"=>TRUE);
        
        if($checkResult["isPass"]){
            $checkResult = CheckHttpParam::check(array("IN_RANGE"), $params, "format", array("json", "xml"));
            if($checkResult["isPass"] && isset($params["format"])){ $this->format = $params["format"]; }
        }
        if($checkResult["isPass"] && isset($params["page"])){
            $checkResult = CheckHttpParam::check(array("IS_NUMBER"), $params, "page");
            $this->page = $params["page"]; 
        }
        if($checkResult["isPass"] && isset($params["pageSize"])){
            $checkResult = CheckHttpParam::check(array("IS_NUMBER"), $params, "pageSize");
            $this->pageSize = $params["pageSize"];
        }
        $isPassed = isset($checkResult["isPass"])?$checkResult["isPass"]:TRUE;
        $errorMsg = isset($checkResult["errorMsg"])?$checkResult["errorMsg"]:NULL;
        $resultCode = isset($checkResult["resultCode"])?$checkResult["resultCode"]:200;
        self::setReturnStates($isPassed,$resultCode,$errorMsg);
    }
    
    public function connectDB() {
        try{
            $this->db = DatabaseFactory::factory($this->config["db"]);
        } catch(Exception $e){ 
            echo $e->getMessage(); //输出异常信息。 
        }
    }
    
    public function closeDB() {
        $this->db->destruct();
    }
    
    public function output() {
        $params = array(
            "errorCode" => $this->resultCode,
            "errorMessage" => $this->errorMsg, 
            "errorAction" => $this->serverName, 
            "rootTab" => $this->config["outputWsRootTagName"],
            "format" => $this->format, 
            "data" => $this->outputData,
            "returnResult" => "nodata",
            "config" => $this->config
        );
        Output::wsData($params);
    }
    
    protected function setReturnStates($isPassed,$resultCode,$errorMsg) {
        $this->isPassed = $isPassed;
        $this->resultCode = $resultCode;
        $this->errorMsg = $errorMsg;
    }

    /**
     * WsLog::WriteWSLog()
     * 记录WS访问日志
     * @param string $action
     * @param string $otherMsg
     * @return string
     */
    protected function writeWSLog($action,$otherMsg=''){
        $log = new Log();
        $logPath = $this->config['db']['logPath'];
        $log->OpenDateTimeFile($logPath, '', '-'.IpHelper::GetRealIp()."-".$action);
        $log->WriteMsg($action , "-");
        $log->WriteSessionStart();
        $log->WriteMsg( "ResultCode:$this->resultCode", "-");
        $log->WriteMsg( "ResultMessage:$this->errorMsg,", "");
        $log->WriteArray("Result:",$this->outputData);
        if( !empty($otherMsg) ) $log->WriteMsg( print_r($otherMsg, true), "-");
    }
}
