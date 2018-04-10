<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
// 应用公共文件
/**
 * api接口错误
 * @param string $errname
 * @param string $errmsg
 * @return bool
 */
if (!function_exists('apiError')) {
	function apiError($errname='')
	{
		if (empty($errname)) {
			return false;
		}
		$errname_config = 'error.'.$errname;
		$config = config($errname_config);
		if (!empty($config)){
			$return = ['errmsg'=>\think\facade\Lang::get($config['msg']),'errcode'=>(string)$config['code']];
		}else{
			$return = ['errmsg'=>\think\facade\Lang::get('error_system'),'errcode'=>'50001'];
		}
		return $return;
	}

}


if (!function_exists('encrypt')) {
    function encrypt($string,$operation="e",$key = '')
    {
        $src = array("/","+","=");
        $dist = array("_a","_b","_c");
        if ($operation == 'D') {
            $string = str_replace($dist,$src,$string);
        }
        $key = md5($key);
        $key_length = strlen($key);
        $string = $operation == 'D' ? base64_decode($string) : substr(md5($string.$key),0,8).$string;
        $string_length = strlen($string);
        $rndkey = $box = array();
        $result = '';
        for ($i = 0 ;$i <= 255 ;$i ++) {
            $rndkey[$i] = ord($key[$i % $key_length]);
            $box[$i] = $i;
        }
        for ($j = $i = 0 ;$i < 256 ;$i ++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }
        for ($a = $j = $i = 0 ;$i < $string_length ;$i ++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }
        if ($operation == 'D') {
            if (substr($result,0,8) == substr(md5(substr($result,8).$key),0,8)) {
                return substr($result,8);
            } else {
                return'';
            }
        } else {
            $rdate = str_replace('=','',base64_encode($result));
            $rdate = str_replace($src,$dist,$rdate);
            return $rdate;
        }
    }
}


if (!function_exists('httpData')) {
     function httpData($http_data){
        return json_decode($http_data);
    }

}

