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

// 东方财富标红数据采集
//$url_page = 'http://basic.10jqka.com.cn/api/stock/finance/300124_cash.json';
$url_page = 'http://newsapi.eastmoney.com/kuaixun/v1/getlist_102_ajaxResult_100_1_.html?r=&_=';
$string = getCurl($url_page);


$pos1 = stripos($string, 'var ajaxResult=');

$string = substr($string,$pos1+15);
$arrStr = object_array(json_decode($string));

$redMsg = array();
foreach($arrStr['LivesList'] as $msg=>$text){ 
    if ( $text['titlestyle'] == '3' ){
        array_push($redMsg, $text);
    }
}

foreach($redMsg as $line){
    echo $line['id'].$line['url_w'].$line['title'].$line['title'].$line['digest'].$line['showtime'].$line['ordertime'].'<br><br>';
}
//print_r( $arrStr['LivesList']  );
//print_r($arrStr);