@extends('layouts.bst')

@section('content')
    <form action="/text/dopwd" method="post" class="form-signin">
        {{csrf_field()}}
        <label for="inputPassword1">原密码</label>
        <input type="password" name="pwd1" id="inputPassword" class="form-control" placeholder="***" required>
        <label for="inputPassword2" >新密码</label>
        <input type="password" name="pwd2" id="inputPassword1" class="form-control" placeholder="***" required>
        <label for="inputPassword3" >确认密码</label>
        <input type="password" name="pwd3" id="inputPassword2" class="form-control" placeholder="***" required>
        <button class="btn btn-lg btn-primary btn-block" type="submit">修改</button>
    </form>
@endsection