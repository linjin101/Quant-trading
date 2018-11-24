<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
 
include_once 'common/config.php';
include_once 'common/mongodb.php'; //加载数据库操作类 
include_once 'common/mongodbStock.php'; //加载数据库操作类 
include_once 'common/common.php';

 //查询概念是否变化，然后更新
$m = new MongoDBStock();
$t = $m->MongoUpdateQuery('test.stocktemp', ['code'=>'300059'], ['GN'=>['期货概念','互联网金融','互联网+','ddwerew']]);
var_dump($t);
$rowlist = $m->mongoSearch('test.stocktemp',['code'=>'300059'] );
print_r($rowlist);
/*
$m = new MongoDBOP();
$collection = 'test.stocktemp';
//查询股票是否存在
$sql= ['code'=>'300059'];
$rowlist = $m->mongoSearch($collection,$sql );
//查询概念数据是否修改
$GN = ['GN'=>['期货概念','互联网金融','互联网+','新找到概念ww']] ;
$sqlGN= ['code'=>'300059',$GN];
$rowlist = $m->mongoSearch($collection,$sqlGN );
//找不到就更新
if ( empty($rowlist))
{ 
    $r = $m->mongoUpate($collection, $sql, ['$set' => $GN  ]); 
}

*/



//
//
//$sql= ['code'=>'300059','GN'=>['期货概念','互联网金融','互联网+']];
//$rowlist = $m->mongoSearch('test.stocktemp',['code'=>'300059'] );
//print_r($rowlist);


//$r = $m->mongoUpate('test.stocktemp', $sql, ['$set' => ['GN' => ['期货概念','互联网金融','互联网+','222']]]);
// 
//
//$rowlist = $m->mongoSearch('test.stocktemp',['code'=>'300059'] );
//print_r($rowlist);


//$bulk = new MongoDB\Driver\BulkWrite;
//$bulk->update(['code'=>'300059','GN'=>['期货概念','互联网金融','互联网+']], ['$set' => ['GN' => ['期货概念','互联网金融','互联网+','lll']]], ['upsert' => false]);
//$result = $m->manager->executeBulkWrite('test.stocktemp', $bulk);
//var_dump($result->getUpsertedIds());