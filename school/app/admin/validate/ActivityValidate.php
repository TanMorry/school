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

class ActivityValidate extends Validate
{
    protected $rule = [
        'title' => 'require',
        'photo_names' => 'require',
        'type' => 'require',
        'start_age' => 'require',
        'end_age' => 'require',
//        'price' => 'require',
//        'formerprice' => 'require',

    ];
    protected $message = [
        'category_id.require' => '请选择或输入正确的分类！',
        'title.require' => '活动名称不能为空！',
        'photo_names.require' => '请上传活动图片！',
        'type.require' => '请选择类型！',
        'start_age.require' => '请填写完整的年龄段！',
        'end_age.require' => '请填写完整的年龄段！',
//        'price.require' => '请输入价格！',
//        'formerprice.require' => '请输入促销价格！',


    ];

}