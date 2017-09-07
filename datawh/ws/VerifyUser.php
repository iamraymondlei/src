<?php
require_once(dirname(__FILE__) . "/../application/config/WsConfig.php");

class VerifyUser extends WebService implements iWebService{
    protected $isDebug      = FALSE;
    protected $userInfo     = array();

    function __construct() {
        if($this->isDebug){ echo DatetimeUtil::getTime("begin"); }
        self::getRequestParams();
        if($this->isDebug){ echo DatetimeUtil::getTime("getRequestParams"); }
        self::checkBaseParams();
        if($this->isDebug){ echo DatetimeUtil::getTime("checkBaseParams"); }
        self::checkWsParams();
        if($this->isDebug){ echo DatetimeUtil::getTime("checkWsParams"); }
        self::connectDB();
        if($this->isDebug){ echo DatetimeUtil::getTime("connectDB"); }
        self::process();
        if($this->isDebug){ echo DatetimeUtil::getTime("process"); }
        self::closeDB();
        if($this->isDebug){ echo DatetimeUtil::getTime("closeDB"); }
        self::output();
        if($this->isDebug){ echo DatetimeUtil::getTime("output"); }
        self::destory();
    }

    public function checkWsParams() {
        $this->serverName = __CLASS__;
        $params = $this -> params;
        $checkResult["isPass"] = TRUE;

        if($checkResult["isPass"]){ $checkResult = CheckHttpParam::check(array("NOT_NULL"), $params, "un"); }
        if($checkResult["isPass"]){ $checkResult = CheckHttpParam::check(array("NOT_NULL"), $params, "pw"); }
        if($checkResult["isPass"]){ $checkResult = CheckHttpParam::check(array("IN_RANGE"), $params, "format", array("json", "xml")); }

        if($this->isDebug){  echo "checkWsParams".PHP_EOL; print_r($checkResult); }

        $isPassed = isset($checkResult["isPass"])?$checkResult["isPass"]:TRUE;
        $errorMsg = isset($checkResult["errorMsg"])?$checkResult["errorMsg"]:null;
        $resultCode = isset($checkResult["resultCode"])?$checkResult["resultCode"]:200;
        self::setReturnStates($isPassed,$resultCode,$errorMsg);
    }
    
    private function process() {
        if( $this->isPassed ){
            $this -> verifyAccount();
            $this -> updateUserLoginTime();
            $this -> setSession();
        }
    }
    
    private function verifyAccount() {
        $params = $this->params;
        $username = $params["un"];
        $password = md5(trim($params["pw"]));
        
        $sqlHelper = new MySQLHelper();
        $sqlHelper->field(array('UserId','RoleId','FamilyId','UserName','DisplayName','LastLoginTime'));
        $sqlHelper->order(array('LastUpdate'=>'desc'));
        $sqlHelper->where(array('UserName'=>$username,'Password'=>$password));
        $queryResult = $sqlHelper->select($this->db,'User');
        
        if($queryResult["state"] === 200){
            if(count($queryResult["body"])===1){
                $this -> userInfo = $queryResult["body"][0];
            }
            else{
                self::setReturnStates(FALSE,404,'账号名或密码错误');
            }
        }
        else{
            self::setReturnStates(FALSE,500,'不能连接数据库或不能预计的错误');
        }
    }
    
    private function updateUserLoginTime() {
        if( $this->isPassed ){
            $userId = $this->userInfo["UserId"];
            $updateData = array('LastLoginTime'=>DatetimeUtil::getDateTime() );
            $sqlHelper = new MySQLHelper();        
            $sqlHelper->where(array('UserId'=>$userId));
            $updateResult = $sqlHelper->update($this->db,'User',$updateData);

            if($updateResult["state"] !== 200){
                //更新失败
            }
        }
    }
    
    private function setSession() {
        if( $this->isPassed ){
            $sessionId = session_id();
            if(empty($sessionId)){ session_start(); }
            $_SESSION['userInfo'] = $this->userInfo;
            self::setReturnStates(TRUE,200,'SUCCESS');
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

new VerifyUser();