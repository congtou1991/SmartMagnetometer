SmartMagnetometer 1.0
===============
##简介
本服务端基于ThinkPHP3.2.3开发而成，集成了微信公众号类(麦当苗儿版本)和阿里大于短信发送API，能实现警报信息短信推送和使用微信公众平台对主机状态查询、房间状态查询、开/关报警等的相关操作。

##服务端环境要求
* PHP版本 > 5.3
* 带MYSQL数据库

##配置
> 需要修改以下文件才能正常使用：
* 1、 [\Application\Common\Conf\config.php](Application/Common/Conf/config.php)

  配置相应数据库信息
  配置阿里大于信息

\Application\Home\Controller\IndexController.class.php

第29、30、31行  微信公众号配置

第106行  阿里大于短信模板ID

第109行  阿里大于短信签名

如需传入短信变量则需要修改第100行中的相应数组，具体参考阿里大于API文档

第122、199行  和arduino程序对应的KEY
