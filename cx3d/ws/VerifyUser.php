<?php
require_once("../config/wsconfig.php");
/**
 * Description of VerifyUser
 * params:
 * @ code
 * @ api value=>getUserInfo/getEmployeeInfo
 * @author icm
 */

class VerifyUser extends WebService implements iWebService {
    protected $isDebug      = FALSE;
    protected $userData     = "";
    
    function __construct($config) {
        $this->config = $config;
        if($this->isDebug){ echo DatetimeUtil::getTime("begin"); }
        self::getRequestParams();
        if($this->isDebug){ echo DatetimeUtil::getTime("getRequestParams"); }
        self::checkBaseParams();
        if($this->isDebug){ echo DatetimeUtil::getTime("checkBaseParams"); }
        self::checkWsParams();
        if($this->isDebug){ echo DatetimeUtil::getTime("checkWsParams"); }
        self::getData();
        if($this->isDebug){ echo DatetimeUtil::getTime("getData"); }
        self::setUserToDB();
        if($this->isDebug){ echo DatetimeUtil::getTime("setUserToDB"); }
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
                $checkResult = CheckHttpParam::check(array("NOT_ZERO","NOT_EMPTY","NOT_NULL"), $params, "code");
            }
            if($checkResult["isPass"]){
                $checkResult = CheckHttpParam::check(array("NOT_EMPTY","NOT_NULL","IN_RANGE"), $params, "api", array("getUserInfo", "getEmployeeInfo"));
            }

            if($this->isDebug){ echo "CheckParam".PHP_EOL; print_r($checkResult); }

            $isPassed = isset($checkResult["isPass"])?$checkResult["isPass"]:TRUE;
            $errorMsg = isset($checkResult["errorMsg"])?$checkResult["errorMsg"]:null;
            $resultCode = isset($checkResult["resultCode"])?$checkResult["resultCode"]:200;
            self::setReturnStates($isPassed,$resultCode,$errorMsg);
        }
    }

    private function getData() {
        $params = $this->params;
        $isTest = $this->config["IsTestVerifyUser"];
        if( $this->isPassed && (!$isTest || $params["api"] === "getUserInfo") ){
            $code = $params["code"];
            $apiName = $params["api"];
            $tokenData = CSGAPI::getToken($code);

            if($tokenData["status"] == 200){
                $xmlContent = $tokenData["body"];
                $json = XMLUtil::xmlToJson($xmlContent);
                $tokenData = json_decode($json,true);
                $userData = CSGAPI::getUserInfo($tokenData["access_token"],$tokenData["uid"],$apiName);

                if($userData["status"] == 200){
                    $this -> userData = $userData["body"];
                }
                else{
                    $userJson = XMLUtil::xmlToJson($userData["body"]);
                    $errorResult = json_decode($userJson,true);
                    $isPassed = FALSE;
                    $errorMsg = $errorResult["message"];
                    $resultCode = $errorResult["code"];
                    self::setReturnStates($isPassed,$resultCode,$errorMsg);
                }
            }
            elseif($tokenData["status"] == 0){
                $isPassed = FALSE;
                $errorMsg = $tokenData["error"];
                $resultCode = 500;
                self::setReturnStates($isPassed,$resultCode,$errorMsg);
            }
            else{
                $errorResult = json_decode($tokenData["body"],true);
                $isPassed = FALSE;
                $errorMsg = $errorResult["message"];
                $resultCode = $errorResult["code"];
                self::setReturnStates($isPassed,$resultCode,$errorMsg);
            }
        }
    }

    private function setUserToDB() {
        $params = $this->params;
        if($this->isPassed) {
            if ($params["api"] === "getEmployeeInfo") {
                if ($this->config["IsTestVerifyUser"]) {
                    if($params["code"] === "1467848AB9734647B8D545E5132CCB5D")
                        $this->userData = '{"id":658190,"enterpriseid":"1","enterprisename":"南方电网公司","departmentid":"69085","departmentname":"其他","code":null,"realname":"張三","position":"","headimg":"http://www.esyun.cn/wicsg/portal_t/ftpweb//wicsg/platform/201710/weixin_head_img_A8012D0FB08E43FD916EF7029CE1004B.jpg","userstatus":1,"mobile":"13826471633","loginmode":2,"nickname":"","businessdomain":1,"email":null,"modifyTime":null,"createTime":null,"isSecret":null,"integral":0,"accountId":null,"gender":null,"weight":null,"pinyin":null,"birthday":null,"politicalStatus":"99","positionLevel":"99"}';
                    else
                        $this->userData = '{"code":500,"message":"服务方未定义异常"}';
                }
                $json = $this->userData;
                $result = json_decode($json, true);

                if(isset($result["code"])){
                    $saveResult["isPass"] = FALSE;
                    $saveResult["errorMsg"] = $result["message"];
                    $saveResult["resultCode"] = $result["code"];
                    self::setReturnStates($saveResult["isPass"],$saveResult["resultCode"],$saveResult["errorMsg"]);
                }
                else {
                    $id = $result["id"];
                    $name = $result["realname"];
                    $image = $result["headimg"];
                    self::connectDB();
                    $idExist = self::checkIdExist($id);

                    $sqlResult = FALSE;
                    if ($idExist) {
                        //update
                        $sqlResult = self::updateUser($id, $name, $image);
                    } else {
                        //add
                        $sqlResult = self::addUser($id, $name, $image);
                    }

                    if ($sqlResult === null) {
                        $isPassed = FALSE;
                        $errorMsg = "数据库连接异常";
                        $resultCode = "500";
                        self::setReturnStates($isPassed, $resultCode, $errorMsg);
                    } else {
                        self::setOutputData();
                    }
                }
            }
            elseif($params["api"] === "getUserInfo"){
//                if ($this->config["IsTestVerifyUser"]) {
//                    if($params["code"] === "1467848AB9734647B8D545E5132CCB5D")
//                        $this->userData = '{"id":658190,"enterpriseid":"1","businessdomain":"123","realname":"何泽长","userstatus":"1","role":"系统管理员","roleid":"系统管理员"}';
//                    else
//                        $this->userData = '{"code":500,"message":"服务方未定义异常"}';
//                }
                $this->userData = XMLUtil::xmlToJson($this->userData);
                $result = json_decode($this->userData, true);

                if(isset($result["code"])){
                    $saveResult["isPass"] = FALSE;
                    $saveResult["errorMsg"] = $result["message"];
                    $saveResult["resultCode"] = $result["code"];
                    self::setReturnStates($saveResult["isPass"],$saveResult["resultCode"],$saveResult["errorMsg"]);
                }
                else{
                    self::setSession();
                    self::setOutputData();
                }
            }
        }
    }

    private function checkIdExist($id) {
        $idExist = FALSE;
        $sql = "SELECT * FROM Employee WHERE Employee.`EmployeeId` = " . $id . ";";
        $result = $this->db->query($sql);
        if(count($result) > 1){
            $saveResult["isPass"] = FALSE;
            $saveResult["errorMsg"] = "用户Id存在重复";
            $saveResult["resultCode"] = 500;
            self::setReturnStates($saveResult["isPass"],$saveResult["resultCode"],$saveResult["errorMsg"]);
        }
        elseif(count($result) === 1){
            $idExist = TRUE;
        }
        return $idExist;
    }

    private function addUser($id,$name,$img) {
        if( $this->isPassed ) {
            $sql_insertKey = "INSERT INTO `Employee` (`EmployeeId`, `EmployeeName`, `EmployeeHeadImg`) ";
            $sql_val = "VALUES ('" . $id . "', '" . $name . "', '" . $img . "'); ";
            $sql = $sql_insertKey . $sql_val;
            $result = $this->db->query($sql);
            return $result;
        }
    }

    private function updateUser($id,$name,$img) {
        if( $this->isPassed ) {
            $sql_update = "UPDATE `Employee` ";
            $sql_val = "SET `EmployeeName` = '".$name."', `EmployeeHeadImg` = '".$img."' WHERE `EmployeeId` = '".$id."';";
            $sql = $sql_update . $sql_val;
            $result = $this->db->query($sql);
            return $result;
        }
    }

    private function setOutputData() {
        $dataAry = json_decode($this->userData,true);
        $result = array();
        foreach ($dataAry as $key=>$val) {
            $result[ucwords($key)] = $val;
        }
        $this->outputData = array("User"=>$result);
    }

    private function setSession() {
        if( $this->isPassed) {
            $json = $this->userData;
            $result = json_decode($json, true);

            $sessionId = session_id();
            if(empty($sessionId)){ session_start(); }
            $_SESSION['userInfo'] = $result;

            self::setReturnStates(TRUE,200,'SUCCESS');
        }
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

new VerifyUser($config);