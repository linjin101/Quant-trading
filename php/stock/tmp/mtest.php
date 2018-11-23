<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include_once 'mongodb.php'; //加载数据库操作类 
include_once 'common.php';

/*
$m = new MongoDBOP();
//$rowlist = $m->mongoSearch('test.stocktemp', ['$or'=>[['GN'=>'5G'],['GN'=> '互联网金融' ]]]);
//print_r($rowlist);
 
$bulk = new MongoDB\Driver\BulkWrite;
$bulk->insert(['x' => 1]);
$bulk->update(['x' => 1], ['$set' => ['y' => 3]]);
$bulk->update(['x' => 2], ['$set' => ['y' => 1]], ['upsert' => true]);
$bulk->update(['x' => 3], ['$set' => ['y' => 2]], ['upsert' => true]);
$bulk->delete(['x' => 1]);

$result = $m->executeBulkWrite('db.my', $bulk);

var_dump($result->getUpsertedIds());
*/

$m = new MongoDBOP();
$bulk = new MongoDB\Driver\BulkWrite;
$bulk->update(
    ['x' => 1],
    ['$set' => ['name' => 34444444444433]],
    ['upsert' => false]
);

//$manager = new MongoDB\Driver\Manager('mongodb://192.168.3.12:27017');
$result = $m->getManage()->executeBulkWrite('test.eee', $bulk);
var_dump($result );
// 
//$filter = ['x' => ['$gt' => 1]];
//$options = [
//    'projection' => ['_id' => 0],
//    'sort' => ['x' => -1],
//];
//$strQuery  = ['$or'=>[['GN'=>'5G'],['GN'=> '互联网金融' ]]];
//// 查询数据
//$query = new MongoDB\Driver\Query([] , []);
//$cursor = $m->manager->executeQuery('test.stocktemp', $query);
//
//foreach ($cursor as $document) {
//    print_r($document);
//}
// 