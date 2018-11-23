<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class MongoDBOP {

    public $manager;

    function __construct() {
        $this->manager = new MongoDB\Driver\Manager("mongodb://192.168.3.12:27017");
    }
    
    public function getManage(){
        return $this->manager;
    }

    public function mongoInsert($collection, $arrData) {

        // 插入数据
        $bulk = new MongoDB\Driver\BulkWrite;
        $bulk->insert($arrData);
        $this->manager->executeBulkWrite($collection, $bulk);
    }

    public function mongoArrInsert($collection, $arrData) {

        // 插入数据
        $bulk = new MongoDB\Driver\BulkWrite;
        foreach ($arrData as $value) {
            $bulk->insert($value);
        }
        $this->manager->executeBulkWrite($collection, $bulk);
    }

    public function mongoSearch($collection, $filter = [], $options = []) {
        $query = new MongoDB\Driver\Query($filter, $options);
        $rows = $this->manager->executeQuery($collection, $query); // $mongo contains the connection object to MongoDB
        $rowslist = array();
        foreach ($rows as $document) {
            array_push($rowslist,$document);
        }
        return $rowslist;
    }

    public function mongotest() {
        // 插入数据
        $bulk = new MongoDB\Driver\BulkWrite;

        $bulk->insert(['x' => 1, 'name' => '菜鸟教程', 'url' => 'http://www.runoob.com']);
        $bulk->insert(['x' => 2, 'name' => 'Google', 'url' => 'http://www.google.com']);
        $bulk->insert(['x' => 3, 'name' => 'taobao', 'url' => 'http://www.taobao.com']);
        $this->manager->executeBulkWrite('test.eee', $bulk);
        $filter = ['x' => ['$gt' => 1]];
        $options = [
            'projection' => ['_id' => 0],
            'sort' => ['x' => -1],
        ];

// 查询数据
        $query = new MongoDB\Driver\Query($filter, $options);
        $cursor = $this->manager->executeQuery('test.eee', $query);

        foreach ($cursor as $document) {
            print_r($document);
        }
    }

}

//$mongodbC = new MongoDBOP();
//$mongodbC->mongotest();
//
//$filter = ['x' => ['$gt' => 1]];
//$options = [
//    'projection' => ['_id' => 0],
//    'sort' => ['x' => -1],
//];
//
//// 查询数据
//$query = new MongoDB\Driver\Query($filter, $options);
//$cursor = $manager->executeQuery('test.132', $query);
//
//foreach ($cursor as $document) {
//    print_r($document);
//}