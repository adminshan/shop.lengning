var openid = $("#openid").val();

setInterval(function(){
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url     :   '/admin/content/getmsg?openid=' + openid + '&pos=' + $("#msg_pos").val(),
        type    :   'get',
        dataType:   'json',
        success :   function(d){
            if(d.errno==0){     //服务器响应正常
                //数据填充
                var msg_str = '<blockquote>' + d.data.openid +':'+ d.data.content+ '</blockquote>';

                $("#chat_div").append(msg_str);
                $("#msg_pos").val(d.data.id)
            }else{

            }
        }
    });
},5000);

// 客服发送消息 begin
$("#send_msg_btn").click(function(e){
    e.preventDefault();
    var send_msg = $("#send_msg").val().trim();
    var msg_str = '<p style="color: mediumorchid"> 客服：'+send_msg+'</p>';
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url     :   'content',
        type    :   'post',
        data    :   {msg:send_msg,openid:openid},
        success :   function(res){
            /* if(res.error==301){
             window.location.href=res.url;
             }else{
             alert(res.msg);
             window.location.href="/cartlist";
             }*/
        }
    });

    $("#chat_div").append(msg_str);
    $("#send_msg").val("");
});
// 客服发送消息 end