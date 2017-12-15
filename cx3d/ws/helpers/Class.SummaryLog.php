<?php
require_once('../config/config.php');
require_once('Class.Log.php');

class SummaryLog {

    protected static $enabled = false;
    protected static $serviceName = '';
    protected static $fields = array();
    protected static $data = array();
    protected static $filenamePrefix = 'Summary';
    protected static $config = [];

    public static function Enable($enabled) {
        if ($enabled === true) {
            static::$enabled = true;
        } else {
            static::$enabled = false;
        }
    }

    public static function SetConfig($config) {
        static::$config = $config;
    }

    public static function SetServiceName($serviceName) {
        static::$serviceName = $serviceName;
    }

    public static function SetFields($fields) {
        if (is_array($fields) && !empty($fields)) {
            static::$fields = $fields;
        } else {
            throw new InvalidArgumentException("SummaryLog: Invalid fields input.");
        }
    }

    public static function Set($name, $value) {
        static::$data[$name] = $value;
    }

    public static function WriteSummaryLog() {
        if (!static::$enabled)
            return;
        if (empty(static::$fields))
            return;

        $serviceName = static::$serviceName;

        $log = new Log();
        $logPath = self::$config['db']['logPath'] ;//WsConfig::$logPath;
        // $errCodeStr = '';
        // if(!empty($errCode)) $errCodeStr = "-$errCode";
        // $log->OpenDateFile($g_logPath, '', '-'.Common::GetRealIp()."-$errAction$errCodeStr");
        $dateStr = date('Ymd');
        $filenamePrefix = static::$filenamePrefix;
        // echo __CLASS__.' '.$filenamePrefix;
        $filepath = "$logPath/$dateStr/{$filenamePrefix}-$serviceName.log";
        $log->Open($filepath);
        if ($log->IsNewFile()) {
            // $line = implode(', ', $summaryFields);
            $log->WriteCsv(static::$fields);
        }
        $log->WriteCsv(static::FormatCsvFieldData(static::$fields, static::$data));
        // $log->WriteLine("test");
        // $log->WriteMsg($errAction , "-");
        // $log->WriteSessionStart();
        // $log->WriteMsg( "ResultCode:$errCode", "-");
        // $log->WriteMsg( "ResultMessage:$errMsg", "");
        // if( !empty($otherMsg) ) $log->WriteMsg( print_r($otherMsg, true), "-");
        // if(!empty(static::$ExtraLogMessages)) {
        // 	if(!empty(static::$ExtraLogMessages[static::LOG_INFO])) {
        // 		$log->WriteMsg( "Extra Info:", "-");
        // 		foreach(static::$ExtraLogMessages[static::LOG_INFO] as $msg) {
        // 			$log->WriteMsg( print_r($msg, true), '');
        // 		}
        // 	}
        // }
        // if(!empty(static::$ExtraLogMessages)) {
        // 	if(!empty(static::$ExtraLogMessages[static::LOG_WARN])) {
        // 		$log->WriteMsg( "Warnings:", "-");
        // 		foreach(static::$ExtraLogMessages[static::LOG_WARN] as $msg) {
        // 			$log->WriteMsg( print_r($msg, true), '');
        // 		}
        // 	}
        // }
        $log->Close();
    }

    /**
     * Common::WriteWSLogSummary()
     * 记录WS访问日志
     * @param string $errCode
     * @param string $errMsg
     * @param string $errAction
     * @param string $otherMsg
     * @return string
     */
    public static function WriteWSSummaryLog($serviceName, $errCode) {
        if (!static::$enabled)
            return;
        // if($errCode == 200 && static::HasExtraWarningLog()) $errCode = 202;
        // fill extra fields
        self::Set('Time', date('H:i:s'));
        self::Set('Result', $errCode);
        static::$serviceName = $serviceName;

        self::WriteSummaryLog();
    }

    public static function FormatCsvFieldData($fields, $data) {
        if (!is_array($fields))
            return null;
        $result = array();
        foreach ($fields as $field) {
            if (isset($data[$field])) {
                $result[] = $data[$field];
            } else {
                $result[] = '';
            }
        }

        return $result;
    }

}
