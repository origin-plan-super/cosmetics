<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

//跨域
$origin = isset($_SERVER['HTTP_ORIGIN'])? $_SERVER['HTTP_ORIGIN'] : '';
$allow_origin=[
'http://192.168.1.251:8080',
'http://120.78.162.200:12139',
"http://cuelyine.cn",
'http://cosmetics.com',
];
$is=in_array($origin, $allow_origin);
if(in_array($origin, $allow_origin)){
    header('Access-Control-Allow-Origin:'.$origin);
    header('Access-Control-Allow-Credentials:true');
}else{
    header('Access-Control-Allow-Origin:*');
}

// 应用入口文件

// 检测PHP环境
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');

// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG',true);

// 定义应用目录
define('APP_PATH','./app/');

//自定义常量
// 全局key
define('__KEY__', 'c12138..');
//定义工作路径
define('WORKING_PATH', str_replace('\\', '/', __DIR__));
//定义根上传路径
define('UPLOAD_ROOT_PATH', '/Public/Upload/');
//定义上传的根目录——用户
define('__UPLOAD__USER__', '/Public/Upload/user/');
//定义上传的根目录——管理
define('__UPLOAD__ADMIN__', '/Public/Upload/admin/');
define('__UPLOAD__HOME__', '/Public/Upload/home/');

// 引入ThinkPHP入口文件
require './ThinkPHP/ThinkPHP.php';

// 亲^_^ 后面不需要任何代码了 就是如此简单