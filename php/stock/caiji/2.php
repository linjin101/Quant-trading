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

$logfile = __ROOT_PATH__.'/logtemp.txt';
echo $logfile;
$rd =  rand(1,10);
echo $rd;
file_put_contents($logfile, $rd."\r\n", FILE_APPEND | LOCK_EX);
echo '<br>';
$i = 4000;
while($i--){
    //echo $i.'<br>';
    if( $i % 20 == 0 ) 
    {
                echo  $i. '<br>';
                 file_put_contents($logfile, $i. '<br>'."\r\n", FILE_APPEND | LOCK_EX);
    }
    
}

$MongoDBOP = new MongoDBOP();
// 概念数组多条件查询db.getCollection('stocktemp2').find( { $and: [ { GN: { $eq: '5G' } }, { GN: { $eq: '军工' } }, { GN: { $eq: '创投' } } ] } )
// 查询参数输入：[ '$and'=> [ [ 'GN'=> [ '$eq'=>'上海国资改革' ]], [ 'GN'=> [ '$eq'=>'上海自贸区' ]], [ 'GN'=> [ '$eq'=>'MSCI概念' ]] ] ]
$rowlist = $MongoDBOP->mongoSearch('test.stocktemp2', [ '$and'=> [ [ 'GN'=> [ '$eq'=>'上海国资改革' ]], [ 'GN'=> [ '$eq'=>'上海自贸区' ]], [ 'GN'=> [ '$eq'=>'MSCI概念' ]] ] ]);
print_r( $rowlist );