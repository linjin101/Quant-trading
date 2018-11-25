<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
set_time_limit(0);   // 设置脚本最大执行时间 为0 永不过期

include_once '../vendor/autoload.php'; //加载composer
include_once '../common/config.php';
include_once '../common/mongodb.php'; //加载数据库操作类 
include_once '../common/mongodbStock.php'; //加载股票专用数据操作类 
include_once '../common/common.php';

use QL\QueryList;

$logfile = __ROOT_PATH__.'/logStockGN.txt';

$MongoDBOP = new MongoDBOP();
// $options指定选择列[ 'projection' =>['code'=>1]]
$rowlist = $MongoDBOP->mongoSearch('test.stocklist', [], ['projection' => ['code' => 1, '_id' => 0]]);
//股票列表获取
$stockList = array();
foreach ($rowlist as $key) {

    array_push($stockList, $key['code']);
}
//$stockList = ['300059', '300015', '600460', '300124'];
$i = 1;
foreach ($stockList as $stockCode) {
    $url_page = 'http://basic.10jqka.com.cn/' . $stockCode . '/concept.html';
    $string = getCurl($url_page);
    //$str_encode = mb_convert_encoding($string, 'UTF-8', 'GBK');
    //解析股票名称和代码
    $stockName = QueryList::html($string)->find('h1>a')->attrs('title');
    $arrStock = $stockName->all();
    // 基本的数据校验Array( [0] => 浦发银行 600000 )
    if (!empty($arrStock[0])) {
        $arrStockName = explode(' ', $arrStock[0]);
        if (!empty($arrStockName[0]) && !empty($arrStockName[1])) {
            //解析股票概念
            $table = QueryList::html($string)->find('table');
            $tableRows = $table->find('tr:gt(0)')->find('.gnName')->texts();
            $arrGN = $tableRows->all();

            $arrStockGN = array('code' => $arrStockName[1], 'name' => $arrStockName[0], 'GN' => $arrGN);

            
            $MongoDBOP->mongoInsert("test.stocktemp2", $arrStockGN);
            print_r($arrStockGN);
            if ($i % 3 == 0) {
                sleep(  rand(1,5)  );
            }
        }else{
            file_put_contents($logfile, "inline:".$i.":".$stockCode."\r\n", FILE_APPEND | LOCK_EX);
        }
    }
    else
    {
        file_put_contents($logfile, $i.":".$stockCode."\r\n", FILE_APPEND | LOCK_EX);
    }
    $i++;
}
/*
  概念数组多条件查询db.getCollection('stocktemp2').find( { $and: [ { GN: { $eq: '5G' } }, { GN: { $eq: '军工' } }, { GN: { $eq: '创投' } } ] })
  查询参数输入：[ '$and'=> [ [ 'GN'=> [ '$eq'=>'上海国资改革' ]], [ 'GN'=> [ '$eq'=>'上海自贸区' ]], [ 'GN'=> [ '$eq'=>'MSCI概念' ]] ] ]
  cd D:\dev\Quant-trading\php\stock\caiji
 * D:\dev\phpStudy\PHPTutorial\php\php-7.2.1-nts\php stockgn.php
 */
$rowlist = $MongoDBOP->mongoSearch('test.stocktemp2', []);
//print_r($rowlist);

