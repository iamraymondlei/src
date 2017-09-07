<!DOCTYPE html>
<html lang="zh-CN">
  <head>
        <?php require_once 'htmlhead.php'; ?>
        
        <!-- morris图表控件 API:http://morrisjs.github.io/morris.js/-->
        <script type="text/javascript" src="public/plugins/morris/raphael-min.js"></script>
        <script type="text/javascript" src="public/plugins/morris/morris-0.5.1.min.js"></script>
        <link href="public/plugins/morris/morris-0.5.1.css" rel="stylesheet" />
  </head>
  <body>
    <div id="theme-wrapper">
        <?php require_once 'header.php'; ?> 
        
        <!-- countTo -->
        <script src="public/plugins/countTo/jquery.countTo.js"></script>
        <!-- 首页对应js -->
        <script src="application/views/backend/index.js"></script>
        <div id="page-wrapper" class="container">
            <div class="row">
                <div id="nav-col">
                    <?php require_once 'menu.php'; ?> 
                </div>
                <div id="content-wrapper">
                    <div class="row" style="opacity: 1;">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-12">
                                    <ol class="breadcrumb">
                                        <li><a href="index.php?p=backend&c=Index&a=index">Home</a></li>
                                    </ol>
                                    <h1>信息集</h1>
                                </div>
                            </div>
                            <div class="row" id="infographic-box-group">
<!--                                <div class="col-lg-3 col-sm-6 col-xs-12">
                                    <div class="main-box infographic-box">
                                        <i class="fa fa-user red-bg"></i>
                                        <span class="headline">Users</span>
                                        <span class="value">
                                            <span class="timer" data-from="120" data-to="2562" data-speed="1000" data-refresh-interval="50">2562</span>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6 col-xs-12">
                                    <div class="main-box infographic-box">
                                        <i class="fa fa-shopping-cart emerald-bg"></i>
                                        <span class="headline">Purchases</span>
                                        <span class="value">
                                            <span class="timer" data-from="30" data-to="658" data-speed="800" data-refresh-interval="30">658</span>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6 col-xs-12">
                                    <div class="main-box infographic-box">
                                        <i class="fa fa-money green-bg"></i>
                                        <span class="headline">Expense</span>
                                        <span class="value">
                                            <span class="timer" data-from="83" data-to="8400" data-speed="900" data-refresh-interval="60">8400</span>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6 col-xs-12">
                                    <div class="main-box infographic-box">
                                        <i class="fa fa-eye yellow-bg"></i>
                                        <span class="headline">Monthly Visits</span>
                                        <span class="value">
                                            <span class="timer" data-from="539" data-to="12526" data-speed="1100">12526</span>
                                        </span>
                                    </div>
                                </div>-->
                            </div>
                            <HR>
                            <div id="graph-flot-points" >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </body>
</html>