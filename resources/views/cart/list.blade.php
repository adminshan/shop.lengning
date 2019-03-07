@extends('layouts.bst')
@section('content')

    <h1 align="center" style="color:red;">Welcome back </h1>
    <a href="/users/quit" style="align:right">退出</a>
    <table class="table table-striped">
        <tr>
            <td>goods_name</td>
            <td>num</td>
            <td>price</td>
            <td>add_time</td>
            <td>操作</td>
        </tr>
        @foreach($list as $v)
            <tr>
                <td>{{$v->goods_name}}</td>
                <td>{{$v->num}}</td>
                <td>{{$v->price / 100}}</td>
                <td>{{date("Y-m-d H:i:s",$v ->add_time)}}</td>
                <td><a href="/cart/del/{{$v->id}}">删除</a>|<a href="/order/add/{{$v->id}}">提交订单</a></td>
            </tr>

        @endforeach
    </table>
@endsection