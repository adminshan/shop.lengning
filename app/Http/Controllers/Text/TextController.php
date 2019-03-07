<?php

namespace App\Http\Controllers\Text;

use Illuminate\Http\Request;
use App\Model\TextModel;
use App\Http\Controllers\Controller;

class TextController extends Controller
{
    public function login(){
        return view('text.login');
    }
    public function doLogin(Request $request){
        $name =$request->input('name');
        $pwd =$request->input('pwd');
        $data=[
            'name'=>$name
        ];
        $info=TextModel::where($data)->first();
        if(empty($info)){
            echo '用户名或密码不正确';
        } else if($pwd !== $info->pwd){
            echo '用户名或密码不正确';
        }else{
            $token = substr(md5(time().mt_rand(1,99999)),10,10);
            setcookie('uid',$info->uid,time()+86400,'/','',false,true);
            setcookie('token',$token,time()+86400,'/','',false,true);

            $request->session()->put('u_token',$token);
            $request->session()->put('uid',$info->uid);
            echo '登陆成功';
            header('refresh:0.2;/text/list');
        }
    }
    public function list(){
        $where=[
            'uid'=>session('uid')
        ];
        $info=TextModel::where($where)->first();
        $data=[
            'name'=>$info->name
        ];
        return view('text.list',$data);
    }
    public function pwd(){
        return view('text.pwd');
    }
    public function dopwd(Request $request){
        $pwd=$request->input('pwd1');
        $pd2=$request->input('pwd2');
        $pwd3=$request->input('pwd3');
        $where=[
            'uid'=>session('uid')
        ];
        $info=TextModel::where($where)->first();
        if($pwd !== $info->pwd){
            echo "原密码不正确";
            header('refresh:2;/text/pwd');
        }else if($pd2 !== $pwd3){
            echo "密码与确认密码不一致";
            header('refresh:2;/text/pwd');
        }else{
            $res=TextModel::where($where)->update(['pwd'=>$pd2]);
            if($res){
                echo "修改成功";
                header('refresh:2;/text/login');
            }
        }
    }
}
