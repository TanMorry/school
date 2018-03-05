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

class ActivitySchemeValidate extends Validate
{
    protected $rule = [
        'activity_id' => 'require',
        'name' => 'require',
        'type' => 'require',
//        'start_time' => 'require',
//        'end_time' => 'require',
//        'photo_names' => 'require',
//        'detail' => 'require',
    ];
    protected $message = [
        'activity_id.require' => '请选择活动！',
        'name.require' => '方案名不能为空！',
        'type.require' => '请选择方案类型！',
//        'start_time.require' => '请选择日程开始时间！',
//        'end_time.require' => '请选择日程结束时间！',
//        'photo_names.require' => '请上传活动日程图片！',
//        'detail.require' => '日程详情不能为空！',
    ];

}