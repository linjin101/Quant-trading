<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * 
 * @param type $url
 * @return type
 */
function getCurl($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); //抓取时依然返回的果js跳转。
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

    curl_setopt($ch, CURLOPT_TIMEOUT, 20);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}
/*
$url_page = "http://basic.10jqka.com.cn/603128/concept.html"; 
$user_agent = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.2; .NET CLR 1.1.4322)";
$proxy = "113.116.131.239";
$proxy_port="808";
$cookie_file = dirname ( __FILE__ ) . "/cookie_" . md5 ( basename ( __FILE__ ) ) . ".txt"; // 设置Cookie文件保存路径及文件名
$string = curl_string($url_page,$user_agent,$proxy,$proxy_port,$cookie_file);
$str_encode = mb_convert_encoding($string, 'UTF-8', 'GBK');
echo '<textarea rows="500" cols="220">'.$str_encode.'</textarea>';
 */
/**
 * 
 * @param type $url
 * @param type $user_agent
 * @param type $proxy
 * @param type $proxy_port
 * @param type $referer
 * @param type $cookie_file
 * @return type
 * 
 */
function getProxyCurl($url, $user_agent, $proxy, $proxy_port, $referer, $cookie_file
) {

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_PROXY, $proxy);
    curl_setopt($ch, CURLOPT_PROXYPORT, $proxy_port); //代理服务器端口
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file); //使用上面获取的cookies

    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); //抓取时依然返回的果js跳转。
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);


    // IP伪装
    $ip = randIP();
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:' . $ip, 'CLIENT-IP:' . $ip));  //构造IP  
    curl_setopt($ch, CURLOPT_REFERER, $referer);   //构造来路  

    curl_setopt($ch, CURLOPT_TIMEOUT, 20);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

function randIP() {
    $IP = rand(1, 255) . "." . rand(1, 255) . "." . rand(1, 255) . "." . rand(1, 255) . "";
    return $IP;
}
