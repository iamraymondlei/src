<?php
/**
 * Created by PhpStorm.
 * User: icm
 * Date: 2017/9/11
 * Time: 16:15
 */

class ConceptController extends Controller
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

        $newsList = (new ConceptModel("CatNodeList"))->search($params);

        $this->assign('rootDir', $this->_config["rootDir"]);
        $this->assign('title', '理念展示');

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

        $removeHtmlTag = FALSE;
        $news = (new ConceptModel("CatNodeList"))->search($params,$removeHtmlTag);
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

        $data = array();
        $data["NewsId"] = $_GET['id'];
        $data["Article"]['ArticleListId'] = $_GET['aid'];
        $data["Article"]["Title"] = $_POST["ArticleTitle"];
        $data["Article"]["SubTitle"] = $_POST["ArticleSubTitle"];
        $data["Article"]["RepresentImageUrl"] = $_POST['ArticleRepresentImageUrl'];
        $data["Article"]["Content"] = $_POST["ArticleContent"];
        $data['Video']['VideoListId'] = $_GET['vid'];
        $data['Video']["VideoUrl"] = $_POST["VideoUrl"];

        $result = (new ConceptModel("News"))->update($_GET['aid'],$data);

        $this->assign('rootDir', $this->_config["rootDir"]);
        $this->assign('title', '修改成功');
        $this->assign('count', $result);
        $this->render();
    }
}