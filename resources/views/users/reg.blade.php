{{-- 用户注册--}}

@extends('layouts.bst')

@section('content')
    <form class="form-signin" action="/reg" method="post">
        {{csrf_field()}}
        <h2 class="form-signin-heading">User registration</h2>
        <label for="inputNickName">Nickname</label>
        <input type="text" name="name" id="inputNickName" class="form-control" placeholder="nickname" required autofocus>
        <label for="inputEmail">Email</label>
        <input type="email" name="email" id="inputEmail" class="form-control" placeholder="@" required autofocus>
        <label for="inputAge">Age</label>
        <input type="text" name="age" id="inputAge" class="form-control" placeholder="age" required autofocus>
        <label for="inputPassword" >Password</label>
        <input type="password" name="pwd" id="inputPassword" class="form-control" placeholder="***" required>
        <label for="inputPassword2" >Password</label>
        <input type="password" name="pwd2" id="inputPassword2" class="form-control" placeholder="***" required>
        <button class="btn btn-lg btn-primary btn-block" type="submit">注册</button>
    </form>
@endsection