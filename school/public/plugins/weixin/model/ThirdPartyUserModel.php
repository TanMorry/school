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
use plugins\weixin\model\UserModel as User;

//第三方用户模型
class ThirdPartyUserModel extends Model
{
    protected $autoWriteTimestamp = true; 

    //关联用户
    public function User(){
        return static::hasOne('UserModel','id','user_id');
    }

    //登录
    public static function login($id)
    {
        $res = User::get($id);
        if($res){
            session('user',$res);
        }
    }

    //注册
    public static function register($user)
    {
        self::startTrans();
        try{
            $data['user_login'] = self::createLogin();
            $result = User::get($data);
            while($result){
                $data['user_login'] = self::createLogin();
            }
            if(!empty($user['headimgurl'])){
                $data['avatar'] = $user['headimgurl'];
            }
            $data['user_pass'] = cmf_password($data['user_login']);//密码   
            $data['user_nickname'] = 'baoli_user';
            $data['weixin_nickname'] = $user['nickname'];
            $result = User::create($data,true);
            $id = $result->id;
            $user['user_id'] = $id;
            self::create($user,true);
            self::commit();  
            self::login($id);
        } catch (\Exception $e) {
            // 回滚事务
            self::rollback();
        }
    }

    protected static function createLogin()
    {
        return 'baoli_user'.mt_rand(1000,99999999);
    } 
}