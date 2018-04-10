<?php
/**
 * Created by PhpStorm.
 * User: FengYongQing
 * Date: 2018/3/16
 * Time: 20:22
 */

namespace app\v1\controller;


use think\Controller;
use think\Exception;

class Base extends Controller
{
	protected $system_lang;//系统语言
	protected $p ;
	protected $row;
	protected $offset;
	protected $accesstoken;
	protected $base_api_fix = 'dev';//版本控制
	protected $base_api = [];
	protected $authorization = '';
	protected $is_login = '';
	public function initialize()
	{
//        session('authorization',null);
        //请求的api地址
        $this->base_api = $api = config('api.'.$this->base_api_fix);
	    //判断登录状态
        if (session('authorization') == ""){
            $login['is_login'] = 0;
            $login_val = 0;
            $this->assign('userinfo',[]);
            $this->assign('userinfo_json',json_encode([]));
        }else{
            $login['is_login'] = 1;
            $login_val = 1;
            //用户侧边栏用户信息
            $headers = [
                'accept-lang' => $_SERVER['HTTP_ACCEPT_LANGUAGE'],
                'Authorization' => session('authorization'),
            ];
            $user_info = $this->http($this->base_api['url'].'/v1/user/info',"get",[],$headers);
            switch (httpData($user_info)->errcode){
                case 0:
                    $user_info_arr = json_decode($user_info,true);
                    $this->assign('userinfo_json',$user_info);
                    $this->assign('userinfo',$user_info_arr['data']);
                    break;
                case config('error.error_server')['code']:
                    //服务器错误
                    return $this->redirect('/error/404');
                    break;
                default :
                    session('authorization',null);
//                    $user_info_arr = json_decode($user_info,true);
//                    $this->assign('userinfo_json',$user_info);
//                    $this->assign('userinfo',$user_info_arr['data']);
                    break;
            }

        }
        $this->assign('is_login',json_encode($login));
        $this->assign('login_val',$login_val);
	}
	/**
	 * 发送HTTP请求方法
	 * @param  string $url    请求URL
	 * @param  string $method 请求方法GET/POST/PUT/DELETE
	 * @param  array  $data   上传数据
	 * @return array  $result 响应数据
	 */
/*
	private function http($url, $method="GET",array $data = array(), $header = array()){
        $curl = curl_init();
        foreach( $header as $n => $v ) {
            $headerArr[] = $n .':' . $v;
        }
        curl_setopt ($curl, CURLOPT_HTTPHEADER , $headerArr );  //构造IP
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        if ($method == "post"){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data['data']);
        }
        $data = curl_exec($curl);
        curl_close($curl);
        return $data;
    }*/
protected function http($url, $method = 'GET', array $data = array(), $header = array(), $isfile = FALSE, $cookie = [])
	{
        $time = time();
        $sign = encrypt('swisstime24h','E',$time.'3');
        $header['sign'] = $sign;
        $header['signtime'] = $time;
        foreach( $header as $n => $v ) {
            $headerArr[] = $n .':' . $v;
        }
		$opts = array(
			CURLOPT_URL            => $url,
			CURLOPT_CONNECTTIMEOUT => 5,
			CURLOPT_TIMEOUT        => 30,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_SSL_VERIFYHOST => false,
			CURLOPT_HTTPHEADER     => $headerArr
		);
		// cookie 设置
		if ( array_key_exists('cookie', $cookie) ) {
			$opts[CURLOPT_COOKIE] = $cookie['cookie'];
		}
		if ( array_key_exists('cookiejar', $cookie) ) {
			$opts[CURLOPT_COOKIEJAR] = $cookie['cookiejar'];      //cookie 保存到文件
		}
		if ( array_key_exists('cookiefile', $cookie) ) {
			$opts[CURLOPT_COOKIEFILE] = $cookie['cookiefile'];    //从文件读取 cookie
		}
		/* 根据请求类型设置特定参数 */
		switch(strtoupper($method)){
			case 'GET':
				break;
			case 'POST':
				$opts[CURLOPT_POST] = 1;
				if ( $isfile ) {
					$opts[CURLOPT_POSTFIELDS] = $data;
				} else {
					$opts[CURLOPT_POSTFIELDS] = http_build_query($data);//支持3维数组
				}
				break;
			case 'PUT':
				$opts[CURLOPT_CUSTOMREQUEST] = 'PUT';
				$opts[CURLOPT_POSTFIELDS] = http_build_query($data);
				break;
			case 'DELETE':
				$opts[CURLOPT_CUSTOMREQUEST] = 'DELETE';
				$opts[CURLOPT_POSTFIELDS] = http_build_query($data);
				break;
			default:
				return Exception('不支持的请求方式！');
		}
		/* 初始化并执行curl请求 */
		$ch = curl_init();
		curl_setopt_array($ch, $opts);
		$result  = curl_exec($ch);
		$error = curl_error($ch);
		//抓去请求错误返回状态码
		$code = curl_getinfo($ch,CURLINFO_HTTP_CODE);
		curl_close($ch);
		if ($code != '200'){
            //跳转404
            $result = ['errcode' => config('error.error_server')['code']]; //服务器错误
            return json_encode($result);
        }else{
		    //返回数据
            return $result;
        }
	}
}