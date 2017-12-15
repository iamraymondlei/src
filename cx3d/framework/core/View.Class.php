<?php
/**
 * Description of View
 *
 * @author raymond lui
 */
class View {
    protected $variables = array();
    protected $_controller;
    protected $_action;

    function __construct($controller, $action)
    {
        $this->_controller = strtolower($controller);
        $this->_action = strtolower($action);
    }

    // 分配变量
    public function assign($name, $value)
    {
        $this->variables[$name] = $value;
    }

    // 渲染显示
    public function render()
    {
        extract($this->variables);//从数组中将变量导入到当前的符号表
        $defaultHeader = CURR_VIEW_PATH . 'header.php';
        $defaultFooter = CURR_VIEW_PATH . 'footer.php';
        $defaultTop = CURR_VIEW_PATH . '/top.php';
        $defaultMenu = CURR_VIEW_PATH . '/menu.php';

        $controllerHeader = CURR_VIEW_PATH . $this->_controller . '/header.php';
        $controllerTop = CURR_VIEW_PATH . $this->_controller . '/top.php';
        $controllerMenu = CURR_VIEW_PATH . $this->_controller . '/menu.php';
        $controllerFooter = CURR_VIEW_PATH . $this->_controller . '/footer.php';
        $controllerLayout = CURR_VIEW_PATH . $this->_controller . '/' . $this->_action . '.php';

        // 页头文件
        if (file_exists($controllerHeader) && file_exists($controllerFooter) ) {
            include ($controllerHeader);
        } else {
            include ($defaultHeader);
        }

        //顶栏及菜单文件
        if (file_exists($controllerTop) && file_exists($controllerMenu)) {
            include ($controllerTop);
            include ($controllerMenu);
        }
        else{
            include ($defaultTop);
            include ($defaultMenu);
        }

        //主体页面
        include ($controllerLayout);

        // 页脚文件
        if (file_exists($controllerFooter)) {
            include ($controllerFooter);
        } else {
            include ($defaultFooter);
        }
    }
}
