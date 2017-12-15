<div id="content-wrapper">
    <div class="row" style="opacity: 1;">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-12">
                    <ol class="breadcrumb">
                        <li><a href="index.php?p=backend&c=Index&a=index">Home</a></li>
                    </ol>
                    <h1 id="typical-title"><?php echo $title; ?></h1>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="main-box" style="min-height: 300px;">
                        <header class="main-box-header clearfix">
                            <h1 class="center-block text-center" style="margin-top:120px;">
                                <a class="big" href="
<?php
    echo "index.php?p=backend&c=culture&a=index";
    echo isset($ps)?'&ps='.$ps:"";
    echo isset($pi)?'&pi='.$pi:"";
    echo isset($ci)?'&ci='.$ci:"";
    echo isset($ob)?'&ob='.$ob:"";
    echo isset($sb)?'&sb='.$sb:"";
?>
                                ">成功修改，点击返回</a>
                            </h1>
                        </header>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>