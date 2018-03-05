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

class ActivityController extends AdminBaseController
{
    public function _initialize()
    {

    }

    //活动列表
    public function index()
    {
        $result = Db::name('activity')->select();
        $data['list'] = $result;
        $this->assign('data',$data);
        return $this->fetch();
    }

    //活动添加
    public function add_activity()
    {
        //查找所有活动分类
        return $this->fetch();
    }

    //活动添加提交
    public function add_activity_post()
    {
        if ($this->request->isPost()) {
            $data   = $this->request->param();
            $result = $this->validate($data, 'Activity');
            $post['img_src'] = [];
            if (!empty($data['photo_names']) && !empty($data['photo_urls'])) {
                $post['img_src']['photos'] = [];
                foreach ($data['photo_urls'] as $key => $url) {
                    $photoUrl = cmf_asset_relative_url($url);
                    array_push($post['img_src']['photos'], ["url" => $photoUrl, "name" => $data['photo_names'][$key]]);
                }
            }
            if($data['type'] == 2){
                $post['show_time'] = date('Y.m.d',strtotime(max($data['show_time']))).'-'.date('Y.m.d',strtotime(min($data['show_time'])));
                $post['show_addr'] = $data['show_addr'];
            }else{
                $post['price'] = $data['price'];
            }
            $ticket_stock = [];

            if (!empty($data['show_time']) && !empty($data['ticket_stock'] && !empty($data['ticket_price']))) {
                foreach ($data['show_time'] as $key => $time) {
                    if(!empty($time) && !empty($data['ticket_stock'][$key] && !empty($data['ticket_price'][$key]))){
                        $arr = explode(' ',$time);
                        array_push($ticket_stock, ["show_date" => date('Y-n-j',strtotime($arr[0])),"show_time" => $arr[1], "stock" => $data['ticket_stock'][$key],"price"=>$data['ticket_price'][$key],"dateAndTime"=>(strtotime($time)),"expir_time" => (strtotime($time)-86400)]);
                    }
             /*       else{
                        $this->error('请输入正确的票务信息');
                    }*/
                }
            }
//            echo "<pre>";
//            print_r($ticket_stock);die;
            $post['user_id'] = $post['edit_user_id'] = cmf_get_current_admin_id();
            $post['title'] = $data['title'];
            if(isset($data['detail'])){
                $post['detail'] = htmlspecialchars(cmf_replace_content_file_url(htmlspecialchars_decode($data['detail']), true));
            }else{
                $post['detail']="";
            }
            if(isset($data['reason'])){
                $post['reason'] = htmlspecialchars(cmf_replace_content_file_url(htmlspecialchars_decode($data['reason']), true));
            }else{
                $post['reason']="";
            }

//            $post['show_time'] = json_encode(array_values($newTime));
            $post['age'] = $data['start_age'].'-'.$data['end_age'];

            $post['type'] = $data['type'];
            $post['formerprice'] = $data['formerprice'];
            $post['create_time'] = time();
            $post['update_time'] = time();
            $post['stock'] = $data['stock'];
            $post['img_src'] = json_encode($post['img_src']);
            if ($result !== true) {
                // 验证失败 输出错误信息
                $this->error($result);
            } else {
                $result = Db::name('activity')->insert($post);
                if ($result) {
                    if($data['type'] == 2){
                        $resultId = Db::name('activity')->getLastInsID();
                        $sql = "Insert into baoli_ticket_stock values";
                        foreach($ticket_stock as $k=>$v){
                            $sql .= "(null,{$resultId},'{$v['show_date']}','{$v['show_time']}',{$v['stock']},{$v['price']},{$v['dateAndTime']},{$v['expir_time']},".time()."),";
                        }
                        //将票务信息存入票务信息表
                        Db::name('ticket_stock')->query(rtrim($sql,','));
                    }

                    $this->success("添加活动成功", url("Activity/index"));
                } else {
                    $this->error("添加活动失败");
                }

            }
        }
    }

    //活动编辑
    public function edit_activity()
    {
        $id = $this->request->param('id',0,'intval');
        $info['data'] = Db::name('activity')->where(['id'=>$id])->find();
//        var_dump($info['data']);die;

        if(!$info['data']){
            $this->error('该活动不存在！');
        }
        if($info['data']['type'] == 2){
            //票务
            $showList = Db::name('ticket_stock')->where(['ticket_id'=>$id])->select();
            $this->assign('showList',$showList);
        }
        $photos = json_decode($info['data']['img_src'],true);
//        var_dump($date);die;
        $age = explode("-",$info['data']['age']);
        $this->assign('age',$age);
        $this->assign('photos',$photos['photos']);
        $this->assign('info',$info);
        return $this->fetch();
    }

    public function edit_activity_post()
    {
        $id = $this->request->param("id", 0, 'intval');
        $data = Db::name('activity')->where(["id" => $id])->find();
//        var_dump($data);die;
        if (!$data) {
            $this->error("该活动不存在！");
        }
        if ($this->request->isPost()) {
            $data   = $this->request->param();
//            print_r($data);die;
            $result = $this->validate($data, 'Activity');
            $post['img_src'] = [];
            if (!empty($data['photo_names']) && !empty($data['photo_urls'])) {
                $post['img_src']['photos'] = [];
                foreach ($data['photo_urls'] as $key => $url) {
                    $photoUrl = cmf_asset_relative_url($url);
                    array_push($post['img_src']['photos'], ["url" => $photoUrl, "name" => $data['photo_names'][$key]]);
                }
            }
            if($data['type'] == 2){
                $post['show_time'] = date('Y.m.d',strtotime(max($data['show_time']))).'-'.date('Y.m.d',strtotime(min($data['show_time'])));
                $post['show_addr'] = $data['show_addr'];
            }else{
                $post['price'] = $data['price'];
            }

            $ticket_stock = [];
            if (!empty($data['show_time']) && !empty($data['ticket_stock'] && !empty($data['ticket_price']))) {
                foreach ($data['show_time'] as $key => $time) {
                    if(!empty($time) && !empty($data['ticket_stock'][$key] && !empty($data['ticket_price'][$key]))){
                        $arr = explode(' ',$time);
                        array_push($ticket_stock, ["show_date" => date('Y-n-j',strtotime($arr[0])),"show_time" => $arr[1], "stock" => $data['ticket_stock'][$key],"price"=>$data['ticket_price'][$key],"dateAndTime"=>(strtotime($time)),"expir_time" => (strtotime($time)-86400)]);
                    }
                    /*       else{
                               $this->error('请输入正确的票务信息');
                           }*/
                }
            }
            $post['edit_user_id'] = cmf_get_current_admin_id();
            $post['title'] = $data['title'];
            $post['recommended'] = $data['recommended'];
            if(isset($data['detail'])){
                $post['detail'] = htmlspecialchars(cmf_replace_content_file_url(htmlspecialchars_decode($data['detail']), true));
            }else{
                $post['detail']="";
            }
            if(isset($data['reason'])){
                $post['reason'] = htmlspecialchars(cmf_replace_content_file_url(htmlspecialchars_decode($data['reason']), true));
            }else{
                $post['reason']="";
            }
            $post['age'] = $data['start_age'].'-'.$data['end_age'];
            $post['type'] = $data['type'];
            $post['formerprice'] = $data['formerprice'];
            $post['update_time'] = time();
            if($data['type'] == 1){
                $post['stock'] = $data['stock'];
            }else{
                $post['stock'] = 0;
            }
            $post['img_src'] = json_encode($post['img_src']);

//            var_dump($post);die;
            if ($result !== true) {
                // 验证失败 输出错误信息
                $this->error($result);
            } else {

                $result = Db::name('activity')->where(["id" => $id])->update($post);
//                var_dump($result);die;

                if ($result) {
                    if($data['type'] == 2) {
                        //先将原来的演出时间和库存删掉
                        $del_stock = Db::name('ticket_stock')->where(["ticket_id" => $id])->delete();
                        //添加
//                        if ($del_stock) {
                            $sql = "Insert into baoli_ticket_stock values";
                            foreach($ticket_stock as $k=>$v){
                                $sql .= "(null,{$id},'{$v['show_date']}','{$v['show_time']}',{$v['stock']},{$v['price']},{$v['dateAndTime']},{$v['expir_time']},".time()."),";
                            }
                            //将票务信息存入票务信息表
                            Db::name('ticket_stock')->query(rtrim($sql, ','));
                            $this->success("修改活动成功", url("activity/index"));
//                        } else {
//                            $this->error("演出时间添加失败");
//                        }
                    }else{
                        $this->success("修改活动成功", url("activity/index")  );;
                    }
                }else {
                    $this->error("修改活动失败");
                }

            }
        }
    }

    //活动删除
    public function del_activity()
    {
        $id = $this->request->param("id", 0, 'intval');
        $data = Db::name('activity')->where(["id" => $id])->find();
//        var_dump($data);die;
        if (!$data) {
            $this->error("该活动不存在！");
        }
        //判断此分类下有无订单，有订单删除不了
        $isOrder = Db::name('activity_order')->where(["good_id" => $id])->find();

        if ($isOrder) {
            $this->error('此活动生成过订单，不能删除!');
        }
        $status = Db::name('activity')->delete($id);
        if (!empty($status)) {
            $this->success("删除成功！", url('Activity/index'));
        } else {
            $this->error("删除失败！");
        }
    }



}