<!DOCTYPE html>
<html lang="zh-CN">
  <head>
        <?php require_once 'application/views/backend/htmlhead.php'; ?>   
        <!-- 首页对应js -->
        <script src="application/views/backend/expense/setCat.js"></script>
        <script src="application/views/backend/expense/setCatDialog.js"></script>
        <!-- validator -->
        <script src="public/plugins/validator/validator.min.js"></script>
        <!-- nestable -->
        <script src="public/plugins/nestable/jquery.nestable.js"></script>
        <link href="public/plugins/nestable/nestable.css" rel="stylesheet">
        <!-- Text Box -->
        <script src="public/plugins/uiwidget/customTextWidget.js"></script>
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
                                        <li class="active"><span>Cat</span></li>
                                    </ol>
                                    <h1 id="setCat-title" >編緝分類</h1>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="main-box">
                                        <header class="main-box-header clearfix">
                                            <h2 id="setCat-header"></h2>
                                        </header>
                                        <div class="main-box-body clearfix">
                                           <div id="setCat-nestable-menu">
                                                <button type="button" class="btn btn-primary" data-action="expand-all">展開所有</button>
                                                <button type="button" class="btn btn-danger" data-action="collapse-all">折疊所有</button>
                                            </div>
                                            <div class="row cf nestable-lists">
                                                <div class="col-md-12 dd" id="setCat-nestable">
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