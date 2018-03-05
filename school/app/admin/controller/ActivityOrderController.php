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

class ActivityOrderController extends AdminBaseController
{
    public function _initialize()
    {

    }

    //订单列表
    public function index()
    {
        $search = [];
        $statusList = ['-1'=>'全部','0'=>'待付款','1'=>'已付款','2'=>'待退款','3'=>'已退款'];
        if($this->request->isPost()){
            $param = $this->request->param();
            if(!empty($param)) {
                if ($param['order_status'] != '-1') {
                    $search['ao.order_status'] = $param['order_status'];
                }
                $startTime = empty($param['start_time']) ? 0 : strtotime($param['start_time']);
                $endTime   = empty($param['end_time']) ? 0 : strtotime($param['end_time']);
                if (!empty($startTime) && !empty($endTime)) {
                    $search['ao.order_time'] = [['>= time', $startTime], ['<= time', $endTime]];
                } else {
                    if (!empty($startTime)) {
                        $search['ao.order_time'] = ['>= time', $startTime];
                    }
                    if (!empty($endTime)) {
                        $search['ao.order_time'] = ['<= time', $endTime];
                    }
                }
                if(!empty(trim($param['order_id']))){
                    $search['ao.order_id'] = trim($param['order_id']);
                }
            }
        }
        $result = Db::table('baoli_activity_order ao')->join('baoli_activity_order_detail aod','ao.order_id=aod.order_id','left')->join('baoli_activity a','aod.good_id=a.id','left')->field('ao.*,aod.sale_num,a.title')->where($search)->select();
        $data['list'] = $result;
        $this->assign('order_status',isset($param['order_status'])?$param['order_status']:'-1');
        $this->assign('start_time', isset($param['start_time']) ? $param['start_time'] : '');
        $this->assign('end_time', isset($param['end_time']) ? $param['end_time'] : '');
        $this->assign('order_id',isset($param['order_id'])?$param['order_id']:'');
        $this->assign('statusList',$statusList);
        $this->assign('data',$data);
        return $this->fetch();
    }

    //订单修改
    public function activity_order_detail()
    {
        $id = $this->request->param('id',0,'intval');
        $info['data'] = Db::table('baoli_activity_order ao')->join('baoli_activity_order_detail aod','ao.order_id=aod.order_id','left')->join('baoli_activity a','aod.good_id=a.id','left')->field('ao.*,aod.sale_num,a.title')->where(['ao.id'=>$id])->find();
//        $photos = json_decode($info['data']['img_src'],true);
//        $this->assign('photos',$photos['photos']);
        $this->assign('info',$info);
        return $this->fetch();
    }

    public function edit_activity_order_post()
    {
        $id = $this->request->param("id", 0, 'intval');
        if ($this->request->isPost()) {
            $data   = $this->request->param();
//            var_dump($data);die;
            $auth = $this->validate($data, 'ActivityOrder');
//            var_dump($result);die;
            if ($auth !== true) {
                // 验证失败 输出错误信息
                $this->error($auth);
            } else {
                if (!preg_match('/(^(13\d|15[^4\D]|17[013678]|18\d)\d{8})$/', $data['consignee_phone'])) {
                    $this->error("请输入正确的手机或者邮箱格式!");
                }
                $post['order_money'] = $data['order_money'];
                $post['consignee_name'] = $data['consignee_name'];
                $post['consignee_phone'] = $data['consignee_phone'];
                $post['consignee_addr'] = $data['consignee_addr'];
//                var_dump($post);die;
                $result = Db::name('activity_order')->where(["id" => $id])->update($post);
//                var_dump($result);die;

                if ($result) {
                    $this->success("修改活动成功", url("ActivityOrder/index"));
                } else {
                    $this->error("修改活动失败");
                }
            }
        }
    }

    //订单删除(只改变订单状态，假删)
    public function del_activity_order()
    {
        $id = $this->request->param("id", 0, 'intval');
        $data = Db::name('activity_order')->where(["id" => $id])->find();
//        var_dump($data);die;
        if (!$data) {
            $this->error("该订单不存在！");
        }

        $status = Db::name('activity_order')->where('id',$id)->update(['enable'=>1]);
        if (!empty($status)) {
            $this->success("删除成功！", url('ActivityOrder/index'));
        } else {
            $this->error("删除失败！");
        }
    }
}