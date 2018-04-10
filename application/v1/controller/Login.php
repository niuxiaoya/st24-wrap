<?php
namespace app\v1\controller;

use think\Controller;
use think\facade\Lang;
use think\Request;
header("Content-type: text/html; charset=utf-8");
class Login extends Base
{
    public function index($action = 'index')
    {
        switch ($action){
            case 'index':
	            return $this->home();
                break;
            case 'send':
                return $this->send();
                break;
            case 'quit';
                return $this->quit();
                break;
            default :
                return $this->home();
                break;
        }
    }
    //登录页面
    private function home() {
        if (strtolower(\think\facade\Request::method()) == 'get' ){
            //返回国家信息
            $headers = [
                'accept-lang' => $_SERVER['HTTP_ACCEPT_LANGUAGE']
            ];
            //获取国家信息
            $country_data = $this->http($this->base_api['url'].'/v1/dict/country',"get",[],$headers);
            switch (httpData($country_data)->errcode){
                case 0:
                    $this->assign('country_data',$country_data);
                    return $this->fetch('index');
                    break;
                case config('error.error_server')['code']:
                    //服务器错误
                    return $this->fetch('error/404');
                    break;
                default :
                    //删除失败
                    $this->assign('country_data',$country_data);
                    return $this->fetch('index');
                    break;
            }
        } else {
            //登录数据
            $login_data = \think\facade\Request::param();
            unset($login_data['lang']);
            unset($login_data['action']);
            $headers = [
                'accept-lang' => $_SERVER['HTTP_ACCEPT_LANGUAGE']
            ];
            //登录
            $res = $this->http($this->base_api['url'].'/v1/login',"post",$login_data,$headers);
            switch (httpData($res)->errcode){
                case 0:
                    $authorization = json_decode($res,true);
                    //登录成功
                    session('authorization',$authorization['Authorization']);
                    echo json_encode(['errcode'=>0,'url'=>'home/index']);
                    break;
                case config('error.error_server')['code']:
                    //服务器错误
                    echo json_encode(['errcode'=>config('error.error_server')['code']]);
                    break;
                default :
                    //登录失败
                    echo json_encode(['errcode'=>1,'msg'=>Lang::get('error_login')]);
                    break;
            }
        }
    }

    //发送手机号验证码
    private function send(){
        $param['tel']        =  input('tel','');
        $param['country_id'] =  input('country_id','');
        $headers = [
            'accept-lang' => $_SERVER['HTTP_ACCEPT_LANGUAGE']
        ];
        $send = $this->http($this->base_api['url'].'/v1/sms',"post",$param,$headers);
        switch (httpData($send)->errcode){
            case 0:
                echo json_encode(['errcode'=>0]);
                break;
            default :
                //登录失败
                echo json_encode(['errcode'=>1]);
                break;
        }
    }



    //退出登录
    private function quit (){
        $quit = session('authorization',null);
        if ($quit == ""){
            //退出成功
            echo json_encode(['errcode'=>0]);
        }else{
            //退出失败
            echo json_encode(['errcode'=>1]);
        }
    }
}