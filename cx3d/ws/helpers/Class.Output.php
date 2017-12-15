<?php
class Output {
    public static function printData($outputData,$outputFormat,$rootNodeName){
    	$result = '';
        if( $outputFormat == "xml" ){
            $result = Array2XML::createXML($rootNodeName, $outputData);
            $result = $result->saveXML();
            $result = trim($result);
            //$result = self::addSignToXML($result);
            header("Content-type: text/xml; charset=utf-8");
        }
        elseif( $outputFormat == "json" ){
            $data = array($rootNodeName=>$outputData);
            $result = json_encode($data);
            header('Content-type: application/json; charset=utf-8');
        }

        echo $result;
    }

    public static function printXMLData($xmlContents){
        header("Content-type: text/xml; charset=utf-8");
        echo $xmlContents;
    }
    
    public static function wsData($outputParam) {
        $errCode = (isset($outputParam["errorCode"]))?$outputParam["errorCode"]:200;
        $errMsg = (isset($outputParam["errorMessage"]))?$outputParam["errorMessage"]:"";
        $errAction = (isset($outputParam["errorAction"]))?$outputParam["errorAction"]:"unknow service"; 
        $rootTab = (isset($outputParam["rootTab"]))?$outputParam["rootTab"]:"None";
        $format = (isset($outputParam["format"]))?$outputParam["format"]:"xml";
        $exLogMsg = (isset($outputParam["exLogMsg"]))?$outputParam["exLogMsg"]:array();
        $config = (isset($outputParam["config"]))?$outputParam["config"]:array();
                
        $result = false;
        if (class_exists('SummaryLog')) {
            SummaryLog::Enable(true);
            SummaryLog::SetFields(array('Time', 'ServiceName', 'Result', 'ErrMsg',));
            SummaryLog::Set('ServiceName', $errAction);
            SummaryLog::Set('ErrMsg', $errMsg);
            SummaryLog::SetConfig($config);
            SummaryLog::WriteWSSummaryLog("ALL", $errCode);
        }

        //Common::WriteWSLog($errCode, $errMsg, $errAction, $exLogMsg);
        $data = static::addHeadInfo($outputParam);
        header('Access-Control-Allow-Origin:*');
        if ($format === "json") {
            $data = array($rootTab => $data);
            $result = json_encode($data);
            header('Content-type: application/json; charset=utf-8');
        } else {
            $result = Array2XML::createXML($rootTab, $data);
            $result = $result->saveXML();
            //$result = static::addSignToXML(trim($result));
            header("Content-type: text/xml; charset=utf-8");
        }
        exit($result);
    }
    
    private static function addSignToXML($p_xmlstr) {
        $result = $p_xmlstr;
        if (XMLUtil::isXML($p_xmlstr)) {
            $requestResult = HttpGet::Request(WsConfig::$signUrl, array("xmlstr"=>$p_xmlstr));
            $httpCode = $requestResult["status"];
            if ($httpCode == 200){
                $result = $requestResult["body"];
            }
            else{
                $result = false;
            }
        }
        return $result;
    }
    
    private static function addHeadInfo($outputParam) {
        $resultCode = (isset($outputParam["errorCode"]))?$outputParam["errorCode"]:200;
        $errMsg = (isset($outputParam["errorMessage"]))?$outputParam["errorMessage"]:"";
        $errAction = (isset($outputParam["errorAction"]))?$outputParam["errorAction"]:"unknow service"; 
        $dataAry = (isset($outputParam["data"]))?$outputParam["data"]:array();
        $returnResult = (isset($outputParam["returnResult"]))?$outputParam["returnResult"]:"nodata";
        $pollingFrequency = (isset($outputParam["pollingFrequency"]))?$outputParam["pollingFrequency"]:0;
        $lastPage = (strtolower($returnResult) === 'nodata')?"yes":"no";
                
        $result = array();
        $datetimestamp = DatetimeUtil::getDateTimeStamp(); //取当前时间
        $result["@attributes"] = array("datetimestamp"=>$datetimestamp);//, "result"=>$returnResult, "lastpage"=>$lastPage,"pollingFrequency"=>$pollingFrequency, "signature"=>""
        $result["Service"] = $errAction;
        $result["ResultCode"] = $resultCode;
        $result["ResultMessage"] = (empty($errMsg) && $resultCode !== 200)?HttpError::GetErrorMessage($resultCode):$errMsg;
        //$result['TraceCode'] = (isset(Common::$enableTraceCode))?Common::getTraceCode():"";
        
        return array_merge($result,$dataAry);
    }
}