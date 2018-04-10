<?php
/**
 * Created by PhpStorm.
 * User: FengYongQing
 * Date: 2018/3/16
 * Time: 20:40
 */
return [
	'invalid_client'     => ['code' => '40001', 'msg' => '无效的客户端'],
	'invalid_captcha'    => ['code' => '40002', 'msg' => '无效的验证码'],
	'need_login'         => ['code' => '40003', 'msg' => '需要登录'],
	'expire_login'       => ['code' => '40004', 'msg' => '登录已过期'],
	'error_login'        => ['code' => '40005', 'msg' => '登录失败'],
	'error_register'     => ['code' => '40006', 'msg' => '注册失败'],
	'need_identify'      => ['code' => '40020', 'msg' => '需要实名认证'],
	'error_noauth'       => ['code' => '40021', 'msg' => '对不起，您没有权限进行此操作'],
	'login_elsewhere'    => ['code' => '40023', 'msg' => '账号在其他地方登陆'],
	'error_account'      => ['code' => '40024', 'msg' => '该账号不存在'],
	'error_password'     => ['code' => '40025', 'msg' => '密码错误'],
	'exist_account'      => ['code' => '40026', 'msg' => '帐号已存在'],

	'error_nologin'      => ['code'=>'40100','msg'=>'请登录后操作'],
	'error_param'        => ['code'=>'40102','msg'=>'参数错误'],
	'error_nodata'       => ['code'=>'40103','msg'=>'暂无数据'],
	'error_add'          => ['code'=>'40104','msg'=>'添加失败，请刷新后重试'],
	'error_edit'         => ['code'=>'40105','msg'=>'修改失败，请刷新后重试'],
	'error_delete'       => ['code'=>'40106','msg'=>'删除失败，请刷新后重试'],
	'error_method'       => ['code'=>'40107','msg'=>'不支持的请求类型'],
	'error_action'       => ['code'=>'40108','msg'=>'暂不支持该操作'],
	'error_file'         => ['code'=>'40109','msg'=>'文件不存在'],
	'error_upload'       => ['code'=>'40110','msg'=>'上传失败'],
	'error_username'     => ['code'=>'40111','msg'=>'已存在该用户'],
	'error_download'     => ['code'=>'40112','msg'=>'下载失败'],
	'error_unbundling'   => ['code'=>'40113','msg'=>'该银行卡存在商品，不能解绑'],
	'error_type'         => ['code'=>'40114','msg'=>'图片格式错误'],



    'error_server'       => ['code'=>'500500','msg'=>'服务器错误'],
];