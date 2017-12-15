<?php
date_default_timezone_set("PRC");
ini_set('user_agent', 'Mozilla/5.0 (Windows NT 6.1; rv:13.0) Gecko/20100101 Firefox/13.0');
ini_set("memory_limit","2048M");
set_time_limit (0);

// application specific constants
if(!defined('PROJ_DIR')) define('PROJ_DIR', dirname(__FILE__));
if(!defined('SESSION_PREFIX')) define('SESSION_PREFIX', null);

//数据库连接配置
$config['db']['host'] = '127.0.0.1';
$config['db']['username'] = 'root';
$config['db']['password'] = 'goqoicm';
$config['db']['dbname'] = 'ChuXiong3D';
$config['db']['port'] = '3306';
//数据库日志文件配置
$config['db']['serviceCode'] = 'CX3D';
$config['db']['enableLog'] = true;
$config['db']['logPath'] = "/var/goqolog/chuxiong3d";

// 默认控制器和操作名
$config['defaultController'] = 'home';
$config['defaultAction'] = 'index';
$config['defaultPlatform'] = 'backend';

//分页大小配置
$config['pageIndex'] = '1';
$config['pageSize'] = '50';
$config['maxPageSize'] = '1000';

//上传文件配置
$config['thumbImageSize'] = 512;
$config['filePhysicalPath'] = "/chuxiong3d/files/";
$config['filePath'] = "../files/";
$config['thumbPotSize'] = 512;

//接口服务根节点名
$config['outputWsRootTagName'] = "WebService";

//预加载图片百分比
$config['preloadPercent'] = 0.5;

//统计分析设定
$config['analysis']['urlPrefix'] = "http://data.qojet.local/piwik/index.php";
$config['analysis']['token_auth'] = "850349c433b69816bde9dd43367dac11";
$config['analysis']['pageUrl'] = array(
    "home" => urlencode("http://data.qojet.local/chuxiong3d/index.html"),//總展廳
    "p1" => urlencode("http://data.qojet.local/chuxiong3d/application/views/frontend/page1.html"),//展廳1
    "p2" => urlencode("http://data.qojet.local/chuxiong3d/application/views/frontend/page2.html"),//展廳2
    "p3" => urlencode("http://data.qojet.local/chuxiong3d/application/views/frontend/page3.html")//展廳3
);
$config['analysis']['user'] = array(
    "1"=>array("title"=>"访客","url"=>$config['analysis']['urlPrefix']."?module=Widgetize&action=iframe&disableLink=1&widget=1&token_auth=".$config['analysis']['token_auth']."&idSite=1&period=range&columns[]=nb_visits&evolutionDay=&segment="),
    "2"=>array("title"=>"回访","url"=>$config['analysis']['urlPrefix']."?module=Widgetize&action=iframe&disableLink=1&widget=1&token_auth=".$config['analysis']['token_auth']."&idSite=1&period=range&columns[]=nb_visits_returning&moduleToWidgetize=VisitFrequency&actionToWidgetize=getEvolutionGraph&evolutionDay=&segment=")
);
$config['analysis']['scene'] = array(
    "1"=>array("title"=>"总展厅","url"=>$config['analysis']['urlPrefix']."?module=Widgetize&action=iframe&disableLink=1&widget=1&token_auth=".$config['analysis']['token_auth']."&idSite=1&period=range&columns[]=nb_visits&moduleToWidgetize=VisitsSummary&actionToWidgetize=getEvolutionGraph&evolutionDay=&segment=pageUrl%3D%3D".$config['analysis']['pageUrl']['home']),
    "2"=>array("title"=>"展厅1","url"=>$config['analysis']['urlPrefix']."?module=Widgetize&action=iframe&disableLink=1&widget=1&token_auth=".$config['analysis']['token_auth']."&idSite=1&period=range&columns[]=nb_visits&moduleToWidgetize=VisitsSummary&actionToWidgetize=getEvolutionGraph&evolutionDay=&segment=pageUrl%3D%3D".$config['analysis']['pageUrl']['p1']),
    "3"=>array("title"=>"展厅2","url"=>$config['analysis']['urlPrefix']."?module=Widgetize&action=iframe&disableLink=1&widget=1&token_auth=".$config['analysis']['token_auth']."&idSite=1&period=range&columns[]=nb_visits&moduleToWidgetize=VisitsSummary&actionToWidgetize=getEvolutionGraph&evolutionDay=&segment=pageUrl%3D%3D".$config['analysis']['pageUrl']['p2']),
    "4"=>array("title"=>"展厅3","url"=>$config['analysis']['urlPrefix']."?module=Widgetize&action=iframe&disableLink=1&widget=1&token_auth=".$config['analysis']['token_auth']."&idSite=1&period=range&columns[]=nb_visits&moduleToWidgetize=VisitsSummary&actionToWidgetize=getEvolutionGraph&evolutionDay=&segment=pageUrl%3D%3D".$config['analysis']['pageUrl']['p3'])
);
$config['analysis']['map'] = array(
    "1"=>array("title"=>"总展厅","url"=>$config['analysis']['urlPrefix']."?module=Widgetize&action=iframe&disableLink=1&widget=1&token_auth=".$config['analysis']['token_auth']."&idSite=1&period=range&moduleToWidgetize=UserCountryMap&actionToWidgetize=visitorMap&evolutionDay=&segment=pageUrl%3D%3D".$config['analysis']['pageUrl']['home']),
    "2"=>array("title"=>"展厅1","url"=>$config['analysis']['urlPrefix']."?module=Widgetize&action=iframe&disableLink=1&widget=1&token_auth=".$config['analysis']['token_auth']."&idSite=1&period=range&moduleToWidgetize=UserCountryMap&actionToWidgetize=visitorMap&evolutionDay=&segment=pageUrl%3D%3D".$config['analysis']['pageUrl']['p1']),
    "3"=>array("title"=>"展厅2","url"=>$config['analysis']['urlPrefix']."?module=Widgetize&action=iframe&disableLink=1&widget=1&token_auth=".$config['analysis']['token_auth']."&idSite=1&period=range&moduleToWidgetize=UserCountryMap&actionToWidgetize=visitorMap&evolutionDay=&segment=pageUrl%3D%3D".$config['analysis']['pageUrl']['p2']),
    "4"=>array("title"=>"展厅3","url"=>$config['analysis']['urlPrefix']."?module=Widgetize&action=iframe&disableLink=1&widget=1&token_auth=".$config['analysis']['token_auth']."&idSite=1&period=range&&moduleToWidgetize=UserCountryMap&actionToWidgetize=visitorMap&evolutionDay=&segment=pageUrl%3D%3D".$config['analysis']['pageUrl']['p3'])
);

$config["rootDir"] = "http://data.qojet.local/chuxiong3d/";
$config['outputImagePrefix'] = "http://data.qojet.local/chuxiong3d/files";
$config['IsTestVerifyUser'] = TRUE;

return $config;