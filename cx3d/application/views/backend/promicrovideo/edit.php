
<!-- bootstrap dialog 1.34.7 from:https://github.com/nakupanda/bootstrap3-dialog, demo:https://nakupanda.github.io/bootstrap3-dialog/ -->
<link href="<?php echo $rootDir; ?>public/plugins/dialog/1.34.7/css/bootstrap-dialog.css" rel="stylesheet">
<script src="<?php echo $rootDir; ?>public/plugins/dialog/1.34.7/js/bootstrap-dialog.js"></script>

<!-- text box -->
<script src="<?php echo $rootDir; ?>public/plugins/uiwidget/customTextWidget.js"></script>

<!-- Image Upload -->
<script src="<?php echo $rootDir; ?>public/plugins/uiwidget/customImageWidget.js"></script>
<link href="<?php echo $rootDir; ?>public/plugins/gridLoadingEffects/gridLoadingEffects.css" rel="stylesheet">

<!-- multiselect -->
<script src="<?php echo $rootDir; ?>public/plugins/multiselect/bootstrap-multiselect-2.0.js"></script>
<link href="<?php echo $rootDir; ?>public/plugins/multiselect/bootstrap-multiselect-2.0.css" rel="stylesheet">

<script src="<?php echo $rootDir; ?>/application/views/backend/promicrovideo/js/edit.js"></script>

<div id="content-wrapper">
    <div class="row" style="opacity: 1;">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-12">
                    <ol class="breadcrumb">
                        <li><a href="index.php?p=backend&c=Index&a=index">Home</a></li>
                        <li class="active"><span><?php echo $title; ?></span></li>
                    </ol>
                    <h1 id="promicrovideo-title"><?php echo $title; ?></h1>
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
                            <form id="main-group" action="" method="post" name="mainForm" data-toggle="validator" role="form">
                                <div id="EditPromicrovideo-Title"></div>
                                <div id="EditPromicrovideo-VideoUrl"></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    <?php
    echo isset($ps)?'EditPromicrovideo.pageSize = "'.$ps.'";':"";
    echo isset($pi)?'EditPromicrovideo.pageIndex = "'.$pi.'";':"";
    echo isset($ob)?'EditPromicrovideo.orderBy = "'.$ob.'";':"";
    echo isset($sb)?'EditPromicrovideo.sortBy = "'.$sb.'";':"";
    echo isset($ci)?'EditPromicrovideo.catId = "'.$ci.'";':"";
    echo isset($id)?'EditPromicrovideo.newsId = "'.$id.'";':"";
    echo isset($vid)?'EditPromicrovideo.videoId = "'.$vid.'";':"";
    echo 'EditPromicrovideo.uploadWS = "'.$rootDir.'/ws/UploadFile.php";';
    foreach($items as $itemKey=>$itemVal) {
        echo "EditPromicrovideo.saveData.".$itemKey." = '".$itemVal."';";
    }
    ?>
</script>