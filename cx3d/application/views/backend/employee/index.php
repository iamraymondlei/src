
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
<script src="<?php echo $rootDir; ?>/application/views/backend/employee/js/employee.js"></script>

<div id="content-wrapper">
    <div class="row" style="opacity: 1;">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-12">
                    <ol class="breadcrumb">
                        <li><a href="index.php?p=backend&c=Index&a=index">Home</a></li>
                        <li class="active"><span><?php echo $title; ?></span></li>
                    </ol>
                    <h1 id="employee-title"><?php echo $title; ?></h1>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="main-box">
                        <header class="main-box-header clearfix">
                            <h2></h2>
                            <form class="form-inline" id="" role="form">
                                <div class="filter-block col-lg-2 pull-left">
                                    <label>信息类型：</label>
                                    <select class="multiselect" multiple="multiple" id="employee-dataType-multiSelect">
                                        <option value="" <?php echo empty($dt)?"selected":"";?> >全部</option>
                                        <?php foreach ($dataType as $key=>$type): ?>
                                            <?php $selected = ($dt == $type["EmployeeUploadDataTypeId"])?"selected":""; ?>
                                            <option value="<?php echo $type["EmployeeUploadDataTypeId"]; ?>" <?php echo $selected; ?> ><?php echo $type["DisplayName"]; ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                                <div class="filter-block col-lg-2 pull-left">
                                    <label>审核状态：</label>
                                    <select class="multiselect" multiple="multiple" id="employee-state-multiSelect">
                                        <?php
                                            $s0 = empty($s)?"selected":"";
                                            $s1 = ($s == 1)?"selected":"";
                                            $s2 = ($s == 2)?"selected":"";
                                            $s3 = ($s == 3)?"selected":"";
                                        ?>
                                        <option value="" <?php echo $s0; ?>>全部</option>
                                        <option value="1" <?php echo $s1; ?>>待审核</option>
                                        <option value="2" <?php echo $s2; ?>>审核不通过</option>
                                        <option value="3" <?php echo $s3; ?>>审核通过</option>
                                    </select>
                                </div>
                                <div class="filter-block col-lg-4 pull-left">
                                    <div class="form-group" style="width:100%">
                                        <input type="text" class="form-control" style="width:100%;" id="employee-searchBox"  placeholder="内容模糊搜索" onkeypress="EmployeeList.SearchOnKeypress(event)" value="<?php echo isset($k)?$k:""; ?>">
                                        <i class="fa fa-search search-icon"></i>
                                    </div>
                                </div>
                                <div class="filter-block pull-left">
                                    <button id="employee-search" type="button" class="btn btn-primary" onclick="EmployeeList.SearchOnBtn()">查询</button>
                                </div>
                            </form>
                        </header>
                        <HR>
                        <div class="main-box-body clearfix" id="employeeList-table">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
<?php
    echo isset($ps)?'EmployeeList.pageSize = "'.$ps.'";':"";
    echo isset($pi)?'EmployeeList.pageIndex = "'.$pi.'";':"";
    echo isset($s)?'EmployeeList.state = "'.$s.'";':"";
    echo isset($dt)?'EmployeeList.type = "'.$dt.'";':"";
    echo isset($ob)?'EmployeeList.orderBy = "'.$ob.'";':"";
    echo isset($sb)?'EmployeeList.sortBy = "'.$sb.'";':"";
    echo 'EmployeeList.dataCount="'.$itemCount.'";';
?>
var tableData = [];
<?php foreach ($items as $item): ?>
var columnData = {};
<?php foreach ($item as $key=>$value): ?>
columnData.<?php echo $key; ?> = '<?php echo str_replace("\r\n","",$value); ?>';
<?php endforeach ?>
tableData.push(columnData);
<?php endforeach ?>
</script>
