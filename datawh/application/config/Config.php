<?php
date_default_timezone_set("PRC");
ini_set('user_agent', 'Mozilla/5.0 (Windows NT 6.1; rv:13.0) Gecko/20100101 Firefox/13.0');
ini_set("memory_limit","2048M");
set_time_limit (0);
//错误显示
//ini_set('display_errors', 1);
error_reporting(E_ALL);

// application specific constants
if(!defined('PROJ_DIR')) define('PROJ_DIR', dirname(__FILE__));
if(!defined('SESSION_PREFIX')) define('SESSION_PREFIX', null);

class Config {	
	//数据库连接
	const G_HOST = "localhost";
	const G_NAME = "root";
	const G_PSW = "123456";
	const G_DBNAME = "DataWH";
	const G_PORT = 3306;
        
	public static $g_pageSize = '50';
        public static $g_pageSizeMax = '1000';
        public static $g_page = '1';
        public static $g_thumbPotSize=512;
                
	public static $g_take_file_path = "/datawh/files/"; // 上传图片服务路径
	public static $g_upload_file_path = "../files/";           //取图片路径
	        	
        public static $g_serviceCode = 'EXD';
        public static $g_enableLog = true;
        public static $g_logPath = "/var/goqolog/datawh";
}
