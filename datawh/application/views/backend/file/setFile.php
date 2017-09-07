<!DOCTYPE html>
<html lang="zh-CN">
  <head>
        <?php require_once 'application/views/backend/htmlhead.php'; ?>   
        <!-- 首页对应js -->
        <script src="application/views/backend/file/setFile.js"></script>
        <script src="application/views/backend/file/projectDialog.js"></script>
        <script src="application/views/backend/file/fileCatDialog.js"></script>
        <script src="application/views/backend/file/tagDialog.js"></script>
                
        <!-- validator -->
        <script src="public/plugins/validator/validator.min.js"></script>
        
        <!-- combobox -->
        <script src="public/plugins/combobox/bootstrap-combobox-1.1.6.js"></script>
        <link href="public/plugins/combobox/bootstrap-combobox-1.1.6.css" rel="stylesheet">
        
        <!-- multiselect -->
        <script src="public/plugins/multiselect/bootstrap-multiselect-2.0.js"></script>
        <link href="public/plugins/multiselect/bootstrap-multiselect-2.0.css" rel="stylesheet">
        
        <!-- typeahead -->
        <script src="public/plugins/typehead/typeahead.bundle.min.js"></script>
        
        <!-- angular -->
        <script src="public/plugins/angular/angular.min.js"></script>
        
        <!-- tags -->
        <script src="public/plugins/tagsInput/bootstrap-tagsinput.js"></script>
        <script src="public/plugins/tagsInput/bootstrap-tagsinput-angular.js"></script>
        <link href="public/plugins/tagsInput/bootstrap-tagsinput.css" rel="stylesheet">
        <link href="public/plugins/tagsInput/app.css" rel="stylesheet">
        
        <!-- 子菜单 -->
        <link href="public/plugins/submenu/css/bootstrap-submenu.min.css" rel="stylesheet">
        <script src="public/plugins/submenu/js/bootstrap-submenu.min.js"></script>
        
        <!-- Text Box -->
        <script src="public/plugins/uiwidget/customTextWidget.js"></script>
        <!-- Textarea Box -->
        <script src="public/plugins/uiwidget/customTextareaWidget.js"></script>
        <!-- Select Box -->
        <script src="public/plugins/uiwidget/customSelectBoxWidget.js"></script>
        <!-- SubMenu -->
        <script src="public/plugins/uiwidget/customSubMenuSelectBoxWidget.js"></script>
        <!-- Image Upload -->
        <script src="public/plugins/uiwidget/customImageWidget.js"></script>
        <link href="public/plugins/gridLoadingEffects/gridLoadingEffects.css" rel="stylesheet">
        <!-- File Upload -->
        <script src="public/plugins/uiwidget/customUploadFileWidget.js"></script>
        <!-- Tags -->
        <script src="public/plugins/uiwidget/customTagsWidget.js"></script> <!-- 修改为select2 -->
  </head>
  <body>
    <div id="theme-wrapper">
        <?php require_once 'application/views/backend/header.php'; ?> 
        <div id="page-wrapper" class="container">
            <div class="row">
                <div id="nav-col">
                    <?php require_once 'application/views/backend/menu.php'; ?> 
                </div>
                <div id="content-wrapper">
                    <div class="row" style="opacity: 1;">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-12">
                                    <ol class="breadcrumb">
                                        <li><a href="index.php?p=backend&c=Index&a=index">Home</a></li>
                                        <li><a id="setFile-nav-title">File</a></li><!--  class="active" -->
                                    </ol>
                                    <h1 id="setFile-title" >添加文件</h1>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="main-box">
                                        <header class="main-box-header clearfix">
                                            <h2></h2>
                                        </header>
                                        <div class="main-box-body clearfix">
                                            <form  id="main-group" data-toggle="validator" role="form">
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </body>
</html>