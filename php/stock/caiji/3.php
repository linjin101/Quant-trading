<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once '../common/config.php';
include_once '../common/mongodb.php'; //加载数据库操作类 
include_once '../common/mongodbStock.php'; //加载股票专用数据操作类 
include_once '../common/common.php';


$MongoDBOP = new MongoDBOP();

function getEmptyStockGN($MongoDBOP) {
    $arrStock = array();
// $options指定选择列[ 'projection' =>['code'=>1]]
    $stocklist = $MongoDBOP->mongoSearch('test.stocklist', [], ['projection' => ['code' => 1, 'name' => 1, '_id' => 0]]);
    print_r($stocklist);
    $arrMerge = array();
    foreach ($stocklist as $key) {
        $stockgnlist = $MongoDBOP->mongoSearch('test.stockgn', ['code' => $key['code']], ['projection' => ['GN' => 1, '_id' => 0]]);
        //获取股票代码=>空，概念GN=>空的行
        if (empty($stockgnlist) || empty($stockgnlist[0]['GN'])) {
            array_push($arrStock, $key);
            //echo $key['code'].'<br>'; 
        }
    }
    return $arrStock;
}

$emptyStock = getEmptyStockGN($MongoDBOP);
print_r($emptyStock);