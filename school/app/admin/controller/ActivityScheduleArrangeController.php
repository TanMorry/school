<?php
/**
 * Created by PhpStorm.
 * User:  惠普
 * Date: 2017/12/20
 * Time: 15:43
 */

namespace app\admin\controller;


use cmf\controller\AdminBaseController;
use think\Db;

class ActivityScheduleArrangeController extends AdminBaseController
{
    public function _initialize()
    {

    }

    //日程安排
    public function index()
    {
        $where = [];
        $activity_id = 0;
        $scheme_id = 0;
        if ($this->request->isPost()) {
            $param = $this->request->param();
            if($param['scheme_id'] > 0){
                $where['asa.scheme_id'] = $param['scheme_id'];
                $scheme_id = $param['scheme_id'];
            }
            if($param['activity_id'] > 0){
                $where['asa.activity_id'] = $param['activity_id'];
                $activity_id = $param['activity_id'];
            }
        }
        $new = [];
        $activityList = Db::name('activity')->field("id,title")->where('type=1')->select();
        if(empty($activityList) === false){
            foreach ($activityList as $k=>$v){
                $new[0]['id'] = 0;
                $new[0]['title'] = '全部';
                $new[$k+1] = $v;
            }
            $activityList = $new;
        }else{
            $activityList = [];
        }

        $data = Db::table('baoli_activity_scheme_arrange')->alias('asa')->join('baoli_activity_scheme as', 'asa.scheme_id=as.id', 'LEFT')->join('baoli_activity a', 'as.activity_id=a.id', 'LEFT')->field('asa.*,a.title,as.name')->where($where)->select();
        $this->assign('activityList', $activityList);
        $this->assign('activity_id', $activity_id);
        $this->assign('scheme_id', $scheme_id);
//        $this->assign('scheduleList',$scheduleList);
        $this->assign('data', $data);
        return $this->fetch();
    }


    //活动日程添加
    public function add_schedule()
    {
        //提供有方案的所有活动
        $activityList = Db::name('activity')->field("id,title")->where('type=1')->select();
        //根据活动选择ajax获取相应的方案
        $this->assign('activityList', $activityList);
        $this->assign('schemeList', []);
        return $this->fetch();
    }

    //ajax获取对应活动下的方案
    public function ajaxGetScheme()
    {
        $id = $this->request->param('activity_id', 0, 'intval');
        $data = Db::name('activity_scheme')->where(['activity_id' => $id])->select();
        echo json_encode($data);
    }

    //活动日程添加提交
    public function add_schedule_post()
    {
        if ($this->request->isPost()) {
            $data = $this->request->param();
            $result = $this->validate($data, 'ActivityScheduleArrange');
            $post['arrange_src'] = [];
            if (!empty($data['photo_names']) && !empty($data['photo_urls'])) {
                $post['arrange_src']['photos'] = [];
                foreach ($data['photo_urls'] as $key => $url) {
                    $photoUrl = cmf_asset_relative_url($url);
                    array_push($post['arrange_src']['photos'], ["url" => $photoUrl, "name" => $data['photo_names'][$key]]);
                }
            }
            $post['user_id'] = cmf_get_current_admin_id();
            $post['detail'] = trim($data['detail']);
            $post['arrange_name'] = trim($data['arrange_name']);
            $post['arrange_src'] = json_encode($post['arrange_src']);
            $post['start_time'] = strtotime($data['start_time']);
            $post['end_time'] = strtotime($data['end_time']);
            $post['scheme_id'] = intval($data['scheme_id']);
            $post['activity_id'] = intval($data['activity_id']);
            $post['create_time'] = time();
            $post['update_time'] = time();
            if ($result !== true) {
                $this->error($result);
            } else {
                $res = Db::name('activity_scheme_arrange')->insert($post);
                if ($result) {
                    $this->success("添加成功", url("ActivityScheduleArrange/index"));
                } else {
                    $this->error("添加失败");
                }
            }
        }
    }

    //活动日程编辑
    public function edit_schedule()
    {
        $id = $this->request->param("id", 0, 'intval');
        $res = Db::name('activity_scheme_arrange')->where(["id" => $id])->find();
//        var_dump($data);die;
        if (!$res) {
            $this->error("该方案不存在！");
        }
        //关联方案和活动表，找出该日程的详细信息（主要是方案名和所属的活动名）
        $data = Db::table('baoli_activity_scheme_arrange')->alias('asa')->join('baoli_activity_scheme as', 'asa.scheme_id=as.id', 'LEFT')->join('baoli_activity a', 'as.activity_id=a.id', 'LEFT')->field('asa.*,a.title,as.name')->where(["asa.id" => $id])->find();
        //根据活动id找出所有的方案
        $schemeList = Db::name('activity_scheme')->where(["activity_id" => $data['activity_id']])->select();

        //提供有方案的所有活动
        $activityList = Db::name('activity_scheme')->alias('as')->join('activity a', 'as.activity_id=a.id')->field('a.id,a.title')->group('as.activity_id')->select();

        $photos = json_decode($data['arrange_src'], true);
        if (!$data) {
            $this->error("该方案不存在！");
        }
        $this->assign("schemeList", $schemeList);
        $this->assign('photos', $photos['photos']);
        $this->assign("data", $data);
        $this->assign("activityList", $activityList);
        return $this->fetch();
    }

    //活动日程编辑提交
    public function edit_schedule_post()
    {
        $id = $this->request->param("id", 0, 'intval');
        $ishave = Db::name('activity_scheme_arrange')->where(["id" => $id])->find();
//        var_dump($ishave);die;
        if (!$ishave) {
            $this->error("该方案不存在！");
        }
        if ($this->request->isPost()) {
            $data = $this->request->param();
            $post['arrange_src'] = [];
            if (!empty($data['photo_names']) && !empty($data['photo_urls'])) {
                $post['arrange_src']['photos'] = [];
                foreach ($data['photo_urls'] as $key => $url) {
                    $photoUrl = cmf_asset_relative_url($url);
                    array_push($post['arrange_src']['photos'], ["url" => $photoUrl, "name" => $data['photo_names'][$key]]);
                }
            }
            $result = $this->validate($data, 'ActivityScheduleArrange');
            $post['user_id'] = cmf_get_current_admin_id();
            $post['detail'] = $data['detail'];
            $post['arrange_name'] = trim($data['arrange_name']);
            $post['arrange_src'] = json_encode($post['arrange_src']);
            $post['start_time'] = strtotime($data['start_time']);
            $post['end_time'] = strtotime($data['end_time']);
            $post['scheme_id'] = intval($data['scheme_id']);
            $post['activity_id'] = intval($data['activity_id']);
            $post['update_time'] = time();
            if ($result !== true) {
                $this->error($result);
            } else {
                $res = Db::name('activity_scheme_arrange')->where(['id' => $id])->update($post);
                if ($res) {
                    $this->success("编辑成功", url("ActivityScheduleArrange/index"));
                } else {
                    $this->error("编辑失败");
                }
            }
        }
    }

    //活动日程删除
    public function del_schedule()
    {
        $id = $this->request->param("id", 0, 'intval');
//        var_dump($id);die;
        $data = Db::name('activity_scheme_arrange')->where(["id" => $id])->find();
        if (!$data) {
            $this->error("该活动不存在！");
        }

        $status = Db::name('activity_scheme_arrange')->delete($id);
        if (!empty($status)) {
            $this->success("删除成功！", url('ActivityScheduleArrange/index'));
        } else {
            $this->error("删除失败！");
        }
    }

}