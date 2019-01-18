<?php

namespace App\Http\Controllers\Cart;

use App\Model\CartModel;
use App\Model\GoodsModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request){
        $uid = session()->get('uid');
        $cart_goods = CartModel::where(['uid'=>$uid])->get()->toArray();
        if(empty($cart_goods)){
            die("购物车是空的");
        }

        if($cart_goods){
            //获取商品最新信息
            foreach($cart_goods as $k=>$v){
                $goods_info = GoodsModel::where(['goods_id'=>$v['goods_id']])->first();
                $goods_info['num']  = $v['num'];
                $goods_info['id']=$v['id'];
                $list[] = $goods_info;
            }
        }
        $data = [
            'list'  => $list,
        ];
        return view('cart.list',$data);
    }

    public function del($id){
        $res = CartModel::where(['id'=>$id])->delete();
        if($res){
            echo "删除成功";
            header("refresh:1;/cart/list");
        }else{
            echo "删除失败";
        }

    }
    public function add2(Request $request){
        $goods_id=$request->input('goods_id');
        $num=$request->input('num');
        $goods_store=GoodsModel::where(['goods_id'=>$goods_id])->value('store');
        if($goods_store<=0){
            $response=[
                'error'=>5001,
                'msg'=>'库存不足',
            ];
            return $response;
        }
        $goods_num=CartModel::where(['goods_id'=>$goods_id,'uid'=>session('uid')])->value('num');
        if(!empty($goods_num)){
            $data=[
                'num'=>$num+$goods_num,
            ];
            $res=CartModel::where(['goods_id'=>$goods_id])->update($data);
        }else{
            $data=[
                'goods_id'=>$goods_id,
                'num'=>$num,
                'add_time'=>time(),
                'uid'=>session('uid'),
                'session_token'=>session('u_token')
            ];
            $res=CartModel::insertGetId($data);
        }
        if(!$res){
            $response=[
                'error'=>5002,
                'msg'=>'添加失败，请重试',
            ];
            return $response;
        }else{
            $response=[
                'error'=>0,
                'msg'=>'添加成功',
            ];
            return $response;
        }
    }
    public function cartList(){
        $where=[
            'uid'=>session('uid')
        ];
        $list=CartModel::where($where)->all();
        foreach($list as $k=>$v){
            $name=GoodsModel::where(['goods_id'=>$v->goods_id])->value('goods_name');
        }
       ;
        $data=[
            'list'=>$list,
            'name'=>$name
        ];
        return view('cart.list',$data);
    }
}
