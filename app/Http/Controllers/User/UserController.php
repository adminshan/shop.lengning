<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Model\UserModel;
use Illuminate\Support\Facades\Redis;

class UserController extends Controller
{
    //
	public function bcd(){
		echo '<pre>';dump($_POST);dump($_GET);echo '</pre>';
	}
	public function user($uid)
	{
		echo $uid;
	}

	public function test()
    {
        echo '<pre>';print_r($_GET);echo '</pre>';
    }

	public function add()
	{
		$data = [
			'name'      => str_random(5),
			'age'       => mt_rand(20,99),
			'email'     => str_random(6) . '@gmail.com',
			'reg_time'  => time()
		];

		$id = UserModel::insertGetId($data);
		var_dump($id);
	}
	public function showlist(){
		$list = UserModel::all()->toArray();
		$data = [
				'title'     => 'abc',
				'list'      => $list
		];

		return view('test.child',$data);
	}
	public function reg(){
		return view('users.reg');
	}
	public function doReg(Request $request){
		$name=$request->input('name');
		$pwd=password_hash($request->input('pwd'),PASSWORD_BCRYPT);
		$pwd2=password_verify($request->input('pwd2'),$pwd);
		$where=[
				'name'=>$name
		];
		$info=UserModel::where($where)->get()->toArray();
		if(!empty($info)){
			die('User registered');

		}else if($pwd2===false){
			die('Confirm that the password must match the password');
		}else{
			$data=[
				'name' =>$name,
				'pwd' =>$pwd,
				'age' =>$request->input('age'),
				'email' =>$request->input('email'),
				'score'=>0
			];
			$uid=UserModel::insertGetId($data);
			if($uid){
				setcookie('uid',$uid,time()+86400,'/','',false,true);
				echo '1';
				//header('refresh:1;/userlogin');
			}else{
				echo 'Registration failed';
				header('refresh:0.2;/reg');
			}
		}

	}
	public function login(){
		return view('users.login');
	}
	public function doLogin(Request $request){
		$name =$request->input('name');
		//echo $name;
		$pwd=$request->input('pwd');
		//echo $pwd;die;
		$data=[
			'name'=>$name
		];
		$info=UserModel::where($data)->first();
		$pwd2=password_verify($pwd,$info->pwd);
		if(empty($info)){
			echo '2';
		}else if($pwd2===false){
			echo '3';
		}else {
			$token = substr(md5(time().mt_rand(1,99999)),10,10);
			setcookie('uid',$info->uid,time()+86400,'/','',false,true);
			setcookie('token',$token,time()+86400,'/','',false,true);

			$request->session()->put('u_token',$token);
			$request->session()->put('uid',$info->uid);
			echo '1';
//			echo 'Login successful';
//			header('refresh:0.2;/goods/list');
		}

		}
	public function center(){
		echo 1111;
	}
	public function start(Request $request){
		//print_r($_SERVER);die;
		$url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		$data=[
			'login'=>$request->get('is_login'),
			'recurl'=>urlencode($url)
		];
		return view('users.start',$data);

	}
	public function api(Request $request){
		$name=$request->input('name');
		$pwd=$request->input('pwd');
		$url="http://port.tactshan.com/apilogin";
		//向服务器传送数据
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, ['name' => $name, 'pwd' => $pwd]);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		$rs = curl_exec($ch);
		$response = json_decode($rs, true);
		var_dump($response);
		die;
		if(!empty($response['uid'])){
			$redis_token=$response['redis_token'].$response['uid'];
			$token=Redis::get($redis_token);
			$arr=[
					'error'=>0,
					'msg'=>$response['msg'],
					'token'=>$token,
					'name'=>$response['name'],
					'age'=>$response['age'],
					'email'=>$response['email'],
					'redis'=>$response['redis_token'],
			];
			if($response['token']==$token){
				return json_encode($arr);
			}else{
				$arr=[
						'msg'=>'登录失败1'
				];
				return json_encode($arr);
			}
		}else{
			$arr=[
					'msg'=>$response['msg']
			];
			return json_encode($arr);
		}
	}
	public function quit(){
		setcookie('uid',$_COOKIE['uid'],time()-1,'/','tactshan.com',false,true);
		setcookie('token',$_COOKIE['token'],time()-1,'/','tactshan.com',false,true);
		header('refresh:0.2;/start');
	}
	public function userquit(Request $request){
		$uid=$request->input('uid');
		$redis=$request->input('redis');
		$redis_token=$redis.$uid;
		Redis::del($redis_token);
		$token=Redis::hget($redis_token);
		if(empty($token)){
			$arr=[
				'error'=>0,
				'msg'=>'退出成功'
			];
			return json_encode($arr);
		}
	}
}
