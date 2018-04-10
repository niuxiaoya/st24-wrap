<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]
namespace think;

// 加载基础文件
require __DIR__ . '/../thinkphp/base.php';

// 支持事先使用静态方法设置Request对象和Config对象
$lang_list =[
	'zh'    => 'zh-hk',
	'zh-cn' => 'zh-cn',
	'en'    => 'en-us',
	'en-us' => 'en-us',
	'fr'    => 'fr-fr',
	'fr-fr' => 'fr-fr',
	'de'    => 'de-de',
	'de-de' => 'de-de',
    'hk'    => 'zh-hk',
    'zh-hk' => 'zh-hk',
];
//preg_match('/^([a-z\d\-]+)/i', $_SERVER['HTTP_ACCEPT_LANGUAGE'], $matches);
//$langSet = isset($_GET['lang'])?$_GET['lang']:strtolower($matches[1]);
//
//if (isset($lang_list[$langSet])){
//	$_GET['lang'] = $lang_list[$langSet];
//}else{
//	$langSet = substr($langSet,0,2);
//	if (isset($lang_list[$langSet])){
//		$_GET['lang'] = $lang_list[$langSet];
//	}else{
//		$_GET['lang'] = 'en-us';//默认英语
//	}
//}
if (file_exists('./lang.txt')){
    $lang = file_get_contents('./lang.txt');
    if (!key_exists($lang,$lang_list)){
        $lang = 'en-us';
    }
}else{
    $lang = 'en-us';
}
$_GET['lang'] = $lang;
// 执行应用并响应
Container::get('app')->run()->send();
