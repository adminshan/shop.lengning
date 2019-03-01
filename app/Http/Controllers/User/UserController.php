<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Model\UserModel;

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
				echo 'Registered successfully';
				header('refresh:1;/userlogin');
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
		$pwd=$request->input('pwd');
		$data=[
			'name'=>$name
		];
		$info=UserModel::where($data)->first();
		$pwd2=password_verify($pwd,$info->pwd);
		if(empty($info)){
			echo 'Login failed';
		}else if($pwd2===false){
			echo 'Wrong account or password';
		}else {
			$token = substr(md5(time().mt_rand(1,99999)),10,10);
			setcookie('uid',$info->uid,time()+86400,'/','',false,true);
			setcookie('token',$token,time()+86400,'/','',false,true);

			$request->session()->put('u_token',$token);
			$request->session()->put('uid',$info->uid);
			echo 'Login successful';
			header('refresh:0.2;/goods/list');
		}

		}
	public function goodslist(Request $request){
		if(empty(setcookie('token'))){
			header('refresh:1;/login');
			echo 'Please log in';
		}else if(setcookie('token') != $request->session()->get('u_token')){
			header('refresh:1;/login');
			echo 'Please log in';
		}else{
			$where=[
				'uid'=>$_COOKIE['uid']
			];
			$info=UserModel::where($where)->first();
			$list=UserModel::all();
			$data=[
				'list' => $list,
				'name' => $info->name
			];
			return view('users.list',$data);
		}

	}
	public function quit(){
		setcookie('uid','',time()-1);
		header("refresh:0;url=/login");

	}

}
