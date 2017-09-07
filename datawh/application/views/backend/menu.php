<?php
$dir = "http://".$_SERVER['HTTP_HOST']."/datawh/public";

if( !isset($_SESSION['userInfo']) || empty($_SESSION['userInfo'])){
    echo '<script language="javascript"> location.href="http://"'.$_SERVER['HTTP_HOST'].'/datawh/index.php";</script>';
}
else{
    $userInfo = $_SESSION['userInfo'];
    $UserId = isset($userInfo["UserId"])?$userInfo["UserId"]:$userInfo["UserId"];
    $username = isset($userInfo["UserName"])?$userInfo["UserName"]:$userInfo["UserName"];
    $displayname = isset($userInfo["DisplayName"])?$userInfo["DisplayName"]:$userInfo["DisplayName"];
    $familyId = isset($userInfo["FamilyId"])?$userInfo["FamilyId"]:"";
    $lastLogin = isset($userInfo["LastLoginTime"])?$userInfo["LastLoginTime"]:$userInfo["LastLoginTime"];

echo <<<menu
                    <!-- 菜单 -->
                    <script src="$dir/js/menu.js"></script>
                    <section id="col-left" class="col-left-nano">
                        <div id="col-left-inner" class="col-left-nano-content">
                            <div id="user-left-box" class="clearfix hidden-sm hidden-xs">
                                <img alt="" src="$dir/images/icon.jpg">
                                <div class="user-box">
                                    <span class="name">
                                        $displayname
                                    </span>
                                    <span class="status">
                                        <i class="fa fa-circle"></i> Online
                                    </span>
                                </div>
                            </div>
                            <div class="collapse navbar-collapse navbar-ex1-collapse" id="sidebar-nav">
                                <ul class="nav nav-pills nav-stacked">
                                    <li class="active">
                                        <a href="index.php?p=backend&c=Index&a=index">
                                            <i class="fa fa-bar-chart-o"></i>
                                            <span>统计</span>
                                            <!-- <span class="label label-info label-circle pull-right">28</span> -->
                                        </a>
                                    </li>
                                    <!-- <li>
                                        <a href="widgets.html">
                                            <i class="fa fa-th-large"></i>
                                            <span>Widgets</span>
                                            <span class="label label-success pull-right">New</span>
                                        </a>
                                    </li> -->
                                    <li>
                                        <a href="#" class="dropdown-toggle">
                                            <i class="fa fa-bitbucket"></i>
                                            <span>模型库</span>
                                            <i class="fa fa-chevron-circle-right drop-icon"></i>
                                        </a>
                                        <ul class="submenu" style="display: none;">
                                            <li>
                                                <a href="index.php?p=backend&c=File&a=list&catId=1&type=1&folder=1">
                                                    <i class="fa fa-edit"></i>
                                                    <span>查看模型</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="index.php?p=backend&c=File&a=add&catId=1&type=1&folder=1">
                                                    <i class="fa fa-plus"></i>
                                                    <span>添加模型</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="index.php?p=backend&c=Cat&a=filelist&catId=1">
                                                    <i class="fa fa-sitemap"></i>
                                                    <span>編緝分類</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="#" class="dropdown-toggle">
                                            <i class="fa fa-image"></i>
                                            <span>贴图库</span>
                                            <i class="fa fa-chevron-circle-right drop-icon"></i>
                                        </a>
                                        <ul class="submenu" style="display: none;">
                                            <li>
                                                <a href="index.php?p=backend&c=File&a=list&catId=2&type=2&folder=2">
                                                    <i class="fa fa-edit"></i>
                                                    <span>查看貼圖</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="index.php?p=backend&c=File&a=add&catId=2&type=2&folder=2">
                                                    <i class="fa fa-plus"></i>
                                                    <span>添加貼圖</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="index.php?p=backend&c=Cat&a=filelist&catId=2">
                                                    <i class="fa fa-sitemap"></i>
                                                    <span>編緝分類</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="#" class="dropdown-toggle">
                                            <i class="fa fa-book"></i>
                                            <span>图书库</span>
                                            <i class="fa fa-chevron-circle-right drop-icon"></i>
                                        </a>
                                        <ul class="submenu">
                                            <li>
                                                <a href="index.php?p=backend&c=File&a=list&catId=3&type=3&folder=3">
                                                    <i class="fa fa-edit"></i>
                                                    <span>查看圖書</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="index.php?p=backend&c=File&a=add&catId=3&type=3&folder=3">
                                                    <i class="fa fa-plus"></i>
                                                    <span>添加圖書</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="index.php?p=backend&c=Cat&a=filelist&catId=3">
                                                    <i class="fa fa-sitemap"></i>
                                                    <span>編緝分類</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="#" class="dropdown-toggle">
                                            <i class="fa fa-money"></i>
                                            <span>消费记录</span>
                                            <i class="fa fa-chevron-circle-right drop-icon"></i>
                                        </a>
                                        <ul class="submenu" style="display: none;"> 
                                            <li>
                                                <a href="index.php?p=backend&c=Expense&a=list">
                                                    <i class="fa fa-list-alt"></i>
                                                    <span>过往消费记录</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="index.php?p=backend&c=Expense&a=add">
                                                    <i class="fa fa-plus"></i>
                                                    <span>添加消费项</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="index.php?p=backend&c=Cat&a=productlist&catId=1">
                                                    <i class="fa fa-sitemap"></i>
                                                    <span>編緝商品分類</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </section>
menu;
}