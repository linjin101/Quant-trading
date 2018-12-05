<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * http://basic.10jqka.com.cn/api/stock/finance/300124_debt.json
 */

 set_time_limit(0);   // 设置脚本最大执行时间 为0 永不过期

include_once '../vendor/autoload.php'; //加载composer
include_once '../common/config.php';
include_once '../common/mongodb.php'; //加载数据库操作类 
include_once '../common/mongodbStock.php'; //加载股票专用数据操作类 
include_once '../common/common.php';

// 云财经采集
//$url_page = 'http://basic.10jqka.com.cn/api/stock/finance/300124_cash.json';
$url_page = 'https://www.yuncaijing.com/news/id_12130627.html';
$string = getCurl($url_page);

 echo $string;