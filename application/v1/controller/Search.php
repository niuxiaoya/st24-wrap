<?php
namespace app\v1\controller;

use think\Controller;
use think\facade\Request;
use think\Lang;

class Search extends Base
{
    public function index($action = 'index')
    {
        switch ($action){
            case 'index':
	            return $this->home();
                break;
            case 'lists':
                return $this->lists();
                break;
            case 'search':
                return $this->search();
                break;
            case 'dict':
                return $this->dict();
                break;
            case 'wattl':
                return $this->wattl();
                break;
            case 'search_list':
                return $this->search_list();
                break;
            case 'search_res':
                return $this->search_res();
                break;
            case 'search_res_data':
                return $this->search_res_data();
                break;
            case 'screen':
                return $this->screen();
                break;
            default :
                return $this->home();
                break;
        }
    }
    //搜索首页（品牌列表）
    private function home(){
        $headers = [
            'accept-lang' => $_SERVER['HTTP_ACCEPT_LANGUAGE']
        ];
        $brank_data = $this->http($this->base_api['url'].'/v1/dict/brands',"get",[],$headers);
        switch (httpData($brank_data)->errcode){
            case 0:
                $this->assign('brank_data',$brank_data);
                return $this->fetch('index');
                break;
            case config('error.error_server')['code']:
                //服务器错误
                return $this->fetch('error/404');
                break;
            default :
                $this->assign('brank_data',$brank_data);
                return $this->fetch('index');
                break;
        }
    }

    private function lists(){
        $headers = [
            'accept-lang' => $_SERVER['HTTP_ACCEPT_LANGUAGE']
        ];
        $list_data = $this->http($this->base_api['url'].'/v1/watch/list',"get",[],$headers);
        var_dump($list_data);exit;

        return $this->fetch('list');
    }
    private function search(){
        return $this->fetch();
    }
    private function screen(){
        return $this->fetch('screen');
    }
    private function search_list(){
        return $this->fetch('search_list');
    }

    //模糊搜索
    private function wattl (){
        $keywords = input('keywords','');
        $headers = [
            'accept-lang' => $_SERVER['HTTP_ACCEPT_LANGUAGE'],
            'Authorization' => session('authorization'),
        ];
        $keywords_data = $this->http($this->base_api['url']."/v1/watch/wattl?keywords=$keywords","get",[],$headers);
        switch (httpData($keywords_data)->errcode){
            case 0:
                echo $keywords_data;
                break;
            case config('error.error_server')['code']:
                //服务器错误
                echo json_encode(['errcode'=>config('error.error_server')['code']]);
                break;
            default :
                echo $keywords_data;
                break;
        }
    }

    //搜索頁面
    private function search_res(){
        return $this->fetch('search_res');
    }
    //搜索列表
    private function search_res_data(){
        $param = Request::param();
        //搜索参数路由
        if (!empty($param)){
            $param = http_build_query($param);
        }else{
            $param = "";
        }
        $headers = [
            'accept-lang' => $_SERVER['HTTP_ACCEPT_LANGUAGE']
        ];
        //获取搜索数据
        $list_data = $this->http($this->base_api['url']."/v1/watch/list?$param","get",[],$headers);
        switch (httpData($list_data)->errcode){
            case 0:
                echo $list_data;
                break;
            case config('error.error_server')['code']:
                //服务器错误
                echo json_encode(['errcode'=>config('error.error_server')['code']]);
                break;
            default :
                echo $list_data;
                break;
        }
    }

    //筛选列表字典类数据
    private function dict (){
        $param = $_GET;
        unset($param['lang']);
        $url = $param['params'];
        unset($param['params']);
        $param = http_build_query($param);
        $url = $url."?$param";
        $headers = [
            'accept-lang' => $_SERVER['HTTP_ACCEPT_LANGUAGE']
        ];
        $param = $this->http($this->base_api['url']."/v1/dict/$url","get",[],$headers);
        switch (httpData($param)->errcode){
            case 0:
                echo $param;
                break;
            case config('error.error_server')['code']:
                //服务器错误
                echo json_encode(['errcode'=>config('error.error_server')['code']]);
                break;
            default :
                echo $param;
                break;
        }
    }
}
