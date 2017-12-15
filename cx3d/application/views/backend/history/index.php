
<!-- bootstrap dialog 1.34.7 from:https://github.com/nakupanda/bootstrap3-dialog, demo:https://nakupanda.github.io/bootstrap3-dialog/ -->
<link href="<?php echo $rootDir; ?>public/plugins/dialog/1.34.7/css/bootstrap-dialog.css" rel="stylesheet">
<script src="<?php echo $rootDir; ?>public/plugins/dialog/1.34.7/js/bootstrap-dialog.js"></script>

<!-- 分页控件 from:https://github.com/esimakin/twbs-pagination -->
<script src="<?php echo $rootDir; ?>/public/plugins/pagination/jquery.twbsPagination-1.3.1.min.js"></script>

<!-- 多选下拉 -->
<link href="<?php echo $rootDir; ?>/public/plugins/multiselect/bootstrap-multiselect-2.0.css" rel="stylesheet">
<script src="<?php echo $rootDir; ?>/public/plugins/multiselect/bootstrap-multiselect-2.0.js"></script>

<!-- combobox -->
<script src="<?php echo $rootDir; ?>/public/plugins/combobox/bootstrap-combobox-1.1.6.js"></script>
<link href="<?php echo $rootDir; ?>/public/plugins/combobox/bootstrap-combobox-1.1.6.css" rel="stylesheet">

<!-- 列表样式 -->
<script src="<?php echo $rootDir; ?>/public/plugins/uiwidget/customTableWidget.js"></script>

<!-- 对应js -->
<script src="<?php echo $rootDir; ?>/application/views/backend/history/js/index.js"></script>

<div id="content-wrapper">
    <div class="row" style="opacity: 1;">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-12">
                    <ol class="breadcrumb">
                        <li><a href="index.php?p=backend&c=Index&a=index">Home</a></li>
                        <li class="active"><span><?php echo $title; ?></span></li>
                    </ol>
                    <h1 id="typical-title"><?php echo $title; ?></h1>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="main-box">
                        <header class="main-box-header clearfix">
                            <h2></h2>
                            <form class="form-inline" id="" role="form">

                            </form>
                        </header>
                        <div class="main-box-body clearfix" id="newsList-table">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
<?php
echo isset($ps)?'History.pageSize = "'.$ps.'";':"";
echo isset($pi)?'History.pageIndex = "'.$pi.'";':"";
echo isset($ob)?'History.orderBy = "'.$ob.'";':"";
echo isset($sb)?'History.sortBy = "'.$sb.'";':"";
echo isset($ci)?'History.catId = "'.$ci.'";':"";
echo 'History.dataCount="'.$itemCount.'";';

echo "var tableData = [];".PHP_EOL;
foreach($items as $item) {
    echo "var columnData = {};";
    foreach ($item as $key=>$value){
        if($key !== "ImageList"){
            echo "columnData.".$key."='".$value."';".PHP_EOL;
        }
    }
    echo "tableData.push(columnData);".PHP_EOL;
}
?>
</script>
