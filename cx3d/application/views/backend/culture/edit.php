
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

<script src="<?php echo $rootDir; ?>/application/views/backend/culture/js/edit.js"></script>

<div id="content-wrapper">
    <div class="row" style="opacity: 1;">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-12">
                    <ol class="breadcrumb">
                        <li><a href="index.php?p=backend&c=Index&a=index">Home</a></li>
                        <li class="active"><span><?php echo $title; ?></span></li>
                    </ol>
                    <h1 id="culture-title"><?php echo $title; ?></h1>
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
                                <div id="EditCulture-title"></div>
                                <div id="EditCulture-album">
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
    echo isset($ps)?'EditCulture.pageSize = "'.$ps.'";':"";
    echo isset($pi)?'EditCulture.pageIndex = "'.$pi.'";':"";
    echo isset($ob)?'EditCulture.orderBy = "'.$ob.'";':"";
    echo isset($sb)?'EditCulture.sortBy = "'.$sb.'";':"";
    echo isset($ci)?'EditCulture.catId = "'.$ci.'";':"";
    echo isset($id)?'EditCulture.newsId = "'.$id.'";':"";
    echo 'EditCulture.uploadWS = "'.$rootDir.'/ws/UploadFile.php";';
    foreach($items as $itemKey=>$itemVal) {
        if($itemKey == "ImageList"){
            echo 'EditCulture.saveData.ImageList = [];';
            foreach($itemVal as $imgIndex => $img) {
                echo 'var imgObj = {};';
                foreach($img as $imgKey => $imgVal){
                    echo 'imgObj.'.$imgKey.' = "'.$imgVal.'";';
                }
                echo 'EditCulture.saveData.ImageList.push(imgObj);';
            }
        }
        else{
            echo 'EditCulture.saveData.'.$itemKey.' = "'.$itemVal.'";';
        }
    }
    ?>
</script>