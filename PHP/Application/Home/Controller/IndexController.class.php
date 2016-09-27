<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2015 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 葱头 <ct@congtou.me> <http://congtou.me>  2016-09-26
// +----------------------------------------------------------------------

namespace Home\Controller;

use Think\Controller;
use Com\Wechat;
use Com\WechatAuth;
use Alidayu\AlidayuClient as Client;
use Alidayu\Request\SmsNumSend;

class IndexController extends Controller{
    /**
     * 微信消息接口入口
     * 所有发送到微信的消息都会推送到该操作
     * 所以，微信公众平台后台填写的api地址则为该操作的访问地址
     */
    public function index($id = ''){
        //调试
        try{
            $appid = ''; //AppID(应用ID)
            $token = ''; //微信后台填写的TOKEN
            $crypt = ''; //消息加密KEY（EncodingAESKey）
            
            /* 加载微信SDK */
            $wechat = new Wechat($token, $appid, $crypt);
            
            /* 获取请求信息 */
            $data = $wechat->request();

            if($data && is_array($data)){
                /**
                 * 你可以在这里分析数据，决定要返回给用户什么样的信息
                 * 接受到的信息类型有10种，分别使用下面10个常量标识
                 * Wechat::MSG_TYPE_TEXT       //文本消息
                 * Wechat::MSG_TYPE_IMAGE      //图片消息
                 * Wechat::MSG_TYPE_VOICE      //音频消息
                 * Wechat::MSG_TYPE_VIDEO      //视频消息
                 * Wechat::MSG_TYPE_SHORTVIDEO //视频消息
                 * Wechat::MSG_TYPE_MUSIC      //音乐消息
                 * Wechat::MSG_TYPE_NEWS       //图文消息（推送过来的应该不存在这种类型，但是可以给用户回复该类型消息）
                 * Wechat::MSG_TYPE_LOCATION   //位置消息
                 * Wechat::MSG_TYPE_LINK       //连接消息
                 * Wechat::MSG_TYPE_EVENT      //事件消息
                 *
                 * 事件消息又分为下面五种
                 * Wechat::MSG_EVENT_SUBSCRIBE    //订阅
                 * Wechat::MSG_EVENT_UNSUBSCRIBE  //取消订阅
                 * Wechat::MSG_EVENT_SCAN         //二维码扫描
                 * Wechat::MSG_EVENT_LOCATION     //报告位置
                 * Wechat::MSG_EVENT_CLICK        //菜单点击
                 */

                //记录微信推送过来的数据
                file_put_contents('./data.json', json_encode($data));

                /* 响应当前请求(自动回复) */
                //$wechat->response($content, $type);

                /**
                 * 响应当前请求还有以下方法可以使用
                 * 具体参数格式说明请参考文档
                 * 
                 * $wechat->replyText($text); //回复文本消息
                 * $wechat->replyImage($media_id); //回复图片消息
                 * $wechat->replyVoice($media_id); //回复音频消息
                 * $wechat->replyVideo($media_id, $title, $discription); //回复视频消息
                 * $wechat->replyMusic($title, $discription, $musicurl, $hqmusicurl, $thumb_media_id); //回复音乐消息
                 * $wechat->replyNews($news, $news1, $news2, $news3); //回复多条图文消息
                 * $wechat->replyNewsOnce($title, $discription, $url, $picurl); //回复单条图文消息
                 * 
                 */
               
				
				
				
                //执行Demo
                $this->demo($wechat, $data);
            }
        } catch(\Exception $e){
            file_put_contents('./error.json', json_encode($e->getMessage()));
        }
        
    }
    
	//阿里大于短信发送函数
	private function tosms($to, $name) {
		$client  = new Client;
        $request = new SmsNumSend;

        // 短信内容参数
        $smsParams = [
            //'code'    => $this->randString(),
            //'name' => $name  //接收方用户名
        ];

        // 设置请求参数
        $req = $request->setSmsTemplateCode('模板ID')  //填入阿里大于短信模板ID
            ->setRecNum($to)
            ->setSmsParam(json_encode($smsParams))
            ->setSmsFreeSignName('')  //阿里大于短信签名
            ->setSmsType('normal')
            ->setExtend('');
        $r = $client->execute($req);

		return $r;

    }
	
	
	//体感数据上传函数
	public function up(){
	  if (IS_GET){
	    if(I('get.key') == "填入key"){  //和arduino程序里面的KEY对应
		    $RS_id = I('get.RS_id');
			$RS_state = I('get.RS_state');
			if($RS_state == 1){
			  $User = M('User');
			  $r = $User->where('inhome=1')->select();
			    if(count($r) > 0){
			      for($i=0;$i<count($r);$i++){
		            $b=$b.$r[$i][id].",";
		          }
			    }
			  $homemember = substr($b,0,-1);
			  $Log = M('Alertlog');
			  $data['rs_id'] = $RS_id;
              $data['rs_state'] = $RS_state;
			  if($homemember == NULL){
			     $data['homemember'] = "empty";
			  }else{
			     $data['homemember'] = $homemember;
			  }
			  $data['time'] = time();
              $Log->add($data);
			  $Alert = M('Config');
			 $alert_state = $Alert->where('name="alert_state"')->getField('value');
			 $sms_on = $Alert->where('name="sms_on"')->getField('value');
			  if($alert_state == on && $sms_on == 1 && $b == NULL){
			     $r2 = $User->where('checkid=1')->select();
			        if(count($r2) > 0){
			            for($i=0;$i<count($r2);$i++){
							$phone = $phone.$r2[$i][phone].",";
							$name = $name.$r2[$i][name].",";
		                }
						$phone = substr($phone,0,-1);
						$name = substr($name,0,-1);
						$Smslog = M('Smslog');
						$checktime = $Smslog->where('success="1"')->order('time DESC')->getField('time');
					    if($checktime == NULL || time() - $checktime > 120){
						  $res = $this->tosms($phone);
						  if($res[alibaba_aliqin_fc_sms_num_send_response][result][success] == 1){
							$Smslog = M('Smslog');
							$data['name'] = $name;
							$data['phone'] = $phone;
							$data['success'] = "1";
							$data['err_code'] = "0";
							$data['time'] = time();
							$Smslog->add($data);
						  }else{
							$Smslog = M('Smslog');
							$data['name'] = $name;
							$data['phone'] = $phone;
							$data['success'] = "0";
							$data['err_code'] = $r[alibaba_aliqin_fc_sms_num_send_response][result][err_code];
							$data['time'] = time();
							$Smslog->add($data);
						  }
					    }else{return;}
			        }
			  }
			}else if($RS_state == 0){
			
			
			}
		   
		}else{
		  $this->error('非法请求');
		}


         }else{
             $this->error('非法请求');
         }
	
	}
	
	//写入握手信息
	public function hand(){
	  if (IS_GET){
	    if(I('get.key') == "填入key"){  //和arduino程序里面的KEY对应
		    $Hand = M('Handshake');
			$data['time'] = time();
			$Hand->where('id=1')->save($data);
		}else{
		  $this->error('非法请求');
		}
         }else{
             $this->error('非法请求');
         }
	
	}
	
    /**
     * DEMO
     * @param  Object $wechat Wechat对象
     * @param  array  $data   接受到微信推送的消息
     */
    private function demo($wechat, $data){
        switch ($data['MsgType']) {
            case Wechat::MSG_TYPE_EVENT:
                switch ($data['Event']) {
                    case Wechat::MSG_EVENT_SUBSCRIBE:
                        $wechat->replyText('欢迎您关注葱头安防系统公众平台');
                        break;

                    case Wechat::MSG_EVENT_UNSUBSCRIBE:
                        //取消关注，记录日志
                        break;
                    case Wechat::MSG_EVENT_CLICK:
					    switch ($data['EventKey']){
						  case 'bdsj':
						    $wechat->replyText('请直接回复BD+手机号 如"BD134********"');
						      break;
						  case 'bdyx':
						    $wechat->replyText('请直接回复邮箱进行绑定 如"abc@qq.com"');
						      break;
					      case 'fjzt':
						    $Alertlog = M('Alertlog');
		                    $cur_date = strtotime(date('Y-m-d'));
		                    $map['time'] = array('gt',$cur_date);
		                    $map['rs_state'] = 1;
		                    $map['homemember'] = 'empty';
                            $r = $Alertlog->where($map)->order('time DESC')->limit(10)->select();
		                    $r2 = count($r);
							if($r2 > 0){
							  for($i=0;$i<$r2;$i++){
		                      $b=$b.date('Y-m-d H:i:s',$r[$i][time])."\n";
		                      }
						      $wechat->replyText("在以下时间房门被打开，要注意啦！"."\n".$b);
							}else{
							  $wechat->replyText("闺房很安全，没有被入侵过的迹象");
							}
		                    
						      break;
					      case 'xtzt':
						    $Alert = M('Config');
			                $alert_state = $Alert->where('name="alert_state"')->getField('value');
							$Hand = M('Handshake');
	                        $alert_time = $Hand->where('id=1')->getField('time');
	                        if((time()-$alert_time)>20){
							     $sys_s = "离线";
							}else{
							     $sys_s = "在线";
							}
						    $wechat->replyText("安防警报开关：".$alert_state."\n"."安防系统联网状态：".$sys_s);
						      break;
					      case 'inhome':
						    $Log = M('Log');
			                $data['wechatid'] = $data['FromUserName'];
                            $data['operation'] = 0;
			                $data['time'] = time();
                            $Log->add($data);
							$Alert = M('Config');
							$data2['value'] = "off";
							$Alert->where('id=1')->save($data2);
							$User = M('User');
							$map['wechatid'] = $data['FromUserName'];
							$data3['id'] = $User->getFieldByWechatid($data['FromUserName'],'id');
							$data3['inhome'] =1 ;
							$User->where($map)->save($data3);
						    $wechat->replyText("欢迎回家，安防警报已关闭.");
						      break;
					      case 'outhome':
						    $Log = M('Log');
			                $data['wechatid'] = $data['FromUserName'];
                            $data['operation'] = 1;
			                $data['time'] = time();
                            $Log->add($data);
							$User = M('User');
							$map['wechatid'] = $data['FromUserName'];
							$data3['id'] = $User->getFieldByWechatid($data['FromUserName'],'id');
							$data3['inhome'] =0 ;
							$User->where($map)->save($data3);
							$isnobody = $User->where('inhome = 1')->select();
							if($isnobody == NULL){
							  $Alert = M('Config');
							  $data2['value'] = "on";
							  $Alert->where('id=1')->save($data2);
						      $wechat->replyText("外出请注意安全，安防警报已开启.");
							}else{
							  $wechat->replyText("外出请注意安全,还有小伙伴在房间，妥妥的");
							}
							
						      break;
						  default:
						    $wechat->replyText("欢迎访问葱头安防系统公众平台！您的事件类型：{$data['Event']}，EventKey：{$data['EventKey']}");
						      break;
						 }

                    default:
                        $wechat->replyText("欢迎访问葱头安防系统公众平台！您的事件类型：{$data['Event']}，EventKey：{$data['EventKey']}");
                        break;
                }
                break;

            case Wechat::MSG_TYPE_TEXT:
			    if(strlen($data['Content']) == 13 && strstr($data['Content'],'BD')){
				       $num = substr($data['Content'], -11);
                       $User = M('User');
	                   $map['phone'] = $num;
					   $map2['wechatid'] = $data['FromUserName'];
	                   $check = $User->where($map)->select();
					   $check2 = $User->where($map2)->select();
	                       if($check[0][id] == NULL){
						     if($check2[0][phone] !== NULL){
							    $oldnum = $check2[0][phone];
								$new['id']= $check2[0][id];
								$new['phone'] = $num;
								if($User->save($new)){
								  $wechat->replyText("更新号码成功!\n"."原号码为:".$oldnum."\n"."现号码为:".$num);
								}else{$wechat->replyText('操作失败:(');}
						    }else{
						     $new['phone'] = $num;
							 $new['wechatid'] = $data['FromUserName'];
							 if($User->add($new)){
							   $wechat->replyText('成功绑定手机：'.$num);
							 }
							 }
	                       }else{
	                         $wechat->replyText('该号码已被注册');
	                       }
				       
				}else if(strstr($data['Content'],'delid')){
				      $id = substr($data['Content'], 5);
					  if($id == NULL){
					      $wechat->replyText("ID不能为空!");
					  }else{
					      $Alertlog = M('Alertlog');
						  $map['id'] = $id;
                          $r = $Alertlog->where($map)->delete();
						  if($r>0){
						    $wechat->replyText("操作成功,删除的ID为".$id);
						  }else{
						    $wechat->replyText("删除失败，请确认是否有ID为:".$id."的数据");
						  }
					      
					  }
				}else if(strstr($data['Content'],'delnew')){
				      $num = substr($data['Content'], 6);
					  if($num == NULL){
					      $wechat->replyText("数据条数不能为空!");
					  }else{
					      $Alertlog = M('Alertlog');
						  $map['rs_state'] = 1;
		                  $map['homemember'] = 'empty';
                          $r = $Alertlog->where($map)->order('time DESC')->limit($num)->delete();
                          if($r>0){
						    $wechat->replyText("数据删除成功，共删除".$r."条数据");
						  }else{
                            $wechat->replyText('数据删除失败');
						  }
					  }
				}else if(strstr($data['Content'],'sidall')){
				      $num = substr($data['Content'], 6);
					  if($num == NULL){
					      $wechat->replyText("数据条数不能为空!");
					  }else{
					      $Alertlog = M('Alertlog');
						  $map['rs_state'] = 1;
                          $r = $Alertlog->where($map)->order('time DESC')->limit($num)->select();
						  $r2 = count($r);
						  	  if($r2 > 0){
							    for($i=0;$i<$r2;$i++){
		                        $b=$b.$r[$i][id]."_".date('Y-m-d H:i:s',$r[$i][time])."\n";
		                        }
						        $wechat->replyText($b);
							  }else{
                          $wechat->replyText("无记录");
						  }
					  }
				}else if(strstr($data['Content'],'@')){
                       $User = M('User');
	                   $map['email'] = $data['Content'];
					   $map2['wechatid'] = $data['FromUserName'];
	                   $check = $User->where($map)->select();
					   $check2 = $User->where($map2)->select();
	                       if($check[0][id] == NULL){
						     if($check2[0][email] !== NULL){
							    $old = $check2[0][email];
								$new['id']= $check2[0][id];
								$new['email'] = $data['Content'];
								if($User->save($new)){
								  $wechat->replyText("成功更新邮箱!\n"."原邮箱为:".$old."\n"."现邮箱为:".$data['Content']);
								}else{$wechat->replyText('操作失败:(');}
						    }else{
						     $new['email'] = $data['Content'];
							 $new['wechatid'] = $data['FromUserName'];
							 if($User->add($new)){
							   $wechat->replyText('成功绑定邮箱：'.$data['Content']);
							 }
							 }
	                       }else{
	                         $wechat->replyText('该邮箱已被注册');
	                       }
				       
				}else{
                switch ($data['Content']) {
                    case '绑定':
                        $wechat->replyText('请直接回复BD+手机号 如"BD134********"');
                        break;

                    case 'szj':
                        $User = M('User');
						$r = $User->where('inhome = 1')->select();
						$r2 = count($r);
							if($r2 > 0){
							  for($i=0;$i<$r2;$i++){
		                      $b=$b.$r[$i][name]."\n";
		                      }
						      $wechat->replyText("谁在家呢："."\n".$b);
							}else{
                        $wechat->replyText('啊噢，没人在家哦！');
						}
                        break;

                    case 'sid':
                        $Alertlog = M('Alertlog');
						$map['rs_state'] = 1;
		                $map['homemember'] = 'empty';
                        $r = $Alertlog->where($map)->order('time DESC')->limit('10')->select();
						$r2 = count($r);
							if($r2 > 0){
							  for($i=0;$i<$r2;$i++){
		                      $b=$b.$r[$i][id]."_".date('Y-m-d H:i:s',$r[$i][time])."\n";
		                      }
						      $wechat->replyText($b);
							}else{
                        $wechat->replyText("无记录");
						}
                        break;
                    
                    default:
                        $wechat->replyText("主人，不要给我乱发东西哦，不然闹情绪了会罢工！");
                        break;
                }
				}
                break;
            
            default:
                # code...
                break;
        }
    }

    /**
     * 资源文件上传方法
     * @param  string $type 上传的资源类型
     * @return string       媒体资源ID
     */
    private function upload($type){
        $appid     = '';
        $appsecret = '';

        $token = session("token");

        if($token){
            $auth = new WechatAuth($appid, $appsecret, $token);
        } else {
            $auth  = new WechatAuth($appid, $appsecret);
            $token = $auth->getAccessToken();

            session(array('expire' => $token['expires_in']));
            session("token", $token['access_token']);
        }

        switch ($type) {
            case 'image':
                $filename = './Public/image.jpg';
                $media    = $auth->materialAddMaterial($filename, $type);
                break;

            case 'voice':
                $filename = './Public/voice.mp3';
                $media    = $auth->materialAddMaterial($filename, $type);
                break;

            case 'video':
                $filename    = './Public/video.mp4';
                $discription = array('title' => '视频标题', 'introduction' => '视频描述');
                $media       = $auth->materialAddMaterial($filename, $type, $discription);
                break;

            case 'thumb':
                $filename = './Public/music.jpg';
                $media    = $auth->materialAddMaterial($filename, $type);
                break;
            
            default:
                return '';
        }

        if($media["errcode"] == 42001){ //access_token expired
            session("token", null);
            $this->upload($type);
        }

        return $media['media_id'];
    }
}
