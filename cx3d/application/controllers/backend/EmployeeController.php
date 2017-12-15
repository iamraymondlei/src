<?php
/**
 * Created by PhpStorm.
 * User: icm
 * Date: 2017/9/11
 * Time: 16:15
 */

class EmployeeController extends Controller
{
    // 首页方法
    public function index()
    {
        $params = array();
        if(isset($_GET['k']) && strlen($_GET['k']) ){
            $params["keyword"] = $_GET['k'];
            $this->assign('k', $params["keyword"]);
        }
        if(isset($_GET['s']) && strlen($_GET['s']) ){
            $params["state"] = $_GET['s'];
            $this->assign('s', $params["state"]);
        }
        if(isset($_GET['dt']) && strlen($_GET['dt']) ){
            $params["type"] = $_GET['dt'];
            $this->assign('dt', $params["type"]);
        }
        if(isset($_GET['pi']) && strlen($_GET['pi']) ){
            $params["pIndex"] = $_GET['pi'];
            $this->assign('pi', $params["pIndex"]);
        }
        if(isset($_GET['ps']) && strlen($_GET['ps']) ){
            $params["pSize"] = $_GET['ps'];
            $this->assign('ps', $params["pSize"]);
        }
        if(isset($_GET['ob']) && strlen($_GET['ob']) ){
            $params["order"] = $_GET['ob'];
            $this->assign('ob', $params["order"]);
        }
        if(isset($_GET['sb']) && strlen($_GET['sb']) ){
            $params["sort"] = $_GET['sb'];
            $this->assign('sb', $params["sort"]);
        }

        $items = (new EmployeeModel("EmployeeUploadData"))->search($params);
        $dataType = (new EmployeeModel("EmployeeUploadDataType"))->getDataType();
        $itemCount = (new EmployeeModel("EmployeeUploadDataType"))->getCount($params);

        $this->assign('rootDir', $this->_config["rootDir"]);
        $this->assign('title', '员工感悟');

        $this->assign('items', $items);
        $this->assign('itemCount', $itemCount);
        $this->assign('dataType', $dataType);
        $this->render();
    }

    // 更新记录
    public function update()
    {
        $data = array();
        if(isset($_GET['us'])) {$data['State'] = $_GET['us'];}
        if(isset($_GET['usp'])) {$data['StickyPost'] = $_GET['usp'];}

        if(isset($_GET['k']) && strlen($_GET['k']) ){ $this->assign('k', $_GET['k']); }
        if(isset($_GET['s']) && strlen($_GET['s']) ){ $this->assign('s', $_GET["s"]); }
        if(isset($_GET['dt']) && strlen($_GET['dt']) ){ $this->assign('dt', $_GET["dt"]); }
        if(isset($_GET['pi']) && strlen($_GET['pi']) ){ $this->assign('pi', $_GET["pi"]); }
        if(isset($_GET['ps']) && strlen($_GET['ps']) ){ $this->assign('ps', $_GET["ps"]); }
        if(isset($_GET['ob']) && strlen($_GET['ob']) ){ $this->assign('ob', $_GET['ob']); }
        if(isset($_GET['sb']) && strlen($_GET['sb']) ){ $this->assign('sb', $_GET["sb"]); }

        $result = (new EmployeeModel("EmployeeUploadData"))->update($_GET['id'], $data);

        $this->assign('rootDir', $this->_config["rootDir"]);
        $this->assign('title', '修改成功');
        $this->assign('count', $result);
        $this->render();
    }
}