<!DOCTYPE html>
<html lang="zh-CN">
  <head>
        <?php require_once '/../htmlhead.php'; ?>   
        <!-- 首页对应js -->
        <script src="application/views/backend/analysis/productPrice.js"></script>
        
        <!-- daterangepicker -->
        <script src="public/plugins/daterangepicker/moment.js"></script>
        <script src="public/plugins/daterangepicker/daterangepicker.js"></script>
        <link href="public/plugins/daterangepicker/daterangepicker.css" rel="stylesheet">
        
        <!-- combobox -->
        <script src="public/plugins/combobox/bootstrap-combobox-1.1.6.js"></script>
        <link href="public/plugins/combobox/bootstrap-combobox-1.1.6.css" rel="stylesheet">
        
        <!-- morris图表控件 API:http://morrisjs.github.io/morris.js/-->
        <script type="text/javascript" src="public/plugins/morris/raphael-min.js"></script>
        <script type="text/javascript" src="public/plugins/morris/morris-0.5.1.min.js"></script>
        <link href="public/plugins/morris/morris-0.5.1.css" rel="stylesheet">
        
        <!-- uiwidget -->
        <script src="public/plugins/uiwidget/customDatetimeWidget.js"></script>
  </head>
  <body>
    <div id="theme-wrapper">
        <?php require_once '/../header.php'; ?> 
        <div id="page-wrapper" class="container">
            <div class="row">
                <div id="nav-col">
                    <?php require_once '/../menu.php'; ?> 
                </div>
                <div id="content-wrapper">
                    <div class="row" style="opacity: 1;">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-12">
                                    <ol class="breadcrumb">
                                        <li><a href="index.php?p=backend&c=Index&a=index">Home</a></li>
                                        <li class="active"><span>Analysis</span></li>
                                    </ol>
                                    <h1>价格变化</h1>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="main-box">
                                        <header class="main-box-header clearfix">
                                            <h2 class="pull-left" id="productPrice-title"></h2>
                                            <div class="filter-block pull-right" >
                                                <form class="form-inline" id="" role="form">
                                                    <div class="form-group pull-left daterange-filter" id="productPrice-datetime" style="margin-top:2px;" >
                                                        <i class="fa fa-calendar"></i>
                                                        <span></span> <b class="caret"></b>
                                                    </div>
                                                    <div class="form-group  pull-left" id="productPrice-selectProduct-box" ></div>
                                                    <div class="form-group  pull-left">
                                                        <button id="productPrice-search" type="button" class="btn btn-primary" onclick="productPrice.ReDrawAnalytics()">查询</button>
                                                    </div> 
                                                </form>
                                            </div>
                                        </header>
                                        <HR>
                                        <div class="main-box-body clearfix">                                            
                                            <div class="graph-box emerald-bg">
                                                <h2></h2>
                                                <div class="graph" id="productPrice-graph-morris-points" style="height: 250px;">
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
        </div>
    </div>
  </body>
</html>