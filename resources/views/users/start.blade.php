@extends('layouts.bst')
@section('content')
    @if($login==1)
        <a href="{{url('/users/center')}}">个人中心</a>
        <a href="{{url('/quit')}}">退出</a>
    @else
    <button class="reg">注册</button>
    <button class="login">登录</button>
    @endif
@endsection
@section('footer')
@parent
<script>
    $('.reg').click(function(){
        location.href="http://port.tactshan.com/reg?reurl="+"{{$recurl}}";
    })
    $('.login').click(function(){
        location.href="http://port.tactshan.com/userlogin?reurl="+"{{$recurl}}";
    })
</script>
@endsection