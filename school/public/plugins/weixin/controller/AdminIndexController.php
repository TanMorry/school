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
namespace plugins\weixin\controller; //

use cmf\controller\PluginBaseController;
use plugins\weixin\lib\TPweixin as Wechat;
use plugins\weixin\model\PluginPassiveModel as Passive;

class AdminIndexController extends PluginBaseController
{
    private $weObj;
    private $options;
    private $plugin;
    private $config;

    public function getDatacube()
    {
        //数据统计
        $input      = $this->request->param();
        $type       = $input['type'];
        $subtype    = $input['subtype'];
        $begin_date = $input['begin'];
        $end_date   = $input['end'];
        if ($data = $this->weObj->getDatacube($type, $subtype, $begin_date, $end_date)) {
            return json(['code' => 1, 'msg' => $data]);
        } else {
            return json(['code' => 0, 'msg' => false]);
        }

    }

    //素材各类总数
    protected function _ForeverCount()
    {
        return $this->weObj->getForeverCount();
    }

    //素材列表构造
    protected function _ForeverList()
    {
        $input  = $this->request->param();
        $type   = isset($input['type']) ? $input['type'] : 'news';
        $offset = isset($input['offset']) ? $input['offset'] : 0;
        $count  = isset($input['count']) ? $input['count'] : 20;
        return $this->weObj->getForeverList($type, $offset, $count);
    }

    //永久素材列表
    public function getForeverList()
    {
        $ForeverList  = $this->_ForeverList();
        $ForeverCount = $this->_ForeverCount();
        $this->assign(['ForeverCount' => $ForeverCount, 'ForeverList' => $ForeverList]);
        return $this->fetch('/admin_ForeverList');
    }

    protected function _initialize()
    {
        $adminId = cmf_get_current_admin_id(); //获取后台管理员id，可判断是否登录
        if (!empty($adminId)) {
            $this->assign("admin_id", $adminId);
        } else {
            //TODO no login
            $this->error('未登录');
        }

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

    public function index()
    {
        //用户管理
        $userlist = $this->weObj->getUserList();
        $tagslist = $this->weObj->tagsList();
        $userdata = [];
        if (isset($userlist['data'])) {
            foreach ($userlist['data']['openid'] as $key => $openid) {
                //追加信息
                $userdata[$key] = $this->weObj->getUserInfo($openid);
                //修复bug $userdata[$key]['tagid_list'] 为空不追加
                if(!empty($userdata[$key]['tagid_list'])){
                    foreach ($userdata[$key]['tagid_list'] as $k => $v) {
                        
                        foreach ($tagslist['tags'] as $index => $val) {
    
                            if ($val['id'] == $v) {
                                $userdata[$key]['tagname'][] = $val['name'];
                            }
                        }
                    }
                }
                
            }
        }

        $users['total']       = $userlist['total'];
        $users['count']       = $userlist['count'];
        $users['data']        = $userdata;
        $users['next_openid'] = $userlist['next_openid'];
        $this->assign(['users' => $users, 'tagslist' => $tagslist['tags']]);
        return $this->fetch('/admin_index');
    }

    public function tagsBatchTagging()
    {
        //批量打标签
        $input = $this->request->param();
        if (!empty($input['ids']) && !empty($input['tagid'])) {
            if ($this->weObj->tagsBatchTagging($input['ids'], $input['tagid'])) {
                return json(['code' => 1, 'msg' => '打标签成功']);
            } else {
                return json(['code' => 0, 'msg' => '打标签失败']);
            }
        } else {
            return json(['code' => 0, 'msg' => '打标签失败']);
        }
    }

    public function tagsBatchunTagging()
    {
        //批量取消标签
        $input = $this->request->param();
        if (!empty($input['ids']) && !empty($input['tagid'])) {
            if ($this->weObj->tagsBatchunTagging($input['ids'], $input['tagid'])) {
                return json(['code' => 1, 'msg' => '取消标签成功']);
            } else {
                return json(['code' => 0, 'msg' => '取消标签失败']);
            }
        } else {
            return json(['code' => 0, 'msg' => '取消标签失败']);
        }
    }

    public function tagsCreate()
    {
        //新建用户标签
        $input = $this->request->param();
        if (!empty($input['name'])) {
            if ($this->weObj->tagsCreate($input['name'])) {
                return json(['code' => 1, 'msg' => '新建标签成功']);
            } else {
                return json(['code' => 0, 'msg' => '新建标签失败']);
            }
        } else {
            return json(['code' => 0, 'msg' => '新建标签失败']);
        }
    }

    public function tagDelete()
    {
        //删除用户标签
        $input = $this->request->param();
        if (!empty($input['tagid'])) {
            if ($this->weObj->tagsDelete($input['tagid'])) {
                return json(['code' => 1, 'msg' => '删除用户标签成功']);
            } else {
                return json(['code' => 0, 'msg' => '删除用户标签失败']);
            }
        } else {
            return json(['code' => 0, 'msg' => '用户标签不存在']);
        }

    }

    public function remark()
    {
        //用户备注
        $input = $this->request->param();
        if (!empty($input['remark'])) {
            $this->weObj->updateUserRemark($input['openid'], $input['remark']);
        }

        return json(['code' => 1, 'msg' => '保存成功']);
    }

    public function menu()
    {
        //自定义菜单
        $menu = $this->weObj->getMenu();
        $this->assign('menu', $menu);
        return $this->fetch('/admin_menu');
    }

    public function saveMenu()
    {
        //更改微信平台服务器信息
        $input = $this->request->param();
        $num   = count($input['name']);
        if ($num > 3) {
            $num = 3;
        }

        for ($i = 0; $i < $num; $i++) {
            $data['button'][$i]['name'] = $input['name'][$i + 1];
            if (isset($input['sub_name'][$i + 1])) {
                $sub_num = count($input['sub_name'][$i + 1]);
                if ($sub_num > 5) {
                    $sub_num = 5;
                }

                for ($j = 0; $j < $sub_num; $j++) {
                    $data['button'][$i]['sub_button'][$j]['name'] = $input['sub_name'][$i + 1][$j + 1];
                    $data['button'][$i]['sub_button'][$j]['type'] = $input['sub_type'][$i + 1][$j + 1];

                    switch ($input['sub_type'][$i + 1][$j + 1]) {
                        case 'view':
                            $data['button'][$i]['sub_button'][$j]['url'] = $input['sub_value'][$i + 1][$j + 1];
                            break;
                        case 'click':
                            $data['button'][$i]['sub_button'][$j]['key'] = $input['sub_value'][$i + 1][$j + 1];
                            break;
                        default:
                            # code...
                            break;
                    }

                }
            } else {

                $data['button'][$i]['name'] = $input['name'][$i + 1];
                $data['button'][$i]['type'] = $input['type'][$i + 1];
                switch ($input['type'][$i + 1]) {
                    case 'view':
                        $data['button'][$i]['url'] = $input['value'][$i + 1];
                        break;
                    case 'click':
                        $data['button'][$i]['key'] = $input['value'][$i + 1];
                        break;
                    default:
                        # code...
                        break;
                }

            }

        }

        if ($this->weObj->createMenu($data)) {
            $this->success('保存成功');
        } else {
            $this->error('保存失败');
        }
    }

    public function passiveRecovery()
    {
        //被动回复规则
        $list = Passive::all();
        $this->assign('list', $list);
        return $this->fetch('/admin_passiveRecovery');
    }

    public function passiveDelete()
    {
        //被动回复规则删除
        $input = $this->request->param();
        if (Passive::destroy($input['id'])) {
            return json(['code' => 1, 'msg' => '删除成功']);
        } else {
            return json(['code' => 0, 'msg' => '删除失败']);
        }
    }

    public function passiveAdd()
    {
        //被动回复规则添加
        $input           = $this->request->param();
        $data['intype']  = $input['intype'];
        $data['outtype'] = $input['outtype'];
        $data['mark']    = isset($input['mark']) ? $input['mark'] : '';
        $data['content'] = $input['content'];
        $res             = Passive::create($data);
        if ($res->id > 0) {
            return json(['code' => 1, 'msg' => '添加成功']);
        } else {
            return json(['code' => 0, 'msg' => '添加失败']);
        }
    }

}
