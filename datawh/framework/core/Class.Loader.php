<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Loader
 *
 * @author icm
 */
class Loader {
    // Load library classes
    public function library($lib){
        include LIB_PATH . "Class.$lib.php";
    }

    // loader helper functions. Naming conversion is xxx_helper.php;
    public function helper($helper){
        include HELPER_PATH . "Fn.{$helper}_helper.php";
    }
}
