<?php
/**
 * Description of IndexController
 *
 * @author icm
 */
//错误显示
ini_set('display_errors', 1);
error_reporting(E_ALL);

class ExpenseController extends Controller {
    
    public function listAction(){
        $userInfo = $_SESSION['userInfo'];
        if(!$userInfo || !$userInfo["UserId"]){
            header('Location:http://localhost/datawh/');
        }
        else{
            include  CURR_VIEW_PATH . "/expense/expenseList.php";
        }
    }
    
    public function addAction(){
        $userInfo = $_SESSION['userInfo'];
        if(!$userInfo || !$userInfo["UserId"]){
            header('Location:http://localhost/datawh/');
        }
        else{
            include  CURR_VIEW_PATH . "/expense/setExpense.php";
        }
    }
    
    public function updateAction(){
        $userInfo = $_SESSION['userInfo'];
        if(!$userInfo || !$userInfo["UserId"]){
            header('Location:http://localhost/datawh/');
        }
        else{
            include  CURR_VIEW_PATH . "/expense/setExpense.php";
        }
    }
}
