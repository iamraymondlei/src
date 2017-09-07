<!DOCTYPE html>
<html lang="zh-CN">
  <head>
        <?php require_once 'htmlhead.php'; ?>   
        <!-- 主题 -->
        <?php
        $dir = "http://".$_SERVER['HTTP_HOST']."/datawh/public";
        echo '<link href="'.$dir.'/css/theme/styles.css" rel="stylesheet">';
        echo '<script src="'.$dir.'/js/theme.js"></script>';
        ?>
  </head>
  <body id="error-page">
      <div class="container">
          <div class="row">
              <div class="col-xs-12">
                  <div id="error-box">
                      <div class="row">
                          <div class="col-xs-12">
                              <div id="error-box-inner">
                                  <img src="<?php echo $dir; ?>/images/error-404-v3.png" alt="Have you seen this page?"/>
                              </div>
                              <h1>ERROR 404</h1>
                              <p>
                                  Page not found.<br/>
                                  If you find this page, let us know.
                              </p>
                              <p>
                                  Go back to <a href="index.php?p=backend&c=Index&a=index">homepage</a>.
                              </p>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </body>
</html>