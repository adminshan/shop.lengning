@extends('layouts.bst')
@section('content')

    <h1 align="center" style="color:red;">Welcome back </h1>
    <table class="table table-striped">
        <tr>

            <td>订单号</td>
            <td>总计</td>
            <td>时间</td>
            <td>订单状态</td>
            <td>操作</td>
        </tr>
        @foreach($list as $v)
            <tr>
                <td>{{$v->order_sn}}</td>
                <td>{{$v->order_amount / 100}}</td>
                <td>{{date("Y-m-d H:i:s",$v ->add_time)}}</td>
                <td>
                     @if($v->status==1)
                        未支付
                     @elseif($v->status==2)
                        已取消
                     @elseif($v->status==3)
                        已支付
                    @endif
                </td>
                <td>
                    @if($v->status==1)
                        <a href="/pay/alipay/test">支付</a>|
                        <a href="/order/detail/{{$v->order_sn}}">查看详情</a>
                        <a href="/order/del/{{$v->order_sn}}">取消</a>
                    @elseif($v->status==2)
                        <a href="/order/detail/{{$v->order_sn}}">查看详情</a>
                    @elseif($v->status==3)
                        已支付|
                        <a href="/order/detail/{{$v->order_sn}}">查看详情</a>
                        |取消
                    @endif
                </td>
            </tr>

        @endforeach
    </table>
@endsection