<?php
header('Access-Control-Allow-Origin: *');
date_default_timezone_set("PRC");
ini_set('user_agent', 'Mozilla/5.0 (Windows NT 6.1; rv:13.0) Gecko/20100101 Firefox/13.0');
//错误显示
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once (dirname(__FILE__) . "/Config.php");
require_once (dirname(__FILE__) . "/../../framework/database/Class.Database.php");

class WebConfig extends Config {
    //put your code here
}
