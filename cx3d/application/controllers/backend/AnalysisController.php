<?php
/**
 * Created by PhpStorm.
 * User: icm
 * Date: 2017/9/11
 * Time: 16:15
 */

class AnalysisController extends Controller
{
    // 首页方法
    public function scene()
    {
        $id = "1";
        $sceneList = $this->_config['analysis']['scene'];
        $dateTime = date("m/d/Y",strtotime('-1 month')).",".date("m/d/Y");
        $pageUrl = $sceneList[1]["url"];

        if(isset($_GET['id'])) {
            $id = $_GET['id'];
            $pageUrl = $sceneList[$_GET['id']]["url"];
        }
        if(isset($_GET['dt'])) {
            $dateTime = $_GET['dt'];
        }

        list($start,$end) = explode(",",$dateTime);
        $dateTime = date('Y-m-d',strtotime($start)).",".date('Y-m-d',strtotime($end));
        $visitUser["visitsSummary"] = $pageUrl."&moduleToWidgetize=VisitsSummary&actionToWidgetize=getEvolutionGraph"."&date=".$dateTime;
        $visitUser["visitTime"] =  $pageUrl."&moduleToWidgetize=VisitTime&actionToWidgetize=getVisitInformationPerServerTime"."&date=".$dateTime;
        $visitUser["visitorInterest"] =  $pageUrl."&moduleToWidgetize=VisitorInterest&actionToWidgetize=getNumberOfVisitsPerVisitDuration&viewDataTable=graphPie"."&date=".$dateTime;

        $this->assign('rootDir', $this->_config["rootDir"]);
        $this->assign('title', '展厅统计');
        $this->assign('items',$sceneList);
        $this->assign('visitUser',$visitUser);
        $this->assign('start',$start);
        $this->assign('end',$end);
        $this->assign('id',$id);
        $this->render();
    }

    public function map()
    {
        $id = "1";
        $sceneList = $this->_config['analysis']['map'];
        $dateTime = date("m/d/Y",strtotime('-1 month')).",".date("m/d/Y");
        $pageUrl = $sceneList[1]["url"];

        if(isset($_GET['id'])) {
            $id = $_GET['id'];
            $pageUrl = $sceneList[$_GET['id']]["url"];
        }
        if(isset($_GET['dt'])) {
            $dateTime = $_GET['dt'];
        }

        list($start,$end) = explode(",",$dateTime);
        $pageUrl.= "&date=".date('Y-m-d',strtotime($start)).",".date('Y-m-d',strtotime($end));

        $this->assign('rootDir', $this->_config["rootDir"]);
        $this->assign('title', '地域分布');
        $this->assign('items',$sceneList);
        $this->assign('pageUrl',$pageUrl);
        $this->assign('start',$start);
        $this->assign('end',$end);
        $this->assign('id',$id);
        $this->render();
    }

    public function user()
    {
        $sceneList = $this->_config['analysis']['user'];
        $dateTime = date("m/d/Y",strtotime('-1 month')).",".date("m/d/Y");
        $pageUrl_user = $sceneList[1]["url"];
        $pageUrl_returnUser = $sceneList[2]["url"];

        if(isset($_GET['dt'])) {
            $dateTime = $_GET['dt'];
        }

        list($start,$end) = explode(",",$dateTime);
        $dateTime = date('Y-m-d',strtotime($start)).",".date('Y-m-d',strtotime($end));
        $visitsUser["visitsSummary"] = $pageUrl_user."&moduleToWidgetize=VisitsSummary&actionToWidgetize=getEvolutionGraph"."&date=".$dateTime;
        $visitsUser["visitTime"] =  $pageUrl_user."&moduleToWidgetize=VisitTime&actionToWidgetize=getVisitInformationPerServerTime"."&date=".$dateTime;
        $visitsUser["visitorInterest"] =  $pageUrl_user."&moduleToWidgetize=VisitorInterest&actionToWidgetize=getNumberOfVisitsPerVisitDuration&viewDataTable=graphPie"."&date=".$dateTime;

        $returnUser= $pageUrl_returnUser."&date=".date('Y-m-d',strtotime($start)).",".date('Y-m-d',strtotime($end));

        $this->assign('rootDir', $this->_config["rootDir"]);
        $this->assign('title', '用户分析');
        $this->assign('items',$sceneList);
        $this->assign('visitUser',$visitsUser);
        $this->assign('returnUser',$returnUser);
        $this->assign('start',$start);
        $this->assign('end',$end);
        $this->render();
    }
}