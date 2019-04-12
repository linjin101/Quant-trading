<?php
/*
 * 采集工具类
 */
include_once 'config.php';
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

    curl_setopt($ch, CURLOPT_TIMEOUT, __CURL_TIMEOUT__);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

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
function getProxyCurl($url, $user_agent, $proxy, $proxy_port, $referer, $cookie_file) {

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

    curl_setopt($ch, CURLOPT_TIMEOUT, __CURL_TIMEOUT__);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}
/**
 * 
 * @return string
 */
function randIP() {
    $IP = rand(1, 255) . "." . rand(1, 255) . "." . rand(1, 255) . "." . rand(1, 255) . "";
    return $IP;
}


/** 
 * 
 * @param type $array
 * @return typePHP stdClass Object转array  
 */
function object_array($array) {  
    if(is_object($array)) {  
        $array = (array)$array;  
     } if(is_array($array)) {  
         foreach($array as $key=>$value) {  
             $array[$key] = object_array($value);  
             }  
     }  
     return $array;  
}

/**
 * pring_r()调试输出
 * @param $strDebug
 */
function ddd($strDebug){
    print_r($strDebug);
    exit();
}

/**
 * echo调试输出
 * @param $strDebug
 */
function dde($strDebug){
    echo($strDebug);
    exit();
}

/**
 * Object转换json，然后转换成数组
 * @param $object
 * @return mixed
 */
function json_arr($object){
    return objectToarray($object);
}

/**
 * Object转换json，然后转换成数组
 * @param $object
 * @return mixed
 */
function objectToarray($object){
    $objJson = json_encode($object);//object转换成json格式
    $arrJson = json_decode($objJson,TRUE);//json格式转换成Array
    return $arrJson;
}