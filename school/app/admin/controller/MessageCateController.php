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

class MessageCateController extends AdminBaseController
{
    public function _initialize()
    {

    }

    //资讯分类
    public function index()
    {
        $result = Db::name('message_cate')->select();
        $this->assign(  'data',$result);
        return $this->fetch();
    }

    //资讯分类添加
    public function add()
    {
        return $this->fetch();
    }

    //资讯添加提交
    public function addPost()
    {
        if ($this->request->isPost()) {
            $data   = $this->request->param();
            $result = $this->validate($data, 'MessageCate');
            $data['user_id'] = cmf_get_current_admin_id();
            $data['create_time'] = time();
            $data['update_time'] = time();
//            var_dump($data);die;
            if ($result !== true) {
                // 验证失败 输出错误信息
                $this->error($result);
            } else {
                $result = Db::name('message_cate')->insert($data);

                if ($result) {
                    $this->success("添加成功", url("MessageCate/index"));
                } else {
                    $this->error("添加失败");
                }

            }
        }
    }

    //资讯分类编辑
    public function edit()
    {
        $id = $this->request->param("id", 0, 'intval');
        $data = Db::name('message_cate')->where(["id" => $id])->find();
//        var_dump($data);die;
        if (!$data) {
            $this->error("该分类不存在！");
        }
        $this->assign("data", $data);
        return $this->fetch();
    }

    //资讯分类编辑提交
    public function editPost()
    {
        $id = $this->request->param("id", 0, 'intval');
        $data = Db::name('message_cate')->where(["id" => $id])->find();
//        var_dump($data);die;
        if (!$data) {
            $this->error("该分类不存在！");
        }
        if ($this->request->isPost()) {
            $data   = $this->request->param();
            $result = $this->validate($data, 'MessageCate');
            if ($result !== true) {
                // 验证失败 输出错误信息
                $this->error($result);

            } else {
                $data['update_time'] = time();
                if (Db::name('message_cate')->where(["id" => $id])->update($data) !== false) {
                    $this->success("保存成功！", url('MessageCate/index'));
                } else {
                    $this->error("保存失败！");
                }
            }
        }
    }

    //资讯分类删除
    public function delCate()
    {
        $id = $this->request->param("id", 0, 'intval');
        $data = Db::name('message_cate')->where(["id" => $id])->find();
//        var_dump($data);die;
        if (!$data) {
            $this->error("该分类不存在！");
        }
        //判断此分类下有无活动
        $message = Db::name('message')->where(["cate_id" => $id])->find();

        if ($message) {
            $this->error('此分类下有资讯无法删除!');
        }
        $status = Db::name('message_cate')->delete($id);
        if (!empty($status)) {
            $this->success("删除成功！", url('MessageCate/index'));
        } else {
            $this->error("删除失败！");
        }
    }

}