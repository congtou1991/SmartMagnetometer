/*
 *  基于ESP8266WiFi库中WiFiClient示例修改
 *
 *  硬件采用NodeMcu ESP8266开发板 + 干簧管传感器
 *  
 *  Edit by 葱头    编辑于2016-09-26
 *  Email:ct@congtou.me
 *  WEB:http://congtou.me
 *
 */

#include <ESP8266WiFi.h>
#define LED 2
unsigned long wificonnet_time=20000;
const char* ssid;
const char* password;

const char* host = "www.***.com"; //网站域名
const char* controller   = "Index";
const char* action   = "up";
const char* wdaction   = "hand";
const char* Key = "******"; //校验KEY

unsigned long startPost,wdtime,seniortime;
bool flagFirstPost = true;        //第一次发送数据
bool Firsthandshaking = true;
int RS1_state = 0;
int RS_1 = D5;  //感应器输入端口 这里对应NodeMcu开发板的GPIO14口
//int RS_2 = 0;

//智能配置函数
void smartConfig()
{
  WiFi.mode(WIFI_STA);
 // Serial.println("\r\nWait for Smartconfig");
  WiFi.beginSmartConfig();
  while (1)
  {
    //Serial.print(".");
    digitalWrite(LED, 0);
    delay(500);
    digitalWrite(LED, 1);
    delay(500);
    if (WiFi.smartConfigDone())
    {
      //Serial.println("SmartConfig Success");
      //Serial.println(WiFi.SSID().c_str());
      //Serial.println(WiFi.psk().c_str());
      break;
    }
  }
}

void setup() {
  pinMode(LED, OUTPUT);
  pinMode(RS_1,INPUT);
  //pinMode(RS_2,INPUT);
  //Serial.begin(115200);
  digitalWrite(LED, 0);
  //Serial.println();
  //Serial.print("Connecting to ");
  //Serial.println(ssid);
  WiFi.begin(ssid, password);   //上电后读取上一次配置的wifi信息自动连接(通过arikiss配置成功的SSID和PWD会自动保存在模块flash，无需外接eeprom存储)
  
  //20s内连接失败则启用SmartConfig模式等待接收wifi数据UDP包
  while (WiFi.status() != WL_CONNECTED) {
    unsigned long nowtime=millis();
    if(nowtime>wificonnet_time){
      smartConfig();
      delay(10);
      if(WiFi.smartConfigDone()){
  //Serial.println();
  //Serial.println();
  //Serial.print("Connecting to ");
  //Serial.println(ssid);
  
  WiFi.begin(ssid, password);
  
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    //Serial.print(".");
  }

  //Serial.println("");
  //Serial.println("WiFi connected");  
  //Serial.println("IP address: ");
  //Serial.println(WiFi.localIP());
}
      }
    delay(500);
    //Serial.print(".");
  }
  //Serial.println("");
  //Serial.println("WiFi connected");  
  //Serial.println("IP address: ");
  //Serial.println(WiFi.localIP());
}


//定时回传系统状态，用于检测ESP8266是否宕机
void handshaking(){
 // Serial.print("handshaking to ");
  //Serial.println(host);
  
  // Use WiFiClient class to create TCP connections
  WiFiClient client;
  const int httpPort = 80;
  if (!client.connect(host, httpPort)) {
    //Serial.println("handshaking failed");
    return;
  }
  
  // We now create a URI for the request
  String url = "/af/index.php/Home/";
  url += controller;
  url += "/";
  url += wdaction;
  url += "?health=1";
  url += "&key=";
  url += Key;
  //Serial.print("Handshaking URL: ");
  //Serial.println(url);
  
  // This will send the request to the server
  client.print(String("GET ") + url + " HTTP/1.1\r\n" +
               "Host: " + host + "\r\n" + 
               "Connection: close\r\n\r\n");
  delay(10);
  
  // Read all the lines of the reply from server and print them to Serial
  while(client.available()){
    String line = client.readStringUntil('\r');
   // Serial.print(line);
  }
  
  //Serial.println();
  //Serial.println("closing connection");
}


//传感器数据上传函数
void RS1_up(){
  //Serial.print("connecting to ");
  //Serial.println(host);
  
  // Use WiFiClient class to create TCP connections
  WiFiClient client;
  const int httpPort = 80;
  if (!client.connect(host, httpPort)) {
    //Serial.println("connection failed");
    return;
  }
  
  // We now create a URI for the request
  String url = "/af/index.php/Home/";
  url += controller;
  url += "/";
  url += action;
  url += "?RS_id=1";
  url += "&RS_state=";
  url += RS1_state;
  url += "&key=";
  url += Key;
  //Serial.print("Requesting URL: ");
  //Serial.println(url);
  
  // This will send the request to the server
  client.print(String("GET ") + url + " HTTP/1.1\r\n" +
               "Host: " + host + "\r\n" + 
               "Connection: close\r\n\r\n");
               delay(10);
  
  // Read all the lines of the reply from server and print them to Serial
  while(client.available()){
    String line = client.readStringUntil('\r');
    //Serial.print(line);
  }
  
  //Serial.println();
  //Serial.println("closing connection");
  }
  
 
void loop() {
  if (millis()-startPost>5000 || flagFirstPost){
      RS1_up();
      startPost = millis();
      flagFirstPost = false;
      delay(10);
  }else if(millis()-wdtime>15000 || Firsthandshaking){
      handshaking();
      wdtime = millis();
      Firsthandshaking = false;
      delay(10);
  }else if(digitalRead(RS_1)==HIGH){
      RS1_state = 1;
      seniortime = millis();
      delay(10);
  }else if(digitalRead(RS_1)==LOW){
       //这里做一个12s延迟处理，在每5s一次的上传周期内，使传感器每次输出高电平都能成功上传至服务器
	   //因为这个模块没有带延时功能，不像人体红外模块可以调节高电平输出时间，所以要通过程序处理，防止漏报。
       if(millis()-seniortime>12000){
            RS1_state = 0;
            delay(10);
        }else{
          return;
          }
  }else{
    return;
  }
}

