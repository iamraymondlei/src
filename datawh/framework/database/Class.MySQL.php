<?php
/**
 * MyPDO
 * @author 
 * @license 
 * @version 1.0 utf8
 * @how to user
 * 
    $dbConfig = array('host'=>'localhost', 'dbname'=>'MallDB', 'username'=>'root', 'password'=>'123456');
    $db = MySQL::getInstance($dbConfig);
    $result = $db->query("select * from User;");
    print_r($result);
    $db->destruct();
 */
//ini_set('display_errors', 1);
//error_reporting(E_ALL);
//require "../../application/config/Config.php";
//try{
//    $dbConfig = array('host'=>'localhost', 'dbname'=>'MallDB', 'username'=>'root', 'password'=>'123456');
//    $db = MySQL::getInstance($dbConfig);
//    $result = $db->query("select * from User;");
//    print_r($result);
//    $db->destruct();
//} catch(Exception $e){ 
//    echo $e->getMessage(); //输出异常信息。 
//}
    
class MySQL {
    protected static $_instance = null;
    protected $dbh;
    
    /**
     * 获取数据库设定
     *
     * @return Array
     */
    private function getDBConfig($config) {
        $dbConfig = array();
        $dbConfig["host"]     = Config::G_HOST;
        $dbConfig["dbname"]   = Config::G_DBNAME;
        $dbConfig["username"] = Config::G_NAME;
        $dbConfig["password"] = Config::G_PSW;
        
        if( $config !== null && is_array($dbConfig) ) {
            if(isset($config['host']    )){ $dbConfig["host"]     = $config['host']; }
            if(isset($config['dbname']  )){ $dbConfig["dbname"]	  = $config['dbname']; }
            if(isset($config['username'])){ $dbConfig["username"] = $config['username']; }
            if(isset($config['password'])){ $dbConfig["password"] = $config['password']; }
        }
        return $dbConfig;
    }

    /**
     * 构造
     * 
     * @return PDO
     */
    private function __construct($config) {
        try {
            $dbConfig = $this->getDBConfig($config);
            $dsn = 'mysql:host=' . $dbConfig["host"] . ';dbname=' . $dbConfig["dbname"];
            $this->dbh = new PDO(
                $dsn, $dbConfig["username"], $dbConfig["password"], 
                array(
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8, character_set_connection=utf8, character_set_results=utf8, character_set_client=binary",
                    PDO::ATTR_PERSISTENT => TRUE,               // MYSQL 持久连接
                    PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => TRUE,	// 查询缓冲区
                    PDO::ATTR_ERRMODE => 0,			// 错误报告 0 , PDO::ERRMODE_EXCEPTION ， PDO::ERRMODE_WARNING
                    PDO::ATTR_EMULATE_PREPARES => TRUE		// 仿真PREPARES
                )
            );
            //$this->dbh->exec('SET character_set_connection=' . $dbCharset . ', character_set_results=' . $dbCharset . ', character_set_client=binary');
        } catch (PDOException $e) {
            $this->outputError($e->getMessage());
        }
    }

    /**
     * 防止克隆
     * 
     */
    private function __clone() {
        
    }

    /**
     * Singleton instance
     * 
     * @return Object
     */
    public static function getInstance($config=null) {
        if (self::$_instance === null) {
            self::$_instance = new self($config);
        }
        return self::$_instance;
    }

    /**
     * Query 查询
     *
     * @param String $strSql SQL语句
     * @param String $queryMode 查询方式(All or Row)
     * @param Boolean $debug
     * @return Array
     */
    public function query($strSql, $queryMode = 'All', $debug = false) {
        if ($debug === true){ $this->debug($strSql); }
        $recordset = $this->dbh->query($strSql);
        $this->writeLog($strSql);
        $this->getPDOError();
        if ($recordset) {
            $recordset->setFetchMode(PDO::FETCH_ASSOC);
            if ($queryMode == 'All') {
                $result = $recordset->fetchAll();
            } elseif ($queryMode == 'Row') {
                $result = $recordset->fetch();
            }
        } else {
            $result = null;
        }
        return $result;
    }

    /**
     * Update 更新
     *
     * @param String $table 表名
     * @param Array $arrayDataValue 字段与值
     * @param String $where 条件
     * @param Boolean $debug
     * @return Int
     */
    public function update($table, $arrayDataValue, $where = '', $debug = false) {
        $this->checkFields($table, $arrayDataValue);
        if ($where) {
            $strSql = '';
            foreach ($arrayDataValue as $key => $value) {
                $strSql .= ", `$key`='$value'";
            }
            $strSql = substr($strSql, 1);
            $strSql = "UPDATE `$table` SET $strSql WHERE $where";
        } else {
            $strSql = "REPLACE INTO `$table` (`" . implode('`,`', array_keys($arrayDataValue)) . "`) VALUES ('" . implode("','", $arrayDataValue) . "')";
        }
        if ($debug === true){ $this->debug($strSql); }
        $result = $this->dbh->exec($strSql);
        $this->writeLog($strSql);
        $this->getPDOError();
        return $result;
    }

    /**
     * Insert 插入
     *
     * @param String $table 表名
     * @param Array $arrayDataValue 字段与值
     * @param Boolean $debug
     * @return Int
     */
    public function insert($table, $arrayDataValue, $debug = false) {
        $this->checkFields($table, $arrayDataValue);
        $strSql = "INSERT INTO `$table` (`" . implode('`,`', array_keys($arrayDataValue)) . "`) VALUES ('" . implode("','", $arrayDataValue) . "')";
        if ($debug === true){ $this->debug($strSql); }
        $result = $this->dbh->exec($strSql);
        $this->writeLog($strSql);
        $this->getPDOError();
        return $result;
    }
    
    /**
     * Get last insert id
     */
    public function getInsertId(){
        return $this->dbh->lastInsertId();
    }

    /**
     * Replace 覆盖方式插入
     *
     * @param String $table 表名
     * @param Array $arrayDataValue 字段与值
     * @param Boolean $debug
     * @return Int
     */
    private function replace($table, $arrayDataValue, $debug = false) {
        $this->checkFields($table, $arrayDataValue);
        $strSql = "REPLACE INTO `$table`(`" . implode('`,`', array_keys($arrayDataValue)) . "`) VALUES ('" . implode("','", $arrayDataValue) . "')";
        if ($debug === true){ $this->debug($strSql); }
        $result = $this->dbh->exec($strSql);
        $this->writeLog($strSql);
        $this->getPDOError();
        return $result;
    }

    /**
     * Delete 删除
     *
     * @param String $table 表名
     * @param String $where 条件
     * @param Boolean $debug
     * @return Int
     */
    private function delete($table, $where = '', $debug = false) {
        if ($where == '') {
            $this->outputError("'WHERE' is Null");
        } else {
            $strSql = "DELETE FROM `$table` WHERE $where";
            if ($debug === true){ $this->debug($strSql); }
            $result = $this->dbh->exec($strSql);
            $this->writeLog($strSql);
            $this->getPDOError();
            return $result;
        }
    }

    /**
     * execSql 执行SQL语句
     *
     * @param String $strSql
     * @param Boolean $debug
     * @return Int
     */
    public function execSql($strSql, $debug = false) {
        if ($debug === true){ $this->debug($strSql); }
        $result = $this->dbh->exec($strSql);
        $this->writeLog($strSql);
        $this->getPDOError();
        return $result;
    }

    /**
     * 获取字段最大值
     * 
     * @param string $table 表名
     * @param string $field_name 字段名
     * @param string $where 条件
     */
    public function getMaxValue($table, $field_name, $where = '', $debug = false) {
        $strSql = "SELECT MAX(" . $field_name . ") AS MAX_VALUE FROM $table";
        if ($where != ''){ $strSql .= " WHERE $where"; }
        if ($debug === true){ $this->debug($strSql); }
        $arrTemp = $this->query($strSql, 'Row');
        $maxValue = $arrTemp["MAX_VALUE"];
        if ($maxValue == "" || $maxValue == null) {
            $maxValue = 0;
        }
        return $maxValue;
    }

    /**
     * 获取指定列的数量
     * 
     * @param string $table
     * @param string $field_name
     * @param string $where
     * @param bool $debug
     * @return int
     */
    public function getCount($table, $field_name, $where = '', $debug = false) {
        $strSql = "SELECT COUNT($field_name) AS NUM FROM $table";
        if ($where != ''){ $strSql .= " WHERE $where"; }
        if ($debug === true){ $this->debug($strSql); }
        $arrTemp = $this->query($strSql, 'Row');
        return $arrTemp['NUM'];
    }

    /**
     * 获取表引擎
     * 
     * @param String $dbName 库名
     * @param String $tableName 表名
     * @param Boolean $debug
     * @return String
     */
    public function getTableEngine($dbName, $tableName) {
        $strSql = "SHOW TABLE STATUS FROM $dbName WHERE Name='" . $tableName . "'";
        $arrayTableInfo = $this->query($strSql);
        $this->getPDOError();
        return $arrayTableInfo[0]['Engine'];
    }

    /**
     * beginTransaction 事务开始
     */
    private function beginTransaction() {
        $this->dbh->beginTransaction();
    }

    /**
     * commit 事务提交
     */
    private function commit() {
        $this->dbh->commit();
    }

    /**
     * rollback 事务回滚
     */
    private function rollback() {
        $this->dbh->rollback();
    }

    /**
     * transaction 通过事务处理多条SQL语句
     * 调用前需通过getTableEngine判断表引擎是否支持事务
     *
     * @param array $arraySql
     * @return Boolean
     */
    public function execTransaction($arraySql) {
        $retval = 1;
        $this->beginTransaction();
        foreach ($arraySql as $strSql) {
            //echo $strSql.PHP_EOL;
            if ($this->execSql($strSql) == 0){ $retval = 0; }
        }
        if ($retval == 0) {
            $this->rollback();
            return false;
        } else {
            $this->commit();
            return true;
        }
    }

    /**
     * checkFields 检查指定字段是否在指定数据表中存在
     *
     * @param String $table
     * @param array $arrayField
     */
    private function checkFields($table, $arrayFields) {
        $fields = $this->getFields($table);
        foreach ($arrayFields as $key => $value) {
            if (!in_array($key, $fields)) {
                $this->outputError("Unknown column `$key` in field list.");
            }
        }
    }

    /**
     * getFields 获取指定数据表中的全部字段名
     *
     * @param String $table 表名
     * @return array
     */
    public function getFields($table) {
        $fields = array();
        $recordset = $this->dbh->query("SHOW COLUMNS FROM $table");
        $this->getPDOError();
        $recordset->setFetchMode(PDO::FETCH_ASSOC);
        $result = $recordset->fetchAll();
        foreach ($result as $rows) {
            $fields[] = $rows['Field'];
        }
        return $fields;
    }

    /**
     * getPDOError 捕获PDO错误信息
     */
    private function getPDOError() {
        if ($this->dbh->errorCode() != '00000') {
            $arrayError = $this->dbh->errorInfo();
            $this->outputError($arrayError[2]);
        }
    }
    
    /**
     * writeLog 写日志
     */
    private function writeLog($sql) {
        $logPath = Config::$g_logPath;
        $enableLog = Config::$g_enableLog;
        
        if($enableLog === TRUE){
            $logDir = "$logPath/".date('Ymd');
            if(!file_exists($logDir)){ mkdir ($logDir, 0777);}
            
            $hasError = ($this->dbh->errorCode() === "00000" ? false : true);
            $strSql = (!$hasError ? '' : "##### ERROR: Next Query Is Fail #####\r\n" );
            $strSql.= '/* ' . date('Y-m-d H:i:s') . " */\r\n $sql \r\n";
            if ($hasError){
                $strSql.= print_r($this->dbh->errorInfo(), true).PHP_EOL;
            }
            $strSql .= "\r\n\r\n";

            if ($handle = fopen("$logDir/SQLLOG.SQL", 'a+')) {
                fwrite($handle, $strSql);
                fclose($handle);
            }
            if ($hasError && $handle = fopen("$logDir/ERRORSQL.SQL", 'a+')) {
                fwrite($handle, $strSql);
                fclose($handle);
            }
        }
    }

    /**
     * debug
     * 
     * @param mixed $debuginfo
     */
    private function debug($debuginfo) {
        var_dump($debuginfo);
        exit();
    }

    /**
     * 输出错误信息
     * 
     * @param String $strErrMsg
     */
    private function outputError($strErrMsg) {
        throw new Exception('MySQL Error: ' . $strErrMsg);
    }

    /**
     * destruct 关闭数据库连接
     */
    public function destruct() {
        $this->dbh = null;
    }
}
