<!DOCTYPE html>
<html lang="zh-CN">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
        <link rel="icon" href="<?php echo $rootDir; ?>public/images/favicon.ico">
        <title><?php echo $title; ?></title>

        <!-- jQuery (Bootstrap 的 JavaScript 插件需要引入 jQuery) -->
        <script src="<?php echo $rootDir; ?>public/plugins/jquery/jquery-1.12.0.min.js"></script>

        <!-- jQuery UI -->
        <script src="<?php echo $rootDir; ?>public/plugins/jquery/jquery-ui-1.11.4.min.js"></script>
        <link href="<?php echo $rootDir; ?>public/plugins/jquery/jquery-ui-1.11.4.min.css" rel="stylesheet">

        <!-- Bootstrap 3.3.5 -->
        <link href="<?php echo $rootDir; ?>public/css/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
        <script src="<?php echo $rootDir; ?>public/css/bootstrap/3.3.5/js/bootstrap.min.js"></script>

        <!-- 获取URL参数 -->
        <script src="<?php echo $rootDir; ?>public/plugins/urlParams/urlParams-1.0.0.js"></script>
        <!-- 包括所有ajax请求 -->
        <script src="<?php echo $rootDir; ?>public/js/ajax.js"></script>
        <script src="<?php echo $rootDir; ?>public/js/htmlUtil.js"></script>
        <script src="<?php echo $rootDir; ?>public/js/httpUtil.js"></script>
        <!-- 包括所有prototype扩展方法 -->
        <script src="<?php echo $rootDir; ?>public/js/prototype.js"></script>

        <!-- 图标CSS -->
        <link href="<?php echo $rootDir; ?>public/css/font-awesome/font-awesome.min.css" rel="stylesheet">
        <!-- 主题CSS -->
        <link href="<?php echo $rootDir; ?>public/css/theme/styles.css" rel="stylesheet">

        <!-- bootstrap dialog 1.34.7 from:https://github.com/nakupanda/bootstrap3-dialog, demo:https://nakupanda.github.io/bootstrap3-dialog/ -->
        <link href="<?php echo $rootDir; ?>public/plugins/dialog/1.34.7/css/bootstrap-dialog.css" rel="stylesheet">
        <script src="<?php echo $rootDir; ?>public/plugins/dialog/1.34.7/js/bootstrap-dialog.js"></script>

        <!-- HTML5 Shim 和 Respond.js 用于让 IE8 支持 HTML5元素和媒体查询 -->
        <!-- 注意： 如果通过 file://  引入 Respond.js 文件，则该文件无法起效果 -->
        <!--[if lt IE 9]>
        <script src="//cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->

        <!-- 首页对应js -->
        <script src="<?php echo $rootDir; ?>application/views/backend/home/js/index.js"></script>
        <!-- cookie 1.4.1 插件 -->
        <script src="<?php echo $rootDir; ?>public/plugins/cookie/jquery.cookie-1.4.1.js"></script>
        <!-- validator -->
        <script src="<?php echo $rootDir; ?>public/plugins/validator/validator.min.js"></script>
        <!-- Custom styles for this page -->
        <link href="<?php echo $rootDir; ?>application/views/backend/home/css/index.css" rel="stylesheet">
    </head>
    <body class="indexBg">