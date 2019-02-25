<?php

namespace App\Admin\Controllers;

use App\Model\WeixinUser;
use App\Model\WeixinChatModel;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use GuzzleHttp;
use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Request;
class WeixinController extends Controller
{
    protected $redis_weixin_access_token = 'str:weixin_access_token';     //微信 access_token
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('Index')
            ->description('description')
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('Detail')
            ->description('description')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('Edit')
            ->description('description')
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('Create')
            ->description('description')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new WeixinUser);

        $grid->id('Id');
        $grid->uid('Uid');
        $grid->openid('Openid')->display(function($openid){
            return "<a href='/admin/content?openid=".$openid."'>".$openid."</a>";
        });
        $grid->add_time('Add time')->display(function($time){
            return date('Y-m-d H:i:s',$time);
        });
        $grid->nickname('Nickname');
        $grid->sex('Sex');
        $grid->headimgurl('Headimgurl')->display(function ($img_url){
            return '<img src="'.$img_url.'">';
        });
        $grid->subscribe_time('Subscribe time')->display(function($time){
            return date('Y-m-d H:i:s',$time);
        });

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(WeixinUser::findOrFail($id));
        $show->id('Id');
        $show->uid('Uid');
        $show->openid('Openid');
        $show->add_time('Add time')->display(function($time){
            return date('Y-m-d H:i:s',$time);
        });
        $show->nickname('Nickname');
        $show->sex('Sex');
        $show->headimgurl('Headimgurl');
        $show->subscribe_time('Subscribe time')->display(function($time){
            return date('Y-m-d H:i:s',$time);
        });


        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new WeixinUser);
        $form->number('uid', 'Uid');
        $form->text('openid', 'Openid');
        $form->number('add_time', 'Add time');
        $form->text('nickname', 'Nickname');
        $form->switch('sex', 'Sex');
        $form->text('headimgurl', 'Headimgurl');
        $form->number('subscribe_time', 'Subscribe time');


        return $form;
    }


    /**
     * 微信客服聊天
     */
    public function chatView(Content $content)
    {
        $openid=$_GET['openid'];
        return $content
            ->header('私聊')
            ->description('description')
            ->body(view('/send/content',['openid'=>$openid]));

    }

    public function getChatMsg()
    {
        $openid = $_GET['openid'];  //用户openid
        $pos = $_GET['pos'];        //上次聊天位置
       /* $response=[
            'openid'=>$openid,
            'pos'=>$pos
        ];
        exit( json_encode($response));exit;*/
        $msg = WeixinChatModel::where(['openid'=>$openid])->where('id','>',$pos)->first();
        //$msg = WeixinChatModel::where(['openid'=>$openid])->where('id','>',$pos)->get();
        if($msg){
            $response = [
                'errno' => 0,
                'data'  => $msg->toArray()
            ];
        }else{
            $response = [
                'errno' => 50001,
                'msg'   => '服务器异常，请联系管理员'
            ];
        }
        die( json_encode($response));
    }
    public function doChat(Request $request){
        $openid=$request->input('openid');
        $msg=$request->input('msg');
        $url='https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$this->getWXAccessToken();
        //请求微信接口
        $client=new GuzzleHttp\Client(['base_uri' => $url]);
        $data=[
            'touser'=>$openid,
            'msgtype'=>'text',
            'text'=>['content'=>$msg]
        ];
        $r=$client->request('post',$url,['body'=>json_encode($data,JSON_UNESCAPED_UNICODE)]);
        //解析接口返回信息
        $response_arr=json_decode($r->getBody(),true);
        //var_dump($response_arr);
        if($response_arr['errcode']==0){
            echo "群发成功";
        }else{
            echo "群发失败，请重试";
            echo "<br/>";
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
}
