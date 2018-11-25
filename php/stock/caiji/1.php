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
$arrQuery = array( );
// $options指定选择列[ 'projection' =>['code'=>1]]
$rowlist = $MongoDBOP->mongoSearch('test.stocklist',[],[ 'projection' =>['code'=>1,'_id'=>0]]);

$stocklist = array();
foreach ($rowlist as $key){ 
     
    array_push($stocklist,$key['code']);
}
print_r($stocklist);




//$ttt = 'R003(201000)';
//$pos = strrpos($ttt, '(');
//if ( !empty($pos) ) {
//    $strTmp = substr($ttt, $pos+1,6);
//    $strTmpEd = substr($ttt, 0,$pos);
//    //echo $strTmpEd;
//    //echo '<br>';
//    echo $strTmp;
//    echo '<br>';
//    $posCode = substr($strTmp, 0, 1);
//    
//    echo $posCode;
//}
