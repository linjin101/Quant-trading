<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * Mongodb新版驱动
 */
include_once 'config.php';

class MongoDBOP {

    public $manager;

    function __construct() {
        $this->manager = new MongoDB\Driver\Manager(__MONGODB_HOST__);
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
    /**
     * 
     * @param type $collection
     * @param type $query
     * @param type $setData
     * @param type $upsert
     * @return type
     */
    public function mongoUpate($collection, $query, $setData, $upsert = ['upsert' => false]) {
        $bulk = new MongoDB\Driver\BulkWrite;
        $bulk->update($query, $setData, $upsert);
        $result = $this->manager->executeBulkWrite($collection, $bulk);
        return $result->getUpsertedIds();
    }

    public function mongoSearch($collection, $filter = [], $options = []) {
        $query = new MongoDB\Driver\Query($filter, $options);
        $rows = $this->manager->executeQuery($collection, $query); // $mongo contains the connection object to MongoDB
        $rowslist = array();
        foreach ($rows as $document) {
            array_push($rowslist, $document);
        }
        return $rowslist;
    }

}
