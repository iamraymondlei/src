<?php
/**
 * Created by PhpStorm.
 * User: icm
 * Date: 2017/9/11
 * Time: 16:15
 */

class PromicrovideoController extends Controller
{
    // 首页方法
    public function index()
    {
        $params = array();
        if(isset($_GET['ci']) && strlen($_GET['ci']) ){
            $params["catId"] = $_GET['ci'];
            $this->assign('ci', $params["catId"]);
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
        if(isset($_GET['id']) && strlen($_GET['id']) ){
            $params["newsIds"] = $_GET['id'];
            $this->assign('id', $params["newsIds"]);
        }

        $newsList = (new PromicrovideoModel("CatNodeList"))->search($params);
        $this->assign('rootDir', $this->_config["rootDir"]);
        $this->assign('title', '专业文化建设微视频');

        $this->assign('items', $newsList["Result"]);
        $this->assign('itemCount', $newsList["Count"]);
        $this->assign('catId', $params["catId"]);
        $this->render();
    }

    // 修改记录
    public function edit()
    {
        $params = array();
        if(isset($_GET['ci']) && strlen($_GET['ci']) ){
            $params["catId"] = $_GET['ci'];
            $this->assign('ci', $params["catId"]);
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
        if(isset($_GET['id']) && strlen($_GET['id']) ){
            $params["newsId"] = $_GET['id'];
            $this->assign('id', $params["newsId"]);
        }
        if(isset($_GET['vid']) && strlen($_GET['vid']) ){
            $params["videoListId"] = $_GET['vid'];
            $this->assign('vid', $params["videoListId"]);
        }

        $removeHtmlTag = FALSE;
        $news = (new PromicrovideoModel("CatNodeList"))->search($params,$removeHtmlTag);
        $this->assign('rootDir', $this->_config["rootDir"]);
        $this->assign('title', $news["Result"][0]["Title"]);
        $this->assign('items', $news["Result"][0]);
        $this->render();
    }

    // 更新记录
    public function update()
    {
        if(isset($_GET['ci']) && strlen($_GET['ci']) ){ $this->assign('ci', $_GET['ci']); }
        if(isset($_GET['pi']) && strlen($_GET['pi']) ){ $this->assign('pi', $_GET["pi"]); }
        if(isset($_GET['ps']) && strlen($_GET['ps']) ){ $this->assign('ps', $_GET["ps"]); }
        if(isset($_GET['ob']) && strlen($_GET['ob']) ){ $this->assign('ob', $_GET['ob']); }
        if(isset($_GET['sb']) && strlen($_GET['sb']) ){ $this->assign('sb', $_GET["sb"]); }
        if(isset($_GET['id']) && strlen($_GET['id']) ){ $this->assign('id', $_GET["id"]); }

        $data = array();
        $data['NewsId'] = $_GET['id'];
        $data["Title"] = $_POST["Title"];
        $data["PreviewImageUrl"] = $_POST["PreviewImageUrl"];
        $data["VideoUrl"] = $_POST["VideoUrl"];
        if(isset($_GET['vid'])){
            $result = (new PromicrovideoModel("News"))->update($_GET['vid'],$data);
            $this->assign('title', '修改成功');
        }
        else{
            $result = (new PromicrovideoModel("News"))->add($data);
            $this->assign('title', '添加成功');
        }

        $this->assign('rootDir', $this->_config["rootDir"]);
        $this->assign('count', $result);
        $this->render();
    }

    // 添加记录
    public function add()
    {
        if(isset($_GET['ci']) && strlen($_GET['ci']) ){ $this->assign('ci', $_GET['ci']); }
        if(isset($_GET['pi']) && strlen($_GET['pi']) ){ $this->assign('pi', $_GET["pi"]); }
        if(isset($_GET['ps']) && strlen($_GET['ps']) ){ $this->assign('ps', $_GET["ps"]); }
        if(isset($_GET['ob']) && strlen($_GET['ob']) ){ $this->assign('ob', $_GET['ob']); }
        if(isset($_GET['sb']) && strlen($_GET['sb']) ){ $this->assign('sb', $_GET["sb"]); }
        if(isset($_GET['id']) && strlen($_GET['id']) ){ $this->assign('id', $_GET["id"]); }

        $this->assign('rootDir', $this->_config["rootDir"]);
        $this->assign('title', '添加视频');
        $this->render();
    }
}