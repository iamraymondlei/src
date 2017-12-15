<?php
/**
 * Description of Model
 *
 * @author raymond lui
 */
class Model {
    protected $db; //database connection object
    protected $table; //table name
    protected $tableField;
    public static $config = [];

    public function __construct($table)
    {
        try{
            $this->table = $table;
            $this->db = DatabaseFactory::factory(self::$config["db"]);
            $this->getFields();
        } catch(Exception $e){
            echo $e->getMessage(); //输出异常信息。
        }
    }

    /**
     * Get the list of table fields
     *
     */
    private function getFields(){
        $sql = "DESC ". $this->table;
        $result = $this->db->query($sql);
        foreach ($result as $v) {
            $this->tableField[] = $v['Field'];
            if ($v['Key'] == 'PRI') {
                // If there is PK, save it in $pk
                $pk = $v['Field'];
            }
        }

        // If there is PK, add it into fields list
        if (isset($pk)) {
            $this->tableField['pk'] = $pk;
        }
    }

    /**
     * destruct db
     */
    public function destruct() {
        $this->db->destruct();
    }

    /**
     * add image url prefix
     */
    public function setImagePrefix($imageUrl) {
        if(substr($imageUrl,0,7) != "http://" && substr($imageUrl,0,8) != "https://"){
            return self::$config["outputImagePrefix"].$imageUrl;
        }
        else{
            return $imageUrl;
        }
    }

    /**
     * add image url prefix
     */
    public function removeImagePrefix($imageUrl) {
        if(stripos($imageUrl,self::$config["outputImagePrefix"]) == 0){
            $imageUrl = str_replace(self::$config["outputImagePrefix"],"",$imageUrl);
        }

        if(stripos($imageUrl,self::$config['filePhysicalPath']) == 0){
            $imageUrl = str_replace(self::$config["filePhysicalPath"],"/",$imageUrl);
        }

        return $imageUrl;
    }

    /**
     * 移除html tag
     * @param string
     * @return string
     */
    public function removeHtmlTag($str){
        $str = trim($str);
        $str = strip_tags($str,"");
        $str = str_replace("\t","",$str);
        $str = str_replace("\r","",$str);
        $str = str_replace("\n","",$str);
        $str = str_replace(" "," ",$str);
        $str = str_replace("\/","/",$str);
        return trim($str);
    }
}
