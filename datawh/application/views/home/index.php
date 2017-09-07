<!DOCTYPE html>
<html lang="zh-CN">
  <head>
<?php
//公用的head文件
require_once 'htmlhead.php';
?>   
    <!-- 首页对应js -->
    <script src="application/views/home/js/index.js"></script>
    <!-- cookie 1.4.1 插件 -->
    <script src="public/plugins/cookie/jquery.cookie-1.4.1.js"></script>
    <!-- combobox 1.1.6 插件, From:https://github.com/danielfarrell/bootstrap-combobox -->
    <script src="public/plugins/combobox/bootstrap-combobox-1.1.6.js"></script>
    <link href="public/plugins/combobox/bootstrap-combobox-1.1.6.css" rel="stylesheet">
    <!-- validator -->
    <script src="public/plugins/validator/validator.min.js"></script>
    <!-- Custom styles for this page -->
    <link href="application/views/home/css/index.css" rel="stylesheet">
  </head>
  <body class="indexBg">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div id="login-box">
                    <div id="login-box-holder">
                        <div class="row">
                            <div class="col-xs-12">
                                <header id="login-header">
                                    <div id="login-logo">
                                        <i class="glyphicon glyphicon-leaf"></i>
                                        <h4>DATA WAREHOUSE</h4>
                                    </div>
                                </header>
                                <div id="login-box-inner">
                                    <form data-toggle="validator" role="form" id="login-form-group" method="post" >
                                        <div class="input-group" id="divUsername">
                                            <span class="input-group-addon"><i class="fa fa-user fa-2x"></i></span>
                                            <input class="form-control" type="text" id="inputUsername" name="un" placeholder="Email address" required autofocus>
                                        </div>
                                        <div class="input-group" id="divPassword">
                                            <span class="input-group-addon"><i class="fa fa-key fa-2x"></i></span>
                                            <input type="password" class="form-control" id="inputPassword" name="pw" placeholder="Password" required>
                                        </div>
                                        <div id="remember-me-wrapper">
                                            <div class="row">
                                                <div class="col-xs-6">
                                                    <div class="checkbox-nice">
                                                        <input type="checkbox" id="inputRememberMe" checked="checked">
                                                        <label for="remember-me">
                                                            记住我
                                                        </label>
                                                    </div>
                                                </div>
                                                <a id="erroInfo" class="col-xs-6" style="display:none;"></a>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <input type="button" value="Login" onclick="SigninGo()" class="btn btn-success col-xs-12">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="login-box-footer" style="display: none;">
                        <div class="row">
                            <div class="col-xs-12">
                                Do not have an account?
                                <a href="registration-full.html">
                                    Register now
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </body>
</html>