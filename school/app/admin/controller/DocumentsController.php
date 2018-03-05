<?php
/**
 * Created by PhpStorm.
 * User:  惠普
 * Date: 2018/1/18
 * Time: 18:15
 */

namespace app\admin\controller;

use think\Db;
use think\Validate;
use cmf\controller\AdminBaseController;

class DocumentsController extends AdminBaseController
{
    /*
     * 文档列表
     */
    public function index()
    {
        $data = Db::table('baoli_documents')->alias('a')->join('baoli_documents_cate b','a.cate_id=b.id')->field("a.*,b.name catename")->select();
        $this->assign('data',$data);
        return $this->fetch();
    }

    /*
     * 添加文档
     */
    public function add()
    {
        $list = Db::name('documents_cate')->select();
        $this->assign("cateList",$list);
        return $this->fetch();
    }

    /*
     * 添加文档提交
     */
    public function addPost()
    {
        if($this->request->isPost()){
            $data = $this->request->param();
            $validate = new Validate([
                'cate_id' => 'require',
                'name' => 'require',
                'content' => 'require',
            ]);

            $validate->message([
                'cate_id.require'  => '请选择分类！',
                'name.require'  => '文档名称不能为空！',
                'content.require'  => '文档内容不能为空！',
            ]);
            if (!$validate->check($data)) {
                $this->error($validate->getError());
            }
            $data['create_time'] = time();
            $data['update_time'] = time();
            $result = Db::name('documents')->insert($data);
            if($result){
                $this->success('提交成功',url('Documents/index'));
            }else{
                $this->error("提交失败");
            }
        }else{
            $this->error('提交失败');
        };
    }

    /*
     * 编辑文档
     */
    public function edit()
    {
        $id = $this->request->param('id',0,'intval');
        $result = Db::name('documents')->where(['id'=>$id])->find();
        $list = Db::name('documents_cate')->select();
        $data = Db::table('baoli_documents')->alias('a')->join('baoli_documents_cate b','a.cate_id=b.id')->field("a.*,b.name catename,b.id cate_id")->where(['a.id'=>$id])->find();
        if(!$result){
            $this->error('该文档不存在');
        }
        $this->assign("cateList",$list);
        $this->assign('data',$data);
        return $this->fetch();
    }

    /*
     * 编辑文档提交
     */
    public function editPost()
    {
        if($this->request->isPost()){
            $id = $this->request->param('id',0,'intval');
            if(!Db::name('documents')->where(['id'=>$id])->find()){
                $this->error('该文档不存在');
            }
            $data = $this->request->param();
            $validate = new Validate([
                'cate_id' => 'require',
                'name' => 'require',
                'content' => 'require',
            ]);

            $validate->message([
                'cate_id.require'  => '请选择分类！',
                'name.require'  => '文档名称不能为空！',
                'content.require'  => '文档内容不能为空！',
            ]);
            if (!$validate->check($data)) {
                $this->error($validate->getError());
            }
            $data['update_time'] = time();
            $result = Db::name('documents')->where(['id'=>$id])->update($data);
            if($result){
                $this->success('提交成功',url('Documents/index'));
            }else{
                $this->error("提交失败");
            }
        }else{
            $this->error('提交失败');
        }
    }

    /*
     * 删除文档
     */
    public function delete()
    {
        $id = $this->request->param('id',0,'intval');
        $list = Db::name('documents')->where(['id'=>$id])->find();
        if(!$list){
            $this->error('该文档不存在');
        }
        $result = Db::name('documents')->delete($id);
        if($result){
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }
    }
}