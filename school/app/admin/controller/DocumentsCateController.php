<?php
/**
 * Created by PhpStorm.
 * User:  惠普
 * Date: 2018/1/18
 * Time: 18:15
 */

namespace app\admin\controller;

use think\Db;
use cmf\controller\AdminBaseController;

class DocumentsCateController extends AdminBaseController
{
    /*
     * 文档分类列表
     */
    public function index()
    {
        $data = Db::name('documents_cate')->select();
        $this->assign('data',$data);
        return $this->fetch();
    }

    /*
     * 添加文档分类
     */
    public function add()
    {
        return $this->fetch();
    }

    /*
     * 添加文档提交
     */
    public function addPost()
    {
        if($this->request->isPost()){
            $data = $this->request->param();
            if(empty($data['name'])){
                $this->error('分类不能为空');
            }
            $data['create_time'] = time();
            $data['update_time'] = time();
            $result = Db::name('documents_cate')->insert($data);
            if($result){
                $this->success('提交成功',url('DocumentsCate/index'));

            }else{
                $this->error('提交失败');
            }
        }else{
            $this->error('提交失败');
        };

    }

    /*
     * 编辑文档分类
     */

    public function edit()
    {
        $id = $this->request->param('id',0,'intval');
        $list = Db::name('documents_cate')->where(['id'=>$id])->find();
        if(!$list){
            $this->error('改分类不存在');
        }
        $this->assign('data',$list);
        return $this->fetch();
    }

    /*
     * 编辑文档分类提交
     */
    public function editPost()
    {
        if($this->request->isPost()){
            $data = $this->request->param();
            if(empty($data['name'])){
                $this->error('分类不能为空');
            }
            $data['update_time'] = time();
            $result = Db::name('documents_cate')->update($data);
            if($result){
                $this->success('提交成功',url('DocumentsCate/index'));

            }else{
                $this->error('提交失败');
            }
            $this->success('提交成功',url('Documents/index'));
        }else{
            $this->error('提交失败');
        };
    }

    /*
     * 删除文档分类
     */
    public function delete()
    {
        $id = $this->request->param('id',0,'intval');
        $list = Db::name('documents_cate')->where(['id'=>$id])->find();
        if(!$list){
            $this->error('改分类不存在');
        }
        $result = Db::name('documents_cate')->where(['id'=>$id])->delete();
        if($result){
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }
    }
}