<!DOCTYPE html>
<html lang="zh-CN">
  <head>
        <?php require_once 'application/views/backend/htmlhead.php'; ?>   
        <!-- 首页对应js -->
        <script src="application/views/backend/expense/expenseList.js"></script>
        
        <!-- 分页控件 from:https://github.com/esimakin/twbs-pagination -->
        <script src="public/plugins/pagination/jquery.twbsPagination-1.3.1.min.js"></script>
        
        <!-- daterangepicker -->
        <script src="public/plugins/daterangepicker/moment.js"></script>
        <script src="public/plugins/daterangepicker/daterangepicker.js"></script>
        <link href="public/plugins/daterangepicker/daterangepicker.css" rel="stylesheet">
        
        <!-- combobox -->
        <script src="public/plugins/combobox/bootstrap-combobox-1.1.6.js"></script>
        <link href="public/plugins/combobox/bootstrap-combobox-1.1.6.css" rel="stylesheet">
        
        <!-- multiselect -->
        <script src="public/plugins/multiselect/bootstrap-multiselect-2.0.js"></script>
        <link href="public/plugins/multiselect/bootstrap-multiselect-2.0.css" rel="stylesheet">
        
        <!-- text box -->
        <script src="public/plugins/uiwidget/customTextWidget.js"></script>
        <script src="public/plugins/uiwidget/customTextareaWidget.js"></script>
        <script src="public/plugins/uiwidget/customSelectBoxWidget.js"></script>
        <script src="public/plugins/uiwidget/customDatetimeWidget.js"></script>
        <script src="public/plugins/uiwidget/customTableWidget.js"></script>
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
                                        <li class="active"><span>Expense</span></li>
                                    </ol>
                                    <h1>过往消费记录</h1>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="main-box">
                                        <header class="main-box-header clearfix">
                                            <h2 class="pull-left" id="expenseList-expense-header-info"></h2>
                                            <div class="filter-block pull-right" >
                                                <form class="form-inline" id="" role="form">
                                                    <div class="form-group pull-left daterange-filter" id="expenseList-datetime" style="margin-top:2px;" >
                                                        <i class="fa fa-calendar"></i>
                                                        <span></span> <b class="caret"></b>
                                                    </div>
                                                    <div class="form-group pull-left">
                                                        <select class="multiselect" multiple="multiple" id="expenseList-store-multiselect"></select>
                                                    </div>  
                                                    <div class="form-group pull-left">
                                                        <input type="text" class="form-control" id="expenseList-product-searchBox" onkeypress="expenseList.searchExpenseOnKeypress(event)" placeholder="名称模糊搜索">
                                                        <i class="fa fa-search search-icon"></i>
                                                    </div>
                                                    <div class="form-group pull-left">
                                                        <button id="expenseList-search" type="button" class="btn btn-primary" onclick="expenseList.DrawVisitTable()">查询</button>
                                                    </div> 
                                                </form>
                                            </div>
                                        </header>
                                        <HR>
                                        <div class="main-box-body clearfix" id="expenseList-expense-table">

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