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

class ChildController extends AdminBaseController
{
    /*
     * 童乐会信息列表(手机号不需要验证唯一性)
     */
    public function index()
    {
//        $list = Db::name('child_info')->select();
        $list = Db::table('baoli_child_info')->alias('ci')->join('baoli_child_relation cr','ci.relation_id=cr.id','left')->field('ci.*,cr.name relation_name')->select();
        $this->assign('data',$list);
        return $this->fetch();
    }

    /*
     * 童乐会信息编辑
     */
/*    public function edit()
    {
        $id = $this->request->param('id',0,'intval');
        $data = Db::name('child_info')->where(['id'=>$id])->find();
        if(!$data){
            $this->success('信息不存在');
        }
        $info = Db::name('list')->where(['id'=>$id])->find();
        $this->assign('info',$info);
        return $this->fetch();
    }*/

    /*
     * 童乐会编辑提交
     */
//    public function editPost()
//    {
//        $id = $this->request->param('id',0,'intval');
//        $result = Db::name('child_info')->where(['id'=>$id])->find();
//        if(!$result){
//            $this->success('信息不存在');
//        }
//        if($this->request->isPost()){
//            $data = $this->request->param();
//            $post = [];
//            $post['name'] = $data['name'];
//            $post['nickname'] = $data['nickname'];
//            $post['userid'] = $data['userid'];
//            $post['sex'] = $data['sex'];
//            $post['date'] = strtotime($data['date']);
//            $post['phone'] = intval($data['phone']);
//            $post['relation'] = $data['phone'];
//
//            $child = $this->validate($data, 'Child');
//            if ($child !== true) {
//                // 验证失败 输出错误信息
//                $this->error($child);
//            } else {
//                $update = Db::name('child_info')->where(["id" => $id])->update($post);
////                var_dump($update);die;
//                if ($update) {
//                    $this->success("修改信息成功", url("Child/index"));
//                } else {
//                    $this->error("修改信息失败");
//                }
//            }
//        }
//    }

    /*
     * 童乐会信息详情
     */
    public function detail()
    {
        $id = $this->request->param('id',0,'intval');
        $info = Db::name('child_info')->where(['id'=>$id])->find();
        if(!$info){
            $this->error('信息不存在');
        }
        $data = Db::table('baoli_child_info')->alias('ci')->join('baoli_child_relation cr','ci.relation_id=cr.id','left')->field('ci.*,cr.name relation_name')->where(['ci.id'=>$id])->find();
        $this->assign('data',$data);
        return $this->fetch();
    }

    /*
     * 童乐会信息删除
     */
    public function delete()
    {
        $id = $this->request->param('id',0,'intval');
        $data = Db::name('child_info')->where(['id'=>$id])->find();
        if(!$data){
            $this->error('信息不存在');
        }
        $result = Db::name('child_info')->where(['id'=>$id])->delete();
        if($result){
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }
    }

}