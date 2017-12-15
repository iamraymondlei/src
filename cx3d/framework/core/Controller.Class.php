<?php
/**
 * Description of Class
 *
 * @author raymond.lui
 */
class Controller {
    protected $_controller;
    protected $_action;
    protected $_view;
    protected $_config;

    // 构造函数，初始化属性，并实例化对应模型
    public function __construct($controller, $action, $config){
        $this->checkSession();
        $this->_controller = $controller;
        $this->_action = $action;
        $this->_config = $config;
        $this->_view = new View($controller, $action);
    }

    //检查session
    public function checkSession()
    {
        if(!isset($_SESSION['userInfo']) && $_GET['c'] !== "home"){
            //header('Location: 404.php');
        }
    }

    // 分配变量
    public function assign($name, $value)
    {
        $this->_view->assign($name, $value);
    }

    // 渲染视图
    public function render()
    {
        $this->_view->render();
    }
}
