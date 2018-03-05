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

class MessageValidate extends Validate
{
    protected $rule = [
        'cate_id' => 'require',
        'mes_name' => 'require',
        'mes_con' => 'require',
        'photo_names' => 'require',
    ];
    protected $message = [
        'cate_id.require' => '请选择正确的分类！',
        'mes_name.require' => '资讯标题不能为空！',
        'mes_con.require' => '资讯内容不能为空！',
        'photo_names.require' => '请选择图片！',
    ];

}