<?php
/**
 * Description of IndexController
 *
 * @author icm
 */
//错误显示
ini_set('display_errors', 1);
error_reporting(E_ALL);

class CatController extends Controller {
    
    public function filelistAction(){
        $userInfo = $_SESSION['userInfo'];
        if(!$userInfo || !$userInfo["UserId"]){
            header('Location:http://localhost/cat/');
        }
        else{
            include  CURR_VIEW_PATH . "/cat/setCat.php";
        }
    }
    
    public function productlistAction(){
        $userInfo = $_SESSION['userInfo'];
        if(!$userInfo || !$userInfo["UserId"]){
            header('Location:http://localhost/expense/');
        }
        else{
            include  CURR_VIEW_PATH . "/expense/setCat.php";
        }
    }
}
