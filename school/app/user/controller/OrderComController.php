<?php
/**
 * Created by PhpStorm.
 * User:  惠普
 * Date: 2018/1/18
 * Time: 11:30
 */

namespace app\user\controller;
use cmf\controller\AdminBaseController;
use think\Db;

class OrderComController extends AdminBaseController
{
    /*
     * 评论列表
     */
    public function index()
    {
        $list = Db::table('baoli_order_com')->alias('oc')->join('baoli_user u','oc.userid=u.id','left')->join('baoli_activity a','oc.activity_id=a.id')->field('oc.id,a.title,oc.com,oc.com_star,u.weixin_nickname,oc.type,oc.status,oc.create_time')->order('oc.create_time desc')->select();
        $this->assign('list',$list);
        return $this->fetch();

    }

    /*
     * 评论详情
     */
    public function detail()
    {
        $id = $this->request->param('id',0,'intval');
        $data = Db::table('baoli_order_com')->alias('oc')->join('baoli_user u','oc.userid=u.id','left')->join('baoli_activity a','oc.activity_id=a.id')->field('oc.id,a.title,oc.com,oc.com_star,u.weixin_nickname,oc.type,oc.status,oc.create_time')->where(['oc.id'=>$id])->order('oc.create_time desc')->find();
//        $data = Db::name('order_com')->where(['id'=>$id])->find();
        if(!$data){
            $this->error('该评论不存在');
        }
        $this->assign('data',$data);
        return $this->fetch();
    }

    /*
     * 删除评论
     */
    public function delete()
    {
        $id = $this->request->param('id',0,'intval');
        $data = Db::name('order_com')->where(['id'=>$id,'status'=>'1'])->find();
        if(!$data){
            $this->error('该评论不存在');
        }
        $result = Db::name('order_com')->where(['id'=>$id])->update(['status'=>2]);
        if($result){
            $this->success('删除成功',url('OrderCom/index'));
        }else{
            $this->error('删除失败');
        }
    }

    /*
     * 恢复评论
     */
    public function huifu()
    {
        $id = $this->request->param('id',0,'intval');
        $data = Db::name('order_com')->where(['id'=>$id,'status'=>'2'])->find();
        if(!$data){
            $this->error('该评论不存在');
        }
        $result = Db::name('order_com')->where(['id'=>$id])->update(['status'=>1]);
        if($result){
            $this->success('恢复成功',url('OrderCom/index'));
        }else{
            $this->error('恢复失败');
        }
    }
}