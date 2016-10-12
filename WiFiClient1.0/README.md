WIFIClient 1.0
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
