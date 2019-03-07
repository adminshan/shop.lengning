@extends('layouts.bst')

@section('content')
    <form action="/text/login" method="post" class="form-signin">
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
    </form>
@endsection