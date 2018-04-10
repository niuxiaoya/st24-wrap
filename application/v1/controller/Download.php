<?php
namespace app\v1\controller;

use think\Controller;

class Download extends Base
{
    public function index($action = 'index')
    {
        switch ($action){
            case 'index':
	            return $this->fetch('index');
                break;
            case 'app':
                return $this->app();
                break;
            default :
                return $this->fetch();
                break;
        }
    }
    private function app(){
        return $this->fetch('app');
    }
}
