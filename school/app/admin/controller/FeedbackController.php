<?php
/**
 * Created by PhpStorm.
 * User:  惠普
 * Date: 2018/1/17
 * Time: 11:26
 */

namespace app\admin\controller;

use think\Db;
use cmf\controller\AdminBaseController;

class FeedbackController extends AdminBaseController
{
    /*
     * 反馈建议列表
     */
    public function index()
    {
        $list = Db::name('feedback')->select();
        $this->assign('list',$list);
        return $this->fetch();
    }

    /*
     * 反馈建议详情
     */
    public function detail()
    {
        $id = $this->request->param('id',0,'intval');
        $info = Db::name('feedback')->where(['id'=>$id])->find();
        if(!$info){
            $this->error('信息不存在');
        }
        $photos = json_decode($info['img_src'],true);
        $this->assign('photos',$photos['photos']);
        $this->assign('data',$info);
        return $this->fetch();
    }

    /*
     * 反馈建议删除
     */
    public function delete()
    {
        $id = $this->request->param('id',0,'intval');
        $data = Db::name('feedback')->where(['id'=>$id])->find();
        if(!$data){
            $this->error('信息不存在');
        }
        $result = Db::name('feedback')->where(['id'=>$id])->delete();
        if($result){
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }
    }
}