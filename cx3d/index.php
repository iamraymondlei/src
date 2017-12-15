<?php
error_reporting(E_ALL);
ini_set('display_errors','On');

if(!isset($_SESSION)){
    session_start();
}

//应用目录为当前目录
define('WEB_PATH', __DIR__.'/');

//开启调试模式
define('WEB_DEBUG', true);

//加载框架文件
require(WEB_PATH . 'framework/core/Framework.Class.php');

//实例化框架类
Framework::run();