<?php

namespace App\Http\Controllers\Weixin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\WeixinUser;
use Illuminate\Support\Facades\Redis;
use GuzzleHttp;
use Illuminate\Support\Facades\Storage;

class WeixinController extends Controller
{
    protected $redis_weixin_access_token = 'str:weixin_access_token'; //微信 access_token
    public function validToken1(){
        echo $_GET['echostr'];
    }
    public function wxEvent(){
        $data = file_get_contents("php://input");

        //解析XML
        $xml = simplexml_load_string($data);        //将 xml字符串 转换成对象

        $event = $xml->Event;                       //事件类型
        $openid=$xml->FromUserName;
        //处理用户信息
        if(isset($xml->MsgType)){
            if($xml->MsgType=='text'){            //用户发送文本消息
                $msg = $xml->Content;
                $xml_response = '<xml><ToUserName><![CDATA['.$openid.']]></ToUserName><FromUserName><![CDATA['.$xml->ToUserName.']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['. $msg. date('Y-m-d H:i:s') .']]></Content></xml>';
                echo $xml_response;

            }elseif($xml->MsgType=='image'){
                //视业务需求是否需要下载保存图片
                if(1){  //下载图片素材
                    $this->dlWxImg($xml->MediaId);
                    $xml_response = '<xml><ToUserName><![CDATA['.$openid.']]></ToUserName><FromUserName><![CDATA['.$xml->ToUserName.']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['. date('Y-m-d H:i:s') .']]></Content></xml>';
                    echo $xml_response;
                }
            }
        }
        if($event=='subscribe'){
            $openid = $xml->FromUserName;               //用户openid
            $sub_time = $xml->CreateTime;               //扫码关注时间

//            echo 'openid: '.$openid;echo '</br>';
//            echo '$sub_time: ' . $sub_time;

            //获取用户信息
            $user_info = $this->getUserInfo($openid);
            echo '<pre>';print_r($user_info);echo '</pre>';

            //保存用户信息
            $u = WeixinUser::where(['openid'=>$openid])->first();
            //var_dump($u);die;
            if($u){       //用户不存在
                echo '用户已存在';
            }else{
                $user_data = [
                    'openid'            => $openid,
                    'add_time'          => time(),
                    'nickname'          => $user_info['nickname'],
                    'sex'               => $user_info['sex'],
                    'headimgurl'        => $user_info['headimgurl'],
                    'subscribe_time'    => $sub_time,
                ];

                $id = WeixinUser::insertGetId($user_data);      //保存用户信息
                var_dump($id);
            }
        }elseif($event=='CLICK'){
            if($xml->EventKey=='kefu01'){
                $this->kefu01($openid,$xml->ToUserName);
            }
        }

        $log_str = date('Y-m-d H:i:s') . "\n" . $data . "\n<<<<<<<";
        file_put_contents('logs/wx_event.log',$log_str,FILE_APPEND);
    }
    /***
     * 客服
     *  @param $openid   用户openid
     * @param $from     开发者公众号id 非 APPID
     */
    public function kefu01($openid,$from){
        $xml_response = '<xml><ToUserName><![CDATA['.$openid.']]></ToUserName><FromUserName><![CDATA['.$from.']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['. '您好，请问有什么可以为您服务的吗'. ']]></Content></xml>';
        echo $xml_response;
    }
    /**
     * 下载图片素材
     * @param $media_id
     */
    public function dlWxImg($media_id){
        $url = 'https://api.weixin.qq.com/cgi-bin/media/get?access_token='.$this->getWXAccessToken().'&media_id='.$media_id;
        //保存照片
        $client = new GuzzleHttp\Client();
        $response = $client->get($url);
        //获取文件名
        $file_info = $response->getHeader('Content-disposition');
        $file_name = substr(rtrim($file_info[0],'"'),-20);
        $wx_image_path = 'wx/images/'.$file_name;
        //保存图片
        $r = Storage::disk('local')->put($wx_image_path,$response->getBody());
        if($r){
            return true;
        }else{
            return false;
        }
    }
    /**
     * 获取微信AccessToken
     */
    public function getWXAccessToken()
    {

        //获取缓存
        $token = Redis::get($this->redis_weixin_access_token);
        if(!$token){        // 无缓存 请求微信接口
            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.env('WEIXIN_APPID').'&secret='.env('WEIXIN_APPSECRET');
            $data = json_decode(file_get_contents($url),true);

            //记录缓存
            $token = $data['access_token'];
            Redis::set($this->redis_weixin_access_token,$token);
            Redis::setTimeout($this->redis_weixin_access_token,3600);
        }
        return $token;

    }

    /**
     * 获取用户信息
     * @param $openid
     */
    public function getUserInfo($openid)
    {
        //$openid = 'oLreB1jAnJFzV_8AGWUZlfuaoQto';
        $access_token = $this->getWXAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';

        $data = json_decode(file_get_contents($url),true);
        //echo '<pre>';print_r($data);echo '</pre>';
        return $data;
    }

    //创建菜单
    public function createMenu(){
        //获取access_token拼接请求接口
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$this->getWXAccessToken();

        //请求微信接口
        $client = new GuzzleHttp\Client(['base_url' => $url]);

        $data=[

            "button" =>[
                [
                    "type" => "view",
                    "name" => "lening",
                    "url"  => "https://www.baidu.com"
                ],
                [
                    "type"  => "click",      // click类型
                    "name"  => "客服",
                    "key"   => "kefu01"
                ]
            ]
        ];
        $r = $client->request('POST',$url,["body" => json_encode($data,JSON_UNESCAPED_UNICODE)]);

        //解析微信接口返回信息
        $response_arr = json_decode($r->getBody(),true);
        if($response_arr['errcode']==0){
            echo "菜单创建成功";
        }else{
            echo "菜单创建失败，请重试";echo '</br>';
            echo $response_arr['errmsg'];
        }

    }

}






























