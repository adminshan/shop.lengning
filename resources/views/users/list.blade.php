@extends('layouts.bst')
@section('content')

   <h1 align="center" style="color:red;">Welcome back {{$name}}</h1>
   <a href="/users/cart">进入购物车</a>
   <a href="/users/quit" style="align:right">退出</a>
<table class="table table-striped">
    <tr>
        <td>account</td>
        <td>age</td>
        <td>email</td>
    </tr>
    @foreach($list as $v)
        <tr>
            <td>{{$v->name}}</td>
            <td>{{$v->age}}</td>
            <td>{{$v->email}}</td>
        </tr>

    @endforeach
</table>
@endsection