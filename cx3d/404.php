<!DOCTYPE html>
<html lang="zh-CN">
  <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
      <link rel="icon" href="images/favicon.ico">
      <title>楚雄3D</title>

      <!-- jQuery (Bootstrap 的 JavaScript 插件需要引入 jQuery) -->
      <script src="public/plugins/jquery/jquery-1.12.0.min.js"></script>

      <!-- jQuery UI -->
      <script src="public/plugins/jquery/jquery-ui-1.11.4.min.js"></script>
      <link href="public/plugins/jquery/jquery-ui-1.11.4.min.css" rel="stylesheet">

      <!-- Bootstrap 3.3.5 -->
      <link href="public/css/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
      <script src="public/css/bootstrap/3.3.5/js/bootstrap.min.js"></script>

      <!-- 获取URL参数 -->
      <script src="public/plugins/urlParams/urlParams-1.0.0.js"></script>
      <!-- 包括所有ajax请求 -->
      <script src="public/js/ajax.js"></script>
      <script src="public/js/htmlUtil.js"></script>
      <script src="public/js/httpUtil.js"></script>
      <!-- 包括所有prototype扩展方法 -->
      <script src="public/js/prototype.js"></script>

      <!-- 图标CSS -->
      <link href="public/css/font-awesome/font-awesome.min.css" rel="stylesheet">

      <!-- HTML5 Shim 和 Respond.js 用于让 IE8 支持 HTML5元素和媒体查询 -->
      <!-- 注意： 如果通过 file://  引入 Respond.js 文件，则该文件无法起效果 -->
      <!--[if lt IE 9]>
      <script src="//cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
      <![endif]-->

      <!-- 主题 -->
      <link href="public/css/theme/styles.css" rel="stylesheet">
  </head>
  <body id="error-page">
      <div class="container">
          <div class="row">
              <div class="col-xs-12">
                  <div id="error-box">
                      <div class="row">
                          <div class="col-xs-12">
                              <div id="error-box-inner">
                                  <img src="public/images/error-404-v3.png" alt="Have you seen this page?"/>
                              </div>
                              <h1>ERROR 404</h1>
                              <p>
                                  该账号不是管理员，或者session过期，请重新登入
                              </p>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </body>
</html>