<?php
/**
 * Created by PhpStorm.
 * User: raymond.lui
 * Date: 2017/9/11
 * Time: 16:15
 */

class HomeController extends Controller
{
    // 首页方法
    public function index()
    {
        $this->assign('title', '楚雄3D');
        $this->assign('rootDir', $this->_config["rootDir"]);
        $this->render();
    }
}