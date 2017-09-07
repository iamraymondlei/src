<?php
/*=================================
//调用说明
$mysql = new MysqlHelper();
$tableName = 'User';
$db = DatabaseFactory::factory();

//直接执行sql语句
$sql = "show tables";
$res = $mysql->doSql($sql);

//查询1
$mysql->field(array('UserId','UserName','LastUpdate'))
$mysql->order(array('LastUpdate'=>'desc','UserId'=>'asc'))
$mysql->where(array('UserName'=>"ray",'UserId'=>array('1','>','or')))
$mysql->limit(1,2)
$res = $mysql->select($tableName);

//查询2
$mysql->field('UserId,UserName,LastUpdate')
$mysql->order('LastUpdate desc,UserId asc')
$mysql->where('UserName=ray or UserId>1')
$mysql->limit(1,2)
$res = $mysql ->select($tableName);

//插入
$insertData = array('name'=>'ray','Gender'=>'Man' );
$mysql->insert($db,$tableName,$insertData);

//修改
$updateData = array('name'=>'raymond','Gender'=>'Male' );
$mysql->where(array('UserId'=>1));
$mysql->update($db,$tableName,$updateData);

//删除
$mysql->where(array('UserId'=>1))
$mysql->delete($db,$tableName);

//获取最后执行的sql语句
$sql = $mysql->getLastSql();

//事务调用
$sqlAry = array("update User set User.UserName = 'a' where User.UserId = 2;","delete from User where User.UserId = 4;");
$res = $mysql->execTrans($this->db,$sqlAry);
===========================================*/

class MySQLHelper {
    protected $sql = FALSE; //最后一条sql语句
    protected $sqlWhere = '';
    protected $sqlOrder = '';
    protected $sqlLimit = '';
    protected $sqlField = '*';
    protected $clear = TRUE; //状态，TRUE表示查询条件干净，FALSE表示查询条件污染
    
    /**
     * 初始化类
     * @param array $conf 数据库配置
     */
    public function __construct() {

    }
    
    /** 
    * 字段和表名添加 `符号
    * 保证指令中使用关键字不出错 针对mysql 
    * @param string $value 
    * @return string 
    */
    protected function addChar($value) { 
        if ('*'==$value || false!==strpos($value,'(') || false!==strpos($value,'.') || false!==strpos($value,'`')) { 
            //如果包含* 或者 使用了sql方法 则不作处理 
        } elseif (false === strpos($value,'`') ) { 
            $value = '`'.trim($value).'`';
        } 
        return $value; 
    }
    
    /** 
    * 取得数据表的字段信息 
    * @param string $tbName 表名
    * @return array 
    */
    protected function getTableFields($db,$tbName) {
        $result = $db->getFields($tbName);
        $ret = array();
        foreach ($result as $key=>$value) {
            $ret[$value] = 1;//$ret[$value['COLUMN_NAME']] = 1;
        }
        return $ret;
    }
 
    /** 
    * 过滤并格式化数据表字段
    * @param string $tbName 数据表名 
    * @param array $data POST提交数据 
    * @return array $newdata 
    */
    protected function dataFormat($db,$tbName,$data) {
        if (!is_array($data)) return array();
        $table_column = $this->getTableFields($db,$tbName);
        $ret=array();
        foreach ($data as $key=>$val) {
            if (!is_scalar($val)) continue; //值不是标量则跳过
            if (array_key_exists($key,$table_column)) {
                $key = $this->addChar($key);
                if (is_int($val)) { 
                    $val = intval($val); 
                } elseif (is_float($val)) { 
                    $val = floatval($val); 
                } elseif (preg_match('/^\(\w*(\+|\-|\*|\/)?\w*\)$/i', $val)) {
                    // 支持在字段的值里面直接使用其它字段 ,例如 (score+1) (name) 必须包含括号
                    $val = $val;
                } elseif (is_string($val)) { 
                    $val = '"'.addslashes($val).'"';
                }
                $ret[$key] = $val;
            }
        }
        return $ret;
    }
     
    /**
    * 执行查询 主要针对 SELECT, SHOW 等指令
    * @param string $sql sql指令 
    * @return array 
    */
    protected function doQuery($db,$sql='') {
        try{            
            $this->sql = $sql;
            return array("state"=>200,"sql"=>$sql,"body"=>$db->query($sql));
        }
        catch(Exception $ex){
            return array("state"=>500,"sql"=>$sql,"body"=>$ex);
        }
    }
     
    /** 
    * 执行语句 针对 INSERT, UPDATE 以及DELETE,exec结果返回受影响的行数
    * @param string $sql sql指令 
    * @return array 
    */
    protected function doExec($db,$sql='') {
        try{  
            $this->sql = $sql;
            return array("state"=>200,"sql"=>$sql,"body"=>$db->execSql($sql));
        }
        catch(Exception $ex){
            return array("state"=>500,"sql"=>$sql,"body"=>$ex);
        }
    }
 
    /** 
    * 执行sql语句，自动判断进行查询或者执行操作 
    * @param string $sql SQL指令 
    * @return mixed 
    */
    public function doSql($db,$sql='') {
        $queryIps = 'INSERT|UPDATE|DELETE|REPLACE|CREATE|DROP|LOAD DATA|SELECT .* INTO|COPY|ALTER|GRANT|REVOKE|LOCK|UNLOCK'; 
        if (preg_match('/^\s*"?(' . $queryIps . ')\s+/i', $sql)) { 
            return $this->doExec($db,$sql);
        }
        else {
            //查询操作
            return $this->doQuery($db,$sql);
        }
    }
 
    /** 
    * 获取最近一次查询的sql语句 
    * @return String 执行的SQL 
    */
    public function getLastSql() { 
        return $this->sql;
    }
 
    /**
     * 插入方法
     * @param string $tbName 操作的数据表名
     * @param array $data 字段-值的一维数组
     * @return int 受影响的行数
     */
    public function insert($db,$tbName,array $data){
        $data = $this->dataFormat($db,$tbName,$data);
        if (!$data) return;
        $sql = "insert into ".$tbName."(".implode(',',array_keys($data)).") values(".implode(',',array_values($data)).")";
        return $this->doExec($db,$sql);
    }
 
    /**
     * 删除方法
     * @param string $tbName 操作的数据表名
     * @return int 受影响的行数
     */
    public function delete($db,$tbName) {
        //安全考虑,阻止全表删除
        if (!trim($this->sqlWhere)) return false;
        $sql = "delete from ".$tbName." ".$this->sqlWhere;
        $this->clear = FALSE;
        $this->clearAll(); 
        return $this->doExec($db,$sql);
    }
  
    /**
     * 更新函数
     * @param string $tbName 操作的数据表名
     * @param array $data 参数数组
     * @return int 受影响的行数
     */
    public function update($db,$tbName,array $data) {
        //安全考虑,阻止全表更新
        if (!trim($this->sqlWhere)) return false;
        $data = $this->dataFormat($db,$tbName,$data);
        if (!$data) return;
        $valArr = '';
        foreach($data as $k=>$v){
            $valArr[] = $k.'='.$v;
        }
        $valStr = implode(',', $valArr);
        $sql = "update ".trim($tbName)." set ".trim($valStr)." ".trim($this->sqlWhere);
        return $this->doExec($db,$sql);
    }
  
    /**
     * 查询函数
     * @param string $tbName 操作的数据表名
     * @return array 结果集
     */
    public function select($db,$tbName='') {
        $sql = "select ".trim($this->sqlField)." from ".$tbName." ".trim($this->sqlWhere)." ".trim($this->sqlOrder)." ".trim($this->sqlLimit);
        $this->clear = FALSE;
        $this->clearAll(); 
        return $this->doQuery($db,trim($sql));
    }
  
    /**
     * @param mixed $option 组合条件的二维数组，例：$option['field1'] = array(1,'=>','or')
     * @return $this
     */
    public function where($option) {
        if (!$this->clear){ $this->clearAll(); }
        $this->sqlWhere = ' where ';
        $logic = 'and';
        if (is_string($option)) {
            $this->sqlWhere .= $option;
        }
        elseif (is_array($option)) {
            foreach($option as $k=>$v) {
                if (is_array($v)) {
                    $relative = isset($v[1]) ? $v[1] : '=';
                    $logic    = isset($v[2]) ? $v[2] : 'and';
                    $condition = ' ('.$this->addChar($k).' '.$relative.' \''.$v[0].'\') ';
                }
                else {
                    $logic = 'and';
                    $condition = ' ('.$this->addChar($k).'=\''.$v.'\') ';
                }
                $this->sqlWhere .= isset($mark) ? $logic.$condition : $condition;
                $mark = 1;
            }
        }
        
        return $this;
    }
  
    /**
     * 设置排序
     * @param mixed $option 排序条件数组 例:array('sort'=>'desc')
     * @return $this
     */
    public function order($option) {
        if (!$this->clear){ $this->clearAll(); }
        $this->sqlOrder = ' order by ';
        if (is_string($option)) {
            $this->sqlOrder .= $option;
        }
        elseif (is_array($option)) {
            foreach($option as $k=>$v){
                $order = $this->addChar($k).' '.$v;
                $this->sqlOrder .= isset($mark) ? ','.$order : $order;
                $mark = 1;
            }
        }
        return $this;
    }
  
    /**
     * 设置查询行数及页数
     * @param int $page pageSize不为空时为页数，否则为行数
     * @param int $pageSize 为空则函数设定取出行数，不为空则设定取出行数及页数
     * @return $this
     */
    public function limit($page,$pageSize=null) {
        if (!$this->clear){ $this->clearAll(); }
        if ($pageSize===null) {
            $this->sqlLimit = "limit ".$page;
        }
        else {
            $pageval = intval( ($page - 1) * $pageSize);
            $this->sqlLimit = "limit ".$pageval.",".$pageSize;
        }
        return $this;
    }
  
    /**
     * 设置查询字段
     * @param mixed $field 字段数组
     * @return $this
     */
    public function field($field){
        if (!$this->clear){ $this->clearAll(); }
        if (is_string($field)) {
            $field = explode(',', $field);
        }
        $nField = array_map(array($this,'addChar'), $field);
        $this->sqlField = implode(',', $nField);
        return $this;
    }
  
    /**
     * 手动清理标记
     * @return $this
     */
    public function clearKey() {
       $this->clearAll(); 
        return $this;
    }
     
    /**
     * 清理标记函数
     * $dataCount = $db->query($sql);
     */
    protected function clearAll() {
        $this->sqlWhere = '';
        $this->sqlOrder = '';
        $this->sqlLimit = '';
        $this->sqlField = '*';
        $this->clear = TRUE;
    }
    
    /**
     * transaction 通过事务处理多条SQL语句
     * 调用前需通过getTableEngine判断表引擎是否支持事务
     *
     * @param array $arraySql
     * @return Boolean
     */
    public function execTrans($db,$sqlAry) { 
        return $db->execTransaction($sqlAry);
    }
}