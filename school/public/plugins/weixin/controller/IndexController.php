<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2014 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: wuwu <15093565100@163.com>
// +----------------------------------------------------------------------
// | https://github.com/yangguangwuwu/thinkcmf5_wechat.git
// +----------------------------------------------------------------------
namespace plugins\weixin\controller;

use cmf\controller\PluginBaseController;
use plugins\weixin\lib\TPweixin as Wechat;
use plugins\weixin\model\PluginPassiveModel as Passive;
use plugins\weixin\model\ThirdPartyUserModel;
use plugins\weixin\model\UserModel;
use think\Db;

class IndexController extends PluginBaseController
{
    private $weObj;
    private $options;
    private $plugin;
    private $config;

    protected function _initialize()
    {
        $pluginClass = cmf_get_plugin_class('Weixin');

        $this->plugin  = new $pluginClass();
        $this->config  = $this->plugin->getConfig();
        $this->options = array(
            'token'          => $this->config['token'], //填写你设定的key
            'encodingaeskey' => $this->config['encodingaeskey'], //填写加密用的EncodingAESKey
            'appid'          => $this->config['appid'], //填写高级调用功能的app id, 请在微信开发模式后台查询
            'appsecret'      => $this->config['appsecret'], //填写高级调用功能的密钥
        );

        $this->weObj = new Wechat($this->options); //创建实例对象
    }

    /**
     * [index 微信交互入口]
     * @Author:   wuwu<15093565100@163.com>
     * @DateTime: 2017-07-02T16:24:17+0800
     * @since:    1.0
     * @return    [type]                    [description]
     */
    public function index()
    {
        $this->weObj->valid(); //微信接口验证
        $msg  = $this->weObj->getRev();
        $type = $msg->getRevType();

        switch ($type) {
            case Wechat::MSGTYPE_TEXT: //文字回复
                $this->text();
                break;
            case Wechat::MSGTYPE_EVENT: //事件
                $eventType = $msg->getRevEvent();

                switch ($eventType['event']) {
                    case Wechat::EVENT_SUBSCRIBE: //订阅回复subscribe
                        $this->subscribe();
                        break;
                    default:
                        break;
                }

                break;
            default:
                $this->weObj->text("对不起，此类回复尚未开发")->reply();
        }
    }

    protected function text()
    {
        //文字回复
        $keyword = $this->weObj->getRevContent();
        $data    = Passive::where('mark', $keyword)->find();

        if (!isset($data['content'])) {
        //关键词未查到
            $this->weObj->text("对不起，回复关键词不存在")->reply();
        }

        //根据回复类型回复
        $type    = $data['outtype'];
        $content = $data['content'];
        $this->Reply($type, $content);

    }

    public function sendTemptele($param){
        $data=array(
            'touser'=>$param['buyer_id'],
            'template_id'=>"_0DQerSIqPZaB4vjQjjOIPRXZhcVooFT_390vDhHhVw",    //模板的id
            'url'=>"http://weixin.qq.com/download",
            'topcolor'=>"#FF0000",
            'data'=>array(
                'first'=>array('value'=>'购买成功','color'=>"#00008B"),    //函数传参过来的name
                'product'=>array('value'=>$param['product'],'color'=>'#00008B'),        //函数传参过来的zu
                'price'=>array('value'=>$param['price'],'color'=>'#00008B'),        //函数传参过来的zu
                'time'=>array('value'=>$param['time'],'color'=>'#00008B'),   //时间
                'remark'=>array('value'=>'我们将尽快发货，祝您生活愉快','color'=>'#00008B'),//函数传参过来的ramain
            )
        );
        $this->weObj->sendTemplateMessage(json_decode($data));
    }

    protected function subscribe()
    {
        //订阅回复
        $data = Passive::where('intype', 'subscribe')->find();
        if (empty($data['content'])) {
        //关键词未查到
            $this->weObj->text("欢迎您关注此公众号")->reply();
            return;
        }

        $type    = $data['outtype'];
        $content = $data['content'];
        $this->Reply($type, $content);
    }

    protected function Reply($type, $content)
    {
        //回复方式
        switch ($type) {
            case 'text':

                $this->weObj->text($content)->reply();
                break;

            default:
                $this->weObj->text("对不起，此类回复类型尚未开发")->reply();
                break;
        }
    }

//    public function login()
//    {
//        $redirect = $this->request->server('HTTP_REFERER');
//        session('login_http_referer', $redirect);//记录来源
//        if (cmf_is_user_login()) { //已经登录时直接跳到首页
//            return redirect($redirect);
//        }
//        $this->oauth();
//    }

    //微信授权
    public function oauth()
    {
        if($this->request->has('code')){
            $code = $this->request->param('code');

            if(empty($_GET['code'])){
                $_GET['code'] = $code;
            }

            //@return array {access_token,expires_in,refresh_token,openid,scope}
            $data = $this->weObj->getOauthAccessToken();

            if(empty($data)){
                exit('系统错误，请联系管理员');
            }
            //验证授权是否有效 无效重新获取
            if($this->weObj->getOauthAuth($data['access_token'],$data['openid'])){
                //存入session
                session('weixin_oauth',$data);
                
                //进行绑定账户 没有则 注册 最后放置登录信息
                //openid app_id对比数据库
                $user['app_id'] = $this->config['appid'];
                $user['openid'] = $data['openid'];
                $res = ThirdPartyUserModel::get($user);
                if(empty($res)){
                    $user['expires_time'] = $data['expires_in'];
                    $user['access_token'] = $data['access_token'];

                    //用户不存在 进入注册
                    //获取微信信息存入$user
                    //{openid,nickname,sex,province,city,country,headimgurl,privilege,[unionid]}
                    $wechatData = $this->weObj->getOauthUserinfo($data['access_token'],$data['openid']);
                    if($wechatData){
                        $user = array_merge($user,$wechatData);
                    }
                    $result = ThirdPartyUserModel::register($user);
                }else{
                    //存在进入登录
                    $result = ThirdPartyUserModel::login($res->user_id);

                    $userTokenQuery = Db::name("user_token")
                        ->where('user_id', $res->user_id)
                        ->where('device_type', 'weixin');
                    $findUserToken  = $userTokenQuery->find();
                    $currentTime    = time();
                    $expireTime     = $currentTime + 24 * 3600 * 180;
                    $token          = md5(uniqid()) . md5(uniqid());
                    if (empty($findUserToken)) {
                        $userTokenQuery->insert([
                            'token'       => $token,
                            'user_id'     => $res->user_id,
                            'expire_time' => $expireTime,
                            'create_time' => $currentTime,
                            'device_type' => 'weixin'
                        ]);
                    } else {
                        $userTokenQuery
                            ->where('user_id', $res->user_id)
                            ->where('device_type', 'weixin')
                            ->update([
                                'token'       => $token,
                                'expire_time' => $expireTime,
                                'create_time' => $currentTime,
                            ]);
                    }
                    cookie('token',$token);
                    cookie('device_type','weixin');
                }
               
                $url = session('login_http_referer');
                cmf_user_action('login');
//                $this->success($url);
                $this->redirect($url);
//                redirect($url);
            }else{
                $this->toUrl();
            }

        }else{

            $this->toUrl();
        }

    }

    //跳转到微信授权页面
    protected function toUrl()
    {
        $url = $this->weObj->getOauthRedirect(cmf_plugin_url('Weixin://Index/oauth',[],true),'weixin');
        header("Location:$url");die;
    }

    public function demo()
    {
        $res = ThirdPartyUserModel::get(1);
        dump($res->User);
    }
}
