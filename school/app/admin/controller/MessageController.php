<?php
/**
 * Created by PhpStorm.
 * User:  惠普
 * Date: 2017/12/18
 * Time: 16:31
 */

namespace app\admin\controller;

use cmf\controller\AdminBaseController;
use think\Db;

class MessageController extends AdminBaseController
{
    public function _initialize()
    {

    }

    //资讯列表
    public function index()
    {
        $result = Db::table('baoli_message m')->join('baoli_message_cate mc','m.cate_id=mc.id','left')->field('m.*,mc.catename')->select();
//        var_dump(json_decode($result[0]['mes_src'],true));die;
        $this->assign('data',$result);
        return $this->fetch();
    }

    //资讯添加
    public function add()
    {
        //查找所有活动分类
        $data = Db::name('Message_cate')->select();
        $this->assign('cateList',$data);
        return $this->fetch();
    }

    //资讯添加提交
    public function addPost()
    {
        if ($this->request->isPost()) {
            $data   = $this->request->param();
            $result = $this->validate($data, 'Message');
//            var_dump($data);die;
            if ($result !== true) {
                // 验证失败 输出错误信息
                $this->error($result);
            } else {
                $post['mes_src'] = [];
                if (!empty($data['photo_names']) && !empty($data['photo_urls'])) {
                    $post['mes_src']['photos'] = [];
                    foreach ($data['photo_urls'] as $key => $url) {
                        $photoUrl = cmf_asset_relative_url($url);
                        array_push($post['mes_src']['photos'], ["url" => $photoUrl, "name" => $data['photo_names'][$key]]);
                    }
                }
                $post['mes_con'] = htmlspecialchars(cmf_replace_content_file_url(htmlspecialchars_decode($data['mes_con']), true));
                $post['mes_name'] = $data['mes_name'];
                $post['user_id'] = cmf_get_current_admin_id();
                $post['create_time'] = time();
                $post['update_time'] = time();
                $post['cate_id'] = $data['cate_id'];
                $post['mes_src'] = json_encode($post['mes_src']);
                $result = Db::name('Message')->insert($post);


                if ($result) {
                    $this->success("添加成功", url("Message/index"));
                } else {
                    $this->error("添加失败");
                }

            }
        }
    }

    //资讯编辑
    public function edit()
    {
        $id = $this->request->param("id", 0, 'intval');
//        var_dump($id);die;
        $info['data'] = Db::table('baoli_message m')->join('baoli_message_cate mc','m.cate_id=mc.id','left')->field('m.*,mc.catename')->where(['m.id'=>$id])->find();
//        var_dump($info['data']);die;
        if (!$info['data']) {
            $this->error("该资讯不存在！");
        }
        $info['cateList'] = Db::name('message_cate')->select();
        $photos = json_decode($info['data']['mes_src'],true);
        $this->assign('photos',$photos['photos']);
        $this->assign('info',$info);
        return $this->fetch();
    }

    //资讯编辑提交
    public function editPost()
    {
        $id = $this->request->param("id", 0, 'intval');
        $data = Db::name('message')->where(["id" => $id])->find();
//        var_dump($data);die;
        if (!$data) {
            $this->error("该资讯不存在！");
        }
        if ($this->request->isPost()) {
            $data   = $this->request->param();
            $result = $this->validate($data, 'Message');
            $post['mes_src'] = [];
            if (!empty($data['photo_names']) && !empty($data['photo_urls'])) {
                $post['mes_src']['photos'] = [];
                foreach ($data['photo_urls'] as $key => $url) {
                    $photoUrl = cmf_asset_relative_url($url);
                    array_push($post['mes_src']['photos'], ["url" => $photoUrl, "name" => $data['photo_names'][$key]]);
                }
            }
            $post['mes_con'] = htmlspecialchars(cmf_replace_content_file_url(htmlspecialchars_decode($data['mes_con']), true));
            $post['mes_name'] = $data['mes_name'];
            $post['user_id'] = cmf_get_current_admin_id();
            $post['cate_id'] = $data['cate_id'];
            $post['mes_src'] = json_encode($post['mes_src']);
            if ($result !== true) {
                // 验证失败 输出错误信息
                $this->error($result);

            } else {
                $data['update_time'] = time();
                if (Db::name('message')->where(["id" => $id])->update($post) !== false) {
                    $this->success("保存成功！", url('Message/index'));
                } else {
                    $this->error("保存失败！");
                }
            }
        }
    }

    //资讯删除
    public function del()
    {
        $id = $this->request->param("id", 0, 'intval');
        $data = Db::name('message')->where(["id" => $id])->find();
//        var_dump($data);die;
        if (!$data) {
            $this->error("该资讯不存在！");
        }
        $status = Db::name('message')->delete($id);
        if (!empty($status)) {
            $this->success("删除成功！", url('Message/index'));
        } else {
            $this->error("删除失败！");
        }
    }

}