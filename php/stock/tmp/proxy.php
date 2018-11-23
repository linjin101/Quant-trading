<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * 
 * http://www.stock.com/proxy.php
 */

function curl_string ($url,$user_agent,$proxy,$proxy_port,$cookie_file){

       $ch = curl_init();
        
       curl_setopt ($ch, CURLOPT_PROXY, $proxy);
       curl_setopt ($ch, CURLOPT_PROXYPORT, $proxy_port); //代理服务器端口
       curl_setopt ($ch, CURLOPT_URL, $url);
       curl_setopt ($ch, CURLOPT_USERAGENT, $user_agent);
       curl_setopt ($ch, CURLOPT_COOKIEJAR, $cookie_file);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file); //使用上面获取的cookies
        
       curl_setopt ($ch, CURLOPT_HEADER, 1); 
       curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
       
       curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);//抓取时依然返回的果js跳转。
       curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
       curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

       
        // IP伪装
	$ip = rand(1,255).".".rand(1,255).".".rand(1,255).".".rand(1,255)."";
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:'.$ip, 'CLIENT-IP:'.$ip));  //构造IP  
	curl_setopt($ch, CURLOPT_REFERER, "http://quote.eastmoney.com/stocklist.html");   //构造来路  
        
       curl_setopt ($ch, CURLOPT_TIMEOUT,20);
       $result = curl_exec ($ch);
       curl_close($ch);
       return $result;

}


//
//curl_setopt($ch, CURLOPT_PROXYAUTH, CURLAUTH_BASIC); //代理认证模式
//curl_setopt($ch, CURLOPT_PROXY, "112.123.249.111"); //代理服务器地址
//curl_setopt($ch, CURLOPT_PROXYPORT, 9745); //代理服务器端口
//curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP); //<span style="color: #FF0000;">使用http代理模式,目前就是https类型的代理服务器该如何写呢</span>
//      

            
            

//$url_page = "http://weixin.sogou.com/weixin?type=1&query=%E5%9F%BA%E9%87%91&ie=utf8&s_from=input&_sug_=y&_sug_type_=";
//$url_page = "https://news.163.com/18/1121/12/E14TLOGJ00018AOQ.html";
//$url_page = "http://mp.cnfol.com/26083/article/1537640422-138026764.html";
//$url_page = "https://blog.csdn.net/guiyecheng/article/details/46499839";
//$url_page = "http://www.100ppi.com/ppi/";
//$url_page = "https://weixin.sogou.com/weixin?type=1&s_from=input&query=基金&ie=utf8&_sug_=n&_sug_type_=";
$url_page = "http://basic.10jqka.com.cn/603128/concept.html"; 
 
$user_agent = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.2; .NET CLR 1.1.4322)";
$proxy = "113.116.131.239";
$proxy_port="808";
 
/* cookie文件 */
$cookie_file = dirname ( __FILE__ ) . "/cookie_" . md5 ( basename ( __FILE__ ) ) . ".txt"; // 设置Cookie文件保存路径及文件名

$string = curl_string($url_page,$user_agent,$proxy,$proxy_port,$cookie_file);
//echo $string;

$str_encode = mb_convert_encoding($string, 'UTF-8', 'GBK');
echo '<textarea rows="500" cols="220">'.$str_encode.'</textarea>';



