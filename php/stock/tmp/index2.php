<?php
/**
 * 本机测试地址
 * http://www.stock.com/index.php
 */




namespace QL;
use QL\QueryList;

include_once 'vendor/autoload.php';//加载composer


function curl_string ($url,$user_agent,$proxy,$proxy_port,$cookie_file){
       $ch = curl_init();
        
//       curl_setopt ($ch, CURLOPT_PROXY, $proxy);
//       curl_setopt ($ch, CURLOPT_PROXYPORT, $proxy_port); //代理服务器端口
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
	curl_setopt($ch, CURLOPT_REFERER, "http://data.10jqka.com.cn/market/rzrq/");   //构造来路  
        
       curl_setopt ($ch, CURLOPT_TIMEOUT,20);
       $result = curl_exec ($ch);
       curl_close($ch);
       return $result;
}

$url_page = "http://data.10jqka.com.cn/market/rzrq/"; 
$user_agent = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.2; .NET CLR 1.1.4322)";
$proxy = "113.116.131.239";
$proxy_port="808";
 
/* cookie文件 */
$cookie_file = dirname ( __FILE__ ) . "/cookie_" . md5 ( basename ( __FILE__ ) ) . ".txt"; // 设置Cookie文件保存路径及文件名
$string = curl_string($url_page,$user_agent,$proxy,$proxy_port,$cookie_file);
 
//$html = mb_convert_encoding($string, 'UTF-8', 'GBK'); 
//echo $html;
 
//echo $htmlStart;


//采集某页面所有的图片
//$data = QueryList::get('http://data.10jqka.com.cn/market/rzrq/')->rules([  //设置采集规则
//   // 采集所有a标签的href属性
//   // 'link' => ['a','href'],
//    // 采集所有a标签的文本内容
//    'text' => ['#table1','html'] 
//])->query()->getData();
//
//$html = mb_convert_encoding($data[0]['text'], 'UTF-8', 'GBK'); 

//echo mb_detect_encoding($data[0]['text']);
//echo html_entity_decode($data[0]['text']);
//打印结果
//print_r($data->all());
 
//$html = iconv('GBK','UTF-8',$string);
//然后可以把页面源码或者HTML片段传给QueryList
$data = QueryList::html($string)->rules([  //设置采集规则
   // 采集所有a标签的href属性
   // 'link' => ['a','href'],
    // 采集所有a标签的文本内容
    'text' => ['#table1','html'] 
])->query()->getData();

$table = QueryList::html($data[0]['text'])->find('table'); 
// 采集表的每行内容
$tableRows = $table->find('tr:gt(0)')->map(
    function($row)
    {
        return $row->find('td')->texts()->all();
    }
); 
//print_r($tableRows->all());
include_once 'mongodb.php';
use mongodp\MongoDBOP;

$mongodbC = new MongoDBOP();
$mongodbC->mongotest();



//echo mb_convert_encoding($table, 'UTF-8', 'GBK'); 
//$str_encode = mb_convert_encoding($data[0]['text'], 'UTF-8', 'GBK');
//echo html_entity_decode($data[0]['text'], ENT_QUOTES, "GB2312");
//$str_encode = htmlentities($data[0]['text']);
//echo $str_encode;
//打印结果
//print_r($data->all());
//echo iconv('GBK','UTF-8',$data[0]['text']);

//$html =  mb_convert_encoding($data[0]['text'], 'UTF-8', 'GBK'); 
//echo $html;


//echo $html;
//$data = '{"tdate":"2018-10-12T00:00:00","rzye_h":482668130673.0,"rzye_s":310401544859.0,"rzye_hs":793069675532.0,"ltsz_h":24130839000000.0,"ltsz_s":12243084990428.9,"ltsz_hs":36373923990428.9,"rzyezb_h":2.0002128010261062,"rzyezb_s":2.5353213271136985,"rzyezb_hs":2.1803247726054553,"rzmre_h":11922965049.0,"rzmre_s":9290521317.0,"rzmre_hs":21213486366.0,"rqye_h":6079655317.0,"rqye_s":1064077090.0,"rqye_hs":7143732407.0,"rzrqye_h":488747785990.0,"rzrqye_s":311465621949.0,"rzrqye_hs":800213407939.0,"rzrqyecz_h":476588475356.0,"rzrqyecz_s":309337467769.0,"rzrqyecz_hs":785925943125.0},{"tdate":"2018-10-11T00:00:00","rzye_h":489362003478.0,"rzye_s":316285483705.0,"rzye_hs":805647487183.0,"ltsz_h":23868180000000.0,"ltsz_s":12227292824998.9,"ltsz_hs":36095472824998.9,"rzyezb_h":2.0502694527944736,"rzyezb_s":2.5867171763348069,"rzyezb_hs":2.2319903969370554,"rzmre_h":15297236005.0,"rzmre_s":11352567143.0,"rzmre_hs":26649803148.0,"rqye_h":6030483129.0,"rqye_s":1082172110.0,"rqye_hs":7112655239.0,"rzrqye_h":495392486607.0,"rzrqye_s":317367655815.0,"rzrqye_hs":812760142422.0,"rzrqyecz_h":483331520349.0,"rzrqyecz_s":315203311595.0,"rzrqyecz_hs":798534831944.0},{"tdate":"2018-10-10T00:00:00","rzye_h":496692130111.0,"rzye_s":323031476681.0,"rzye_hs":819723606792.0,"ltsz_h":25107451000000.0,"ltsz_s":13050716157258.5,"ltsz_hs":38158167157258.5,"rzyezb_h":1.9782658546699943,"rzyezb_s":2.4752011520941517,"rzyezb_hs":2.1482258396052734,"rzmre_h":10218445695.0,"rzmre_s":7755377597.0,"rzmre_hs":17973823292.0,"rqye_h":6500221990.0,"rqye_s":1090865573.0,"rqye_hs":7591087563.0,"rzrqye_h":503192352101.0,"rzrqye_s":324122342254.0,"rzrqye_hs":827314694355.0,"rzrqyecz_h":490191908121.0,"rzrqyecz_s":321940611108.0,"rzrqyecz_hs":812132519229.0},{"tdate":"2018-10-09T00:00:00","rzye_h":496798379422.0,"rzye_s":323183149799.0,"rzye_hs":819981529221.0,"ltsz_h":25066232000000.0,"ltsz_s":13084112909179.4,"ltsz_hs":38150344909179.4,"rzyezb_h":1.98194279627668,"rzyezb_s":2.4700425014848726,"rzyezb_hs":2.1493423746837563,"rzmre_h":10432337592.0,"rzmre_s":7377083739.0,"rzmre_hs":17809421331.0,"rqye_h":6530696251.0,"rqye_s":1043232734.0,"rqye_hs":7573928985.0,"rzrqye_h":503329075673.0,"rzrqye_s":324226382533.0,"rzrqye_hs":827555458206.0,"rzrqyecz_h":490267683171.0,"rzrqyecz_s":322139917065.0,"rzrqyecz_hs":812407600236.0},{"tdate":"2018-10-08T00:00:00","rzye_h":496333847498.0,"rzye_s":323624829020.0,"rzye_hs":819958676518.0,"ltsz_h":25021684000000.0,"ltsz_s":13087369466413.2,"ltsz_hs":38109053466413.2,"rzyezb_h":1.9836148817881323,"rzyezb_s":2.472802726709407,"rzyezb_hs":2.1516112365284994,"rzmre_h":16604993961.0,"rzmre_s":10962720445.0,"rzmre_hs":27567714406.0,"rqye_h":6373994495.0,"rqye_s":997165520.0,"rqye_hs":7371160015.0,"rzrqye_h":502707841993.0,"rzrqye_s":324621994540.0,"rzrqye_hs":827329836533.0,"rzrqyecz_h":489959853003.0,"rzrqyecz_s":322627663500.0,"rzrqyecz_hs":812587516503.0},{"tdate":"2018-09-28T00:00:00","rzye_h":492989890192.0,"rzye_s":322152996593.0,"rzye_hs":815142886785.0,"ltsz_h":25987808000000.0,"ltsz_s":13594902293386.5,"ltsz_hs":39582710293386.5,"rzyezb_h":1.8970045114693783,"rzyezb_s":2.3696602567693148,"rzyezb_hs":2.0593407594961848,"rzmre_h":11449499099.0,"rzmre_s":8441924791.0,"rzmre_hs":19891423890.0,"rqye_h":6579183795.0,"rqye_s":998315447.0,"rqye_hs":7577499242.0,"rzrqye_h":499569073987.0,"rzrqye_s":323151312040.0,"rzrqye_hs":822720386027.0,"rzrqyecz_h":486410706397.0,"rzrqyecz_s":321154681146.0,"rzrqyecz_hs":807565387543.0},{"tdate":"2018-09-27T00:00:00","rzye_h":497647547449.0,"rzye_s":325607432496.0,"rzye_hs":823254979945.0,"ltsz_h":25718238000000.0,"ltsz_s":13481633399458.8,"ltsz_hs":39199871399458.8,"rzyezb_h":1.9349986085710849,"rzyezb_s":2.4151927503760118,"rzyezb_hs":2.1001471447591689,"rzmre_h":11426969305.0,"rzmre_s":9569310352.0,"rzmre_hs":20996279657.0,"rqye_h":6625016713.0,"rqye_s":1042117120.0,"rqye_hs":7667133833.0,"rzrqye_h":504272564162.0,"rzrqye_s":326649549616.0,"rzrqye_hs":830922113778.0,"rzrqyecz_h":491022530736.0,"rzrqyecz_s":324565315376.0,"rzrqyecz_hs":815587846112.0},{"tdate":"2018-09-26T00:00:00","rzye_h":499093628658.0,"rzye_s":326683729117.0,"rzye_hs":825777357775.0,"ltsz_h":25813915000000.0,"ltsz_s":13642920303783.9,"ltsz_hs":39456835303783.9,"rzyezb_h":1.933428651399836,"rzyezb_s":2.3945293371419418,"rzyezb_hs":2.0928626216908182,"rzmre_h":14668100299.0,"rzmre_s":10723574284.0,"rzmre_hs":25391674583.0,"rqye_h":6803855948.0,"rqye_s":1114731467.0,"rqye_hs":7918587415.0,"rzrqye_h":505897484606.0,"rzrqye_s":327798460584.0,"rzrqye_hs":833695945190.0,"rzrqyecz_h":492289772710.0,"rzrqyecz_s":325568997650.0,"rzrqyecz_hs":817858770360.0},{"tdate":"2018-09-25T00:00:00","rzye_h":499653250243.0,"rzye_s":327827927346.0,"rzye_hs":827481177589.0,"ltsz_h":25554223000000.0,"ltsz_s":13549062000000.0,"ltsz_hs":39103285000000.0,"rzyezb_h":1.9552668466695309,"rzyezb_s":2.4195617921447257,"rzyezb_hs":2.1161423588555284,"rzmre_h":12021682276.0,"rzmre_s":9134647468.0,"rzmre_hs":21156329744.0,"rqye_h":6508750346.0,"rqye_s":1088256737.0,"rqye_hs":7597007083.0,"rzrqye_h":506162000589.0,"rzrqye_s":328916184083.0,"rzrqye_hs":835078184672.0,"rzrqyecz_h":493144499897.0,"rzrqyecz_s":326739670609.0,"rzrqyecz_hs":819884170506.0},{"tdate":"2018-09-21T00:00:00","rzye_h":498777152833.0,"rzye_s":327576973833.0,"rzye_hs":826354126666.0,"ltsz_h":25702709000000.0,"ltsz_s":13622903745391.5,"ltsz_hs":39325612745391.5,"rzyezb_h":1.9405625797381902,"rzyezb_s":2.4046046272903912,"rzyezb_hs":2.1013127805944714,"rzmre_h":14696528658.0,"rzmre_s":11770630519.0,"rzmre_hs":26467159177.0,"rqye_h":6719384774.0,"rqye_s":1098430321.0,"rqye_hs":7817815095.0,"rzrqye_h":505496537607.0,"rzrqye_s":328675404154.0,"rzrqye_hs":834171941761.0,"rzrqyecz_h":492057768059.0,"rzrqyecz_s":326478543512.0,"rzrqyecz_hs":818536311571.0}';
//$data = substr($data,1,-1);
//$arrData = explode('},{',$data);
////print_r($arrData);
//$str0 = '{'.$arrData[0].'}';
//echo $str0;
//var_dump(json_decode($str0));
 