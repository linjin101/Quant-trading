<?php
/*
 * 同花顺股票概念数据采集
 */
set_time_limit(0);   // 设置脚本最大执行时间 为0 永不过期

include_once '../vendor/autoload.php'; //加载composer
include_once '../common/config.php';
include_once '../common/mongodb.php'; //加载数据库操作类 
include_once '../common/mongodbStock.php'; //加载股票专用数据操作类 
include_once '../common/common.php';

use QL\QueryList;

// $options指定选择列['projection' => ['code' => 1,'name'=> 1,'_id' => 0]] ,股票列表获取
//$rowlist = $MongoDBOP->mongoSearch('test.stocklist', [], ['projection' => ['code' => 1,'name'=> 1,'_id' => 0]]);
//检查没有采集成功的code和gn为空的重新采集
$m = new MongoDBStock();
$rowlist = $m->mongoSearch('test.stocklist', [], ['projection' => ['code' => 1, 'name' => 1, '_id' => 0]]); // ['code'=>'300343']

//$rowlist = $m->getEmptyStockGN();
$i = 1;
foreach ($rowlist as $stockCode) {
    $url_page = 'http://basic.10jqka.com.cn/' . $stockCode['code'] . '/concept.html';// $stockCode['code']

    $string = QueryList::get($url_page)->removeHead()->encoding('UTF-8', 'GBK')->getHtml();
    //解析常规概念
    $table = QueryList::html($string);
    $tableRows = $table->find('.gnContent')->find('tr:gt(0)')->find('.gnName')->texts();
    $arrGN = $tableRows->all();
    
    //解析新兴概念,其他概念
    $tableNew = $table->find('.gnContent')->find('tr:gt(0)')->find('.gnStockList')->texts();
    $arrGNnew = $tableNew->all();
    //常规概念+新兴概念+其他概念
    $arrGN = array_merge($arrGN,$arrGNnew);
    
    $arrStockGN = array('code' => $stockCode['code'], 'name' => $stockCode['name'], 'GN' => $arrGN);
    $m->mongoInsert("test.stockgn", $arrStockGN); // date("Y-m-d")
    /**
     * 释放资源，销毁内存占用。在涉及到循环采集大量网页的场景下，这个方法是很有用的。
     * 注意：此方法并不是销毁QueryList对象，只是销毁phpQuery Document占用的内存，
     * 所以调用此方法后，原先设置过HTML的QueryList对象都会丢失设置的HTML，
     * 需要重新调用html或者get方法设置HTML.
     */
    $table->destruct();
    if ($i % 3 == 0) {
        sleep(rand(1, 3));
    }
    /*     * *************************************************************************
     * Debug
     * ************************************************************************* */
    echo $i . "\r\n";
    print_r($arrStockGN); 

    $i++;
}
/*
  概念数组多条件查询db.getCollection('stocktemp2').find( { $and: [ { GN: { $eq: '5G' } }, { GN: { $eq: '军工' }}] } )
  查询参数输入：[ '$and'=> [ [ 'GN'=> [ '$eq'=>'上海国资改革' ]], [ 'GN'=> [ '$eq'=>'上海自贸区' ]]  ] ]
cd D:\dev\Quant-trading\php\stock\caiji
D:
D:\dev\phpStudy\PHPTutorial\php\php-7.2.1-nts\php stockgn.php
 * 
 * 
cd E:\git\Quant-trading\php\stock\caiji\
E:
G:\phpStudy\PHPTutorial\php\php-7.2.1-nts\php stockgn.php
 */
