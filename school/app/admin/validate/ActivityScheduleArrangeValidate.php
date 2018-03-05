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

class ActivityScheduleArrangeValidate extends Validate
{
    protected $rule = [
        'activity_id' => 'require',
        'scheme_id' => 'require',
        'arrange_name' => 'require',
        'start_time' => 'require',
        'end_time' => 'require',
        'photo_names' => 'require',
        'detail' => 'require',
    ];
    protected $message = [
        'activity_id.require' => '请选择正确的活动！',
        'scheme_id.require' => '请选择正确的方案！',
        'arrange_name.require' => '日程名称不能为空！',
        'start_time.require' => '请选择日程开始时间！',
        'end_time.require' => '请选择日程结束时间！',
        'photo_names.require' => '请上传活动日程图片！',
        'detail.require' => '日程详情不能为空！',
    ];

}