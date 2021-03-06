<?php
/**
 * Created by PhpStorm.
 * User:  惠普
 * Date: 2018/1/15
 * Time: 15:04
 */
namespace plugins\weixin\controller;
use FontLib\Table\Type\head;
use plugins\weixin\controller\IndexController;
class WeChatIndexController extends IndexController
{
    protected $http;
    public function _initialize()
    {
//        $this->http='http://www.flyxiu.com';
        $this->http='http://tltltl.free.ngrok.cc';
        $redirect = $this->request->server('REQUEST_URI');
        session('login_http_referer', $redirect);//记录来源
        parent::_initialize(); // TODO: Change the autogenerated stub
    }

    //我的（用户中心）
    public function userInfo()
    {
        if(cmf_is_user_login()){
            $url = $this->http.'/plugins/weixin/view/wechat-fx/usercenter.html';
            $this->redirect($url);
        }else{
            parent::oauth();
        };
    }
    //票务列表
    public function ticket()
    {
        if(cmf_is_user_login()){
            $url = $this->http.'/plugins/weixin/view/wechat-fx/ticket.html';
            $this->redirect($url);
        }else{
            parent::oauth();
        };
    }

    //票务列表
    public function testzhifu()
    {
        if(cmf_is_user_login()){
            $url = $this->http.'/plugins/weixin/view/wechat-fx/zhifu.html';
            $this->redirect($url);
        }else{
            parent::oauth();
        };
    }

}