<?php
header('Access-Control-Allow-Origin: *');
date_default_timezone_set("PRC");
ini_set('user_agent', 'Mozilla/5.0 (Windows NT 6.1; rv:13.0) Gecko/20100101 Firefox/13.0');
//错误显示
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once (dirname(__FILE__) . "/Config.php");
require_once (dirname(__FILE__) . "/../../framework/database/Class.Database.php");

/**
 * Description of WsConfig
 *
 * @author icm
 */
class WsConfig extends Config {
    //签名服务
    public static $signUrl="https://data.goqo.com.cn/signature/ws/signxml.php";
    //是否验证goqoId, goqoModel, goqoVer
    public static $goqoIdValidation = TRUE;
    //外部服务的根节点名
    public static $outputWsRootTagName = "WebService";
    //日志路径
    public static $logPath = "/var/goqolog/datawh";
    
    public static function includeClass() {
        $helpersFiles = self::getFilesFromDir(dirname(__FILE__)."/../../ws/helpers","php");
        $baseClassFiles = self::getFilesFromDir(dirname(__FILE__)."/../../ws/baseClass","php");
        $interfaceFiles = self::getFilesFromDir(dirname(__FILE__)."/../../ws/interface","php");
        $includeFiles = array_merge($helpersFiles,$baseClassFiles,$interfaceFiles);
        foreach($includeFiles as $file){
            //echo $file.PHP_EOL;
            require_once ($file);
        }
    }

    /* 
     * @todo 查找指定目录下的文件
     * @param String $scanDir 查找路径
     * @param String $fileType 文件类型 ( 文件后缀名, 默认为all )
     * @return Array
     */
    private static function getFilesFromDir($scanDir, $fileType='all') {
        $result = array();
        if (isset($scanDir) && is_dir($scanDir)) {
            $fileList = scandir($scanDir); //列出指定路径中的文件和目录
            foreach ($fileList as $file) {
                if ($file === "." || $file === "..") {
                    continue;
                }
                else{
                    $filePath = $scanDir.DIRECTORY_SEPARATOR.$file;
                    $temp = is_dir($filePath) ? self::getFilesFromDir($filePath, $fileType) : self::filterFileType($filePath, $fileType);
                    $result = ($temp)?array_merge($result, $temp):$result;
                }
            }
        }
        else{
            $result = false;
        }
        return $result;
    }
    
    private static function filterFileType($file,$fileType) {
        $result = array();
        $length = strlen($fileType)+1;
        if ( substr($file,-$length) == "." . $fileType || $fileType == 'all'){
            $result[] = $file;
        }
        return $result;
    }
}

WsConfig::includeClass();