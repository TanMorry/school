<?php
/**
 * Created by PhpStorm.
 * User:  惠普
 * Date: 2018/1/16
 * Time: 11:04
 */

namespace app\admin\controller;

use think\Db;
use cmf\controller\AdminBaseController;

class ChildRelationController extends AdminBaseController
{
    /*
     * 童乐会关系列表
     */
    public function index()
    {
        $list = Db::name('child_relation')->select();
        $this->assign('data',$list);
        return $this->fetch();
    }

    /*
     * 童乐会关系添加
     */
        public function add()
        {
            return $this->fetch();
        }

        /*
         * 童乐会关系添加提交
         */
        public function addPost()
        {
            $data = $this->request->param();
            if(empty(trim($data['name']))){
                $this->error('名称不能为空');
            }
            $data['create_time'] = time();
            $list = Db::name('child_relation')->insert($data);
            if($list){
                $this->success('提交成功');
            }else{
                $this->error('提交失败');
            }
        }

    /*
     * 童乐会关系信息编辑
     */
    public function edit()
    {
        $id = $this->request->param('id',0,'intval');
        $data = Db::name('child_relation')->where(['id'=>$id])->find();
        if(!$data){
            $this->success('信息不存在');
        };
        $this->assign('data',$data);
        return $this->fetch();
    }

    /*
     * 童乐会编辑提交
     */
    public function editPost()
    {
        $id = $this->request->param('id',0,'intval');
        $result = Db::name('child_relation')->where(['id'=>$id])->find();
        if(!$result){
            $this->success('信息不存在');
        }
        if($this->request->isPost()){
            $data = $this->request->param();
            $post = [];
            $post['name'] = $data['name'];
            $post['update_time'] = time();
            if(empty(trim($data['name']))){
                $this->error('名称不能为空');
            }
            $update = Db::name('child_relation')->where(["id" => $id])->update($post);
//                var_dump($update);die;
            if ($update) {
                $this->success("修改信息成功", url("ChildRelation/index"));
            } else {
                $this->error("修改信息失败");
            }
        }
    }


    /*
     * 童乐会信息删除
     */
    public function delete()
    {
        $id = $this->request->param('id',0,'intval');
        $data = Db::name('child_relation')->where(['id'=>$id])->find();
        if(!$data){
            $this->error('信息不存在');
        }
        $result = Db::name('child_relation')->where(['id'=>$id])->delete();
        if($result){
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }
    }

}