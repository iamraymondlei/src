<?php
/**
 * Description of IndexController
 *
 * @author icm
 */
//错误显示
ini_set('display_errors', 1);
error_reporting(E_ALL);

class FileController extends Controller {
    
    public function listAction(){
        $userInfo = $_SESSION['userInfo'];
        if(!$userInfo || !$userInfo["UserId"]){
            header('Location:http://localhost/datawh/');
        }
        else{
            include  CURR_VIEW_PATH . "/file/fileList.php";
        }
    }
    
    public function addAction(){
        $userInfo = $_SESSION['userInfo'];
        if(!$userInfo || !$userInfo["UserId"]){
            header('Location:http://localhost/datawh/');
        }
        else{
            include  CURR_VIEW_PATH . "/file/setFile.php";
        }
    }
    
    public function updateAction(){
        $userInfo = $_SESSION['userInfo'];
        if(!$userInfo || !$userInfo["UserId"]){
            header('Location:http://localhost/datawh/');
        }
        else{
            include  CURR_VIEW_PATH . "/file/setFile.php";
        }
    }
    
    public function detailAction(){
        $userInfo = $_SESSION['userInfo'];
        if(!$userInfo || !$userInfo["UserId"]){
            header('Location:http://localhost/datawh/');
        }
        else{
            include  CURR_VIEW_PATH . "/file/fileDetail.php";
        }
    }
}
