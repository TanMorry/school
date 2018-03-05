<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2017 http://www.wuwuseo.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 五五 <15093565100@163.com>
// +----------------------------------------------------------------------
return [
    'api_url'     => [// 在后台插件配置表单中的键名 ,会是config[text]
        'title' => '微信接口对接地址', // 表单的label标题
        'type'  => 'explain',// 表单的类型：text,password,textarea,checkbox,radio,select等
        'value' => '请到微信平台设置下面地址',// 表单的默认值
        'tip'   => $_SERVER['HTTP_HOST'].cmf_plugin_url('Weixin://Index/index'),//表单的帮助提示
    ],
    'oauth'     => [
        'title' => '网页授权入口地址',
        'type'  => 'text',
        'value' => '网页授权入口地址(加入自定义菜单或者做成链接)',
        'tip'   => $_SERVER['HTTP_HOST'].cmf_plugin_url('Weixin://Index/login'),//表单的帮助提示
        'disabled' => true,
    ],
    'appid'     => [// 在后台插件配置表单中的键名 ,会是config[text]
        'title' => 'appid', // 表单的label标题
        'type'  => 'text',// 表单的类型：text,password,textarea,checkbox,radio,select等
        'value' => '',// 表单的默认值
        'rule'  => [
            'require' => true
        ],
        'message' => [
            "require" => 'appid不能为空'
        ],
        'tip'   => '请到微信平台获取' //表单的帮助提示
       
    ],
    'appsecret'     => [// 在后台插件配置表单中的键名 ,会是config[text]
        'title' => 'appsecret', // 表单的label标题
        'type'  => 'text',// 表单的类型：text,password,textarea,checkbox,radio,select等
        'value' => '',// 表单的默认值
        'rule'  => [
            'require' => true
        ],
        'message' => [
            "require" => 'appid不能为空'
        ],
        'tip'   => '请到微信平台获取' //表单的帮助提示
    ],
    'token'     => [// 在后台插件配置表单中的键名 ,会是config[text]
        'title' => 'token', // 表单的label标题
        'type'  => 'text',// 表单的类型：text,password,textarea,checkbox,radio,select等
        'value' => '',// 表单的默认值
        'tip'   => '', //表单的帮助提示
        "rule"    => [
            "require" => true
        ],
        "message" => [
            "require" => 'token不能为空'
        ],
    ],
    'encodingaeskey'     => [// 在后台插件配置表单中的键名 ,会是config[text]
        'title' => 'encodingaeskey', // 表单的label标题
        'type'  => 'text',// 表单的类型：text,password,textarea,checkbox,radio,select等
        'value' => '',// 表单的默认值
        'tip'   => '请填写微信平台设置的encodingaeskey [通信 选加密模式必须填 明文模式不用]' //表单的帮助提示
    ],
];
					