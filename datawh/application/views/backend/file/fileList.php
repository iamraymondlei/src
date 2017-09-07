<!DOCTYPE html>
<html lang="zh-CN">
  <head>
        <?php require_once 'application/views/backend/htmlhead.php'; ?>   
        <!-- 首页对应js -->
        <script src="application/views/backend/file/fileList.js"></script>
        <script src="application/views/backend/file/fileDetailDialog.js"></script>
        
        <!-- 分页控件 from:https://github.com/esimakin/twbs-pagination -->
        <script src="public/plugins/pagination/jquery.twbsPagination-1.3.1.min.js"></script>
  
        <!-- datetimepicker -->
        <script src="public/plugins/datetimepicker/bootstrap-datetimepicker.min.js"></script>
        <link href="public/plugins/datetimepicker/bootstrap-datetimepicker.min.css" rel="stylesheet">
                
        <!-- combobox -->
        <script src="public/plugins/combobox/bootstrap-combobox-1.1.6.js"></script>
        <link href="public/plugins/combobox/bootstrap-combobox-1.1.6.css" rel="stylesheet">
        
        <!-- multiselect -->
        <script src="public/plugins/multiselect/bootstrap-multiselect-2.0.js"></script>
        <link href="public/plugins/multiselect/bootstrap-multiselect-2.0.css" rel="stylesheet">
        
        <!-- 子菜单 -->
        <link href="public/plugins/submenu/css/bootstrap-submenu.min.css" rel="stylesheet">
        <script src="public/plugins/submenu/js/bootstrap-submenu.min.js"></script>
        
        <!-- 商品列表样式 -->
        <script src="public/plugins/gridLoadingEffects/modernizr.custom.js"></script>
        <script src="public/plugins/gridLoadingEffects/masonry.pkgd.min.js"></script>
        <script src="public/plugins/gridLoadingEffects/imagesloaded.js"></script>
        <script src="public/plugins/gridLoadingEffects/classie.js"></script>
        <script src="public/plugins/gridLoadingEffects/AnimOnScroll.js"></script>
        <link rel="stylesheet" type="text/css" href="public/plugins/gridLoadingEffects/component.css" />
        <link rel="stylesheet" type="text/css" href="public/plugins/gridLoadingEffects/gridLoadingEffects.css" />
        
        <!-- text box -->
        <script src="public/plugins/uiwidget/customTextWidget.js"></script>
        <script src="public/plugins/uiwidget/customSelectBoxWidget.js"></script>
        <script src="public/plugins/uiwidget/customGridView.js"></script>
        <script src="public/plugins/uiwidget/customSubMenuSelectBoxWidget.js"></script>
        
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
                                    </ol>
                                    <h1 id="fileList-title">文件列表</h1>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="main-box">
                                        <header class="main-box-header clearfix">
                                            <h2></h2>
                                            <form class="form-inline" id="" role="form">
                                                <div class="filter-block pull-left" id="fileList-begin-time" ></div>
                                                <div class="filter-block pull-left" id="fileList-end-time" ></div>
                                                <div class="filter-block pull-left">
                                                    <label>项目：</label>
                                                    <select class="multiselect" multiple="multiple" id="fileList-project-multiselect"></select>
                                                </div>
                                                <div class="filter-block pull-left" id="fileList-cat-subment" style="padding:0 10px;">
                                                    <label>分类：</label>
                                                </div>
                                                <div class="filter-block col-lg-4 pull-left">
                                                    <div class="form-group" style="width:100%">
                                                      <input type="text" class="form-control" style="width:100%;" id="fileList-file-searchBox"  placeholder="名称或标签模糊搜索"> <!-- onkeypress="fileList.SearchFileOnKeypress(event)" -->
                                                        <i class="fa fa-search search-icon"></i>
                                                    </div>
                                                </div>
                                                <div class="filter-block pull-left">
                                                    <button id="fileList-search" type="button" class="btn btn-primary" onclick="fileList.RefreshGridTable()">查询</button>
                                                </div> 
                                            </form>
                                        </header>
                                        <HR>
                                        <div class="main-box-body clearfix" id="fileList-file-grid">

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