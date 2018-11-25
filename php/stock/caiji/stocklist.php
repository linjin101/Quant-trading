<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * http://quote.eastmoney.com/stocklist.html
 */
include_once '../vendor/autoload.php'; //加载composer
include_once '../common/config.php';
include_once '../common/mongodb.php'; //加载数据库操作类 
include_once '../common/mongodbStock.php'; //加载股票专用数据操作类 
include_once '../common/common.php';

use QL\QueryList;

$MongoDBOP = new MongoDBOP();
//页面地址
$url_page = 'http://quote.eastmoney.com/stocklist.html';
//用QueryList抓取页面Html并且转码
$string = QueryList::get($url_page)->removeHead()->encoding('UTF-8','GB2312')->getHtml();
//解析页面HTML
$stockName = QueryList::html($string)->find('#quotesearch ul li a')->texts()->all();
$stockLink = QueryList::html($string)->find('#quotesearch ul li a')->attrs('href')->all();

foreach ($stockName as $link => $value) {
    $strStockName = $stockName[$link];
    $pos = strrpos($strStockName, '(');
    if (!empty($pos)) {
        $strCode = substr($strStockName, $pos + 1, 6);
        $name = substr($strStockName, 0, $pos);
        //第一个代码是6,0,3
        $posCode = substr($strCode, 0, 1);
        if ($posCode == '6' || $posCode == '0' || $posCode == '3') {
            if (!empty($strCode) && !empty($name)) {
                $MongoDBOP->mongoInsert("test.stocklist", ['href' => $stockLink[$link], 'name' => $name, 'code' => $strCode]);
            }
        }
    }
}
$rowlist = $MongoDBOP->mongoSearch('test.stocklist');
print_r($rowlist);
