<div id="nav-col" style="display: none; width:0px;">
    <!-- 菜单 -->
    <script src="<?php echo $rootDir; ?>public/js/menu.js"></script>
    <section id="col-left" class="col-left-nano">
        <div id="col-left-inner" class="col-left-nano-content">
            <div id="user-left-box" class="clearfix hidden-sm hidden-xs" >
                <img alt="" src="<?php echo $rootDir; ?>public/images/icon.jpg">
                <div class="user-box">
                    <span class="name"><?php echo $title; ?></span>
                    <span class="status">
                        <i class="fa fa-circle"></i> Online
                    </span>
                </div>
            </div>
            <div class="collapse navbar-collapse navbar-ex1-collapse" id="sidebar-nav">
                <ul class="nav nav-pills nav-stacked">
                    <li><!--class="active"-->
                        <a href="" class="dropdown-toggle">
                            <i class="fa fa-bar-chart-o"></i>
                            <span>数据统计</span>
                            <i class="fa fa-chevron-circle-right drop-icon"></i>
                        </a>
                        <ul class="submenu" style="display: none;">
                            <li>
                                <a href="index.php?p=backend&c=analysis&a=scene">
                                    <i class="fa fa-edit"></i>
                                    <span>展厅统计</span>
                                </a>
                            </li>
                            <li>
                                <a href="index.php?p=backend&c=analysis&a=user">
                                    <i class="fa fa-edit"></i>
                                    <span>用户分析</span>
                                </a>
                            </li>
                            <li>
                                <a href="index.php?p=backend&c=analysis&a=map">
                                    <i class="fa fa-plus"></i>
                                    <span>地域分布</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="index.php?p=backend&c=employee&a=index" >
                            <i class="fa fa-bitbucket"></i>
                            <span>员工感悟</span>
                            <span class="label label-info label-circle pull-right">22</span>
                        </a>
                    </li>
                    <li>
                        <a href="index.php?p=backend&c=history&a=index&ci=5" >
                            <i class="fa fa-image"></i>
                            <span>光辉历程</span>
                        </a>
                    </li>
                    <li>
                        <a href="index.php?p=backend&c=typical&a=index&ci=3" >
                            <i class="fa fa-book"></i>
                            <span>先进典型</span>
                        </a>
                    </li>
                    <li>
                        <a href="" class="dropdown-toggle">
                            <i class="fa fa-book"></i>
                            <span>理念展示</span>
                            <i class="fa fa-chevron-circle-right drop-icon"></i>
                        </a>
                        <ul class="submenu" style="display: none;">
                            <li>
                                <a href="index.php?p=backend&c=concept&a=index&ci=1">
                                    <i class="fa fa-edit"></i>
                                    <span>导语和理念</span>
                                </a>
                            </li>
                            <li>
                                <a href="index.php?p=backend&c=culture&a=index&ci=2">
                                    <i class="fa fa-plus"></i>
                                    <span>“心”品牌</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li style="display: none;" >
                        <a href="index.php?p=backend&c=honor&a=index&ci=4">
                            <i class="fa fa-book"></i>
                            <span>光影记忆</span>
                        </a>
                    </li>
                    <li style="display: none;" >
                        <a href="index.php?p=backend&c=profession&a=index&ci=6">
                            <i class="fa fa-book"></i>
                            <span>专业文化</span>
                        </a>
                    </li>
                    <li style="display: none;" >
                        <a href="index.php?p=backend&c=promicrovideo&a=index&ci=7&id=606">
                            <i class="fa fa-book"></i>
                            <span>专业文化建设微视频</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </section>
</div>