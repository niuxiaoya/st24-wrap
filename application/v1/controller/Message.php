<?php
namespace app\v1\controller;

use think\Controller;

class Message extends Base
{
    public function index($action = 'index')
    {
        switch ($action){
            case 'index':
	            return $this->fetch('index');
                break;
            default :
                return $this->fetch();
                break;
        }
    }
}
