<?php
/**
 * Description of IndexController
 *
 * @author icm
 */
class IndexController extends Controller {
    
    public function mainAction(){
        include CURR_VIEW_PATH . "main.html";

        $userModel = new UserModel("user");
        $users = $userModel->getUsers();
    }

    public function indexAction(){
        $userModel = new UserModel("User");
        $users = $userModel->getUsers();
        $userModel->destruct();
        // Load View template
        include  CURR_VIEW_PATH . "index.php";
    }

    public function menuAction(){
        include CURR_VIEW_PATH . "menu.html";
    }

    public function dragAction(){
        include CURR_VIEW_PATH . "drag.html";
    }

    public function topAction(){
        include CURR_VIEW_PATH . "top.html";
    }
}
