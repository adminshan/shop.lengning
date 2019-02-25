<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');
    $router->resource('/goods',GoodsController::class);
    $router->resource('/users',UsersController::class);
    $router->resource('/weixin',WeixinController::class);
    $router->resource('/group',WeixinMediaController::class);
    //永久素材
    $router->resource('/fover/weixin',WeixinfoverController::class);
    $router->post('/fover/weixin','WeixinfoverController@formTest');
    //群发
    $router->get('/send','WeixinsendController@index');
    $router->post('/send','WeixinsendController@textGroup');
    //私聊
    $router->get('/content','WeixinController@chatView');
    $router->get('/content/getmsg','WeixinController@getChatMsg');
    $router->post('/content','WeixinController@doChat');


});
