<?php
namespace app\v1\controller;

use think\Controller;
use think\facade\Lang;
use think\facade\Request;

class Like extends Base
{
    public function index($action = 'index')
    {
        switch ($action){
            case 'index':
	            return $this->home();
                break;
            case 'del':
                return $this->delLike();
                break;
            case 'detail':
                return $this->detail();
                break;
             case 'detail_api':
                 return $this->detailApi();
                 break;
            case 'order':
                return $this->order();  //立即购买
                break;
            case 'orderInfo':
                return $this->orderInfo();  //支付数据
                break;
            case 'orderCreate':  //生成订单
                return $this->orderCreate();
                break;
            case 'address_info':  //获取地址详情
                return $this->addressInfo();
                break;
            case 'order_del':  //删除订单
                return $this->orderDel();
                break;
            case 'add':
                return $this->add();
                break;
            case 'address_list':
                return $this->address_list();
                break;
            case 'lang':
                return $this->lang();
                break;
            case 'pay':
                return $this->pay();  //支付
                break;
            case 'pay_state':
                return $this->pay_state();  //支付结果
                break;
            default :
                return $this->home();
                break;
        }
    }

    //心愿单列表
    private function home() {
        $method = input('method','0');
        $headers = [
            'accept-lang' => $_SERVER['HTTP_ACCEPT_LANGUAGE'],
            'Authorization' => session('authorization'),
        ];
        switch ($method){
            case 0;
                return $this->fetch('index');
                break;
            case 1://列表
                $p = input('p',1);
                $param['p'] = $p;
                $param = http_build_query($param);
                //获取心愿列表
                $like_data = $this->http($this->base_api['url']."/v1/user/collection?$param","get",[],$headers);
                switch (httpData($like_data)->errcode){
                    case 0:
                        echo $like_data;
                        break;
                    case config('error.error_server')['code']:
                        //服务器错误
                        echo json_encode(['errcode'=>config('error.error_server')['code']]);
                        break;
                    default :
                        echo $like_data;
                        break;
                }
                break;
            case 2://添加
                $param['data_id'] = input('gid',null);
                $like_add = $this->http($this->base_api['url']."/v1/user/collection","post",$param,$headers);
                switch (httpData($like_add)->errcode){
                    case 0:
                        //添加成功
                        echo json_encode(['errcode'=>0,'msg'=>Lang::get('add_success')]);
                        break;
                    case config('error.error_server')['code']:
                        //服务器错误
                        echo json_encode(['errcode'=>config('error.error_server')['code']]);
                        break;
                    default :
                        //添加失败
                        echo json_encode(['errcode'=>1,'msg'=>Lang::get('add_error')]);
                        break;
                }
                break;
            case 3://删除
                $param['data_id'] = input('gid',null);
                $like_del = $this->http($this->base_api['url']."/v1/user/collection","delete",$param,$headers);
                switch (httpData($like_del)->errcode){
                    case 0:
                        //删除成功
                        echo json_encode(['errcode'=>0,'msg'=>Lang::get('delete_success')]);
                        break;
                    case config('error.error_server')['code']:
                        //服务器错误
                        echo json_encode(['errcode'=>config('error.error_server')['code']]);
                        break;
                    default :
                        //删除失败
                        echo json_encode(['errcode'=>1,'msg'=>Lang::get('delete_error')]);
                        break;
                }
                break;
            default :
                return $this->fetch('index');
                break;
        }

    }

    //取消心愿单
    private function delLike() {
        $this->redirect('Like/index');
    }

    //商品详情
    private function detail(){
        //商品id
        $gid = input('gid','');
        $keywords = input('keywords','');
        $headers = [
            'accept-lang' => $_SERVER['HTTP_ACCEPT_LANGUAGE']
        ];
        $param = [
            'gid'       => $gid,
            'keywords'  => $keywords
        ];
        $param = http_build_query($param);
        $brank_data = $this->http($this->base_api['url']."/v1/watch/info?$param","get",[],$headers);
        switch (httpData($brank_data)->errcode){
            case 0:
                $brank_data_arr = json_decode($brank_data,true);
                $this->assign('brank_data_arr',$brank_data_arr);
                return $this->fetch('detail');
                break;
            case config('error.error_server')['code']:
                //服务器错误
                return $this->fetch('error/404');
                break;
            default :
                //服务器错误
                return $this->fetch('error/404');
//                $brank_data_arr = json_decode($brank_data,true);
//                $this->assign('brank_data_arr',$brank_data_arr);
//                return $this->fetch('detail');
                break;
        }
    }

    //支付信息
    private function orderInfo (){
        $headers = [
            'accept-lang' => $_SERVER['HTTP_ACCEPT_LANGUAGE'],
            'Authorization' => session('authorization'),
        ];
        //订单号
        $bill_sn = input('bill_sn','null');
        $order_info = $this->http($this->base_api['url']."/v1/order/getsurplusprice?bill_sn=$bill_sn","get",[],$headers);
        switch (httpData($order_info)->errcode){
            case 0:
                echo $order_info;
                break;
            case config('error.error_server')['code']:
                //服务器错误
                echo json_encode(['errcode'=>config('error.error_server')['code']]);
                break;
            default :
                //获取数据失败
                echo json_encode(['errcode'=>1]);
                break;
        }
    }

    //接口商品详情
    private function detailApi (){
        //商品id
        $gid = input('gid','');
        $keywords = input('keywords','');
        $headers = [
            'accept-lang' => $_SERVER['HTTP_ACCEPT_LANGUAGE']
        ];
        $param = [
            'gid'       => $gid,
            'keywords'  => $keywords
        ];
        $param = http_build_query($param);
        $brank_data = $this->http($this->base_api['url']."/v1/watch/info?$param","get",[],$headers);

        switch (httpData($brank_data)->errcode){
            case 0:
                echo $brank_data;
                break;
            case config('error.error_server')['code']:
                //服务器错误
                echo json_encode(['errcode'=>config('error.error_server')['code']]);
                break;
            default :
                echo json_encode(['errcode'=>1,'msg'=>Lang::get('data_error')]);
                break;
        }
    }

    //立即购买页面
    private function order(){
        $headers = [
            'accept-lang' => $_SERVER['HTTP_ACCEPT_LANGUAGE'],
            'Authorization' => session('authorization'),
        ];
        $gid = input('gid','');
        $paceorder = $this -> http($this->base_api['url']."/v1/order/placeorder?gid=$gid", 'get',[] , $headers);
        switch (httpData($paceorder)->errcode){
            case 0:
                $paceorder = json_decode($paceorder,true);
                $this->assign('paceorder',$paceorder);
                return $this->fetch('order');
                break;
            case config('error.error_server')['code']:
                //服务器错误
                return $this->fetch('error/404');
                break;
            default :
                $paceorder = json_decode($paceorder,true);
                $this->assign('paceorder',$paceorder);
                return $this->fetch('order');
                break;
        }
    }


    //添加收货地址
    private function add(){
        //模版or接口数据
        $method = input('method','0');
        $headers = [
            'accept-lang' => $_SERVER['HTTP_ACCEPT_LANGUAGE'],
            'Authorization' => session('authorization'),
        ];
        $data = Request::param();
        switch ($method){
            case 0:
                //返回国家信息
                $headers = [
                    'accept-lang' => $_SERVER['HTTP_ACCEPT_LANGUAGE']
                ];
                //获取国家信息
                $country_data = $this->http($this->base_api['url'].'/v1/dict/country',"get",[],$headers);
                switch (httpData($country_data)->errcode){
                    case 0:
                        $this->assign('country_data',$country_data);
                        return $this->fetch('add');
                        break;
                    case config('error.error_server')['code']:
                        //服务器错误
                        return $this->fetch('error/404');
                        break;
                    default :
                        $this->assign('country_data',$country_data);
                        return $this->fetch('add');
                        break;
                }
                break;
            case 1:
                $address_list = $this->http($this->base_api['url']."/v1/user/address",'post',$data,$headers);
                switch (httpData($address_list)->errcode){
                    case 0:
                        //添加成功
                        echo json_encode(['errcode'=>'0','msg'=>Lang::get('add_success')]);
                        break;
                    case config('error.error_server')['code']:
                        //服务器错误
                        echo json_encode(['errcode'=>config('error.error_server')['code']]);
                        break;
                    default :
                        //添加失败
                        echo json_encode(['errcode'=>1,'msg'=>Lang::get('add_error')]);
                        break;
                }
                break;
            case 2:
                $address_list = $this->http($this->base_api['url']."/v1/user/address",'put',$data,$headers);
                switch (httpData($address_list)->errcode){
                    case 0:
                        //修改成功
                        echo json_encode(['errcode'=>'0','msg'=>Lang::get('save_success')]);
                        break;
                    case config('error.error_server')['code']:
                        //服务器错误
                        echo json_encode(['errcode'=>config('error.error_server')['code']]);
                        break;
                    default :
                        //修改失败
                        echo json_encode(['errcode'=>1,'msg'=>Lang::get('save_error')]);
                        break;
                }
                break;
            case 3:
                $address_list = $this->http($this->base_api['url']."/v1/user/address",'delete',$data,$headers);
                switch (httpData($address_list)->errcode){
                    case 0:
                        //删除成功
                        echo json_encode(['errcode'=>0,'msg'=>Lang::get('delete_success')]);
                        break;
                    case config('error.error_server')['code']:
                        //服务器错误
                        echo json_encode(['errcode'=>config('error.error_server')['code']]);
                        break;
                    default :
                        //删除失败
                        echo json_encode(['errcode'=>1,'msg'=>Lang::get('delete_error')]);
                        break;
                }
                break;
            default :
                return $this->fetch('add');
                break;
        }
    }


    //获取详细地址
    private function addressInfo (){
        $headers = [
            'accept-lang' => $_SERVER['HTTP_ACCEPT_LANGUAGE'],
            'Authorization' => session('authorization'),
        ];
        $id = input('id','9');
        $addressinfo = $this->http($this->base_api['url']."/v1/user/addressinfo?id=$id", 'get',[] , $headers);
        switch (httpData($addressinfo)->errcode){
            case 0:
                echo  $addressinfo;
                break;
            case config('error.error_server')['code']:
                //服务器错误
                echo json_encode(['errcode'=>config('error.error_server')['code']]);
                break;
            default :
                echo  $addressinfo;
                break;
        }
    }


    //生成订单
    private function orderCreate(){
        $headers = [
            'accept-lang' => $_SERVER['HTTP_ACCEPT_LANGUAGE'],
            'Authorization' => session('authorization'),
        ];
        $data = Request::param();
        $order_create = $this->http($this->base_api['url']."/v1/order/watch", 'post',$data , $headers);
        switch (httpData($order_create)->errcode){
            case 0:
                //生成成功
                echo $order_create;
                break;
            case config('error.error_server')['code']:
                //服务器错误
                echo json_encode(['errcode'=>config('error.error_server')['code']]);
                break;
            default :
                //生成失敗
                echo json_encode(['errcode'=>1,'msg'=>\think\facade\Lang::get('create_success')]);
                break;
        }
    }

    //取消订单
    private function orderDel (){
        $headers = [
            'accept-lang' => $_SERVER['HTTP_ACCEPT_LANGUAGE'],
            'Authorization' => session('authorization'),
        ];
        $data['bill_sn'] = input('bill_sn','');
        $order_del = $this->http($this->base_api['url']."/v1/order/watch", 'delete',$data , $headers);
        switch (httpData($order_del)->errcode){
            case 0:
                //删除成功
                echo json_encode(['errcode'=>0]);
                break;
            case config('error.error_server')['code']:
                //服务器错误
                echo json_encode(['errcode'=>config('error.error_server')['code']]);
                break;
            default :
                //删除失败
                echo json_encode(['errcode'=>1,'msg'=>\think\facade\Lang::get('delete_error')]);
                break;
        }
    }

    //支付
    private function pay(){
        if (strtolower(Request::method()) == 'get'){
            return $this->fetch('pay');
        }else{
            $headers = [
                'accept-lang' => $_SERVER['HTTP_ACCEPT_LANGUAGE'],
                'Authorization' => session('authorization'),
            ];
            $data = Request::param();
            $pay = $this->http($this->base_api['url']."/v1/payment/pay", 'post',$data , $headers);
            switch (httpData($pay)->errcode){
                case 0:
                    //支付成功
                    echo $pay;
                    break;
                case config('error.error_server')['code']:
                    //服务器错误
                    echo json_encode(['errcode'=>config('error.error_server')['code']]);
                    break;
                default :
                    //支付失败
                    echo json_encode(['errcode'=>1,'msg'=>\think\facade\Lang::get('pay_error')]);
                    break;
            }
        }
    }

    //支付结果
    private function pay_state() {
        return $this->fetch('pay_state');
    }

    //收货地址列表
    private function address_list(){
        $method = input('method',0);
        if ($method == 0){
            return $this->fetch('address_list');
        }else{
            $headers = [
                'accept-lang' => $_SERVER['HTTP_ACCEPT_LANGUAGE'],
                'Authorization' => session('authorization'),
            ];
            $address_list = $this->http($this->base_api['url']."/v1/user/address",'get',[],$headers);
            switch (httpData($address_list)->errcode){
                case 0:
                    echo $address_list;
                    break;
                case config('error.error_server')['code']:
                    //服务器错误
                    echo json_encode(['errcode'=>config('error.error_server')['code']]);
                    break;
                default :
                    //登录失败
                    echo json_encode(['errcode'=>1]);
                    break;
            }
        }
    }
}
