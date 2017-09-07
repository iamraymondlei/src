<?php
/**
 * Description of IndexController
 *
 * @author icm
 */
//错误显示
ini_set('display_errors', 1);
error_reporting(E_ALL);

class AnalysisController extends Controller {
    
    public function ProductPriceAction(){
        $userInfo = $_SESSION['userInfo'];
        if(!$userInfo || !$userInfo["UserId"]){
            header('Location:http://localhost/datawh/');
        }
        else{
            include  CURR_VIEW_PATH . "/analysis/productPrice.php";
        }
    }
    
}
