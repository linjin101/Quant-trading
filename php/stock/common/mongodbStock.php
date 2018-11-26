<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once 'config.php';

class MongoDBStock extends MongoDBOP {

    public $mongoStock;

    function __construct() {
        parent::__construct();
        $this->mongoStock = new MongoDBOP();
    }

    /**
     * 
     * @param string $collection 集合名称
     * @param array $query  查询股票是否存在
     * @param array $updateData 需要更新的股票概念数据
     */
    function MongoUpdateQuery($collection, $query, $updateData) {
        //查询股票是否存在
        $rowlist = $this->mongoStock->mongoSearch($collection, $query);
        if (empty($rowlist)) {
            return 0;
        }
        //查询概念数据是否修改 
        $sqlGN = [$query, $updateData];
        $rowlist = $this->mongoStock->mongoSearch($collection, $sqlGN);
        //找不到就更新
        if (empty($rowlist)) {
            $r = $this->mongoStock->mongoUpate($collection, $query, ['$set' => $updateData]);
            var_dump($r);
            exit();
        }
        return $r;
    }
    
    /**
     * 找出股票概念中空股票
     * @return array
     */
    function getEmptyStockGN() {
        $arrStock = array();
    // $options指定选择列[ 'projection' =>['code'=>1]]
        $stocklist = $this->mongoStock->mongoSearch('test.stocklist', [], ['projection' => ['code' => 1, 'name' => 1, '_id' => 0]]);
        $arrMerge = array();
        foreach ($stocklist as $key) {
            $stockgnlist = $this->mongoStock->mongoSearch('test.stockgn', ['code' => $key['code']], ['projection' => ['GN' => 1, '_id' => 0]]);
            //获取股票代码=>空，概念GN=>空的行
            if (empty($stockgnlist) || empty($stockgnlist[0]['GN'])) {
                array_push($arrStock, $key);
                //echo $key['code'].'<br>'; 
            }
        }
        return $arrStock;
    }

}
