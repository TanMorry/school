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

class ActivitySchemeController extends AdminBaseController
{
    public function _initialize()
    {

    }

    //活动方案
    public function index()
    {
        $where = [];
        $activity_id = 0;
        if ($this->request->isPost()) {
            $param = $this->request->param();
            if($param['activity_id'] > 0){
                $where['as.activity_id'] = $param['activity_id'];
                $activity_id = $param['activity_id'];
            }
        }
        $activityList = Db::name('activity')->field("id,title")->where('type=1')->select();
        $new = [];
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

        $data = Db::table('baoli_activity_scheme')->alias('as')->join('baoli_activity a','as.activity_id=a.id','LEFT')->field('as.*,a.title')->where($where)->select();
        $this->assign('activityList', $activityList);
        $this->assign('activity_id', $activity_id);
        $this->assign('data',$data);
        return $this->fetch();
    }

    //活动方案添加
    public function add_scheme()
    {
        //取出所有活动
        $activityList = Db::name('activity')->field('id,title')->where('type=1')->select();
        $this->assign('activityList',$activityList);
        return $this->fetch();
    }

    //活动方案添加提交
    public function add_scheme_post()
    {
        if ($this->request->isPost()) {
            $data   = $this->request->param();
            $result = $this->validate($data,'ActivityScheme');
            $data['user_id'] = cmf_get_current_admin_id();
            $data['create_time'] = time();
            $data['update_time'] = time();
            if($result !== true){
                $this->error($result);
            }else{
//                echo "<pre>";
//                print_r($data);die;
                $res = Db::name('activity_scheme')->insert($data);
                if ($result) {
                    $this->success("添加成功", url("ActivityScheme/index"));
                } else {
                    $this->error("添加失败");
                }
            }
        }
    }

    //活动方案编辑
    public function edit_scheme()
    {
        $id = $this->request->param("id", 0, 'intval');
        $data = Db::table('baoli_activity_scheme')->alias('as')->join('baoli_activity a','as.activity_id=a.id','LEFT')->field('as.*,a.title')->where(["as.id" => $id])->find();
        $activityList = Db::name('activity')->field('id,title')->where('type=1')->select();
//        var_dump($activityList);die;
//        var_dump($data);die;
        if (!$data) {
            $this->error("该方案不存在！");
        }
        $this->assign("data", $data);
        $this->assign("activityList", $activityList);
        return $this->fetch();
    }

    //活动方案编辑提交
    public function edit_scheme_post()
    {
        $id = $this->request->param("id", 0, 'intval');
        $ishave = Db::name('activity_scheme')->where(["id" => $id])->find();
        if (!$ishave) {
            $this->error("该方案不存在！");
        }
        if ($this->request->isPost()) {
            $data   = $this->request->param();
            $result = $this->validate($data,'ActivityScheme');
            $data['update_time'] = time();
            if($result !== true){
                $this->error($result);
            }else{
                $res = Db::name('activity_scheme')->where(['id'=>$id])->update($data);
                if ($result) {
                    $this->success("编辑成功", url("ActivityScheme/index"));
                } else {
                    $this->error("编辑失败");
                }
            }
        }
    }

    //活动方案删除
    public function del_scheme()
    {
        $id = $this->request->param("id", 0, 'intval');
        $data = Db::name('activity_scheme')->where(["id" => $id])->find();
//        var_dump($data);die;
        if (!$data) {
            $this->error("该方案不存在！");
        }
        //判断此分类下有无活动日程安排
        $activity = Db::name('activity_scheme_arrange')->where(["scheme_id" => $id])->find();

        if ($activity) {
            $this->error('此方案下有日程安排无法删除!');
        }
        $status = Db::name('activity_scheme')->delete($id);
        if (!empty($status)) {
            $this->success("删除成功！", url('ActivityScheme/index'));
        } else {
            $this->error("删除失败！");
        }
    }

}