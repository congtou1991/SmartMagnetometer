SmartMagnetometer
===================  
## 基于ESP8266组建的智能安防系统  
### [PHP端说明](#smartmagnetometer-10)
### [arduino代码说明](/WiFiClient1.0/README.md)

## 主要硬件部分：  
* 1、NodeMcu ESP8266开发板
* 2、干簧管传感器
* 3、磁铁

## PHP后端：  
* 1、ThinkPHP框架
* 2、麦当苗儿微信公众平台类
* 3、阿里大于平台(短信推送)

## 项目描述：  

【 硬件部分 】  
* 只负责收集传感器信息并上传至服务器

【  PHP端  】  
* 负责存储和处理上传的传感器信息
* 负责存储公众号用户信息
* 负责连接阿里大于短信API推送报警信息
        
【  公众号  】  
* 提供历史报警数据查询
* 提供系统状态查询
* 配置PHP端参数(是否短信报警，删除数据)
* 用户注册，手机邮件绑定
        
【目前实现功能】  
* 上电自动连接WIFI(读取上一次成功配置的SSID和PWD)，20s内连接失败则启用智能配置模式，等待Airkiss的WIFI信息包。
* 开启报警模式下，门窗被打开后会收到短信提醒。(2分钟内只发送一条信息，可设置时间)
* 结合公众微信号，可以添加寝室人员并绑定各自的手机(每次报警都会发送信息到每个手机上)
* 公众号可以查询历史报警记录
* 公众号可以关闭/打开短信报警

> 由于公众号主动推送功能需要通过认证才能使用，所以没有进行这部分开发。  

# PHP端说明：
# SmartMagnetometer 1.0
===============
##简介
本服务端基于ThinkPHP3.2.3开发而成，集成了微信公众号类(麦当苗儿版本)和阿里大于短信发送API，能实现警报信息短信推送和使用微信公众平台对主机状态查询、房间状态查询、开/关报警等的相关操作。

##服务端环境要求
* PHP版本 > 5.3
* 带MYSQL数据库

##配置
> 需要修改以下文件才能正常使用：  
> 阿里大于配置部分，需要先注册“阿里大于”账号后，新建对应的短信模板和短信签名，等待后台审核通过，即可获取“模板ID”、“短信签名”、“appkey”、“appsecret”

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
> 这部分配置信息需要登陆公众号后台获取

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

# arduino代码说明：
# WIFIClient 1.0
===============
##编译环境
Arduino IDE 1.6.5
##库
[ESP8266WiFi](https://github.com/congtou1991/SmartMagnetometer/tree/master/WiFiClient1.0/libraries/ESP8266WiFi)
##开发板
NodeMCU 1.0(ESP8266-12D)
##配置
> 需要配置以下几处：

```c
const char* host = "www.***.com"; //网站域名

const char* Key = "******"; //校验KEY，与PHP服务端中设置的KEY对应

int RS_1 = D5;  //感应器输入端口 这里对应NodeMcu开发板的GPIO14口
```
