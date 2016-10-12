<?php
header("Content-type: text/html; charset=utf-8");

define("ACCESS_TOKEN", '此处输入获取到的ACCESS_TOKEN');

//创建菜单
function createMenu($data){
 $ch = curl_init();
 curl_setopt($ch, CURLOPT_URL, "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".ACCESS_TOKEN);
 curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
 curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
 curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
 curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
 curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
 curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 $tmpInfo = curl_exec($ch);
 if (curl_errno($ch)) {
  return curl_error($ch);
 }
 curl_close($ch);
 return $tmpInfo;
}


$data = ' {
     "button":[
     
      {
           "name":"用户中心",
           "sub_button":[
            {
               "type":"click",
               "name":"绑定手机",
               "key":"bdsj"
            },
            {
               "type":"click",
               "name":"绑定邮箱",
               "key":"bdyx"
            }]
       },
	   {
           "name":"安防系统",
           "sub_button":[
            {
               "type":"click",
               "name":"房间状态",
               "key":"fjzt"
            },
			{
               "type":"click",
               "name":"系统状态",
               "key":"xtzt"
            },
			{
               "type":"click",
               "name":"我在家咯",
               "key":"inhome"
            },
			{
               "type":"click",
               "name":"我出门咯",
               "key":"outhome"
            }]
       }

	   
	   
	   
	   
	   ]
 }';



echo createMenu($data);//创建菜单

?>