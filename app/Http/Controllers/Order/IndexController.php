<?php

namespace App\Http\Controllers\Order;

use App\Model\CartModel;
use App\Model\DetailModel;
use App\Model\OrderModel;
use App\Model\GoodsModel;
use App\Model\UserModel;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Http\Controllers\Controller;

class indexController extends Controller
{
    public function add($id){
        $cart_goods = CartModel::where(['id'=>$id])->first();
        $cart_num=$cart_goods->num;
        if(empty($cart_goods)){
            echo '购物车中无商品';
        }
        $orde_amount=0;
        $goods_info = GoodsModel::where(['goods_id'=>$cart_goods->goods_id])->first();
        $num=$goods_info->store;
        $update=[
            'store'=>$num-$cart_num
        ];
        $orde_amount+=$cart_goods->num*$goods_info->price;
        $order_sn = OrderModel::generateOrderSN();
        $data=[
            'order_sn'=>$order_sn,
            'goods_id'=>$goods_info->goods_id,
            'uid'=>session('uid'),
            'order_num'=>$cart_goods->num,
            'add_time'=>time(),
            'order_amount'=>$orde_amount
        ];
        $res=OrderModel::insertGetId($data);
        if($res){
            echo '下单成功,订单号：'.$order_sn .' 跳转支付';
            CartModel::where(['id'=>$id])->delete();
            GoodsModel::where(['goods_id'=>$goods_info->goods_id])->update($update);
            $order=OrderModel::where(['order_sn'=>$order_sn])->first();
            $list=OrderModel::where(['order_id'=>$order->order_id])->first();
            $goods=GoodsModel::where(['goods_id'=>$list->goods_id])->first();
            $datainfo=[
                'order_sn'=>$list->order_sn,
                'goods_name'=>$goods->goods_name,
                'price'=>$goods->price,
                'order_num'=>$list->order_num,
                'order_amount'=>$list->order_amount,
                'add_time'=>time()
            ];
            DetailModel::insert($datainfo);
            header("refresh:2;/order/list");
        }else{
            echo '下单失败';
        }
    }
    public function list(){
        $list=OrderModel::all();
        $data=[
            'uid'=>session('uid'),
            'list'=>$list
        ];
        return view("order.list",$data);
    }
    public function detail($order_sn){

        $list=DetailModel::where(['order_sn'=>$order_sn])->first();
        $status=OrderModel::where(['order_sn'=>$order_sn])->value('status');
        $data=[
            'list'=>$list,
            'status'=>$status
        ];
        return view('order.detail',$data);
    }
    public function del($order_sn){
        $data=[
            'status'=>2
        ];
        $res=OrderModel::where(['order_sn'=>$order_sn])->update($data);
        if($res){
            echo "取消成功";
            $orderInfo=OrderModel::where(['order_sn'=>$order_sn])->first();
            $order_num=$orderInfo->order_num;
            $goods_id=$orderInfo->goods_id;
            $store=GoodsModel::where(['goods_id'=>$goods_id])->value('store');
            $store_num=$order_num+$store;
            $dataWhere=[
                'store'=>$store_num
            ];
            GoodsModel::where(['goods_id'=>$goods_id])->update($dataWhere);
            header("refresh:2;/order/list");
        }else{
            echo "取消失败";
        }
    }
    public function pay($order_sn){
        $data=[
            'status'=>3
        ];
        $res=OrderModel::where(['order_sn'=>$order_sn])->update($data);
        if($res){
            echo "支付成功";
            $score=OrderModel::where(['order_sn'=>$order_sn])->value('order_amount');
            $user_score=UserModel::where(['uid'=>session('uid')])->value('score');
            if(empty($user_score)){
                $data=[
                    'score'=>$score
                ];
            }else{
                $data=[
                    'score'=>$score+$user_score
                ];
            }
            UserModel::where(['uid'=>session('uid')])->update($data);
            header("refresh:2;/order/list");
        }else{
            echo "支付失败";
        }
    }
    public function test(){
        $url="http://www.ordershan.com";
        $client=new Client(['base_uri'=>$url,'timeout'=>2.0,]);
        $response=$client->request('GET','/index.php');
        echo $response->getBody();
    }
}
