@extends('layouts.goods')

@section('content')
    <form action="send" method="post">
        {{csrf_field()}}
        <input type="text" name="text">
        <input type="submit" value="发送">
    </form>

@endsection