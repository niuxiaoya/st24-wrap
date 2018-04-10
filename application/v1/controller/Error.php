<?php
namespace app\v1\controller;

use think\Controller;

class Error extends Controller
{
    public function index($action = 'index')
    {
        switch ($action){
            case 'index':
	            return $this->fetch('index');
                break;
            case '404':
                return $this->errors();
                break;
            default :
                return $this->fetch();
                break;
        }
    }
    private function errors(){
        return $this->fetch('404');
    }
}
