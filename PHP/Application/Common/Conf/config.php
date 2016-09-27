<?php
return array(
//'配置项'=>'配置值'
'DEFAULT_MODULE'     => 'Index', //默认模块
	
//数据库配置信息
'DB_TYPE'   => 'mysql', // 数据库类型
'DB_HOST'   => 'localhost', // 服务器地址
'DB_NAME'   => '', // 数据库名
'DB_USER'   => '', // 用户名
'DB_PWD'    => '', // 密码
'DB_PORT'   => 3306, // 端口
'DB_PREFIX' => 'af_', // 数据库表前缀 
'DB_CHARSET'=> 'utf8', // 字符集
//'DB_DEBUG'  =>  TRUE, // 数据库调试模式 开启后可以记录SQL日志 3.2.3新增

//阿里大于配置信息
'AlidayuAppKey'    => '',  // app key
'AlidayuAppSecret' => '',  // app secret
'AlidayuApiEnv'    => 1, // api请求地址，1为正式环境，0为沙箱环境
);