<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2017 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 小夏 < 449134904@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\validate;

use think\Validate;

class VideoValidate extends Validate
{
    protected $rule = [
        'name' => 'require',
        'video_urls' => 'require',
//        'audio_names' => 'require',
        'photo_urls' => 'require',
//        'photo_names' => 'require',
        'price' => 'require',
        'detail' => 'require',
    ];
    protected $message = [
        'name.require' => '视频名称不能为空',
        'video_urls.require' => '请上传视频！',
//        'audio_names.require' => '收货人姓名不能为空！',
        'photo_urls.require' => '请上传视频封面！',
//        'photo_names.require' => '收货人手机号不能为空！',
        'price.require' => '价格不能为空！',
        'detail.unique' => '详情不能为空！',
    ];
}