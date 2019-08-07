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

 <VirtualHost *:80>
    DocumentRoot "E:\project\stock\Quant-trading\php\stock"
    ServerName www.test.com
    ServerAlias test.com
  <Directory "E:\project\stock\Quant-trading\php\stock">
      Options FollowSymLinks ExecCGI
      Options Indexes FollowSymLinks MultiViews
      AllowOverride All
      Order allow,deny
      Allow from all
     Require all granted
  </Directory>
</VirtualHost>

update:
db.getCollection('url').update({"aid":"2247500679_3"},{$set:{"item_show_type":'1'}})   

order by :
db.getCollection('url').find({}).sort({"update_time":-1})

mongodb多行更新：
db.getCollection('url').update( {},{$set:{'item_show_type':'1'}},{multi:true})
1：表示已抓取
0：表示为抓取

 */
include_once '../vendor/autoload.php'; //加载composer
include_once '../common/config.php';
include_once '../common/mongodb.php'; //加载数据库操作类 
include_once '../common/mongodbStock.php'; //加载股票专用数据操作类 
include_once '../common/common.php';

use QL\QueryList;

$MongoDBOP = new MongoDBOP();
$rowlist = $MongoDBOP->mongoSearch('test.url',['item_show_type' => 0] ,['sort'=>[ 'update_time'=> 1 ] ]);
$i = 1;
foreach ($rowlist as $articleData) {
    
	$strHttps = substr($articleData['link'],0,5);
	if ( $strHttps != 'https') {
	    $articleData['link'] = 'https'.substr($articleData['link'],4);
	}

	echo $i.':'.chr(10).chr(13);
	echo $articleData['link'].chr(10).chr(13);
	echo 'date:'.$articleData['date'].chr(10).chr(13);


    $randSleep = rand(2,8);
    echo $randSleep.chr(10).chr(13);
    sleep($randSleep);
    saveWeixinArticle($articleData['link']);
    $i++;

	print_r( $articleData['_id']['oid'] ); 
	exit;

    $MongoDBOP->mongoUpate('test.url',['_id' => $articleData['_id']],['item_show_type' =>'1'] ,['upsert' => true]);
}

// $url = 'https://mp.weixin.qq.com/s?__biz=MzIyNjQ4NDk0NA==&mid=2247499610&idx=1&sn=99de0bc6d2aecca9ca4f42d6953d81f1&chksm=e86d08e5df1a81f382f0caa5275402b8b7380aebf7d4bea15468a06229872b990022228303d5#rd';
// $url = 'https://mp.weixin.qq.com/s?__biz=MzIyNjQ4NDk0NA==&mid=2247500679&idx=1&sn=181c68466db3d0224b7403cca01d8902&chksm=e86d3438df1abd2e94098e3d3beae507053f8e58c675f40db4a218b7497eac9fbb43a20016f8#rd';
// saveWeixinArticle($url);

function saveWeixinArticle($url){
//页面地址
//$url_page = 'https://mp.weixin.qq.com/s?__biz=MzIyNjQ4NDk0NA==&mid=2247499609&idx=1&sn=07330d0930bfbe9de32d9515cfc8c121&chksm=e86d08e6df1a81f01290413a919338f6a8012b4c86eb9cd15e21ff3d748d67206de4aafeef1d&scene=21&token=238317996&lang=zh_CN#wechat_redirect';
//$url_page = 'https://mmbiz.qpic.cn/mmbiz_png/aK1npyTfmquGKNoJdw7Cian5dwia9BXLNASLnwgtT1XrdYw4Okd7YyqddR3EmYO8XoicnURw6LxWNWUpemKS3E7Kw/640?wx_fmt=png';
// $url = 'https://mp.weixin.qq.com/s?__biz=MzIyNjQ4NDk0NA==&mid=2247499610&idx=1&sn=99de0bc6d2aecca9ca4f42d6953d81f1&chksm=e86d08e5df1a81f382f0caa5275402b8b7380aebf7d4bea15468a06229872b990022228303d5#rd';
// $url = 'https://mp.weixin.qq.com/s?__biz=MzIyNjQ4NDk0NA==&mid=2247500679&idx=1&sn=181c68466db3d0224b7403cca01d8902&chksm=e86d3438df1abd2e94098e3d3beae507053f8e58c675f40db4a218b7497eac9fbb43a20016f8#rd';
//用QueryList抓取页面Html并且转码
$string = QueryList::get($url)->getHtml();


//图片地址出现data-backsrc容错
$string = str_replace("tempImg.setAttribute('data-backsrc', cover);","", $string);

//提取文章发布日期 <em id="publish_time" class="rich_media_meta rich_media_meta_text">2015-03-16</em>
// preg_match('/<em[^>]*id=\"publish_time\"[^>]*class=\"rich_media_meta rich_media_meta_text\">(.*?)<\/em>/si',$string, $matchTime);
preg_match('/\",s=\"(.*?)\"/si',$string, $matchTime);
// dd($matchTime);
$articleTime = $matchTime[1];

//正则表达式获取图片data-src
preg_match_all('/<img(.*?)data-src=\"(.*?)\"(.*?)>/i', $string, $matchimg);
// ddd($matchimg);
$time = time();

// 去掉微信二维码 <div class=\"qr_code_pc_inner\">([\s\S]+?)</div>([\s\S]+?)</div>([\s\S]+?)</div>
$string = preg_replace("/<div id=\"js_pc_qr_code\"([\s\S]+?)<\/div>([\s\S]+?)<\/div>([\s\S]+?)<\/div>/", ' ',$string);


//图片文件保存根目录
$ROOT = getcwd();
foreach ($matchimg[2] as $k => $v) {
    if (!empty($v)) {
        $img_url = $v;

        $parse_url = parse_url($img_url);//url地址解析
        $path_arr = array_filter(explode('/', $parse_url['path']));//图片地址解析
        
        $ext = 'jpg';//默认后缀名
        if( isset($parse_url['query']) ){
            parse_str($parse_url['query'], $query);
            if( isset($query['wx_fmt']) ){
                 $ext = $query['wx_fmt'];//后缀名解析
            }
        }
        
        $fileName = md5( $img_url) . '.' . $ext;//随机文件名生成
        $filePath = $ROOT . '/upload/' . $articleTime .'_'.$time . '/';//图片存放目录date('Ymd')
        $imgsrc =  $articleTime .'_'.$time . '/' . $fileName;//图片在线访问地址生成 'http://www.stock.com/caiji' .date('Ymd')
        if (!is_dir($filePath)) {
            mkdir($filePath, 0777, true);//目录创建
        }
        $imageFile = file_get_contents($img_url);//图片文件流获取
        // dd($filePath . $fileName);
        file_put_contents($filePath . $fileName, $imageFile);//图片文件下载保存硬盘
        
        
        //如果图片有二维码,或者图片尺寸大于1200小于20则去除图片
        $img_value = getimagesize($filePath . $fileName);
         //把图片地址换成本地
        $string = str_replace($img_url,$imgsrc, $string);
        // if ($img_value['0'] > 1200 || $img_value['0'] < 20) {
//            $string = str_replace($v, '', $string);
        // } else {
            //如果有src就用src ,如果没有src就用data-src
			/*
            preg_match("/data-src=\"([^\"]*?)\"/", $v, $datasrc);
            $img_v = preg_replace("/data-src=\"([^\"]*?)\"/", "", $v);
            preg_match("/src=\"([^\"]*?)\"/", $img_v, $new_src);
            if (empty($new_src) && !empty($datasrc[1])) {
                $string = preg_replace("/data-src=\"([^\"]*?)\"/", "src=\"" . $datasrc[1] . "\"", $string, 1);
            }*/
            //把图片地址换成本地
            // $string = str_replace($img_url,$imgsrc, $string);
        // }
    }
}
 
file_put_contents($ROOT . '/upload/' . $articleTime .'_'.$time. '.html', $string);// date('Ymd')
//访问地址：http://www.stock.com/caiji/upload/20190325.html
// echo $string;
}




