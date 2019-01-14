@extends('layouts.bst')
@section('content')

    <h1 align="center" style="color:red;">Welcome back </h1>
    <a href="/users/quit" style="align:right">退出</a>
    <table class="table table-striped">
        <tr>
            <td>goods_name</td>
            <td>store</td>
            <td>price</td>
            <td>操作</td>
        </tr>
        @foreach($list as $v)
            <tr>
                <td>{{$v->goods_name}}</td>
                <td>{{$v->store}}</td>
                <td>{{$v->price / 100}}</td>
                <td><a href="/goods/index/{{$v->goods_id}}">详情</a></td>
            </tr>

        @endforeach
    </table>
@endsection