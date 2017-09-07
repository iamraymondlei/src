<?php
$dir = "http://".$_SERVER['HTTP_HOST']."/datawh/public";

if( !isset($_SESSION['userInfo']) || empty($_SESSION['userInfo'])){
    echo '<script language="javascript"> location.href="http://"'.$_SERVER['HTTP_HOST'].'/datawh/index.php";</script>';
}
else{
    $userInfo = $_SESSION['userInfo'];
    $userId = isset($userInfo["UserId"])?$userInfo["UserId"]:$userInfo["UserId"];
    $roleId = isset($userInfo["RoleId"])?$userInfo["RoleId"]:$userInfo["RoleId"];
    $username = isset($userInfo["UserName"])?$userInfo["UserName"]:$userInfo["Username"];
    $familyId = isset($userInfo["FamilyId"])?$userInfo["FamilyId"]:"";
    $lastLogin = isset($userInfo["LastLoginTime"])?$userInfo["LastLoginTime"]:$userInfo["LastLoginTime"];

echo <<<header
        <!-- 主题 -->
        <script src="$dir/js/theme.js"></script>
        
        <header class="navbar" id="header-navbar">
            <div class="container">
                <a id="logo" class="navbar-brand">
                    <i class="fa fa-leaf"></i>
                    <span>Data Warehouse</span>
                </a>
                <div class="clearfix">
                    <button class="navbar-toggle" data-target=".navbar-ex1-collapse" data-toggle="collapse" type="button">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="fa fa-bars"></span>
                    </button>
                    <div class="nav-no-collapse navbar-left pull-left hidden-sm hidden-xs">
                        <ul class="nav navbar-nav pull-left">
                            <li>
                                <a class="btn" id="make-small-nav">
                                    <i class="fa fa-bars"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="nav-no-collapse pull-right" id="header-nav">
                        <ul class="nav navbar-nav pull-right">
                            <li class="mobile-search">
                                <a class="btn">
                                    <i class="fa fa-search"></i>
                                </a>
                                <div class="drowdown-search">
                                    <form role="search">
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="Search...">
                                            <i class="fa fa-search nav-search-icon"></i>
                                        </div>
                                    </form>
                                </div>
                            </li>
                            <li class="dropdown hidden-xs" style="display:none;">
                                <a class="btn dropdown-toggle" data-toggle="dropdown">
                                    <i class="fa fa-warning"></i>
                                    <span class="count">8</span>
                                </a>
                                <ul class="dropdown-menu notifications-list">
                                    <li class="pointer">
                                        <div class="pointer-inner">
                                            <div class="arrow"></div>
                                        </div>
                                    </li>
                                    <li class="item-header">You have 6 new notifications</li>
                                    <li class="item">
                                        <a href="#">
                                            <i class="fa fa-comment"></i>
                                            <span class="content">New comment on ‘Awesome P...</span>
                                            <span class="time"><i class="fa fa-clock-o"></i>13 min.</span>
                                        </a>
                                    </li>
                                    <li class="item-footer">
                                        <a href="#">View all notifications</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="dropdown hidden-xs">
                                <a class="btn dropdown-toggle" data-toggle="dropdown">
                                    <i class="fa fa-cog"></i>
                                </a>
                                <ul class="dropdown-menu notifications-list messages-list">
                                    <li class="item first-item">
                                        <h4 class="text-center">Skin Color</h4>
                                        <hr>
                                        <div id="config-tool">
                                            <ul id="skin-colors" class="clearfix" style="margin-left:-30px;">
                                                <li>
                                                    <a class="skin-changer" data-skin="" data-toggle="tooltip" title="Default" style="background-color: #34495e;"></a>
                                                </li>
                                                <li>
                                                    <a class="skin-changer" data-skin="theme-white" data-toggle="tooltip" title="White/Green" style="background-color: #2ecc71;"></a>
                                                </li>
                                                <li>
                                                    <a class="skin-changer blue-gradient" data-skin="theme-blue-gradient" data-toggle="tooltip" title="Gradient"></a>
                                                </li>
                                                <li>
                                                    <a class="skin-changer" data-skin="theme-turquoise" data-toggle="tooltip" title="Green Sea" style="background-color: #1abc9c;"></a>
                                                </li>
                                                <li>
                                                    <a class="skin-changer" data-skin="theme-amethyst" data-toggle="tooltip" title="Amethyst" style="background-color: #9b59b6;"></a>
                                                </li>
                                                <li>
                                                    <a class="skin-changer" data-skin="theme-blue" data-toggle="tooltip" title="Blue" style="background-color: #2980b9;"></a>
                                                </li>
                                                <li>
                                                    <a class="skin-changer" data-skin="theme-red" data-toggle="tooltip" title="Red" style="background-color: #e74c3c;"></a>
                                                </li>
                                                <li>
                                                    <a class="skin-changer" data-skin="theme-whbl" data-toggle="tooltip" title="White/Blue" style="background-color: #3498db;"></a>
                                                </li>
                                            </ul>
                                        </div>
                                        <hr>
                                    </li>
                                </ul>
                            </li>
                            <li class="dropdown profile-dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <img src="$dir/images/icon.jpg" alt=""/>
                                    <span class="hidden-xs">$username</span> <b class="caret"></b>
                                </a>
                                <ul class="dropdown-menu">
                                    <!--<li><a href="user-profile.html"><i class="fa fa-user"></i>Profile</a></li>
                                    <li><a href="#"><i class="fa fa-cog"></i>Settings</a></li>
                                    <li><a href="#"><i class="fa fa-envelope-o"></i>Messages</a></li>-->
                                    <li><a href="index.php"><i class="fa fa-power-off"></i>Logout</a></li>
                                </ul>
                            </li>
                            <li class="hidden-xxs">
                                <a href="index.php" class="btn" title="登出">
                                    <i class="fa fa-power-off"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </header>
header;
}