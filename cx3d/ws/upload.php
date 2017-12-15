<?php
/**
 * Created by PhpStorm.
 * User: icm
 * Date: 2017/9/27
 * Time: 14:42
 */
if (isset($_POST['upload'])) {
    var_dump($_FILES);
    //move_uploaded_file($_FILES['upfile']['tmp_name'], 'up_tmp/'.time().'.dat');
    //header('location: test.php');
}