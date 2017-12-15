
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

<!-- ckeditor 4.3 -->
<script src="<?php echo $rootDir; ?>public/plugins/ckeditor/ckeditor.js"></script>
<script src="<?php echo $rootDir; ?>public/plugins/uiwidget/customEditorWidget.js"></script>

<!-- multiselect -->
<script src="<?php echo $rootDir; ?>public/plugins/multiselect/bootstrap-multiselect-2.0.js"></script>
<link href="<?php echo $rootDir; ?>public/plugins/multiselect/bootstrap-multiselect-2.0.css" rel="stylesheet">

<script src="<?php echo $rootDir; ?>/application/views/backend/history/js/edit.js"></script>

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
                                <div id="EditHistory-title"></div>
                                <div id="EditHistory-Content-Group">
                                    <div id="EditHistory-Content" style="display: none;" ><?php echo $items["ArticleContent"]; ?></div>
                                </div>
                                <div id="EditHistory-album">
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
    echo isset($ps)?'EditHistory.pageSize = "'.$ps.'";':"";
    echo isset($pi)?'EditHistory.pageIndex = "'.$pi.'";':"";
    echo isset($ob)?'EditHistory.orderBy = "'.$ob.'";':"";
    echo isset($sb)?'EditHistory.sortBy = "'.$sb.'";':"";
    echo isset($ci)?'EditHistory.catId = "'.$ci.'";':"";
    echo isset($id)?'EditHistory.newsId = "'.$id.'";':"";
    echo 'EditHistory.uploadWS = "'.$rootDir.'/ws/UploadFile.php";';
    foreach($items as $itemKey=>$itemVal) {
        if($itemKey == "ImageList"){
            echo 'EditHistory.saveData.ImageList = [];';
            foreach($itemVal as $imgIndex => $img) {
                echo 'var imgObj = {};';
                foreach($img as $imgKey => $imgVal){
                    echo 'imgObj.'.$imgKey.' = "'.$imgVal.'";';
                }
                echo 'EditHistory.saveData.ImageList.push(imgObj);';
            }
        }
        elseif($itemKey === "ArticleContent"){
            echo PHP_EOL;
        }
        else{
            echo 'EditHistory.saveData.'.$itemKey.' = "'.$itemVal.'";';
        }
    }
    ?>
</script>