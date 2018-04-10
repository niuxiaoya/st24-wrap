<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

Route::get('think', function () {
    return 'HELLO,SWISSTIMEVIP-24!';
});

#Route::get('hello/:name', 'index/hello');
Route::rule('home/[:action]','v1/index/index','GET|POST|PUT');
Route::rule('search/[:action]','v1/search/index','GET|POST');
Route::rule('login/[:action]','v1/login/index','GET|POST');
Route::rule('like/[:action]','v1/like/index','GET|POST|PUT|DELETE');
Route::rule('message/[:action]','v1/message/index','GET|POST');
Route::rule('error/[:action]','v1/error/index','GET|POST');

return [

];
