<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author icm
 */
interface IWebService{
    public function getRequestParams();
    public function checkBaseParams();
    public function checkWsParams();
    public function connectDB();
    public function closeDB();
    public function output();
}
