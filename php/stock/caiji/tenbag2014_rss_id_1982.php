<?php
/**
 * 采集基于搜狗-微信窗口http://weixin.sogou.com
 * memory_limit = 512M php.ini配置要高些
 */
set_time_limit(300);//5分钟超时
use QL\QueryList;
$date=date('His');
if($date>0&&$date<80000){
    echo date('Y-m-d H:i:s')."  0~8点不进行采集！";exit;//0~8点不进行采集,这个时间段比较容易禁止采集
}
$map['id']=1982;
$map['type']=2;
$map['status']=13;
$map['rss_model']=0;

$rss_info=$db->table('wm_user_rss')->where($map)->order('rss_time asc')->find();
if(empty($rss_info)){
    echo date('Y-m-d H:i:s')." 暂无采集源采集！";exit;
}

//初始化配置
$config = new \stdClass;
$config->rss_id=$rss_info->id;//采集源id
$config->media_id=$rss_info->user_id;//所属媒体号
$config->url=$rss_info->url;//采集地址
$config->content_remove_selector=$rss_info->content_remove_selector;//采集忽略内容

//1、通过搜狗搜索页搜索微信公众号
$sougou_weixin_url=SOUGOUWX."/weixin?type=1&query=".$config->url."&ie=utf8&s_from=input&_sug_=y&_sug_type_=";
$wx_list=QueryList::get($sougou_weixin_url)->rules([
    'url'=>array('.news-box .tit a','href'),
])->encoding('UTF-8')->query()->getData();

//2、通过搜索结果进入公众号历史文章页
sleep(1);
$weixin_url= str_replace('&amp;','&',$wx_list->all()['0']['url']);
$html=file_get_contents($weixin_url);
$verify_img=QueryList::html($html)->rules([
    'v_img'=>array('.weui_cell_ft','html'),
])->encoding('UTF-8')->query()->getData();
if(!empty($verify_img->all())){
    //刷新太快：搜狗浏览器要输入验证码->搜索文章列表页面
     if(EXEC_MODEL=='CLI'){
        exec(PHP7PATH.' '.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']. ' api change_verifycode');
    }else{
        curl_get($_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'/?rss_type=api&action=change_verifycode');//获取验证码
    }
    die(date('Y-m-d H:i:s')." 请输入验证码-文章列表页面，当前rss_id：".$config->rss_id);
}

//3、获取json数据
preg_match('/var msgList =(.*)seajs.use/is',$html,$list_json);
$list_json=trim(trim($list_json[1]),';');
$list_json=json_decode($list_json);
$data=$list_json->list;

//4、组装文章列表
$rss_data=get_rss_data($data,$config);
$data=wximgreload($rss_data);
$data=img_pass($data);
//5、数据入库
api_add_article($data,$config);
//6、更新采集时间
$db->table('wm_user_rss')->where(['id'=>$config->rss_id])->update(['rss_time'=>date('Y-m-d H:i:s')]);

/**
 * 获取采集数据
 */
function get_rss_data($data,$config)
{
    $_data=array();
    if(!empty($data)){
        foreach ($data as $key => $value) {
            $rss_log=read_rss_log($config->rss_id);//读取抓取日志;已抓取就不在抓取
            if(in_array($value->comm_msg_info->datetime, $rss_log)){continue;}//发布时间去重
            if($value->comm_msg_info->datetime<time()-24*60*60){continue;}//只采集一天以内的文章
            $uri='';
            $uri = urldecode($value->app_msg_ext_info->content_url);
            $uri = str_replace('&amp;','&',$uri);
            $uri = WEIXIN.$uri;
            $html=file_get_contents($uri);
            //不采集分享（转载）文章
            $share_content=QueryList::html($html)->rules([
                'content'=>array('#js_share_content','html'),
            ])->encoding('UTF-8')->query()->getData();
            if(!empty($share_content->all())){
                continue;
            }
            preg_match('/<div class=\"rich_media_content \".*?>.*?<\/div>/ism',$html,$content);

            $_data[]=array(
                'title'=>$value->app_msg_ext_info->title,
                'link'=>$uri,
                'img'=>$value->app_msg_ext_info->cover,
                'content'=>$content['0'],
                'update_time'=>$value->comm_msg_info->datetime,
            );

            if (!empty($value->app_msg_ext_info->multi_app_msg_item_list)) {
                foreach ($value->app_msg_ext_info->multi_app_msg_item_list as $k => $v) {
                    $uri='';
                    $uri = urldecode($v->content_url);
                    $uri = str_replace('&amp;','&',$uri);
                    $uri = WEIXIN.$uri;
                    $html=file_get_contents($uri);
                    //不采集分享（转载）文章
                    $share_content=QueryList::html($html)->rules([
                        'content'=>array('#js_share_content','html'),
                    ])->encoding('UTF-8')->query()->getData();
                    if(!empty($share_content->all())){
                        continue;
                    }
                    preg_match('/<div class=\"rich_media_content \".*?>.*?<\/div>/ism',$html,$content);

                    $_data[]=array(
                        'title'=>$v->title,
                        'link'=>$uri,
                        'img'=>$v->cover,
                        'content'=>$content['0'],
                        'update_time'=>$value->comm_msg_info->datetime,
                    );
                }
            }
        }
    }

    if(empty($_data)){
        echo date('Y-m-d H:i:s')." 今日未发布文章，或者本采集源已采集，当前rss_id：".$config->rss_id."\r\n";
    }
    return $_data;
}

/**
 * 采集数据入库
 * @param  array  $data [description]
 * @return [type]       [description]
 */
function api_add_article($data=array(),$config){
    foreach ($data as $key => $value) {
        $_data=array();
        $_data['rssID']=$config->rss_id;
        $_data['weMediaId']=$config->media_id;
        $_data['title']=$value['title'];
        $_data['coverPic']=$value['img'];
        $_data['content']=$value['content'];
        $res=curl_post(ADD_ARTICLE_API,$_data);
        $info=json_decode($res,true);
        if($info['result']!='10000'){
            $err=array(
                'return'=>$info,
                'data'=>$_data,
            );
            err_log($err);
            echo date('Y-m-d H:i:s')." RSS_ID:".$config->rss_id."->采集失败原因：".$info['msg']."\r\n";
        }else{
            write_rss_log($config->rss_id,$value['update_time']);
            echo date('Y-m-d H:i:s')." RSS_ID:".$config->rss_id."->采集【".$value['title']."】成功。\r\n";
        }
    }
}

/**
 * 下载图片并上传到亚马逊服务器
 * @return [type] [description]
 */
function wximgreload($data=array()){
    foreach ($data as $key => &$value) {
        //上传封面图
        if(!empty($value['img'])){
            $parse_url=parse_url($value['img']);
            $path_arr=array_filter(explode('/',$parse_url['path']));
            parse_str($parse_url['query'],$query);
            $ext=$query['wx_fmt'];

            $filename = time() . mt_rand(100000, 999999).'.'.$ext;
            $filepath=ROOT_PATH.'/upload/'.date('Ymd').'/';
            if(!is_dir($filepath)){
                mkdir($filepath,0777,true);
            }
            $imagefile =curl_get($value['img']);
            file_put_contents($filepath.$filename,$imagefile);
            $imgrs = upload_amazon($filename,$filepath.$filename);
            $value['img']=$imgrs['path'];
        }
        //过滤超链接
        $value['content']=preg_replace("/<a[^>]*>/i","",$value['content']);
        /*过滤图片*/
        preg_match_all('/(<img[\s\S]*?(><\/img>|>))/i', $value['content'], $matchimg);

        if($matchimg){
            foreach($matchimg[1] as $k=>$v)
            {
                $src = array();
                preg_match('/src=["\']?(.+?)("|\'| |>|\/>){1}/i', $v, $src);
                $img_url=$src[1];

                $parse_url=parse_url($img_url);
                $path_arr=array_filter(explode('/',$parse_url['path']));
                parse_str($parse_url['query'],$query);
                $ext=$query['wx_fmt'];
               
                $fileName = md5('cnfol@cnfol.com'.time().mt_rand(100000, 999999)).'.'.$ext;
                $filePath=ROOT_PATH.'/upload/'.date('Ymd').'/';
                if(!is_dir($filePath)){
                    mkdir($filePath,0777,true);
                }
                $imageFile =curl_get($img_url);
                file_put_contents($filePath.$fileName,$imageFile);
                $imgrs = upload_amazon($fileName,$filePath.$fileName);
                
                //如果图片有二维码,或者图片尺寸大于1200小于20则去除图片
                $img_value=getimagesize($filePath.$fileName);
                if($img_value['0']>1200||$img_value['0']<20){
                    $value['content']=str_replace($v,'',$value['content']);
                }else {
                    //如果有src就用src ,如果没有src就用data-src
                    preg_match("/data-src=\"([^\"]*?)\"/", $v, $datasrc);
                    $img_v = preg_replace("/data-src=\"([^\"]*?)\"/", "", $v);
                    preg_match("/src=\"([^\"]*?)\"/", $img_v, $new_src);
                    if (empty($new_src)&&!empty($datasrc[1])) {
                        $value['content']= preg_replace("/data-src=\"([^\"]*?)\"/", "src=\"" . $datasrc[1] . "\"", $value['content'],1);
                    }
                    //把图片地址换成亚马逊的
                    $value['content']=str_replace($img_url,$imgrs['path'],$value['content']);
                }
            }
        }
    }
    return $data;
}
