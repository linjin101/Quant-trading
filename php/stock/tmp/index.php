<?php

/**
 * 说明：
 * 1、采集基于querylistv4.0.0版
 * 2、PHP版本7.0
 *
 * 注意：
 * 支持shell命令执行模式和web访问模式
 * shell示例：/usr/local/php7/bin/php /home/httpd/collect/index.php web base.php  
 * web示例：http://localhost/collection.com/index.php?rss_url=base.php&rss_type=web
 */
date_default_timezone_set('PRC'); //设置中国时区 
define('__ROOT_PATH__', str_replace('\\', '/', dirname(__FILE__)) . "/"); //网站根目录
include_once 'vendor/autoload.php'; //加载composer

include_once 'mongodb.php'; //加载数据库操作类 
include_once 'common.php';

use QL\QueryList;

$MongoDBOP = new MongoDBOP();
/*
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

    
    $MongoDBOP->mongoInsert("test.stocktemp", $arrStockGN);
}
*/ 
$result = $result = $MongoDBOP->mongoSearch("test.stocktemp", ['GN'=>'5G' , 'GN'=>'芯片概念']);
print_r($result);
//$result = $MongoDBOP->mongoSearch("test.arrStockGN", ['GN'=>'5G' , 'GN'=>'金融IC']);

//print_r($result);
// db.getCollection('arrStockGN').find({'GN':'5G' , 'GN':'金融IC'})
/*
//采集某页面所有的图片
$data = QueryList::get('http://data.10jqka.com.cn/market/rzrq/')->rules([  //设置采集规则
//   // 采集所有a标签的href属性
    'link' => ['a','href'],
//    // 采集所有a标签的文本内容
    'text' => ['#table1','html'] 
])->query()->getData();
//
//$html = mb_convert_encoding($data[0]['text'], 'UTF-8', 'GBK'); 

echo mb_detect_encoding($data[0]['text']); 
//打印结果
print_r($data->all());


$mongodbC = new MongoDBOP();
$mongodbC->mongotest(); 
*/
        
        
