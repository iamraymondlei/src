<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Description of Class
 *
 * @author icm
 */
class Controller {
    // Base Controller has a property called $loader, it is an instance of Loader class(introduced later)
    protected $loader;

    public function __construct(){
        $this->loader = new Loader();
    }

    public function redirect($url,$message,$wait=0){
        if ($wait === 0){
            header("Location:$url");
        } else {
            include CURR_VIEW_PATH . "message.html";
        }
        exit;
    }
}
