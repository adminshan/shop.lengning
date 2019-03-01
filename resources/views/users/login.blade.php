
@extends('layouts.bst')

@section('content')
    <form action="/userlogin" method="post" class="form-signin">
        {{csrf_field()}}
        <h2 class="form-signin-heading">Please log in</h2>
        <label for="inputName">Name</label>
        <input type="text" name="name" id="inputNickName" class="form-control" placeholder="nickname" required autofocus>
        <label for="inputPassword" >Password</label>
        <input type="password" name="pwd" id="inputPassword" class="form-control" placeholder="***" required>
        <div class="checkbox">
            <label>
                <input type="checkbox" value="remember-me"> Remember me
            </label>
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
        <h3>
            <a href="https://open.weixin.qq.com/connect/qrconnect?appid=wxe24f70961302b5a5&amp;redirect_uri=http%3a%2f%2fmall.77sc.com.cn%2fweixin.php%3fr1%3dhttp%3a%2f%2fwang.shopshan.com%2fweixin%2fgetcode&amp;response_type=code&amp;scope=snsapi_login&amp;state=STATE#wechat_redirect">
                微信登录</a>
        </h3>
    </form>
@endsection