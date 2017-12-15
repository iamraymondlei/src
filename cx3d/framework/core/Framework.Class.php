<?php
/**
 * Description of Framework
 *
 * @author raymond.lui
 */

class Framework {

    private static $_config = [];

    public static function run() {
        self::init();
        self::autoload();
        self::setReporting();
        self::removeMagicQuotes();
        self::unRegisterGlobals();
        self::setDbConfig();
        self::route();
    }

    // Initialization
    private static function init()
    {
        // Define path constants
        define("DS", DIRECTORY_SEPARATOR);
        define("ROOT", getcwd() . DS);

        define("APP_PATH", ROOT . 'application' . DS);
        define("FRAMEWORK_PATH", ROOT . "framework" . DS);
        define("PUBLIC_PATH", ROOT . "public" . DS);
        define("CONFIG_PATH", ROOT . "config" . DS);

        define("CONTROLLER_PATH", APP_PATH . "controllers" . DS);
        define("MODEL_PATH", APP_PATH . "models" . DS);
        define("VIEW_PATH", APP_PATH . "views" . DS);

        define("CORE_PATH", FRAMEWORK_PATH . "core" . DS);
        define("DB_PATH", FRAMEWORK_PATH . "database" . DS);
        define("UPLOAD_PATH", PUBLIC_PATH . "files" . DS);
        define("LIB_PATH", FRAMEWORK_PATH . "libraries" . DS);
        define("HELPER_PATH", FRAMEWORK_PATH . "helpers" . DS);

        // Load configuration file
        self::$_config = require (CONFIG_PATH . 'config.php');

        // Define platform, controller, action, for example:
        // index.php?p=admin&c=Goods&a=add
        define("PLATFORM", isset($_REQUEST['p']) ? $_REQUEST['p'] : self::$_config['defaultPlatform']);
        define("CONTROLLER", isset($_REQUEST['c']) ? $_REQUEST['c'] : self::$_config['defaultController']);
        define("ACTION", isset($_REQUEST['a']) ? $_REQUEST['a'] : self::$_config['defaultAction']);
        define("CURR_CONTROLLER_PATH", CONTROLLER_PATH . PLATFORM . DS);
        define("CURR_VIEW_PATH", VIEW_PATH . PLATFORM . DS);

        // Load core classes
        require CORE_PATH . "Model.Class.php";
        require CORE_PATH . "View.Class.php";
        require CORE_PATH . "Controller.Class.php";
        require CORE_PATH . "Loader.Class.php";
        require DB_PATH . "Database.Class.php";

        // Load model classes
        require CORE_PATH . "ArticleImageModel.Class.php";
        require CORE_PATH . "ArticleListModel.Class.php";
        require CORE_PATH . "AudioListModel.Class.php";
        require CORE_PATH . "CatNodeListModel.Class.php";
        require CORE_PATH . "ImageListModel.Class.php";
        require CORE_PATH . "VideoListModel.Class.php";
        require CORE_PATH . "NewsModel.Class.php";
    }

    //自动加载类
    private static function autoload() {
        spl_autoload_register(array(__CLASS__,'loadClass'));
    }

    // 加载控制器和模型类
    private static function loadClass($className)
    {
        $controllers = CURR_CONTROLLER_PATH . $className . '.php';
        $models = MODEL_PATH . $className . '.php';

        if (file_exists($controllers)) {
            // 加载应用控制器类
            require_once $controllers;
        } elseif (file_exists($models)) {
            //加载应用模型类
            require_once $models;
        } else {
            // 错误代码
        }
    }

    // 路由处理
    private static function route()
    {
        $controllerName = ucfirst(CONTROLLER);// 获取控制器名
        $actionName = ACTION;// 获取动作名

        // 删除前后的“/”
        $url = trim($_SERVER['REQUEST_URI'], '/');
        $param = array();

        // 判断控制器和操作是否存在
        $controller = $controllerName . 'Controller';
        if (!class_exists($controller)) {
            exit($controller . '控制器不存在');
        }
        if (!method_exists($controller, $actionName)) {
            exit($actionName . '方法不存在');
        }

        // 如果控制器和操作名存在，则实例化控制器，因为控制器对象里面
        // 还会用到控制器名和操作名，所以实例化的时候把他们俩的名称也
        // 传进去。结合Controller基类一起看
        $dispatch = new $controller($controllerName, $actionName, self::$_config);

        // $dispatch保存控制器实例化后的对象，我们就可以调用它的方法，
        // 也可以像方法中传入参数，以下等同于：$dispatch->$actionName($param)
        call_user_func_array(array($dispatch, $actionName), $param);
    }

    // 检测开发环境
    private static function setReporting()
    {
        if (WEB_DEBUG === true) {
            error_reporting(E_ALL);
            ini_set('display_errors','On');
        } else {
            error_reporting(E_ALL);
            ini_set('display_errors','Off');
            ini_set('log_errors', 'On');
        }
    }

    // 删除敏感字符
    private static function stripSlashesDeep($value)
    {
        $value = is_array($value) ? array_map(array(__CLASS__, 'stripSlashesDeep'), $value) : stripslashes($value);
        return $value;
    }

    // 检测敏感字符并删除
    private static function removeMagicQuotes()
    {
        if (get_magic_quotes_gpc()) {
            $_GET = isset($_GET) ? self::stripSlashesDeep($_GET ) : '';
            $_POST = isset($_POST) ? self::stripSlashesDeep($_POST ) : '';
            $_COOKIE = isset($_COOKIE) ? self::stripSlashesDeep($_COOKIE) : '';
            $_SESSION = isset($_SESSION) ? self::stripSlashesDeep($_SESSION) : '';
        }
    }

    // 检测自定义全局变量并移除。因为 register_globals 已经弃用，如果
    // 已经弃用的 register_globals 指令被设置为 on，那么局部变量也将
    // 在脚本的全局作用域中可用。 例如， $_POST['foo'] 也将以 $foo 的
    // 形式存在，这样写是不好的实现，会影响代码中的其他变量。 相关信息，
    // 参考: http://php.net/manual/zh/faq.using.php#faq.register-globals
    private static function unRegisterGlobals()
    {
        if (ini_get('register_globals')) {
            $array = array('_SESSION', '_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');
            foreach ($array as $value) {
                foreach ($GLOBALS[$value] as $key => $var) {
                    if ($var === $GLOBALS[$key]) {
                        unset($GLOBALS[$key]);
                    }
                }
            }
        }
    }

    // 配置数据库信息
    private static function setDbConfig()
    {
        if (self::$_config['db']) {
            Model::$config = self::$_config;
        }
    }
}
