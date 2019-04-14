<!doctype html>
<html lang="en">
 <head>
  <meta charset="UTF-8">
  <meta name="Generator" content="EditPlus®">
  <meta name="Author" content="">
  <meta name="Keywords" content="">
  <meta name="Description" content="">
  <title>inset weixin</title>
 </head>
 <body>

 <form method="post" action="insertWeixin.php">
<textarea name="content" rows="40" cols="90"></textarea>
    <input type="submit" value="保存" >
</form>
 </body>
</html>


<?php

// page 20
//http://www.test.com/caiji/insertWeixin.php
/*
 * 
导入json命令：mongoimport --db test --collection url --file D:\dev\Quant-trading\php\stock\caiji\1423.json
导出json命令：mongoexport --db test --collection url --out D:\dev\Quant-trading\php\stock\caiji\1710.json
 
 *  
 *  
 */
include_once '../common/config.php';
include_once '../common/mongodb.php'; //加载数据库操作类 
include_once '../common/mongodbStock.php'; //加载股票专用数据操作类 
include_once '../common/common.php';

//textarea录入json数据，转换成数组提取出app_msg_list保存到mongodb
if( isset($_POST['content']) ){
    $content = $_POST['content'];
    $strContent = json_decode($content,true);//转换数组

    $MongoDBOP = new MongoDBOP();
    if ( !empty($strContent) ){
        foreach ($strContent['app_msg_list'] as $key => $value) {
            $filter      = ['aid' => $value['aid'] ];
            $rowlist = $MongoDBOP->mongoSearch('test.url',$filter);
            if ( empty($rowlist)){
                $value['date'] = date("Y-m-d H:i:s",$value['update_time']);
                $MongoDBOP->mongoInsert("test.url", $value);
                echo 'insert:'.$value['aid'].' ';
            }
            else
            {
                echo 'Duplicate';
                exit;
            }
        }
    }
    else{
        echo 'app_msg_list is empty!';
    }

}






