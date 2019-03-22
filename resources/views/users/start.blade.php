@extends('layouts.bst')
@section('content')
    @if($login==1)
        <a href="{{url('/users/center')}}">个人中心</a>
    @else
    <button class="reg">注册</button>
    <button class="login">登录</button>
    @endif
@endsection
@section('footer')
@parent
<script>
    $('.reg').click(function(){
        location.href="http://passport.shopshan.com/reg?reurl="+"{{$recurl}}";
    })
    $('.login').click(function(){
        location.href="http://passport.shopshan.com/userlogin?reurl="+"{{$recurl}}";
    })
</script>
@endsection