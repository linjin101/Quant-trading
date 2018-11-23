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
$url_page = 'http://quote.eastmoney.com/stocklist.html';
//$string = getCurl($url_page);
//$string = mb_convert_encoding($string, 'UTF-8', 'gb2312');
$string = QueryList::get($url_page)->getHtml();
$tt = QueryList::html($string)->find('a')->attrs('href')->all();
print_r($tt);
exit();


$stockName = QueryList::html($string)->find('a')->texts()->all();
$stockLink = QueryList::html($string)->find('a')->attrs('href')->all();
//$arrStockName = $stockName->attrs('href')->all(); 
//$arrStockTitle = $stockName->texts()->all(); 
//print_r($stockName  );
//print_r($stockLink  ); 
//exit(); 
//echo $string; 
//exit();
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
