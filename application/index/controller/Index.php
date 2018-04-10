<?php
namespace app\index\controller;

class Index
{
    public function index()
    {
	    return 'Hello Welcome to visit SwissTimeVip';
    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }
}
