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

class ActivityOrderValidate extends Validate
{
    protected $rule = [
        'order_money' => 'require',
        'consignee_name' => 'require',
        'consignee_phone' => 'require|unique:user,mobile',
        'consignee_addr' => 'require',
    ];
    protected $message = [
        'order_money.require' => '价格不能为空',
        'consignee_name.require' => '收货人姓名不能为空！',
        'consignee_phone.require' => '收货人手机号不能为空！',
        'consignee_addr.require' => '收货地址不能为空！',
        'mobile.unique'             => '手机号已经存在！',
    ];
}