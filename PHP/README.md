SmartMagnetometer 1.0
===============
##简介
本服务端基于ThinkPHP3.2.3开发而成，集成了微信公众号类(麦当苗儿版本)和阿里大于短信发送API，能实现警报信息短信推送和使用微信公众平台对主机状态查询、房间状态查询、开/关报警等的相关操作。

##服务端环境要求
* PHP版本 > 5.3
* 带MYSQL数据库

##配置
> 需要修改以下文件才能正常使用：

1、 [\Application\Common\Conf\config.php](Application/Common/Conf/config.php)
```PHP
//数据库配置信息
'DB_TYPE'   => 'mysql', // 数据库类型
'DB_HOST'   => 'localhost', // 服务器地址
'DB_NAME'   => '', // 数据库名
'DB_USER'   => '', // 用户名
'DB_PWD'    => '', // 密码
'DB_PORT'   => 3306, // 端口
//阿里大于配置信息
'AlidayuAppKey'    => '',  // app key
'AlidayuAppSecret' => '',  // app secret
'AlidayuApiEnv'    => 1, // api请求地址，1为正式环境，0为沙箱环境
```

2、[\Application\Home\Controller\IndexController.class.php](Application/Home/Controller/IndexController.class.php)

第29、30、31行  微信公众号配置：
```PHP
$appid = ''; //AppID(应用ID)
$token = ''; //微信后台填写的TOKEN
$crypt = ''; //消息加密KEY（EncodingAESKey）
```

第106行  阿里大于短信模板ID：
```PHP
$req = $request->setSmsTemplateCode('模板ID')  //填入阿里大于短信模板ID
```

第109行  阿里大于短信签名：
```PHP
->setSmsFreeSignName('')  //阿里大于短信签名
```
如需传入短信变量则需要修改第100行中的相应数组，具体参考阿里大于API文档：
```PHP
// 短信内容参数
        $smsParams = [
            //'code'    => $this->randString(),
            //'name' => $name  //接收方用户名
        ];
```
第122、199行  和arduino程序对应的KEY：
```PHP
if(I('get.key') == "填入key"){  //和arduino程序里面的KEY对应
```
##数据库
> 需要用到的数据库文件：
 [anfang.sql](anfang.sql)
 直接导入到MYSQL数据库即可使用
