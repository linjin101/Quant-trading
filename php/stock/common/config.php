<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
define('__MONGODB_HOST__', 'mongodb://192.168.3.12:27017');
define('__CURL_TIMEOUT__',20);

date_default_timezone_set('PRC'); //设置中国时区 
define('__ROOT_PATH__', str_replace('\\', '/', dirname(__FILE__)) . "/"); //网站根目录