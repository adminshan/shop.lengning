@extends('layouts.bootstrap')
@section('content')

    <h1 align="center" style="color:red;">Welcome back </h1>
    <table class="table table-bordered">
        <tr>
            <td>订单号</td>
            <td>{{$list->order_sn}}</td>
        </tr>
        <tr>
            <td>名称</td>
            <td>{{$list->goods_name}}</td>
        </tr>
        <tr>
            <td>单价</td>
            <td>{{$list->price / 100}}</td>
        </tr>
        <tr>
            <td>数量</td>
            <td>{{$list->order_num}}</td>
        </tr>
        <tr>
            <td>总计</td>
            <td>{{$list->order_amount / 100}}</td>
        </tr>
        <tr>
            <td>时间</td>
            <td>{{date("Y-m-d H:i:s",$list ->add_time)}}</td>
        </tr>
        <tr>
            <td>操作</td>
            <td>
                @if($status==1)
                    <button class="btn btn-danger"><a href="/pay/alipay/test/{{$list->order_sn}}" style="text-decoration: none; color: #ffffff;">去付款</a></button>
                    <button class="btn btn-danger"><a href="/order/list" style="text-decoration: none; color: #ffffff;">返回</a></button>
                @elseif($status==3||$status==2)
                    <button class="btn btn-danger"><a href="/order/list" style="text-decoration: none; color: #ffffff;">返回</a></button>
                @endif
            </td>
        </tr>

    </table>
@endsection


























