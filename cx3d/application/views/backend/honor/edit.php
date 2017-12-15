
<!-- bootstrap dialog 1.34.7 from:https://github.com/nakupanda/bootstrap3-dialog, demo:https://nakupanda.github.io/bootstrap3-dialog/ -->
<link href="<?php echo $rootDir; ?>public/plugins/dialog/1.34.7/css/bootstrap-dialog.css" rel="stylesheet">
<script src="<?php echo $rootDir; ?>public/plugins/dialog/1.34.7/js/bootstrap-dialog.js"></script>

<!-- text box -->
<script src="<?php echo $rootDir; ?>public/plugins/uiwidget/customTextWidget.js"></script>

<!-- album -->
<script src="<?php echo $rootDir; ?>public/plugins/uiwidget/customAlbum.js"></script>

<!-- Image Upload -->
<script src="<?php echo $rootDir; ?>public/plugins/uiwidget/customImageWidget.js"></script>
<link href="<?php echo $rootDir; ?>public/plugins/gridLoadingEffects/gridLoadingEffects.css" rel="stylesheet">

<!-- multiselect -->
<script src="<?php echo $rootDir; ?>public/plugins/multiselect/bootstrap-multiselect-2.0.js"></script>
<link href="<?php echo $rootDir; ?>public/plugins/multiselect/bootstrap-multiselect-2.0.css" rel="stylesheet">

<script src="<?php echo $rootDir; ?>/application/views/backend/honor/js/edit.js"></script>

<div id="content-wrapper">
    <div class="row" style="opacity: 1;">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-12">
                    <ol class="breadcrumb">
                        <li><a href="index.php?p=backend&c=Index&a=index">Home</a></li>
                        <li class="active"><span><?php echo $title; ?></span></li>
                    </ol>
                    <h1 id="Honor-title"><?php echo $title; ?></h1>
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
                                <div id="EditHonor-title"></div>
                                <div id="EditHonor-album">
                                </div>
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
    echo isset($ps)?'EditHonor.pageSize = "'.$ps.'";':"";
    echo isset($pi)?'EditHonor.pageIndex = "'.$pi.'";':"";
    echo isset($ob)?'EditHonor.orderBy = "'.$ob.'";':"";
    echo isset($sb)?'EditHonor.sortBy = "'.$sb.'";':"";
    echo isset($ci)?'EditHonor.catId = "'.$ci.'";':"";
    echo isset($id)?'EditHonor.newsId = "'.$id.'";':"";
    echo 'EditHonor.uploadWS = "'.$rootDir.'/ws/UploadFile.php";';
    foreach($items as $itemKey=>$itemVal) {
        if($itemKey == "ImageList"){
            echo 'EditHonor.saveData.ImageList = [];';
            foreach($itemVal as $imgIndex => $img) {
                echo 'var imgObj = {};';
                foreach($img as $imgKey => $imgVal){
                    echo 'imgObj.'.$imgKey.' = "'.$imgVal.'";';
                }
                echo 'EditHonor.saveData.ImageList.push(imgObj);';
            }
        }
        else{
            echo 'EditHonor.saveData.'.$itemKey.' = "'.$itemVal.'";';
        }
    }
    ?>
</script>