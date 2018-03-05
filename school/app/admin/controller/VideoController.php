<?php
/**
 * Created by PhpStorm.
 * User:  惠普
 * Date: 2018/1/3
 * Time: 16:38
 */

namespace app\admin\controller;


use cmf\controller\AdminBaseController;
use think\Db;

class VideoController extends AdminBaseController
{
    public function _initialize(){
        parent::_initialize();
    }

    /*
     * 音频信息列表
     */
    public function index()
    {
        $result = Db::name('multimedia')->where(['file_type'=>2])->select();
        $this->assign('data',$result);
        return $this->fetch();
    }

    /*
     * 添加音频信息
     */
    public function add()
    {
        return $this->fetch();
    }

    /*
     * 添加音频信息提交
     */
    public function add_post()
    {
        if($this->request->isPost()){
            $data = $this->request->param();
            $result = $this->validate($data,'Video');
            if($result !== true){
                $this->error($result);
            }
            $new['cover_src'] = [];
            $new['file_src'] = [];
            if (!empty($data['photo_names']) && !empty($data['photo_urls'])) {
                $new['cover_src']['photos'] = [];
                foreach ($data['photo_urls'] as $key => $url) {
                    $photoUrl = cmf_asset_relative_url($url);
                    array_push($new['cover_src']['photos'], ["url" => $photoUrl, "name" => $data['photo_names'][$key]]);
                }
            }
            if (!empty($data['video_names']) && !empty($data['video_urls'])) {
                $new['file_src']['videos'] = [];
                foreach ($data['video_urls'] as $key => $url) {
                    $videoUrl = cmf_asset_relative_url($url);
                    array_push($new['file_src']['videos'], ["url" => $videoUrl, "name" => $data['video_names'][$key]]);
                }
            }
            $post['user_id'] = cmf_get_current_admin_id();
            $post['name'] = trim($data['name']);
//            $post['status'] = $data['status'];
            $post['detail'] = htmlspecialchars(cmf_replace_content_file_url(htmlspecialchars_decode($data['detail']), true));
            $post['price'] = intval($data['price']);
            $post['create_time'] = time();
            $post['update_time'] = time();
            $post['cover_src'] = json_encode($new['cover_src']);
            $post['file_src'] = json_encode($new['file_src']);
            $post['file_type'] = 2;
            $result = Db::name('multimedia')->insert($post);
//                var_dump($result);die;
            if ($result) {
                $this->success("添加视频成功", url("Video/index"));
            } else {
                $this->error("添加视频失败");
            }
        }
    }

    /*
     * 编辑视频信息
     */
    public function edit()
    {
        $id = $this->request->param('id',0,'intval');
        $data = Db::name('multimedia')->where(['id'=>$id])->find();
        if(!$data){
            $this->error('该视频不存在！');
        }
        $data = Db::name('multimedia')->field('id,name,price,file_src,cover_src,detail,create_time')->where(['file_type'=>2])->find();
        $photos = json_decode($data['cover_src'],true);
        $videos = json_decode($data['file_src'],true);
        $this->assign('photos',$photos['photos']);
        $this->assign('videos',$videos['videos']);
        $this->assign('data',$data);
        return $this->fetch();
    }

    /*
     * 编辑视频信息提交
     */
    public function edit_post()
    {
        if($this->request->isPost()){
            $id = $this->request->param('id',0,'intval');
            $data = Db::name('multimedia')->where(['id'=>$id])->find();
            if(!$data){
                $this->error('该视频不存在！');
            }
            $data = $this->request->param();
            $result = $this->validate($data,'Video');
            if($result !== true){
                $this->error($result);
            }
            $new['cover_src'] = [];
            $new['file_src'] = [];
            if (!empty($data['photo_names']) && !empty($data['photo_urls'])) {
                $new['cover_src']['photos'] = [];
                foreach ($data['photo_urls'] as $key => $url) {
                    $photoUrl = cmf_asset_relative_url($url);
                    array_push($new['cover_src']['photos'], ["url" => $photoUrl, "name" => $data['photo_names'][$key]]);
                }
            }
            if (!empty($data['video_names']) && !empty($data['video_urls'])) {
                $new['file_src']['videos'] = [];
                foreach ($data['video_urls'] as $key => $url) {
                    $videoUrl = cmf_asset_relative_url($url);
                    array_push($new['file_src']['videos'], ["url" => $videoUrl, "name" => $data['video_names'][$key]]);
                }
            }
            $post['name'] = trim($data['name']);
//            $post['status'] = $data['status'];
            $post['detail'] = htmlspecialchars(cmf_replace_content_file_url(htmlspecialchars_decode($data['detail']), true));
            $post['price'] = intval($data['price']);
            $post['update_time'] = time();
            $post['cover_src'] = json_encode($new['cover_src']);
            $post['file_src'] = json_encode($new['file_src']);
            $result = Db::name('multimedia')->where(["id" => $id])->update($post);
//                var_dump($result);die;
            if ($result) {
                $this->success("添加视频成功", url("Video/index"));
            } else {
                $this->error("添加视频失败");
            }
        }
    }

    /*
     * 删除视频信息
     */
    public function delete()
    {
        $id = $this->request->param('id',0,'intval');
        $data = Db::name('multimedia')->where(['id'=>$id])->find();
        if(!$data){
            $this->error('该视频不存在！');
        }
        $status = Db::name('multimedia')->delete($id);
        if (!empty($status)) {
            $this->success("删除成功！", url('Video/index'));
        } else {
            $this->error("删除失败！");
        }
    }
}