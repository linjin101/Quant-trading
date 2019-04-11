<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * http://quote.eastmoney.com/stocklist.html
 * 导出
 * D:\Program Files\MongoDB\Server\4.0\bin>mongoexport -h 127.0.0.1 -d test -c stocklist -o D:\Database\temp\stocklist.json
 * 导入
 * mongoimport --db test --collection stocklist --file d:/Database/temp/stocklist.json
 * 
 * 搜索输入框特效
 * http://www.17sucai.com/pins/demo-show?id=7061
 * 
 * http://www.stock.com/caiji/weixin.php
 */
include_once '../vendor/autoload.php'; //加载composer
include_once '../common/config.php';
include_once '../common/mongodb.php'; //加载数据库操作类 
include_once '../common/mongodbStock.php'; //加载股票专用数据操作类 
include_once '../common/common.php';

use QL\QueryList;

function dd($info=''){ 
    print_r($info);
    exit;
}

$MongoDBOP = new MongoDBOP();
//页面地址
//$url_page = 'https://mp.weixin.qq.com/s?__biz=MzIyNjQ4NDk0NA==&mid=2247499609&idx=1&sn=07330d0930bfbe9de32d9515cfc8c121&chksm=e86d08e6df1a81f01290413a919338f6a8012b4c86eb9cd15e21ff3d748d67206de4aafeef1d&scene=21&token=238317996&lang=zh_CN#wechat_redirect';
//$url_page = 'https://mmbiz.qpic.cn/mmbiz_png/aK1npyTfmquGKNoJdw7Cian5dwia9BXLNASLnwgtT1XrdYw4Okd7YyqddR3EmYO8XoicnURw6LxWNWUpemKS3E7Kw/640?wx_fmt=png';
$url_page = 'https://mp.weixin.qq.com/s?__biz=MzIyNjQ4NDk0NA==&mid=2247499610&idx=1&sn=99de0bc6d2aecca9ca4f42d6953d81f1&chksm=e86d08e5df1a81f382f0caa5275402b8b7380aebf7d4bea15468a06229872b990022228303d5#rd';
//用QueryList抓取页面Html并且转码
$string = QueryList::get($url_page)->getHtml();

/* 过滤图片 */
/*
preg_match_all('/<img(.*?)src=\"(.*?)\"(.*?)>/i', $content, $matches);
matches[0] 整个img标签
matches[2] 图片的url
 * 
/(<img[\s\S]*?(><\/img>|>))/i
 */

//preg_match_all('/<img(.*?)src=\"(.*?)\"(.*?)>/ii', $string, $matchimg);
//print_r($matchimg);exit;
//正则表达式获取图片data-src
preg_match_all('/<img(.*?)data-src=\"(.*?)\"(.*?)>/i', $string, $matchimg);
//print_r($matchimg);exit;
//图片文件保存根目录
$ROOT= 'D:/dev/Quant-trading/php/stock/caiji';
foreach ($matchimg[2] as $k => $v) {
    if (!empty($v)) {
        $img_url = $v;
        
        /**
         * 待处理 //res.wx.qq.com
         
        echo $img_url.'<br>';
        continue;
        */
        $parse_url = parse_url($img_url);
        $path_arr = array_filter(explode('/', $parse_url['path']));//图片地址解析
        parse_str($parse_url['query'], $query);
        $ext = $query['wx_fmt'];//后缀名解析
        $fileName = md5( $img_url) . '.' . $ext;//随机文件名生成
        $filePath = $ROOT . '/upload/' . date('Ymd') . '/';//图片存放目录
        $imgsrc =  date('Ymd') . '/' . $fileName;//图片在线访问地址生成 'http://www.stock.com/caiji' .
        if (!is_dir($filePath)) {
            mkdir($filePath, 0777, true);//目录创建
        }
        $imageFile = file_get_contents($img_url);//图片文件流获取
        file_put_contents($filePath . $fileName, $imageFile);//图片文件下载保存硬盘
        
        
        //如果图片有二维码,或者图片尺寸大于1200小于20则去除图片
        $img_value = getimagesize($filePath . $fileName);
        if ($img_value['0'] > 1200 || $img_value['0'] < 20) {
//            $string = str_replace($v, '', $string);
        } else {
            //如果有src就用src ,如果没有src就用data-src
			/*
            preg_match("/data-src=\"([^\"]*?)\"/", $v, $datasrc);
            $img_v = preg_replace("/data-src=\"([^\"]*?)\"/", "", $v);
            preg_match("/src=\"([^\"]*?)\"/", $img_v, $new_src);
            if (empty($new_src) && !empty($datasrc[1])) {
                $string = preg_replace("/data-src=\"([^\"]*?)\"/", "src=\"" . $datasrc[1] . "\"", $string, 1);
            }*/
            //把图片地址换成本地
            $string = str_replace($img_url,$imgsrc, $string);
        }
    }
}
 

echo $string;

file_put_contents($ROOT . '/upload/' . date('Ymd').'_'.time() . '.html', $string);

//访问地址：http://www.stock.com/caiji/upload/20190325.html

