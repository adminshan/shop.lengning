<?php

namespace App\Http\Controllers\Goods;

use App\Model\GoodsModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class indexController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index($goods_id){
        $goods=GoodsModel::where(['goods_id'=>$goods_id])->first();
        if(empty($goods)){
            echo '商品不存在';
        }else{
            $data=[
                'goods'=>$goods
            ];
            return view('goods.index',$data);
        }
    }
    public function list(){
        $list=GoodsModel::all();
        $data=[
            'list'=>$list
        ];
        return view('goods.goodslist',$data);
    }
}
