<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/adduser','User\UserController@add');

//路由跳转
Route::redirect('/hello1','/world1',301);
Route::get('/world1','Test\TestController@world1');

Route::get('/hello2','Test\TestController@hello2');
Route::get('/world2','Test\TestController@world2');


//路由参数
Route::get('/user/test','User\UserController@test');
//Route::get('/user/{uid}','User\UserController@user');
Route::get('/month/{m}/date/{d}','Test\TestController@md');
Route::get('/name/{str?}','Test\TestController@showName');



// View视图路由
Route::view('/mvc','mvc');
Route::view('/error','error',['code'=>40300]);

//Route::get('/',function(){
//    echo date('Y-m-d H:i:s');
//});

// Query Builder
Route::get('/query/get','Test\TestController@query1');
Route::get('/query/where','Test\TestController@query2');


//Route::match(['get','post'],'/test/abc','Test\TestController@abc');
//Route::any('/test/abc','Test\TestController@abc');

//用户注册
Route::get('/reg','User\UserController@reg');
Route::post('/reg','User\UserController@doReg');

//用户登录
Route::get('/login','User\UserController@login');
Route::post('/login','User\UserController@doLogin');
Route::get('/users/list','User\UserController@list');
//购物车
Route::get('/cart/list','Cart\IndexController@index');
Route::get('/users/add/{goods_id}','Cart\IndexController@add');
Route::get('/cart/del/{id}','Cart\IndexController@del');
Route::post('/cart/add2','Cart\IndexController@add2');

//商品
Route::get('/goods/index/{goods_id}','Goods\IndexController@index');
Route::get('/goods/list','Goods\IndexController@list');
//提交订单
Route::get('/order/add/{id}','Order\IndexController@add');
Route::get('/order/list','Order\IndexController@list');
Route::get('/order/detail/{order_sn}','Order\IndexController@detail');
Route::get('/order/del/{order_sn}','Order\IndexController@del');

Route::get('/order/order','Order\IndexController@test');
//支付
Route::get('/order/pay/{order_sn}','Order\IndexController@pay')->middleware('check.login');
Route::get('/pay/alipay/test/{order_sn}','Pay\AlipayController@test');         //测试
Route::post('/pay/alipay/notify','Pay\AlipayController@aliNotify');        //支付宝支付 通知回调
Route::get('/pay/alipay/alireturn','Pay\AlipayController@aliReturn');        //支付宝支付 同步通知回调
//退出
Route::get('/users/quit','User\UserController@quit');
Route::get('/aazz','User\UserController@aazz');
//Test
Route::get('/test/checkcookie','Test\TestController@checkCookie')->middleware('check.cookie');




Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::get('/goods/upload','Goods\IndexController@upload');
Route::post('/goods/upload/pdf','Goods\IndexController@uploadpdf');


//技能
Route::get('/text/login','Text\TextController@login');
Route::post('/text/login','Text\TextController@dologin');
Route::get('/text/list','Text\TextController@list');
Route::get('/text/pwd','Text\TextController@pwd');
Route::post('/text/dopwd','Text\TextController@dopwd');

//微信
Route::get('/weixin/refresh_token','Weixin\WeixinController@refreshToken');     //刷新token
Route::get('/weixin/test/token','Weixin\WeixinController@test');
Route::get('/weixin/valid','Weixin\WeixinController@validToken');
Route::get('/weixin/valid1','Weixin\WeixinController@validToken1');
Route::post('/weixin/valid1','Weixin\WeixinController@wxEvent');        //接收微信服务器事件推送
Route::post('/weixin/valid','Weixin\WeixinController@validToken');
Route::get('/weixin/grop','Weixin\WeixinController@textGroup');
Route::get('/weixin/create','Weixin\WeixinController@createMenu');//创建菜单

Route::get('/form/show','Weixin\WeixinController@formShow');     //表单测试
Route::post('/form/test','Weixin\WeixinController@formTest');     //表单测试

Route::get('/weixin/material/list','Weixin\WeixinController@materialList');     //获取永久素材列表
Route::get('/weixin/material/upload','Weixin\WeixinController@upMaterial');     //上传永久素材
Route::post('/weixin/material','Weixin\WeixinController@materialTest');     //创建菜单

Route::get('/content','Weixin\WeixinController@content');

//微信聊天
Route::get('/weixin/kefu/chat','Weixin\WeixinController@chatView');     //客服聊天
Route::get('/weixin/chat/get_msg','Weixin\WeixinController@getChatMsg');     //获取用户聊天信息

//微信支付
Route::get('/weixin/pay/test/{order_sn}','Weixin\PayController@test');     //微信支付测试
Route::get('/pay/success','Weixin\PayController@success');     //微信支付测试
Route::get('/weixin/pay/success','Weixin\PayController@successly');     //微信支付测试
Route::post('/weixin/pay/notice','Weixin\PayController@notice');     //微信支付通知回调



