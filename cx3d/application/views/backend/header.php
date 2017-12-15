<!DOCTYPE html>
<html lang="zh-CN">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
        <link rel="icon" href="<?php echo $rootDir; ?>images/favicon.ico">
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

        <!-- bootstrap dialog 1.34.7 from:https://github.com/nakupanda/bootstrap3-dialog, demo:https://nakupanda.github.io/bootstrap3-dialog/ -->
        <link href="<?php echo $rootDir; ?>public/plugins/dialog/1.34.7/css/bootstrap-dialog.css" rel="stylesheet">
        <script src="<?php echo $rootDir; ?>public/plugins/dialog/1.34.7/js/bootstrap-dialog.js"></script>

        <!-- CSS to style the file input field as button and adjust the Bootstrap progress bars -->
        <link rel="stylesheet" href="<?php echo $rootDir; ?>public/plugins/fileupload/9.12.1/css/jquery.fileupload.css">
        <!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
        <script src="<?php echo $rootDir; ?>public/plugins/fileupload/9.12.1/js/vendor/jquery.ui.widget.js"></script>
        <!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
        <script src="<?php echo $rootDir; ?>public/plugins/fileupload/9.12.1/js/jquery.iframe-transport.js"></script>
        <!-- The basic File Upload plugin. wiki:https://github.com/blueimp/jQuery-File-Upload/wiki/Basic-plugin -->
        <script src="<?php echo $rootDir; ?>public/plugins/fileupload/9.12.1/js/jquery.fileupload.js"></script>

        <!-- 图片缩放 plugin. from:https://github.com/adeelejaz/jquery-image-resize -->
        <script src="<?php echo $rootDir; ?>public/plugins/imageResize/jquery.ae.image.resize-2.1.3-min.js"></script>

        <!-- HTML5 Shim 和 Respond.js 用于让 IE8 支持 HTML5元素和媒体查询 -->
        <!-- 注意： 如果通过 file://  引入 Respond.js 文件，则该文件无法起效果 -->
        <!--[if lt IE 9]>
        <script src="//cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->

        <!-- 主题 -->
        <link href="<?php echo $rootDir; ?>public/css/theme/styles.css" rel="stylesheet">

        <!-- 分页控件 from:https://github.com/esimakin/twbs-pagination -->
        <script src="<?php echo $rootDir; ?>/public/plugins/pagination/jquery.twbsPagination-1.3.1.min.js"></script>

        <!-- datetimepicker -->
        <script src="<?php echo $rootDir; ?>/public/plugins/datetimepicker/bootstrap-datetimepicker.min.js"></script>
        <link href="<?php echo $rootDir; ?>/public/plugins/datetimepicker/bootstrap-datetimepicker.min.css" rel="stylesheet">

        <!-- combobox -->
        <script src="<?php echo $rootDir; ?>/public/plugins/combobox/bootstrap-combobox-1.1.6.js"></script>
        <link href="<?php echo $rootDir; ?>/public/plugins/combobox/bootstrap-combobox-1.1.6.css" rel="stylesheet">

        <!-- multiselect -->
        <script src="<?php echo $rootDir; ?>/public/plugins/multiselect/bootstrap-multiselect-2.0.js"></script>
        <link href="<?php echo $rootDir; ?>/public/plugins/multiselect/bootstrap-multiselect-2.0.css" rel="stylesheet">

        <!-- 子菜单 -->
        <link href="<?php echo $rootDir; ?>/public/plugins/submenu/css/bootstrap-submenu.min.css" rel="stylesheet">
        <script src="<?php echo $rootDir; ?>/public/plugins/submenu/js/bootstrap-submenu.min.js"></script>

        <!-- 商品列表样式 -->
        <script src="<?php echo $rootDir; ?>/public/plugins/gridLoadingEffects/modernizr.custom.js"></script>
        <script src="<?php echo $rootDir; ?>/public/plugins/gridLoadingEffects/masonry.pkgd.min.js"></script>
        <script src="<?php echo $rootDir; ?>/public/plugins/gridLoadingEffects/imagesloaded.js"></script>
        <script src="<?php echo $rootDir; ?>/public/plugins/gridLoadingEffects/classie.js"></script>
        <script src="<?php echo $rootDir; ?>/public/plugins/gridLoadingEffects/AnimOnScroll.js"></script>
        <link rel="stylesheet" type="text/css" href="<?php echo $rootDir; ?>/public/plugins/gridLoadingEffects/component.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo $rootDir; ?>/public/plugins/gridLoadingEffects/gridLoadingEffects.css" />

        <!-- text box -->
        <script src="<?php echo $rootDir; ?>/public/plugins/uiwidget/customTextWidget.js"></script>
        <script src="<?php echo $rootDir; ?>/public/plugins/uiwidget/customSelectBoxWidget.js"></script>
        <script src="<?php echo $rootDir; ?>/public/plugins/uiwidget/customGridView.js"></script>
        <script src="<?php echo $rootDir; ?>/public/plugins/uiwidget/customSubMenuSelectBoxWidget.js"></script>

    </head>
    <body>
    <div id="theme-wrapper">
        <!-- 主题 -->
        <script src="<?php echo $rootDir; ?>public/js/theme.js"></script>
        <div id="page-wrapper" class="container">
            <div class="row">