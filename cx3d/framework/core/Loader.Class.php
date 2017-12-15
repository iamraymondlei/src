<?php
/**
 * Description of Loader
 *
 * @author raymond.lui
 */
class Loader {
    // Load library classes
    public function library($lib){
        include LIB_PATH . "$lib.Class.php";
    }

    // loader helper functions. Naming conversion is xxx_helper.php;
    public function helper($helper){
        include HELPER_PATH . "Fn.{$helper}_helper.php";
    }
}
