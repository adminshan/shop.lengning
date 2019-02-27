@extends('layouts.bst')

@section('content')
    
        <input type="hidden" value="{{$code_url}}" id="code">
        <input type="hidden" value="{{$order_sn}}" id="order_id">
        <div id="qrcode" align="center"></div>
        <h2 align="center">订单支付</h2>
@endsection
@section('footer')
    @parent
    <script src="{{URL::asset('/js/qrcode.js')}}"></script>
    <script>
        var code=$('#code').val()
        // 设置参数方式
        var qrcode = new QRCode('qrcode', {
            text:code ,
            width: 256,
            height: 256,
            colorDark : '#000000',
            colorLight : '#ffffff',
            correctLevel : QRCode.CorrectLevel.H
        });
        setInterval(function(){
            var order_sn=$('#order_id').val()
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url     :   '/pay/success',
                type    :   'post',
                data    :   {order_sn:order_sn},
                dataType:   'json',
                success :   function(res){
                    if(res==1){
                       location.href= '/weixin/pay/success';
                    }
                }
            });
        }, 1000*3)


    </script>
@endsection