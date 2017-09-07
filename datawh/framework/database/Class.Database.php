<?php
/**
 * Description of DataBase
 *
 * @author icm
 * 
 * DatabaseFactory::factory();
 */
require_once(dirname(__FILE__) . '/Class.MySQL.php');

//ini_set('display_errors', 1);
//error_reporting(E_ALL);
//try{
//    $db = DatabaseFactory::factory();
//    $result = $db->query("select * from User;");
//    print_r($result);
//    $db->destruct();
//} catch(Exception $e){ 
//    echo $e->getMessage(); //输出异常信息。 
//}

class DatabaseFactory {
    public static function factory() {
        $type = "MySQL";//loadtypefromconfigfile();
        $db = null;
        switch($type) {
            case "MySQL": $db = MySQL::getInstance();
        }
        return $db;
    }
}
