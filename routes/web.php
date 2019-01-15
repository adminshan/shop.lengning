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
Route::get('/cart/list','Cart\IndexController@index')->middleware('check.login');
Route::get('/users/add/{goods_id}','Cart\IndexController@add')->middleware('check.login');
Route::get('/cart/del/{id}','Cart\IndexController@del')->middleware('check.login');
Route::post('/cart/add2','Cart\IndexController@add2')->middleware('check.login');

//商品
Route::get('/goods/index/{goods_id}','Goods\IndexController@index')->middleware('check.login');
Route::get('/goods/list','Goods\IndexController@list')->middleware('check.login');
//提交订单
Route::get('/order/add/{id}','Order\IndexController@add')->middleware('check.login');
Route::get('/order/list','Order\IndexController@list')->middleware('check.login');
Route::get('/order/detail/{order_sn}','Order\IndexController@detail')->middleware('check.login');
Route::get('/order/del/{order_sn}','Order\IndexController@del')->middleware('check.login');

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













