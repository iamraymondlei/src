<!-- daterangepicker -->
<script src="<?php echo $rootDir; ?>/public/plugins/daterangepicker/moment.js"></script>
<script src="<?php echo $rootDir; ?>/public/plugins/daterangepicker/daterangepicker.js"></script>
<link href="<?php echo $rootDir; ?>/public/plugins/daterangepicker/daterangepicker.css" rel="stylesheet">

<!-- combobox -->
<script src="<?php echo $rootDir; ?>/public/plugins/combobox/bootstrap-combobox-1.1.6.js"></script>
<link href="<?php echo $rootDir; ?>/public/plugins/combobox/bootstrap-combobox-1.1.6.css" rel="stylesheet">

<!-- 当前页js -->
<script src="<?php echo $rootDir; ?>application/views/backend/analysis/js/user.js"></script>

<div id="content-wrapper">
    <div class="row" style="opacity: 1;">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-12">
                    <ol class="breadcrumb">
                        <li><a href="index.php?p=backend&c=Index&a=index">Home</a></li>
                        <li class="active"><span>数据统计</span></li>
                    </ol>
                    <h1 id="analysis-title"><?php echo $title; ?></h1>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="main-box">
                        <header class="main-box-header clearfix">
                            <h2></h2>
                            <form class="form-inline" id="" role="form">
                                <div class="form-group pull-left">
                                    <div class="daterange-filter filter-block" id="user-datetime" >
                                        <label>选择时段：</label>
                                        <i class="fa fa-calendar"></i>
                                        <span></span> <b class="caret"></b>
                                    </div>
                                </div>
                                <div class="form-group filter-block pull-left">
                                    <button id="user-search" type="button" class="btn btn-primary" onclick="User.ReDrawAnalytics()">查询</button>
                                </div>
                            </form>
                        </header>
                        <HR>
                        <div class="analytics-group main-box-body clearfix" id="analysis-table">
                            <div class="row">
                                <h2 class="WidgetTitle">用户访问次数</h2>
                                <iframe src="<?php echo $visitUser["visitsSummary"]; ?>" scrolling="no" frameborder="no" marginheight="no" marginwidth="no" style="height: 320px; width: 100%;"></iframe>
                            </div>
                            <div class="row">
                                <h2 class="WidgetTitle">每小时的访问量</h2>
                                <iframe src="<?php echo $visitUser["visitTime"]; ?>" scrolling="no" frameborder="no" marginheight="no" marginwidth="no" style="height: 320px; width: 100%;"></iframe>
                            </div>
                            <div class="row">
                                <h2 class="WidgetTitle">用户停留时长</h2>
                                <iframe src="<?php echo $visitUser["visitorInterest"]; ?>" scrolling="no" frameborder="no" marginheight="no" marginwidth="no" style="height: 320px; width: 100%;"></iframe>
                            </div>
                            <div class="row">
                                <h2 class="WidgetTitle">老访客访问次数</h2>
                                <iframe src="<?php echo $returnUser; ?>" scrolling="no" frameborder="no" marginheight="no" marginwidth="no" style="height: 320px; width: 100%;"></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
<?php
echo isset($start)?'User.startDate = "'.$start.'";':"";
echo isset($end)?'User.endDate = "'.$end.'";':"";
?>
</script>
