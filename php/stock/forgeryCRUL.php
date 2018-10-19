 <a href="http://quote.eastmoney.com/zs000001.html"><img src="https://pifm.eastmoney.com/EM_Finance2014PictureInterface/Index.aspx?id=0000011&imageType=rt&token=28dfeb41d35cc81d84b4664d7c23c49f" /></a>
                    <a href="http://quote.eastmoney.com/zs399001.html"><img src="https://pifm.eastmoney.com/EM_Finance2014PictureInterface/Index.aspx?id=3990012&imageType=rt&token=28dfeb41d35cc81d84b4664d7c23c49f" /></a>
                    <a href="http://quote.eastmoney.com/hk/zs110000.html"><img src="https://pifm.eastmoney.com/EM_Finance2014PictureInterface/Index.aspx?id=110000_UIFO&imageType=rt&token=28dfeb41d35cc81d84b4664d7c23c49f" /></a>
                    <a href="http://stock.eastmoney.com/globalindex/NKY.html"><img src="https://pifm.eastmoney.com/EM_Finance2014PictureInterface/Index.aspx?id=NKY_UIFO&imageType=rt&token=28dfeb41d35cc81d84b4664d7c23c49f" /></a>

<?php

/**
 * 本机测试地址
 * http://www.stock.com/forgeryCRUL.php
 */

//随机IP
function Rand_IP(){

    $ip2id= round(rand(600000, 2550000) / 10000); //第一种方法，直接生成
    $ip3id= round(rand(600000, 2550000) / 10000);
    $ip4id= round(rand(600000, 2550000) / 10000);
    //下面是第二种方法，在以下数据中随机抽取
    $arr_1 = array("218","218","66","66","218","218","60","60","202","204","66","66","66","59","61","60","222","221","66","59","60","60","66","218","218","62","63","64","66","66","122","211");
    $randarr= mt_rand(0,count($arr_1)-1);
    $ip1id = $arr_1[$randarr];
    return $ip1id.".".$ip2id.".".$ip3id.".".$ip4id;
}

//抓取页面内容
function Curl($url){
        $ch2 = curl_init();
        $user_agent = "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.66 Safari/537.36";//模拟windows用户正常访问
        curl_setopt($ch2, CURLOPT_URL, $url);
        curl_setopt($ch2, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch2, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:'.Rand_IP(), 'CLIENT-IP:'.Rand_IP()));
//追踪返回302状态码，继续抓取
        curl_setopt($ch2, CURLOPT_HEADER, true); 
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($ch2, CURLOPT_FOLLOWLOCATION, true);

        curl_setopt($ch2, CURLOPT_NOBODY, false);
        curl_setopt($ch2, CURLOPT_REFERER, 'http://www.sse.com.cn/market/othersdata/margin/sum/');//模拟来路
        curl_setopt($ch2, CURLOPT_USERAGENT, $user_agent);
        $temp = curl_exec($ch2);
        curl_close($ch2);
        return $temp;
}
echo '上海证券交易所：融资融券汇总数据';
echo Curl('http://query.sse.com.cn/marketdata/tradedata/queryMargin.do?jsonCallBack=jsonpCallback51522&isPagination=true&beginDate=20180919&endDate=20181019&tabType=&stockCode=&pageHelp.pageSize=1000&pageHelp.pageNo=1&pageHelp.beginPage=1&pageHelp.cacheSize=1&pageHelp.endPage=5&_=1539912441568');