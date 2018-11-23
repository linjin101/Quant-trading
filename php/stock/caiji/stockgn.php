<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once '../vendor/autoload.php'; //加载composer
include_once '../common/config.php';
include_once '../common/mongodb.php'; //加载数据库操作类 
include_once '../common/mongodbStock.php'; //加载股票专用数据操作类 
include_once '../common/common.php';

use QL\QueryList;
$MongoDBOP = new MongoDBOP();

$stockList = ['300059', '300015', '600460', '300124'];
foreach ($stockList as $stockCode) {
    $url_page = 'http://basic.10jqka.com.cn/'.$stockCode.'/concept.html'; 
    $string = getCurl($url_page);
//$str_encode = mb_convert_encoding($string, 'UTF-8', 'GBK');
//解析股票名称和代码
    $stockName = QueryList::html($string)->find('h1>a')->attrs('title');
    $arrStockName = $stockName->all();
    $arrStockName = explode(' ', $arrStockName[0]);

//解析股票概念
    $table = QueryList::html($string)->find('table');
    $tableRows = $table->find('tr:gt(0)')->find('.gnName')->texts();
    $arrGN = $tableRows->all();

    $arrStockGN = array('code' => $arrStockName[1], 'name' => $arrStockName[0], 'GN' => $arrGN);

    
    $MongoDBOP->mongoInsert("test.stocktemp2", $arrStockGN);
}

$rowlist = $MongoDBOP->mongoSearch('test.stocktemp2', []);
print_r($rowlist);

