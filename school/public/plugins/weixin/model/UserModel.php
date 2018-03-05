<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2017 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 五五 <15093565100@163.com>
// +----------------------------------------------------------------------
namespace plugins\weixin\model;//Demo插件英文名，改成你的插件英文就行了
use think\Model;

//用户模型
class UserModel extends Model
{
    protected $insert = ['last_login_ip','user_type'];
    protected $update = ['last_login_ip','last_login_time'];
    protected $autoWriteTimestamp = true; 

    //自动完成ip
    protected function setLastLoginIpAttr()
    {
        return request()->ip();
    }

    protected function setUserTypeAttr()
    {
        return 2;
    }

    protected function setLastLoginTimeAttr()
    {
        return time();
    }
}